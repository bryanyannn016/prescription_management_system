<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\File;

class FileController extends Controller
{
    public function show($id)
    {
        // Retrieve the file by its ID
        $file = File::findOrFail($id);

        // Construct the full path to the file
        $filePath = public_path($file->file_path); // Using the stored path directly

        // Check if the file exists
        if (!file_exists($filePath)) {
            return abort(404, 'File not found.');
        }

        // Return the file as a response
        return response()->file($filePath);
    }
}


