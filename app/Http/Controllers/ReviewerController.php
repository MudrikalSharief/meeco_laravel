<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Raw;
use App\Models\Reviewer;
use Illuminate\Http\Request;
use App\Models\Topic;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;

class ReviewerController extends Controller
{
    public function downloadPdf(Request $request)
    {
        try {
            // Validate topic ID
            $validated = $request->validate([
                'topicId' => 'required|int',
            ]);
            $topicId = $validated['topicId'];
            
            // Log inputs for debugging
            \Log::info('PDF Download Request:', [
                'topicId' => $topicId,
            ]);
            
            // Get topic name
            $topic = Topic::find($topicId);
            if (!$topic) {
                \Log::warning('Topic not found', ['topicId' => $topicId]);
                return response()->json(['success' => false, 'message' => 'Topic not found.']);
            }
            
            $topicName = $topic->name;
            
            // Fetch reviewer content from the database
            $reviewer = Reviewer::where('topic_id', $topicId)->get();
            if ($reviewer->isEmpty()) {
                \Log::warning('No reviewers found for this topic', ['topicId' => $topicId]);
                return response()->json(['success' => false, 'message' => 'No reviewers found for this topic.']);
            }
            
            $reviewerContent = '';
            foreach ($reviewer as $item) {
                $about = htmlspecialchars($item->reviewer_about, ENT_QUOTES, 'UTF-8');
                $text = htmlspecialchars($item->reviewer_text, ENT_QUOTES, 'UTF-8');
                $reviewerContent .= '<p>' . $text . '</p>';
            }
            
            // Clean up HTML content
            $cleanContent = $this->sanitizeHtml($reviewerContent);
            
            // Create a file name that is filesystem safe
            $safeFileName = preg_replace('/[^a-zA-Z0-9_-]/', '_', $topicName);
            $fileName = $safeFileName . '.pdf';
            
            \Log::info('Generating PDF', ['fileName' => $fileName]);
            
            // Generate PDF with UTF-8 encoding
            $pdf = PDF::loadView('pdf.reviewer', [
                'reviewerContent' => $cleanContent,
                'topicName' => $topicName
            ]);
            
            // Set paper size and orientation
            $pdf->setPaper('a4', 'portrait');
            
            // Set the encoding
            $pdf->getDomPDF()->set_option('isHtml5ParserEnabled', true);
            $pdf->getDomPDF()->set_option('isPhpEnabled', true);
            $pdf->getDomPDF()->set_option('isFontSubsettingEnabled', true);
            $pdf->getDomPDF()->set_option('defaultMediaType', 'print');
            
            // Save PDF to storage
            $pdfPath = 'pdfs/' . $fileName;
            Storage::disk('local')->put($pdfPath, $pdf->output());
            
            \Log::info('PDF generated successfully', ['path' => $pdfPath]);
            
            // Return the PDF as a download
            return $pdf->download($fileName);
            
        } catch (\Exception $e) {
            // Log detailed error information
            \Log::error('PDF Generation Error: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
    
            return response()->json([
                'success' => false, 
                'message' => 'An error occurred while downloading the PDF: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Sanitize HTML content for PDF generation
     * 
     * @param string $html
     * @return string
     */
    private function sanitizeHtml($html)
    {
        // Replace potentially problematic HTML elements
        $html = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', '', $html);
        
        // Convert special characters to HTML entities
        $html = mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8');
        
        return $html;
    }
    public function storeReviewer(Request $request)
    {
        // Validate the request
        $request->validate([
            'topic_id' => 'required|integer',
            'reviewer_text' => 'required|string',
        ]);

        // Find the Reviewer record by topic_id
        $reviewer = Reviewer::where('topic_id', $request->topic_id)->first();

        // If the record exists, update the reviewer_text
        if ($reviewer) {
            $reviewer->reviewer_text = $request->reviewer_text;
            $reviewer->save();
        } else {
            // If the record does not exist, create a new one
            $reviewer = Reviewer::create([
                'topic_id' => $request->topic_id,
                'reviewer_text' => $request->reviewer_text,
            ]);
        }

        return response()->json(['success' => true, 'reviewerText' => $reviewer->reviewer_text]);
        
    }

    public function showReviewPage($topicId)
    {   
            $topic = Topic::findOrFail($topicId);
            $reviewerText = Reviewer::where('topic_id', $topicId)->first()->reviewer_text ?? '';
            $rawText = Raw::where('topic_id', $topicId)->first()->raw_text ?? '';
            return view('posts.reviewer', compact('topic', 'reviewerText', 'topicId','rawText'));
    }
    //=======================================================================================================
    public function disectReviewer(Request $request)
    {
        $topic_id = $request->validate([
            'topicId' => 'required|int',
        ]);
    
        $data = [];

        $reviewer = Reviewer::where(['topic_id' => $topic_id])->get();
        if(empty($reviewer)){
            return response()->json(['success' => false, 'message' => 'There are no reviewers for this topic.']);
        }else{
            foreach($reviewer as $item){
                $data[] = [$item->reviewer_about, $item->reviewer_text];
            }
        }
        if($data == null){
            return response()->json(['success' => false, 'message' => 'There are no reviewers for this topic.']);
        }

        return response()->json(['success' => true,'data' => $data]);
        
    }
    //=======================================================================================================

    
}