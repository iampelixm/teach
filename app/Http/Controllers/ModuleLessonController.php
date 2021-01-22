<?php

namespace App\Http\Controllers;

use App\Models\ModuleLesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ModuleLessonController extends Controller
{
    public function addModuleLesson(Request $request)
    {
        $request->validate([
            'module_id' => 'required',
            'lesson_caption' => 'required',
            'lesson_presc' => 'required',
            'lesson_text' => 'required'
        ]);

        $modelModuleLesson = new ModuleLesson;
        $modelModuleLesson->module_id = $request->input('module_id');
        $modelModuleLesson->lesson_caption = $request->input('lesson_caption');
        $modelModuleLesson->lesson_presc = $request->input('lesson_presc');
        $modelModuleLesson->lesson_text = $request->input('lesson_text');
        $modelModuleLesson->lesson_additional = '[]';

        if (!$modelModuleLesson->save()) {
            return back()->withInput()->withErrors('message', 'asdasd', 'asda');
        }

        return redirect()->action(
            [AdminController::class, 'pageLesson'],
            ['lesson_id' => $modelModuleLesson->lesson_id]
        );
    }

    public function updateModuleLesson(Request $request)
    {
        $request->validate([
            'lesson_id' => 'required',
            'module_id' => 'required',
            'lesson_caption' => 'required',
            'lesson_presc' => 'required',
            'lesson_text' => 'required'
        ]);
        $lesson_id = $request->input('lesson_id');
        $modelModuleLesson = ModuleLesson::find($lesson_id);
        if (!$modelModuleLesson->save()) {
            return back()->withInput()->withErrors('message', 'asdasd', 'asda');
        }
        $modelModuleLesson->module_id = $request->input('module_id');
        $modelModuleLesson->lesson_caption = $request->input('lesson_caption');
        $modelModuleLesson->lesson_presc = $request->input('lesson_presc');
        $modelModuleLesson->lesson_text = $request->input('lesson_text');
        $modelModuleLesson->lesson_additional = '[]';

        if (!$modelModuleLesson->save()) {
            return back()->withInput()->withErrors('message', 'asdasd', 'asda');
        }

        return redirect()->action(
            [AdminController::class, 'pageLesson'],
            ['lesson_id' => $modelModuleLesson->lesson_id]
        );
    }

    public function uploadFiles(Request $request)
    {
        $request->validate([
            'lesson_id' => 'required',
            'type' => 'required'
        ]);

        $lesson_id = $request->input('lesson_id');
        $type = $request->input('type');
        $modelModuleLesson = ModuleLesson::find($lesson_id);

        $path = 'lessons/' . $lesson_id . '/' . $type;
        $name = $request->file('file')->getClientOriginalName();

        $file_path = Storage::putFileAs($path, $request->file('file'), $name);
        Storage::setVisibility($file_path, 'public');
        return redirect()->action(
            [AdminController::class, 'pageLesson'],
            ['lesson_id' => $lesson_id]
        );
    }

    public function deleteFile(Request $request)
    {
        $request->validate([
            'file' => 'required'
        ]);

        $lesson_id = $request->input('lesson_id');
        $file = $request->input('file');
        Storage::delete($file);
        return back();
    }
}
