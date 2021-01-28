<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    /*
    FIELDS:
    course_id
    course_caption
    course_presc
    */

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
        if (!$modelCourse->save()) {
            return back()->withInput();
        }
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
        if (!$modelCourse->save()) {
            return back()->withInput();
        }

        return redirect()->action(
            [AdminController::class, 'pageCourse'],
            ['course_id' => $modelCourse->course_id]
        );
    }
}
