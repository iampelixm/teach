<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseModule;
use App\Models\ModuleLesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class WebController extends Controller
{

    public const nav = [
        // [
        //     'link' => '/',
        //     'caption' => 'Курсы'
        // ]
    ];

    public function getTemplateData()
    {
        return [
            'nav' => $this::nav,
            'page_title' => 'SeVe Realty Teach'
        ];
    }

    public function index()
    {
        $template_data = $this->getTemplateData();
        $template_data['courses'] = Course::all();
        return view('user.courses', $template_data);
    }

    public function pageCourse(Request $request, $course_id)
    {
        $template_data = $this->getTemplateData();
        $template_data['course'] = Course::find($course_id);
        return view('user.coursepage', $template_data);
    }

    public function pageModule(Request $request, $module_id)
    {
        $template_data = $this->getTemplateData();
        $template_data['coursemodule'] = CourseModule::find($module_id);
        return view('user.modulepage', $template_data);
    }

    public function pageLesson(Request $request, $lesson_id)
    {
        $template_data = $this->getTemplateData();
        $template_data['modulelesson'] = ModuleLesson::find($lesson_id);
        $template_data['videos'] = Storage::allFiles('lessons/' . $lesson_id . '/video');
        $template_data['documents'] = Storage::allFiles('lessons/' . $lesson_id . '/document');
        $template_data['all_files'] = Storage::allFiles('lessons/' . $lesson_id);
        return view('user.lessonpage', $template_data);
    }
}
