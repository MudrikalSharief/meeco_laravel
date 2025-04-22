<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class IMAGEcontroller extends Controller
{

    public function checkimagesize(Request $request){
        $user = Auth::user();
        $userid = $user->user_id;
        $imageLimit = 5;
        
        $currentImageCount = 0;

        // Get the current number of uploaded images in the first directory
        $directory1 = storage_path('app/public/uploads/user_' . $userid . '/imagecontainer');
        if (file_exists($directory1)) {
            $files1 = array_diff(scandir($directory1), ['.', '..']);
            $currentImageCount += count($files1);
        }

        // Get the current number of uploaded images in the second directory
        $directory2 = storage_path('app/public/uploads/user_' . $userid . '/graph');
        if (file_exists($directory2)) {
            $files2 = array_diff(scandir($directory2), ['.', '..']);
            $currentImageCount += count($files2);
        }
        
        // Check if there are no images
        $noImages = ($currentImageCount == 0);

        if($currentImageCount > $imageLimit) {
            return response()->json([
                'success' => false, 
                'message' => 'Image limit exceeded. Upload only '. $imageLimit .' image or less.',
                'imageLimit' => $imageLimit,
                'currentCount' => $currentImageCount,
                'noImages' => $noImages
            ]);
        }
        // You don't need to check for uploaded files in a GET request
        return response()->json([
            'success' => true, 
            'message' => 'Image limit is not exceeded. Upload only '. $imageLimit .' image or less.',
            'imageLimit' => $imageLimit,
            'currentCount' => $currentImageCount,
            'noImages' => $noImages
        ]);
    }
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
        
        
        foreach ($request->file('images') as $file) {
            // Create a folder named image{userid} for each user if it doesn't exist
            $folder = 'uploads/user_' . $userid . '/imagecontainer';
            $path = $file->store($folder, 'public');
            $uploadedFiles[] = $path;
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
    
    // Check if the file exists in storage
    if (Storage::disk('public')->exists($filePath)) {
        Storage::disk('public')->delete($filePath);
        return response()->json(['success' => true]);
    }
    
    // If file not found at the original path, try adding 'uploads/' prefix
    if (Storage::disk('public')->exists('uploads/' . $filePath)) {
        Storage::disk('public')->delete('uploads/' . $filePath);
        return response()->json(['success' => true]);
    }
    
    return response()->json(['success' => false, 'message' => 'File not found.', 'path' => $filePath]);
}
}

