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
use App\Models\LessonUser;
use App\Models\Log;
use Illuminate\Support\Facades\Hash;

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
        $template_data['courses'] = collect($user->courses);//->concat(Course::where(['is_access_listed' => 0])->get());
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

    public function pageModuleEnd(Request $request, $module_id)
    {
        $template_data = $this->getTemplateData();
        return view('user.moduleendpage', $template_data);
    }

    public function pageLesson(Request $request, $lesson_id)
    {
        $user = Auth::user();
        if (!BouncerFacade::create($user)->can('viewCourses')) abort(403, 'Нет разрешения на курсы');

        $lesson = ModuleLesson::find($lesson_id);
        if (!$lesson) abort('404', 'Такой урок не найден(((');
        $template_data = $this->getTemplateData();
        $template_data['modulelesson'] = $lesson;
        $template_data['videos'] = Storage::allFiles('lessons/' . $lesson_id . '/video');
        $template_data['documents'] = Storage::allFiles('lessons/' . $lesson_id . '/document');
        $template_data['all_files'] = Storage::allFiles('lessons/' . $lesson_id);
        $template_data['next_lesson'] = $lesson->module->availableLessons->where('lesson_order', '>', $lesson->lesson_order)->first();
        //Если еще нет записи в таблице прохождения уроков - создадим ее со статусом opened (задается по умолчанию)
        if ($user->isA('student')) {
            $lessonProcess = UserLessonProccess::firstOrCreate(['user_id' => $user->id, 'lesson_id' => $lesson_id]);
            //$lessonProcess->save();
        }
        return view('user.lessonpage', $template_data);
    }

    public function checkDoneLesson(Request $request, $lesson_id)
    {
        $lesson = ModuleLesson::find($lesson_id);
        if (!$lesson) abort(404);
        //Если есть задание проверим есть ли выполнение
        $lesson_answer = $lesson->userAnswer; //LessonUserAnswer::where(['lesson_id' => $lesson_id, 'user_id' => Auth::user()->id])->first();
        if ($lesson->lesson_task) {
            if (!$lesson_answer) {
                return redirect(route('web.lessonTask', ['lesson_id' => $lesson_id]))
                    ->withErrors(['Для завершения урока необходимо выполнить задание']);
            }
            if (!$lesson_answer->answer_text) {
                return redirect(route('web.lessonTask', ['lesson_id' => $lesson_id]))
                ->withErrors(['В ответе на задание должен быть хоть какой-то текст']);
            }
        }
        $modulelessoncontroller = new ModuleLessonController;
        if ($lesson->lesson_quiz) {
            if (!$modulelessoncontroller->checkQuiz($lesson_id)) {
                return redirect(route('web.lessonQuiz', ['lesson_id' => $lesson_id]))
                    ->withErrors(['quizcheckfalied' => 'Вы не ответили правильно на достаточное количество вопросов. Необходима пересдача теста']);
            }
        }

        //Дадим доступ к следующему уроку в этом модуле
        $next_lesson = $lesson->module->lessons->where('lesson_order', '>', $lesson->lesson_order)->first();

        //Если это последний урок - отправим пользователя на информационную страницу об окончании модуля
        if (!$next_lesson) return redirect(route('web.module.endPage', ['module_id' => $lesson->module->module_id]));

        //Если не последнее занятие - дадим доступ в следующему занятию и отправим на страницу занятия
        if (!$next_lesson->userHasAccess) {
            LessonUser::firstOrCreate(['lesson_id' => $next_lesson->lesson_id, 'user_id' => Auth::user()->id]);
            Log::create([
                'log_message' => 'Открыт доступ к следующему уроку ' . $next_lesson->lesson_caption . '(' . $next_lesson->lesson_id . ') для ученика' .
                    Auth::user()->name . ' (' . Auth::user()->id . ')'
            ]);
        }
        return redirect(route('web.lessonPage', ['lesson_id' => $next_lesson->lesson_id]));
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

    public function quizResult($lesson_id, $user_id='')
    {
        if(!$user_id) $user_id=Auth::user()->id;
        $lesson = ModuleLesson::find($lesson_id);
        $answer=LessonUserAnswer::where(['lesson_id'=>$lesson_id,'user_id'=>$user_id])->first();
        if(!$answer) return abort(404);
        $quiz = json_decode($lesson->lesson_quiz);
        $quiz_answer = json_decode($answer->answer_quiz);
        $out_data = [];
        foreach ($quiz as $question_i => $question) {
            $question_data= [];
            $question_data['question'] = $question;
            if(!isset($quiz_answer[$question_i])) return abort(403,'Результат не соответствует квизу');
            $question_data['answer'] = $quiz_answer[$question_i];
            //$quiz_answer = $quiz_answer[$question_i];
            if (in_array('yes', $question->answer_correct)) {
                foreach ($question_data['answer']->answered as $answer_i => $answer_value) {
                    $correct_aswer = array_search('yes', $question->answer_correct);
                    $answer_question_index = array_search($answer_value->value, $question->answer_variant);
                    if ($correct_aswer == $answer_question_index) {
                        $question_data['answer']->correct = true;
                    } else {
                        $question_data['answer']->correct = false;
                    }
                }
            } else {
                $question_data['answer']->correct = true;
            }
            $out_data[] = $question_data;
        }
        return $out_data;        
    }

    public function pageQuizResult($lesson_id, $user_id='')
    {
        if(!$user_id) $user_id=Auth::user()->id;
        $quiz_result=$this->quizResult($lesson_id, $user_id);
        $template_data = $this->getTemplateData();
        $template_data['quiz_result']= $quiz_result;
        $template_data['modulelesson']=ModuleLesson::find($lesson_id);
        return view('user.quizresultpage', $template_data);
    }

    public function userProfile()
    {
        $user = Auth::user();
        $template_data = $this->getTemplateData();
        $template_data['user'] = $user;
        return view('user.profile', $template_data);
    }

    public function userProfileUpdate(Request $request)
    {
        $valid = $request->validate([
            'name' => 'string|min:3',
            'id' => 'integer',
            'password' => 'nullable|string|min:4'
        ]);

        if (!$valid) return back()->withInput();

        $user = User::find($request->id);
        if (!$user) abort('404');
        $user->name = $request->name;

        if ($request->password) {
            $user->password = Hash::make($request->input('password'));
        }
        $user->save();
        return redirect(route('web.profile'));
    }
}
