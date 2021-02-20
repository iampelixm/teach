<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseModule;
use App\Models\LessonUserAnswer;
use App\Models\ModuleLesson;
use App\Models\UserLessonProccess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Silber\Bouncer\BouncerFacade;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\User;

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
        $nav = [];
        $user = Auth::user();

        if ($user->isA('su', 'admin', 'coursemanager', 'teache')) {
            $nav[] = [
                'link' => '/admin',
                'caption' => 'Управление',
            ];
        }

        return [
            'nav' => $nav,
            'page_title' => 'SeVe Realty Teach'
        ];
    }

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        if (!BouncerFacade::create($user)->can('viewCourses')) abort(403, 'Нет разрешения на курсы');
        $template_data = $this->getTemplateData();
        $template_data['courses'] = collect($user->courses)->concat(Course::where(['is_access_listed' => 0])->get());
        return view('user.courses', $template_data);
    }

    public function pageCourse(Request $request, $course_id)
    {
        $user = Auth::user();
        if (!BouncerFacade::create($user)->can('viewCourses')) abort(403, 'Нет разрешения на курсы');
        $course = Course::find($course_id);
        //Проверим есть ли такой курс среди разрешенных пользователю для курса с ограничением доступа по списку
        if ($course->is_access_listed) {
            if ($user->courses()->where(['courses.course_id' => $course_id])->get()->isEmpty()) {
                abort(403, 'Нет разрешения курс ' . $course->course_caption);
            }
        }
        $template_data = $this->getTemplateData();
        $a = $course->avaliableModules;
        $template_data['course'] = $course;
        return view('user.coursepage', $template_data);
    }

    public function pageModule(Request $request, $module_id)
    {
        $user = Auth::user();
        if (!BouncerFacade::create($user)->can('viewCourses')) abort(403, 'Нет разрешения на курсы');
        $course_module = CourseModule::find($module_id);

        if ($course_module->course->is_access_listed) {
            //Проверим есть ли такой курс среди разрешенных пользователю для курса с ограничением доступа по списку
            if ($user->courses()->where(['courses.course_id' => $course_module->course->course_id])->get()->isEmpty()) {
                abort(403, 'Нет разрешения на курс ' . $course_module->course->course_caption);
            }
            //проверим доступ к модулю
            if ($user->modules()->where(['course_modules.module_id' => $course_module->module_id])->get()->isEmpty()) {
                abort(403, 'Нет разрешения на модуль ' . $course_module->module_caption);
            }
        }
        $template_data = $this->getTemplateData();
        $template_data['coursemodule'] = $course_module;
        return view('user.modulepage', $template_data);
    }

    public function pageLesson(Request $request, $lesson_id)
    {
        $user = Auth::user();
        if (!BouncerFacade::create($user)->can('viewCourses')) abort(403, 'Нет разрешения на курсы');
        $template_data = $this->getTemplateData();
        $template_data['modulelesson'] = ModuleLesson::find($lesson_id);
        $template_data['videos'] = Storage::allFiles('lessons/' . $lesson_id . '/video');
        $template_data['documents'] = Storage::allFiles('lessons/' . $lesson_id . '/document');
        $template_data['all_files'] = Storage::allFiles('lessons/' . $lesson_id);
        //Если еще нет записи в таблице прохождения уроков - создадим ее со статусом opened (задается по умолчанию)
        if ($user->isA('student')) {
            $lessonProcess = UserLessonProccess::firstOrNew(['user_id' => $user->id, 'lesson_id' => $lesson_id]);
        }
        return view('user.lessonpage', $template_data);
    }

    public function pageLessonTask(Request $request, $lesson_id)
    {
        $user = Auth::user();
        if (!BouncerFacade::create($user)->can('viewCourses')) abort(403, 'Нет разрешения на курсы');
        $template_data = $this->getTemplateData();
        $template_data['modulelesson'] = ModuleLesson::find($lesson_id);
        $template_data['user_answer'] = LessonUserAnswer::where(['user_id' => $user->id, 'lesson_id' => $lesson_id])->first();
        $template_data['answer_files'] = Storage::allFiles('students/' . $user->id . '/lessons/' . $lesson_id);
        $template_data['videos'] = Storage::allFiles('lessons/' . $lesson_id . '/video');
        $template_data['documents'] = Storage::allFiles('lessons/' . $lesson_id . '/document');

        return view('user.lessontaskpage', $template_data);
    }

    public function pageLessonQuiz(Request $request, $lesson_id)
    {
        $user = Auth::user();
        if (!BouncerFacade::create($user)->can('viewCourses')) abort(403, 'Нет разрешения на курсы');
        $template_data = $this->getTemplateData();
        $template_data['modulelesson'] = ModuleLesson::find($lesson_id);
        $template_data['user_answer'] = LessonUserAnswer::where(['user_id' => $user->id, 'lesson_id' => $lesson_id])->get();
        $template_data['videos'] = Storage::allFiles('lessons/' . $lesson_id . '/video');
        $template_data['documents'] = Storage::allFiles('lessons/' . $lesson_id . '/document');
        return view('user.lessonquizpage', $template_data);
    }
}
