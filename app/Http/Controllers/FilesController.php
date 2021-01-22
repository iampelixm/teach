<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FilesController extends Controller
{
    public function getFile(Request $request)
    {
        $file_path = $request->query('file');
        //return storage_path($file_path);
        //return response()->file('storage/app/' . $file_path);
        //return Storage::mimeType($file_path);
        //return Storage::get($file_path);
        return response(Storage::get($file_path), 200)
            ->header('Content-type', Storage::mimeType($file_path))
            ->header('Content-length', Storage::size($file_path))
            ->header('Content-Disposition', 'inline; filename="file.mp4"');
    }

    public function downloadFile(Request $request)
    {
        $file_path = $request->query('file');
        return Storage::download($file_path);
    }

    public function downloadLink($file_path)
    {
        return '/file/download/' . $file_path;
    }

    public function storageWrapper($file_path)
    {
        return Storage::get($file_path);
    }
}
