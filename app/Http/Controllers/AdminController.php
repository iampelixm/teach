<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseModule;
use App\Models\ModuleLesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Models\User;
use Bouncer;

class AdminController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public const nav = [
        [
            'link' => '/admin',
            'caption' => 'Начало'
        ],
        [
            'link' => '/admin/courses',
            'caption' => 'Курсы'
        ],
        [
            'link' => '/admin/courses/new',
            'caption' => 'Добавить курс'
        ]
    ];

    public function getTemplateData()
    {
        return [
            'nav' => $this::nav,
            'page_title' => 'Портал обучения :: админ'
        ];
    }


    public function index()
    {
        return view('admin.index', $this->getTemplateData());
    }

    public function pageNewCourse()
    {
        return view('admin.newcourse', $this->getTemplateData());
    }

    public function pageCourse(Request $request, $course_id)
    {
        $template_data = $this->getTemplateData();
        $template_data['course'] = Course::find($course_id);
        return view('admin.coursepage', $template_data);
    }

    public function pageListCourses()
    {
        $template_data = $this->getTemplateData();
        $template_data['courses'] = Course::all();
        return view('admin.courses', $template_data);
    }

    public function pageModule(Request $request, $module_id)
    {
        $template_data = $this->getTemplateData();
        $template_data['coursemodule'] = CourseModule::find($module_id);
        return view('admin.modulepage', $template_data);
    }

    public function pageLesson(Request $request, $lesson_id)
    {
        $template_data = $this->getTemplateData();
        $template_data['modulelesson'] = ModuleLesson::find($lesson_id);
        $template_data['videos'] = Storage::allFiles('lessons/' . $lesson_id . '/video');
        $template_data['documents'] = Storage::allFiles('lessons/' . $lesson_id . '/document');
        $template_data['all_files'] = Storage::allFiles('lessons/' . $lesson_id);
        return view('admin.lessonpage', $template_data);
    }

    public function pageUserList()
    {
        $template_data = $this->getTemplateData();
        $template_data['page_title'] = 'Управление пользователями';
        $template_data['users'] = User::all();
        return view('admin.users', $template_data);
    }

    public function pageUser(Request $request, $user_id)
    {
        $template_data = $this->getTemplateData();
        $template_data['page_title'] = 'Управление пользователем';
        $template_data['user'] = User::find($user_id);
        return view('admin.userPage', $template_data);
    }
}
