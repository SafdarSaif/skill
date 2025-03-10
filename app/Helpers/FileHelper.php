<?php

use Illuminate\Support\Facades\File;

if (!function_exists('uploadFile')) {
    function uploadFile($file, $folder, $oldFilePath = null)
    {
        $uploadPath = public_path("assets/uploads/{$folder}");

        // Delete old file if it exists
        if ($oldFilePath && File::exists(public_path($oldFilePath))) {
            File::delete(public_path($oldFilePath));
        }

        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

        if (!File::isDirectory($uploadPath)) {
            File::makeDirectory($uploadPath, 0777, true, true);
        }

        $file->move($uploadPath, $filename);

        return "/assets/uploads/{$folder}/{$filename}";
    }
}

if (!function_exists('deleteFile')) {
    function deleteFile($filePath)
    {
        if ($filePath) {
            $fullPath = public_path($filePath);
            if (File::exists($fullPath)) {
                File::delete($fullPath);
            }
        }
    }
}
