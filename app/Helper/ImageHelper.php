<?php

namespace App\Helper;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic;
class ImageHelper
{
    public static function save_image(UploadedFile $image, $uploadPath = 'uploads')
    {
        // Validate the image
        $image->validate([
            'image' => 'required|image|mimes:jpeg,jpg,png,gif|max:10480', // Adjust max size as needed
        ]);

        // Generate a unique file name
        $fileName = uniqid() . '_' . time() . '.' . $image->getClientOriginalExtension();

        // Store the image in the specified path
        $imagePath = $image->storeAs($uploadPath, $fileName, 'public');

        // Generate and return the image URL
        $imageUrl = asset('storage/' . $imagePath);

        return $imageUrl;
    }
}
