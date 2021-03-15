<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use App\Models\Log;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    /*
    FIELDS:
    course_id
    course_caption
    course_presc
    */

    public function __construct()
    {
        $this->middleware('auth');
    }    

    public function addCourse(Request $request)
    {
        $valid = $request->validate([
            'course_caption' => 'required',
            'course_presc' => 'required'
        ]);
        if (!$valid) return back()->withInput();

        $modelCourse = new Course;
        $modelCourse->course_caption = $request->input('course_caption');
        $modelCourse->course_presc = $request->input('course_presc');
        $modelCourse->is_access_listed = $request->input('is_access_listed') || 0;
        if (!$modelCourse->save()) {
            return back()->withInput();
        }

        Log::create([
            'log_message' => 'Создан курс ' .
                $request->input('course_caption') . ' (' . $modelCourse->course_id . ')
            пользователем ' .
                Auth::user()->name . ' (' . Auth::user()->id . ')'
        ]);

        return redirect()->action(
            [AdminController::class, 'pageCourse'],
            ['course_id' => $modelCourse->course_id]
        );
    }

    public function updateCourse(Request $request)
    {
        $valid = $request->validate([
            'course_id' => 'required',
            'course_caption' => 'required',
            'course_presc' => 'required'
        ]);
        if (!$valid) return back()->withInput();

        $modelCourse = Course::find($request->input('course_id'));
        if (!$modelCourse) {
            return back()->withInput();
        }
        $modelCourse->course_caption = $request->input('course_caption');
        $modelCourse->course_presc = $request->input('course_presc');
        $modelCourse->is_access_listed = $request->input('is_access_listed') || 0;
        if (!$modelCourse->save()) {
            return back()->withInput();
        }
        Log::create([
            'log_message' => 'Изменен курс ' .
                $request->input('course_caption') . ' (' . $modelCourse->course_id . ')
            пользователем ' .
                Auth::user()->name . ' (' . Auth::user()->id . ')'
        ]);
        return redirect()->action(
            [AdminController::class, 'pageCourse'],
            ['course_id' => $modelCourse->course_id]
        );
    }

    public function deleteCourse(Request $request)
    {
        $valid = $request->validate(['course_id' => 'required']);
        if (!$valid) {
            return 'no';
        }

        $modelCourse = Course::find($request->input('course_id'));
        if ($modelCourse) {
            if (collect($modelCourse->modules)->isEmpty()) {
                if ($modelCourse->delete()) {
                    Log::create([
                        'log_message' => 'Удален курс ' .
                            $modelCourse->course_caption . ' (' . $modelCourse->course_id . ')
                пользователем ' .
                            Auth::user()->name . ' (' . Auth::user()->id . ')'
                    ]);
                    return 'ok';
                } else {
                    return 'Не удалось удалить курс';
                }
            } else {
                return 'У данного курса есть модули. Удалите сначала все модули, только потом удаляйте курс';
            }
        } else {
            return 'not found';
        }
    }
}
