<?php

namespace App\Http\Controllers;

use App\Models\CourseModule;
use App\Models\ModuleLesson;
use Illuminate\Http\Request;
use App\Models\Log;
use Illuminate\Support\Facades\Auth;

class CourseModuleController extends Controller
{
    /*
    FIELDS:
    module_id
    course_id
    module_caption
    module_presc
    module_quiz 
    */
    public function __construct()
    {
        $this->middleware('auth');
    }    

    public function addCourseModule(Request $request)
    {
        $valid = $request->validate([
            'course_id' => 'required',
            'module_caption' => 'required',
            'module_presc' => 'required',
        ]);
        if (!$valid) return back()->withInput();
        $modelCourseModule = new CourseModule;
        $modelCourseModule->course_id = $request->input('course_id');
        $modelCourseModule->module_caption = $request->input('module_caption');
        $modelCourseModule->module_presc = $request->input('module_presc');
        $modelCourseModule->module_quiz = '[]';

        if (!$modelCourseModule->save()) {
            return back()->withInput()->withErrors('message', 'asdasd', 'asda');
        }

        Log::create([
            'log_message' => 'Создан модуль ' .
                $request->input('module_caption') . ' (' . $modelCourseModule->module_id . ')
            курса ' . $modelCourseModule->course->course_caption .
                '(' . $modelCourseModule->course_id . ')
            пользователем ' .
                Auth::user()->name . ' (' . Auth::user()->id . ')'
        ]);

        return redirect()->action(
            [AdminController::class, 'pageCourse'],
            ['course_id' => $modelCourseModule->course_id]
        );
    }

    public function updateCourseModule(Request $request)
    {
        $valid = $request->validate([
            'module_id' => 'required',
            'course_id' => 'required',
            'module_caption' => 'required',
            'module_presc' => 'required',
        ]);
        if (!$valid) return back()->withInput();

        $modelCourseModule = CourseModule::find($request->input('module_id'));
        $modelCourseModule->course_id = $request->input('course_id');
        $modelCourseModule->module_caption = $request->input('module_caption');
        $modelCourseModule->module_presc = $request->input('module_presc');

        if (!$modelCourseModule->save()) {
            return back()->withInput()->withErrors('message', 'asdasd', 'asda');
        }

        Log::create([
            'log_message' => 'Изменен модуль ' .
                $request->input('module_caption') . ' (' . $modelCourseModule->module_id . ')
            курса ' . $modelCourseModule->course->course_caption .
                '(' . $modelCourseModule->course_id . ')
            пользователем ' .
                Auth::user()->name . ' (' . Auth::user()->id . ')'
        ]);

        return redirect()->action(
            [AdminController::class, 'pageModule'],
            ['module_id' => $modelCourseModule->module_id]
        );
    }

    public function deleteCourseModule(Request $request)
    {
        $valid = $request->validate(['module_id' => 'required']);
        if (!$valid) {
            return 'no';
        }

        $modelCourseModule = CourseModule::find($request->input('module_id'));
        if ($modelCourseModule) {
            foreach ($modelCourseModule->lessons as $lesson) {
                if ($lesson->delete()) {
                    Log::create([
                        'log_message' => 'Удаление занятия '
                            . $lesson->lesson_caption . ' (' . $lesson->lesson_id . ') '
                            . ' при удаление модуля ' .
                            $modelCourseModule->module_caption . ' (' . $modelCourseModule->module_id . ')'
                            . ' пользователем ' .
                            Auth::user()->name . ' (' . Auth::user()->id . ')'
                    ]);
                } else {
                    return 'Не удалилось занятие ' . $lesson->lesson_caption;
                }
            }
            if ($modelCourseModule->delete()) {
                Log::create([
                    'log_message' => 'Удален модуль ' .
                        $modelCourseModule->module_caption . ' (' . $modelCourseModule->module_id . ')
            пользователем ' .
                        Auth::user()->name . ' (' . Auth::user()->id . ')'
                ]);
                return 'ok';
            } else {
                return 'Не удалось удалить модуль';
            }
        } else {
            return 'not found';
        }
    }

    public function setLessonsOrder(Request $request)
    {
        $valid = $request->validate(['module_id' => 'required']);
        if (!$valid) return back();
        $CourseModule = CourseModule::find($request->input('module_id'));

        if (!$CourseModule) abort(404);
        $order = $request->input('order');
        if (!is_array($order)) abort('403');
        //return ModuleLesson::where(['module_id' => $CourseModule->module_id, 'lesson_id' => $order_item['lesson_id']])::get()->toArray();
        foreach ($order as $order_i => $order_item) {
            ModuleLesson::where(['module_id' => $CourseModule->module_id, 'lesson_id' => $order_item['lesson_id']])
                ->update(['lesson_order' => $order_i]);
        }
        return 'ok';
    }
}
