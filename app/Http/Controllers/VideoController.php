<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;
use Illuminate\Support\Facades\Storage;
use FFMpeg\Coordinate\TimeCode;

class VideoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function trim(Request $request)
    {
        $valid = $request->validate(
            [
                'video' => 'required',
                'start' => 'required|min:0',
                'end' => 'required|min:0'
            ]
        );

        if (!$valid) {
            return back()->withInput();
        }

        if (!Storage::exists($request->input('video'))) return abort(404);
        $file_name = collect(explode('/', $request->input('video')))->last();
        $file_info = pathinfo($request->input('video'));

        //return Storage::
        $ffmpeg = FFMpeg::open($request->input('video'));
        $length = $ffmpeg->getDurationInSeconds();
        $start = $request->input('start');
        $end = $length - $request->input('end');
        if ($end <= 0) return 'Нужно взять меньше времени с конца файла';
        $format = new \FFMpeg\Format\Video\X264('copy', 'libx264');
        $ffmpeg->filters()
            ->clip(TimeCode::fromSeconds($start), TimeCode::fromSeconds($end));
        $ffmpeg->save($format, 'storage/' . $file_info['dirname'] . '/' . $file_info['filename'] . '_croped.mp4');
        return 'файл обрезан.';
    }
}
