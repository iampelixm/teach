<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseModule;
use App\Models\ModuleLesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use App\Models\User;
use Auth;
//use Silber\Bouncer\Bouncer;
use Silber\Bouncer\BouncerFacade as Bouncer;
use Silber\Bouncer\Database\Role;

class AdminController extends Controller
{

    public const permission_roles = [
        [
            'slug' => 'su',
            'name' => 'su',
            'title' => 'Суперпользователь',
            'presc' => 'Может абсолютно все'
        ],

        [
            'slug' => 'admin',
            'name' => 'admin',
            'title' => 'Администратор',
            'presc' => 'Управление пользователями'
        ],

        [
            'slug' => 'coursemanager',
            'name' => 'coursemanager',
            'title' => 'Менеджер курсов',
            'presc' => 'Полное управление курсами'
        ],

        [
            'slug' => 'teacher',
            'name' => 'teacher',
            'title' => 'Мастер (учитель)',
            'presc' => 'Проверка заданий, открытие уроков'
        ],

        [
            'slug' => 'student',
            'name' => 'student',
            'title' => 'Учащийся',
            'presc' => 'Может смотреть курсы'
        ],
    ];

    public const permission_abilities = [
        [
            'slug' => 'add-user',
            'name' => 'add-user',
            'title' => 'Управление пользователями',
            'presc' => 'Может абсолютно все',
            'on' => User::class
        ],
    ];


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

    public function buildNav()
    {
        $nav = [];
        $user = Auth::user();

        if ($user->isAn('su', 'coursemanager, teacher')) {
            array_push(
                $nav[],
                [
                    'link' => '/admin/courses',
                    'caption' => 'Курсы',
                    'title' => 'Курсы'
                ]
            );
        }


        if ($user->isAn('su', 'coursemanager')) {
            array_push(
                $nav,
                [
                    'link' => '/admin/courses/new',
                    'caption' => 'Добавить курс',
                    'title' => 'Добавить курс'
                ]
            );
        }

        if ($user->isAn('su', 'admin')) {
            array_push(
                $nav,
                [
                    'link' => '#',
                    'caption' => 'Пользователи',
                    'title' => 'Пользователи',
                    'childrens' => [
                        [
                            'link' => '/admin/user',
                            'caption' => 'Пользователи',
                            'title' => 'Пользователи',
                        ],
                        [
                            'link' => '/admin/roles',
                            'caption' => 'Роли',
                            'title' => 'Роли',
                        ]
                    ]
                ]
            );
        }


        return $nav;
    }

    public function getTemplateData()
    {
        return [
            'nav' => $this->buildNav(),
            'page_title' => 'Портал обучения :: админ'
        ];
    }

    public function __construct()
    {
        $this->middleware('auth');
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
        $current_user = Auth::user();
        if ($current_user->isAn('su', 'admin')) {
            Bouncer::allow('admin')->to('addUser', User::class);
            //$current_user->allow('add-users', User::class);
        }

        if ($current_user->can('addUser', User::class)) {
            $template_data = $this->getTemplateData();
            $template_data['page_title'] = 'Управление пользователем';
            $template_data['user'] = User::find($user_id);
            $template_data['roles'] = Role::all();
            return view('admin.userPage', $template_data);
        } else {
            abort(403);
        }
    }
}
