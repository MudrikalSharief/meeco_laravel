<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class IMAGEcontroller extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'images.*' => ['required','image','mimes:jpeg,png,jpg,gif,svg,','max:2048'],
        ]);

        $uploadedFiles = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $path = $file->store('uploads', 'public');
                $uploadedFiles[] = $path;
            }
        }

        return response()->json(['success' => true, 'files' => $uploadedFiles]);
    }

    public function getUploadedImages()
    {
        $images = [];
        $directory = storage_path('app/public/uploads');
        
        // Check if directory exists
        if (file_exists($directory)) {
            $files = array_diff(scandir($directory), ['.', '..']); // Get all files in the directory
            foreach ($files as $file) {
                $images[] = asset('storage/uploads/' . $file); // Generate public URL for each file
            }
        }

        return response()->json(['images' => $images]);
    }

    public function deleteImage(Request $request)
    {
        $filePath = $request->input('filePath');
        if (Storage::disk('public')->exists($filePath)) {
            Storage::disk('public')->delete($filePath);
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false, 'message' => 'File not found.']);
    }
}

