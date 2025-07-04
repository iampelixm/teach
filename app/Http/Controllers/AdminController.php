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
use App\Models\Log;
use App\Models\UserLessonProccess;
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

    static function buildNav()
    {
        $nav = [];
        $user = Auth::user();
        $menu_courses = [
            'link' => '#',
            'caption' => 'Курсы',
            'title' => 'Курсы',
            'childrens' => []
        ];
        if ($user->isAn('su', 'coursemanager', 'teacher')) {
            $menu_courses['childrens'][] =
                [
                    'link' => '/admin/courses',
                    'caption' => 'Все',
                    'title' => 'Все',
                ];
        }

        if ($user->isA('su', 'coursemanager')) {
            $menu_courses['childrens'][] =
                [
                    'link' => '/admin/courses/new',
                    'caption' => 'Добавить курс',
                    'title' => 'Добавить курс'
                ];
        }
        array_push($nav, $menu_courses);

        if ($user->isAn('su', 'teacher')) {
            array_push(
                $nav,
                [
                    'link' => '#',
                    'caption' => 'Учебный процесс',
                    'title' => 'Учебный процесс',
                    'childrens' => [
                        [
                            'link' => route('admin.studyprocess.activity'),
                            'caption' => 'Последние события',
                            'title' => 'Последние события',
                        ],
                        [
                            'link' => route('admin.studyprocess.bystudent'),
                            'caption' => 'По ученикам',
                            'title' => 'По ученикам',
                        ],
                        [
                            'link' => route('admin.studyprocess.progress'),
                            'caption' => 'По ученикам',
                            'title' => 'По ученикам',
                        ]
                    ]
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
                            'link' => '/admin/user/new',
                            'caption' => 'Создать',
                            'title' => 'Создать',
                        ],
                        [
                            'link' => '/admin/roles',
                            'caption' => 'Роли',
                            'title' => 'Роли',
                        ]
                    ]
                ],
                [
                    'link' => '/admin/log',
                    'caption' => 'Лог',
                    'title' => 'Лог'
                ],
                [
                    'link' => '/admin/telegram_bot',
                    'caption' => 'Боты',
                    'title' => 'Боты'
                ]
            );
        }
        return $nav;
    }

    static function getTemplateData()
    {
        return [
            'nav' => AdminController::buildNav(),
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

    public function pageStudyProcessActivity(Request $request)
    {
        $courses = new Course();
        $modules = new CourseModule();
        $lessons = new ModuleLesson();
        $user_answers = new LessonUserAnswer();
        $students = new User();

        $lessons_filter_array = [];
        //фильтруем каскадно начиная от ответов пользователей и далее занятие - модуль - урок
        if ($request->query('student_id')) {
            $students = $students->where('id', $request->query('student_id'));
            $user_answers = $user_answers->where('user_id', $request->query('student_id'));
        }

        if ($request->query('lesson_id')) {
            $user_answers = $user_answers->where('lesson_id', $request->query('lesson_id'));
            //$lessons=$lessons->where('lesson_id', $user_answers->pluck('lesson_id')->toArray());
        }

        if ($request->query('course_id')) {
            $courses = $courses->where('course_id', $request->query('course_id'));
            $user_answers = $user_answers->whereIn('lesson_id', $courses->first()->lessons->pluck('lesson_id')->toArray());
            $modules = $modules->where('course_id', $request->query('course_id'));
            //$lessons=$lessons->whereIn('module_id', $modules->pluck('module_id')->toArray());
            #$courses = $courses->where('course_id', $request->query('course_id'));
            #$modules = $modules->where('course_id', $request->query('course_id'));
        }

        if ($request->query('module_id')) {
            //$lessons=$lessons->where('module_id', $request->query('module_id'));
            #$modules = $modules->where('module_id', $request->query('module_id'));
            #$courses = $courses->where('course_id', $modules->first()->course->course_id ?? '*');
        }

        $students = $students->whereIn('id', $user_answers->pluck('user_id')->toArray());
        $lessons = $lessons->whereIn('module_id', $modules->pluck('module_id')->toArray());

        $template_data = $this->getTemplateData();

        $template_data['courses'] = $courses->get();
        $template_data['modules'] = $modules->get();
        $template_data['lessons'] = $lessons->get();
        $template_data['user_answers'] = $user_answers->orderBy('answer_id', 'DESC')->get();
        $template_data['students'] = $students->get();
        return view('admin.studyprocess.activity', $template_data);
    }

    public function pageStudyProcessByStudent(Request $request)
    {
        $student = '';
        $lesson_process = '';
        if ($request->query('student_id')) {
            $student = User::find($request->query('student_id'));
            $lesson_process = UserLessonProccess::where(['user_id' => $student->id])->orderBy('updated_at', 'DESC')->get();
        }
        $template_data = $this->getTemplateData();
        $template_data['student'] = $student;
        $template_data['lesson_process'] = $lesson_process;
        return view('admin.studyprocess.bystudent', $template_data);
    }
    
    public function pageStudyProcessProgress(Request $request)
    {

        $template_data = $this->getTemplateData();
        // $template_data['student'] = $student;
        // $template_data['lesson_process'] = $lesson_process;
        return view('admin.studyprocess.progress', $template_data);
    }

    public function pageNewCourse()
    {
        $current_user = BouncerFacade::create(Auth::user());
        if ($current_user->can('manageCourse', Course::class)) {
            return view('admin.newcourse', $this->getTemplateData());
        } else {
            abort(403);
        }
    }

    public function pageCourse(Request $request, $course_id)
    {
        $current_user = BouncerFacade::create(Auth::user());
        if ($current_user->can('manageCourse', Course::class)) {
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
        if ($current_user->can('manageCourse', Course::class)) {
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
        if ($current_user->can('manageCourseModule', CourseModule::class)) {
            $template_data = $this->getTemplateData();
            $template_data['coursemodule'] = CourseModule::find($module_id);
            if (!$template_data['coursemodule']) abort(404, 'Модуль не найден');
            return view('admin.modulepage', $template_data);
        } else {
            abort(403);
        }
    }

    public function pageLesson(Request $request, $lesson_id)
    {
        $current_user = BouncerFacade::create(Auth::user());
        if ($current_user->can('manageModuleLesson', ModuleLesson::class)) {
            $template_data = $this->getTemplateData();
            $template_data['modulelesson'] = ModuleLesson::find($lesson_id);

            if (!$template_data['modulelesson']) {
                abort('404', 'Занятие не найдено ((( ');
            }
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
        if (!$current_user->can('manageUser', User::class)) abort(403);

        $template_data = $this->getTemplateData();
        $template_data['page_title'] = 'Управление пользователем';
        $template_data['user'] = User::find($user_id);
        $template_data['roles'] = Role::all();
        $template_data['courses'] = Course::where('is_access_listed', 1)->get();
        return view('admin.userPage', $template_data);
    }

    public function pageAddUser()
    {
        $current_user = BouncerFacade::create(Auth::user());
        if (!$current_user->can('manageUser', User::class)) abort(403);

        $template_data = $this->getTemplateData();
        $template_data['page_title'] = 'Управление пользователем';
        return view('admin.userAdd', $template_data);
    }

    public function pageLog(Request $request)
    {
        $template_data = $this->getTemplateData();
        $template_data['log'] = Log::orderBy('log_id', 'DESC')->paginate('300')->all();
        return view('admin.log', $template_data);
    }

    public function pageUserCurrentLessons(User $user)
    {
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
            'viewCourses' => [
                'slug' => 'viewCourses',
                'name' => 'viewCourses',
                'title' => 'Смотреть курсы',
                'presc' => 'Основная роль для ученика',
                'on' => null
            ],
        ];

        $role_abilities = [];

        $role_abilities['su'] = array_keys($permission_abilities);

        $role_abilities['admin'] =
            [
                'manageUser',
                'manageUserRole',
                'viewCourses'
            ];

        $role_abilities['coursemanager'] =
            [
                'manageCourse',
                'manageCourseModule',
                'manageModuleLesson',
                'manageModuleLessonUserAccess',
                'manageCourseModuleUserAccess',
                'manageCourseUserAccess',
                'viewCourses',
            ];

        $role_abilities['teacher'] =
            [
                'manageModuleLessonUserAccess',
                'manageCourseModuleUserAccess',
                'manageCourseUserAccess',
                'viewCourses',
            ];

        $role_abilities['student'] =
            [
                'viewCourses',
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
