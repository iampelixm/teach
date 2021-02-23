<?php

namespace App\Http\Controllers;

use App\Models\ModuleLesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Log;
use Illuminate\Support\Facades\Auth;

class ModuleLessonController extends Controller
{
    public function addModuleLesson(Request $request)
    {
        $valid = $request->validate([
            'module_id' => 'required',
            'lesson_caption' => 'required',
            'lesson_presc' => 'required',
            'lesson_text' => 'required'
        ]);
        if (!$valid) return back()->withInput();

        $modelModuleLesson = new ModuleLesson;
        $modelModuleLesson->module_id = $request->input('module_id');
        $modelModuleLesson->lesson_caption = $request->input('lesson_caption');
        $modelModuleLesson->lesson_presc = $request->input('lesson_presc');
        $modelModuleLesson->lesson_text = $request->input('lesson_text');

        if ($request->input('lesson_task')) {
            $modelModuleLesson->lesson_task = $request->input('lesson_task');
        }
        $modelModuleLesson->lesson_additional = '[]';

        if (!$modelModuleLesson->save()) {
            return back()->withInput();
        }

        Log::create([
            'log_message' => 'Создан урок ' .
                $request->input('lesson_caption') . ' (' . $modelModuleLesson->lesson_id . ')
            пользователем ' .
                Auth::user()->name . ' (' . Auth::user()->id . ')'
        ]);

        return redirect()->action(
            [AdminController::class, 'pageLesson'],
            ['lesson_id' => $modelModuleLesson->lesson_id]
        );
    }

    public function updateModuleLesson(Request $request)
    {
        $valid = $request->validate([
            'lesson_id' => 'required',
            'lesson_caption' => 'required',
            'lesson_presc' => 'required',
            'lesson_text' => 'required'
        ]);
        if (!$valid) return back()->withInput();

        $lesson_id = $request->input('lesson_id');
        $modelModuleLesson = ModuleLesson::find($lesson_id);
        if (!$modelModuleLesson->save()) {
            return back()->withInput();
        }

        foreach (array_keys($modelModuleLesson->toArray()) as $field) {

            if ($request->has($field)) {
                $modelModuleLesson->$field = $request->input($field);
            }
        }
        $modelModuleLesson->save();

        Log::create([
            'log_message' => 'Изменен урок ' .
                $request->input('lesson_caption') . ' (' . $modelModuleLesson->lesson_id . ')
            пользователем ' .
                Auth::user()->name . ' (' . Auth::user()->id . ')'
        ]);

        return redirect()->action(
            [AdminController::class, 'pageLesson'],
            ['lesson_id' => $modelModuleLesson->lesson_id]
        );
    }

    public function deleteModuleLesson(Request $request)
    {
        $valid = $request->validate(['lesson_id' => 'required']);
        if (!$valid) {
            return 'no';
        }

        $modelModuleLesson = ModuleLesson::find($request->input('lesson_id'));
        if ($modelModuleLesson) {
            $modelModuleLesson->delete();
            Log::create([
                'log_message' => 'Удален урок ' .
                    $modelModuleLesson->lesson_caption . ' (' . $modelModuleLesson->lesson_id . ')
            пользователем ' .
                    Auth::user()->name . ' (' . Auth::user()->id . ')'
            ]);
            return 'ok';
        } else {
            return 'not found';
        }
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
