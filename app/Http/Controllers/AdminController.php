<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseModule;
use App\Models\ModuleLesson;
use App\Models\User;
use App\Models\LessonUser;
use App\Models\CourseModuleUser;
use App\Models\CourseUser;
use App\Models\LessonUserAnswer;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Auth;
use Silber\Bouncer\Bouncer;
use Silber\Bouncer\BouncerFacade;
use Silber\Bouncer\Database\Role;

class AdminController extends Controller
{

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
                $nav,
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
        $current_user = BouncerFacade::create(Auth::user());
        if ($current_user->can('manageCourse', User::class)) {
            return view('admin.newcourse', $this->getTemplateData());
        } else {
            abort(403);
        }
    }

    public function pageCourse(Request $request, $course_id)
    {
        $current_user = BouncerFacade::create(Auth::user());
        if ($current_user->can('manageCourse', User::class)) {
            $template_data = $this->getTemplateData();
            $template_data['course'] = Course::find($course_id);
            return view('admin.coursepage', $template_data);
        } else {
            abort(403);
        }
    }

    public function pageListCourses()
    {
        $current_user = BouncerFacade::create(Auth::user());
        if ($current_user->can('manageCourse', User::class)) {
            $template_data = $this->getTemplateData();
            $template_data['courses'] = Course::all();
            return view('admin.courses', $template_data);
        } else {
            abort(403);
        }
    }

    public function pageModule(Request $request, $module_id)
    {
        $current_user = BouncerFacade::create(Auth::user());
        if ($current_user->can('manageCourseModule', User::class)) {
            $template_data = $this->getTemplateData();
            $template_data['coursemodule'] = CourseModule::find($module_id);
            return view('admin.modulepage', $template_data);
        } else {
            abort(403);
        }
    }

    public function pageLesson(Request $request, $lesson_id)
    {
        $current_user = BouncerFacade::create(Auth::user());
        if ($current_user->can('manageModuleLesson', User::class)) {
            $template_data = $this->getTemplateData();
            $template_data['modulelesson'] = ModuleLesson::find($lesson_id);
            $template_data['videos'] = Storage::allFiles('lessons/' . $lesson_id . '/video');
            $template_data['documents'] = Storage::allFiles('lessons/' . $lesson_id . '/document');
            $template_data['all_files'] = Storage::allFiles('lessons/' . $lesson_id);
            return view('admin.lessonpage', $template_data);
        } else {
            abort(403);
        }
    }

    public function pageUserList()
    {

        $current_user = BouncerFacade::create(Auth::user());
        Auth::user()->assign('admin');
        if ($current_user->can('manageUser', User::class)) {
            $template_data = $this->getTemplateData();
            $template_data['page_title'] = 'Управление пользователями';
            $template_data['users'] = User::all();
            return view('admin.users', $template_data);
        } else {
            abort(403);
        }
    }

    public function pageUser(Request $request, $user_id)
    {

        $current_user = BouncerFacade::create(Auth::user());

        if ($current_user->can('manageUser', User::class)) {
            $template_data = $this->getTemplateData();
            $template_data['page_title'] = 'Управление пользователем';
            $template_data['user'] = User::find($user_id);
            $template_data['roles'] = Role::all();
            return view('admin.userPage', $template_data);
        } else {
            abort(403);
        }
    }

    public function updateUser(Request $request)
    {
        $current_user = BouncerFacade::create(Auth::user());

        if ($current_user->can('manageUser', User::class)) {
            $user_id = $request->input('id');
            $userModel = User::find($user_id);
            if (!$userModel) abort(404);

            $record_fields = array_keys($userModel->toArray());
            foreach ($record_fields as $field) {
                if (!empty($request->input($field))) {
                    $userModel->update([$field => $request->input($field)]);
                }
            }

            if (!empty($request->input('password'))) {

                $userModel->password = Hash::make($request->newPassword);
                $userModel->save();
            }

            if (!empty($request->input('roles'))) {
                $allRoles = Role::all();
                $recievedRoles = $request->input('roles');

                foreach ($allRoles as $role) {
                    if (in_array($role->name, $recievedRoles)) {
                        $userModel->assign($role->name);
                    } else {
                        $userModel->retract($role->name);
                    }
                }
            }

            return redirect()->action(
                [AdminController::class, 'pageUser'],
                ['user_id' => $user_id]
            );
        } else {
            abort(403);
        }
    }

    public function makeDefaultPermissions()
    {
        $permission_roles = [
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

        $permission_abilities = [
            'manageUser' => [
                'slug' => 'manageUser',
                'name' => 'manageUser',
                'title' => 'Управление пользователями',
                'presc' => 'Создание, изменение, бан пользователей',
                'on' => User::class
            ],
            'manageUserRole' => [
                'slug' => 'manageUserRole',
                'name' => 'manageUserRole',
                'title' => 'Управление правами пользователями',
                'presc' => 'Изменение полномочий пользователей',
                'on' => User::class
            ],
            'manageCourse' => [
                'slug' => 'manageCourse',
                'name' => 'manageCourse',
                'title' => 'Управление курсами',
                'presc' => 'Создание новых курсов, удаление курсов',
                'on' => Course::class
            ],
            'manageCourseModule' => [
                'slug' => 'manageCourseModule',
                'name' => 'manageCourseModule',
                'title' => 'Управление модулями',
                'presc' => 'Создание, изменение, удаление модулей курсов',
                'on' => CourseModule::class
            ],
            'manageModuleLesson' => [
                'slug' => 'manageModuleLesson',
                'name' => 'manageModuleLesson',
                'title' => 'Управление занятиями',
                'presc' => 'Создание, изменение, удаление занятий.',
                'on' => ModuleLesson::class
            ],
            'manageModuleLessonUserAccess' => [
                'slug' => 'manageModuleLessonUserAccess',
                'name' => 'manageModuleLessonUserAccess',
                'title' => 'Давать доступ к занятиям',
                'presc' => 'Управление доступом учеников к занятиям',
                'on' => LessonUser::class
            ],
            'manageCourseModuleUserAccess' => [
                'slug' => 'manageCourseModuleUserAccess',
                'name' => 'manageCourseModuleUserAccess',
                'title' => 'Давать доступ к модулям',
                'presc' => 'Управление доступом учеников к модулям',
                'on' => CourseModuleUser::class
            ],
            'manageCourseUserAccess' => [
                'slug' => 'manageCourseUserAccess',
                'name' => 'manageCourseUserAccess',
                'title' => 'Давать доступ к курсам',
                'presc' => 'Управление доступом учеников к курсам',
                'on' => CourseUser::class
            ],
        ];

        $role_abilities = [];

        $role_abilities['su'] = array_keys($permission_abilities);

        $role_abilities['admin'] = ['manageUser', 'manageUserRole'];

        $role_abilities['coursemanager'] =
            [
                'manageCourse',
                'manageCourseModule',
                'manageModuleLesson',
                'manageModuleLessonUserAccess',
                'manageCourseModuleUserAccess',
                'manageCourseUserAccess',
            ];

        $role_abilities['teacher'] =
            [
                'manageModuleLessonUserAccess',
                'manageCourseModuleUserAccess',
                'manageCourseUserAccess',
            ];

        $template_data = [];
        $template_data['nav'] = $this->buildNav();
        $template_data['page_content'] = '';



        foreach ($permission_roles as $role) {
            $roleFacade = new Role;
            $roleModel = $roleFacade->firstOrCreate(['name' => $role['name']]);
            $roleModel->title = $role['title'];
            $roleModel->save();
            $template_data['page_content'] .= '<br>Created role: ' . $role['name'] . ' :: ' . $role['title'];

            if (!isset($role_abilities[$role['name']])) $role_abilities[$role['name']] = [];

            foreach ($role_abilities[$role['name']] as $ability_name) {
                if (!empty($permission_abilities[$ability_name])) {
                    $ability = $permission_abilities[$ability_name];
                    BouncerFacade::allow($role['name'])->to($ability['name'], $ability['on']);
                    $template_data['page_content'] .= '<br>++ Allowing: ' . $ability['name'] . ' :: ' . $ability['title'];
                }
            }
        }

        return view('layout.admin', $template_data);
    }
}
