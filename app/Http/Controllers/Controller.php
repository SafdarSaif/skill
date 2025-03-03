<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function uploadImage($image, $file_path){
        $extension = $image->getClientOriginalExtension();
        $imagename = time() . '.' . $extension;
        $image->move(public_path($file_path), $imagename);
        return $file_path . '/' . $imagename;
    }

}
