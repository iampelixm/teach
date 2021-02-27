<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\CourseModuleController;
use App\Http\Controllers\FilesController;
use App\Http\Controllers\ModuleLessonController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WebController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\UserAccessController;
use App\Models\LessonUserAnswer;
use App\Http\Controllers\LessonUserAnswerController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\VideoController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Auth::routes();

Route::get('/tech/buildPermissions', [AdminController::class, 'makeDefaultPermissions']);

Route::prefix('/admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'pageListCourses'])
        ->name('homme');

    Route::get('/user', [AdminController::class, 'pageUserList'])
        ->name('user');

    Route::get('/user/new', [AdminController::class, 'pageAddUser'])
        ->name('user.new');

    Route::post('/user/updateLessonAccess', [UserAccessController::class, 'updateLessonAccess'])
        ->name('user.updatelessonaccess');

    Route::post('/user/addLessonAccess', [UserAccessController::class, 'addLessonAccess'])
        ->name('user.addlessonaccess');

    Route::get('/user/{user_id}', [AdminController::class, 'pageUser'])
        ->name('user.userpage');

    Route::post('/user/add', [UserAccessController::class, 'pageAddUser']);
    Route::post('/user/update', [UserAccessController::class, 'updateUser']);
    Route::post('/user/disable', [UserAccessController::class, 'pageAddUser']);
    Route::post('/user/delete', [UserAccessController::class, 'pageAddUser']);

    Route::get('/courses', [AdminController::class, 'pageListCourses']);
    Route::get('/courses/new', [AdminController::class, 'pageNewCourse']);
    Route::get('/courses/{course_id}', [AdminController::class, 'pageCourse']);

    Route::post('/courses/add', [CourseController::class, 'addCourse']);
    Route::post('/courses/update', [CourseController::class, 'updateCourse']);
    Route::post('/courses/delete', [CourseController::class, 'deleteCourse']);


    Route::get('/modules/{module_id}', [AdminController::class, 'pageModule']);

    Route::post('/modules/add', [CourseModuleController::class, 'addCourseModule']);
    Route::post('/modules/update', [CourseModuleController::class, 'updateCourseModule']);
    Route::post('/modules/delete', [CourseModuleController::class, 'deleteCourseModule']);

    Route::get('/lessons/deletefile', [ModuleLessonController::class, 'deleteFile']);
    Route::get('/lessons/{lesson_id}', [AdminController::class, 'pageLesson']);


    Route::post('/lessons/add', [ModuleLessonController::class, 'addModuleLesson']);
    Route::post('/lessons/update', [ModuleLessonController::class, 'updateModuleLesson']);
    Route::post('/lessons/delete', [ModuleLessonController::class, 'deleteModuleLesson']);

    Route::post('/lessons/upload', [ModuleLessonController::class, 'uploadFiles']);

    Route::get('/log', [AdminController::class, 'pageLog']);

    Route::post('/video/trim', [VideoController::class, 'trim'])->name('video.trim');
});

Route::prefix('/')->name('web.')->group(
    function () {

        Route::get('/', [WebController::class, 'index']);

        Route::get('/course/{course_id}', [WebController::class, 'pageCourse']);
        Route::get('/module/{module_id}', [WebController::class, 'pageModule']);
        Route::get('/lesson/{lesson_id}', [WebController::class, 'pageLesson'])->name('lesson');

        Route::get('/lessontask/{lesson_id}', [WebController::class, 'pageLessonTask']);
        Route::post('/userlessonanswer', [LessonUserAnswerController::class, 'saveUserAnswer']);
        Route::post('/userlessonquiz', [LessonUserAnswerController::class, 'saveUserQuiz']);

        Route::get('/lessonquiz/{lesson_id}', [WebController::class, 'pageLessonQuiz']);

        Route::get('/file/get', [FilesController::class, 'getFile']);
        Route::get('/file/download', [FilesController::class, 'downloadFile']);

        Route::get('/storage/{file_path}', [FilesController::class, 'storageWrapper']);

        Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    }
);
