<?php

namespace App\Http\Controllers;

use App\Models\CourseModule;
use Illuminate\Http\Request;

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

    public function addCourseModule(Request $request)
    {
        $request->validate([
            'course_id' => 'required',
            'module_caption' => 'required',
            'module_presc' => 'required',
        ]);

        $modelCourseModule = new CourseModule;
        $modelCourseModule->course_id = $request->input('course_id');
        $modelCourseModule->module_caption = $request->input('module_caption');
        $modelCourseModule->module_presc = $request->input('module_presc');
        $modelCourseModule->module_quiz = '[]';

        if (!$modelCourseModule->save()) {
            return back()->withInput()->withErrors('message', 'asdasd', 'asda');
        }

        return redirect()->action(
            [AdminController::class, 'pageCourse'],
            ['course_id' => $modelCourseModule->course_id]
        );
    }

    public function updateCourseModule(Request $request)
    {
        $request->validate([
            'module_id' => 'required',
            'course_id' => 'required',
            'module_caption' => 'required',
            'module_presc' => 'required',
        ]);

        $modelCourseModule = CourseModule::find($request->input('module_id'));
        $modelCourseModule->course_id = $request->input('course_id');
        $modelCourseModule->module_caption = $request->input('module_caption');
        $modelCourseModule->module_presc = $request->input('module_presc');

        if (!$modelCourseModule->save()) {
            return back()->withInput()->withErrors('message', 'asdasd', 'asda');
        }

        return redirect()->action(
            [AdminController::class, 'pageModule'],
            ['module_id' => $modelCourseModule->module_id]
        );
    }
}
