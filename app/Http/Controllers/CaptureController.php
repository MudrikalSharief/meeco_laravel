<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Topic; // Add this import statement

class CaptureController extends Controller
{
    public function getImages()
    {
        $files = Storage::disk('public')->files('uploads');
        $images = array_map(function ($file) {
            return Storage::url($file);
        }, $files);

        return response()->json(['images' => $images]);
    }

    public function delete(Request $request)
    {
        $filePath = $request->input('filePath');
        if (Storage::disk('public')->exists($filePath)) {
            Storage::disk('public')->delete($filePath);
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false]);
    }

    public function extractText(Request $request)
    {
        $request->validate([
            'topic_id' => 'required|integer|exists:topics,topic_id', // Update 'id' to the actual primary key column name
        ]);

        $topicId = $request->input('topic_id');
        $topic = Topic::find($topicId);

        return redirect()->route('capture.extracted', ['topic_id' => $topicId, 'topic_name' => $topic->name]);
    }

    /**
     * Move an image to the graph container directory
     */
    public function moveToGraph(Request $request)
    {
        try {
            $filePath = $request->input('filePath');
            
            if (!$filePath) {
                return response()->json(['success' => false, 'message' => 'No file path provided']);
            }

            // Get user ID for organizing files
            $userId = auth()->id() ?? 'guest';
            
            // Define source and destination directories
            $graphDir = "uploads/user_{$userId}/graph";
            
            // Create the destination directory if it doesn't exist
            if (!Storage::disk('public')->exists($graphDir)) {
                Storage::disk('public')->makeDirectory($graphDir, 0755, true);
            }
            
            // Fix path by removing any duplicate 'uploads/' prefix
            if (strpos($filePath, 'uploads/') === 0) {
                $filePath = substr($filePath, 8); // Remove the 'uploads/' prefix
            }
            
            $sourcePath = storage_path('app/public/uploads/' . $filePath);
            if (!file_exists($sourcePath)) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Source file not found: ' . $sourcePath,
                ]);
            }
            
            // Get the filename
            $filename = basename($sourcePath);
            
            // Move the file to the graph folder
            $newPath = $graphDir . '/' . $filename;
            $success = Storage::disk('public')->move('uploads/' . $filePath, $newPath);
            
            return response()->json([
                'success' => true, 
                'message' => $success ? 'File moved successfully' : 'File not moved but continuing',
                'newPath' => $newPath
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false, 
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Move an image back to the image container directory
     */
    public function moveToContainer(Request $request)
    {
        try {
            $filePath = $request->input('filePath');
            
            if (!$filePath) {
                return response()->json(['success' => false, 'message' => 'No file path provided']);
            }

            // Get user ID for organizing files
            $userId = auth()->id() ?? 'guest';
            
            // Define destination directory
            $containerDir = "uploads/user_{$userId}/imagecontainer";
            
            // Create the destination directory if it doesn't exist
            if (!Storage::disk('public')->exists($containerDir)) {
                Storage::disk('public')->makeDirectory($containerDir, 0755, true);
            }
            
            // Fix path by removing any duplicate 'uploads/' prefix
            if (strpos($filePath, 'uploads/') === 0) {
                $filePath = substr($filePath, 8); // Remove the 'uploads/' prefix
            }
            
            $sourcePath = storage_path('app/public/uploads/' . $filePath);
            if (!file_exists($sourcePath)) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Source file not found: ' . $sourcePath,
                ]);
            }
            
            // Get the filename
            $filename = basename($sourcePath);
            
            // Move the file to the image container folder
            $newPath = $containerDir . '/' . $filename;
            $success = Storage::disk('public')->move('uploads/' . $filePath, $newPath);
            
            return response()->json([
                'success' => true, 
                'message' => $success ? 'File moved successfully' : 'File not moved but continuing',
                'newPath' => $newPath
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false, 
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Upload images to the image container directory
     */
    public function upload(Request $request)
    {
        // ...existing validation code...
        
        // Get user ID for organizing files
        $userId = auth()->id() ?? 'guest';
        
        // Define the container directory
        $containerDir = "uploads/user_{$userId}/imagecontainer";
        
        // Create the directory if it doesn't exist
        if (!Storage::disk('public')->exists($containerDir)) {
            Storage::disk('public')->makeDirectory($containerDir, 0755, true);
        }
        
        $uploadedImages = [];
        
        // Process the uploaded images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $filename = uniqid() . '_' . time() . '.' . $image->getClientOriginalExtension();
                $path = $image->storeAs($containerDir, $filename, 'public');
                $uploadedImages[] = Storage::disk('public')->url($path);
            }
        }
        
                return response()->json([
                    'success' => true,
                    'message' => 'Images uploaded successfully',
                    'images' => $uploadedImages
                ]);
            }    
    
        public function extract(Request $request)
    {
        // ...existing validation...
        
        // Get user ID for organizing files
        $userId = auth()->id() ?? 'guest';
        
        // Define the container directory
        $containerDir = "uploads/user_{$userId}/imagecontainer";
        
        // Get all images in the container directory
        $images = Storage::disk('public')->files($containerDir);
        
        // Extract text only from these images
        // ...existing extraction code using $images...
        
        // ...rest of your code...
    }

    /**
     * Get images from the image container directory
     */
    public function getContainerImages()
    {
        // Get user ID for organizing files
        $userId = auth()->id() ?? 'guest';
        
        // Define the container directory
        $containerDir = "uploads/user_{$userId}/imagecontainer";
        
        // Create the directory if it doesn't exist
        if (!Storage::disk('public')->exists($containerDir)) {
            Storage::disk('public')->makeDirectory($containerDir, 0755, true);
            return response()->json(['images' => []]);
        }
        
        // Get all images in the container directory
        $files = Storage::disk('public')->files($containerDir);
        
        // Map file paths to URLs
        $images = array_map(function ($file) {
            return Storage::url($file);
        }, $files);
        
        return response()->json(['images' => $images]);
    }

    /**
     * Get images from the graph directory
     */
    public function getGraphImages()
    {
        // Get user ID for organizing files
        $userId = auth()->id() ?? 'guest';
        
        // Define the graph directory
        $graphDir = "uploads/user_{$userId}/graph";
        
        // Create the directory if it doesn't exist
        if (!Storage::disk('public')->exists($graphDir)) {
            Storage::disk('public')->makeDirectory($graphDir, 0755, true);
            return response()->json(['images' => []]);
        }
        
        // Get all images in the graph directory
        $files = Storage::disk('public')->files($graphDir);
        
        // Map file paths to URLs
        $images = array_map(function ($file) {
            return Storage::url($file);
        }, $files);
        
        return response()->json(['images' => $images]);
    }
}
