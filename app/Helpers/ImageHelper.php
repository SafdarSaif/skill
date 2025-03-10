<?php

use Illuminate\Support\Facades\File;

if (!function_exists('uploadImage')) {
    function uploadImage($image, $folder, $oldImagePath = null)
    {
        $uploadPath = public_path("assets/uploads/images/{$folder}");

        // Delete old image if exists
        if ($oldImagePath && File::exists(public_path($oldImagePath))) {
            File::delete(public_path($oldImagePath));
        }

        $filename = time() . '.' . $image->getClientOriginalExtension();

        if (!File::isDirectory($uploadPath)) {
            File::makeDirectory($uploadPath, 0777, true, true);
        }

        $image->move($uploadPath, $filename);

        return "/assets/uploads/images/{$folder}/{$filename}";
    }
}

if (!function_exists('deleteImage')) {
    function deleteImage($imagePath)
    {
        if ($imagePath) {
            $fullPath = public_path($imagePath);
            if (File::exists($fullPath)) {
                File::delete($fullPath);
            }
        }
    }
}
