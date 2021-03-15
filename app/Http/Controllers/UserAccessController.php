<?php

namespace App\Http\Controllers;

use App\Models\CourseUser;
use App\Models\CourseModuleUser;
use App\Models\LessonUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Silber\Bouncer\Bouncer;
use Silber\Bouncer\BouncerFacade;
use Illuminate\Support\Facades\Hash;
use Silber\Bouncer\Database\Role;
use App\Models\User;
use Illuminate\Support\Facades\Schema;

class UserAccessController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function updateUser(Request $request)
    {
        if (!BouncerFacade::create(Auth::user())->can('manageUser', User::class)) abort(403, 'Вы не можете изменять данные пользователей');

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

            $userModel->password = Hash::make($request->input('password'));
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
    }

    public function addUser(Request $request)
    {
        if (!BouncerFacade::create(Auth::user())->can('manageUser', User::class)) abort(403, 'Вы не можете изменять данные пользователей');

        $userModel = new User;

        $record_fields = Schema::getColumnListing('users');
        foreach ($record_fields as $field) {
            if (!empty($request->input($field))) {
                $userModel->$field = $request->input($field);
            }
        }

        if (!empty($request->input('password'))) {

            $userModel->password = Hash::make($request->input('password'));
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
            ['user_id' => $userModel->id]
        );
    }

    /*
        Очищает таблицы доступа перед внесением данных
        Используется только в случае, когда в интерфейсе указаны все необходимые доступы
    */
    public function updateLessonAccess(Request $request)
    {

        if (!BouncerFacade::create(Auth::user())->can('manageModuleLessonUserAccess', LessonUser::class)) abort(403, 'Нет разрешение на управление доступом пользователей к занятиям');

        $user_id = $request->input('id');
        $courses = $request->input('courses');
        $modules = $request->input('modules');
        $lessons = $request->input('lessons');

        if ($courses) {
            $del = CourseUser::where('user_id', '=', $user_id)->delete();
            //dd($del);
            foreach ($courses as $course) {
                CourseUser::updateOrCreate(['user_id' => $user_id, 'course_id' => $course]);
            }
        }

        if ($modules) {
            CourseModuleUser::where('user_id', '=', $user_id)->delete();
            foreach ($modules as $module) {
                CourseModuleUser::updateOrCreate(['user_id' => $user_id, 'module_id' => $module]);
            }
        }

        if ($lessons) {
            LessonUser::where('user_id', '=', $user_id)->delete();
            foreach ($lessons as $lesson) {
                LessonUser::updateOrCreate(['user_id' => $user_id, 'lesson_id' => $lesson]);
            }
        }

        return redirect()->action(
            [AdminController::class, 'pageUser'],
            ['user_id' => $user_id]
        );
    }

    /*
        Добавляет новые доступы не очищая таблицу,
        для существующих доступов просто обновляет записи (updated_at)
        Используется когда нужно только добавить новые доступы
    */
    public function addLessonAccess(Request $request)
    {
        if (!BouncerFacade::create(Auth::user())->can('manageModuleLessonUserAccess', LessonUser::class)) abort(403);

        $user_id = $request->input('id');
        $courses = $request->input('courses');
        $modules = $request->input('modules');
        $lessons = $request->input('lessons');

        if ($courses) {
            foreach ($courses as $course) {
                CourseUser::updateOrCreate(['user_id' => $user_id, 'course_id' => $course]);
            }
        }

        if ($modules) {
            foreach ($modules as $module) {
                CourseModuleUser::updateOrCreate(['user_id' => $user_id, 'module_id' => $module]);
            }
        }

        if ($lessons) {
            foreach ($lessons as $lesson) {
                LessonUser::updateOrCreate(['user_id' => $user_id, 'lesson_id' => $lesson]);
            }
        }

        return redirect()->action(
            [AdminController::class, 'pageUser'],
            ['user_id' => $user_id]
        );
    }

    /*
        Только удаляет доступы 
    */
    public function deleteLessonAccess(Request $request)
    {
        if (!BouncerFacade::create(Auth::user())->can('manageModuleLessonUserAccess', LessonUser::class)) abort(403);
        $user_id = $request->input('id');
        $courses = $request->input('courses');
        $modules = $request->input('modules');
        $lessons = $request->input('lessons');

        if ($courses) {
            foreach ($courses as $course) {
                CourseUser::where(['user_id' => $user_id, 'course_id' => $course])->delete();
            }
        }

        if ($modules) {
            foreach ($modules as $module) {
                CourseModuleUser::where(['user_id' => $user_id, 'module_id' => $module])->delete();
            }
        }

        if ($lessons) {
            foreach ($lessons as $lesson) {
                LessonUser::where(['user_id' => $user_id, 'lesson_id' => $lesson])->delete();
            }
        }

        return redirect()->action(
            [AdminController::class, 'pageUser'],
            ['user_id' => $user_id]
        );
    }
}
