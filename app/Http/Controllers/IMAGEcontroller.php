<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class IMAGEcontroller extends Controller
{
    public function upload(Request $request)
    {
        try {
            $request->validate([
                'images.*' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:5120'],
            ], [
                'images.*.required' => 'Please upload an image.',
                'images.*.mimes' => 'The image must be a file of type: jpeg, png, jpg, svg.',
                'images.*.max' => 'The image size must not exceed 5MB.',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['success' => false, 'message' => 'File type must be a jpeg, png, jpg, or svg.']);
        }
    
        $user = $request->user();
        $userid = $user->user_id;
    
        $subscription = Subscription::where('user_id', $userid)
                        ->whereIn('status', ['Active','Limit Reached'])
                        ->with('promo')
                        ->first();
    
        $imageLimit = $subscription->promo->image_limit ?? 0;
        if(!$subscription) {
            return response()->json(['success' => false, 'message' => 'You are not subscribed to any promos yet.','route' => true]);
        }
    
        // Get the current number of uploaded images
        $directory = storage_path('app/public/uploads/image' . $userid);
        $currentImageCount = 0;
        if (file_exists($directory)) {
            $files = array_diff(scandir($directory), ['.', '..']);
            $currentImageCount = count($files);
        }
    
        $uploadedFiles = [];
        if ($request->hasFile('images')) {
            $newImagesCount = count($request->file('images'));
            if ($currentImageCount + $newImagesCount > $imageLimit) {
                return response()->json(['success' => false, 'message' => 'Image limit exceeded, Upload only '. $imageLimit .' image or less.','imageLimit' => $imageLimit]);
            }
    
            foreach ($request->file('images') as $file) {
                // Create a folder named image{userid} for each user if it doesn't exist
                $folder = 'uploads/image' . $userid;
                if (!Storage::disk('public')->exists($folder)) {
                    Storage::disk('public')->makeDirectory($folder);
                }
                $path = $file->store($folder, 'public');
                $uploadedFiles[] = $path;
            }
        }
    
        return response()->json(['success' => true, 'files' => $uploadedFiles , 'imageLimit' => $imageLimit]);
    }

    public function getUploadedImages(Request $request)
    {
        $user = $request->user();
        $userid = $user->user_id;
    
        $images = [];
        $directory = storage_path('app/public/uploads/image' . $userid);
    
        // Check if directory exists
        if (file_exists($directory)) {
            $files = array_diff(scandir($directory), ['.', '..']); // Get all files in the directory
            foreach ($files as $file) {
                $images[] = asset('storage/uploads/image' . $userid . '/' . $file); // Generate public URL for each file
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

