<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\CourseModuleController;
use App\Http\Controllers\FilesController;
use App\Http\Controllers\ModuleLessonController;
use App\Http\Controllers\WebController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});


Route::get('/admin', [AdminController::class, 'pageListCourses']);

Route::get('/admin/courses', [AdminController::class, 'pageListCourses']);
Route::get('/admin/courses/new', [AdminController::class, 'pageNewCourse']);
Route::get('/admin/courses/{course_id}', [AdminController::class, 'pageCourse']);

Route::post('/admin/courses/add', [CourseController::class, 'addCourse']);
Route::post('/admin/courses/update', [CourseController::class, 'updateCourse']);
Route::post('/admin/courses/delete', [CourseController::class, 'deleteCourse']);


Route::get('/admin/modules/{module_id}', [AdminController::class, 'pageModule']);

Route::post('/admin/modules/add', [CourseModuleController::class, 'addCourseModule']);
Route::post('/admin/modules/update', [CourseModuleController::class, 'updateCourseModule']);
Route::post('/admin/modules/delete', [CourseModuleController::class, 'deleteCourseModule']);

Route::get('/admin/lessons/deletefile', [ModuleLessonController::class, 'deleteFile']);
Route::get('/admin/lessons/{lesson_id}', [AdminController::class, 'pageLesson']);


Route::post('/admin/lessons/add', [ModuleLessonController::class, 'addModuleLesson']);
Route::post('/admin/lessons/update', [ModuleLessonController::class, 'updateModuleLesson']);
Route::post('/admin/lessons/delete', [ModuleLessonController::class, 'deleteModuleLesson']);

Route::post('/admin/lessons/upload', [ModuleLessonController::class, 'uploadFiles']);

Route::get('/', [WebController::class, 'index']);

Route::get('/course/{course_id}', [WebController::class, 'pageCourse']);
Route::get('/module/{module_id}', [WebController::class, 'pageModule']);
Route::get('/lesson/{lesson_id}', [WebController::class, 'pageLesson']);


Route::get('/file/get', [FilesController::class, 'getFile']);
Route::get('/file/download', [FilesController::class, 'downloadFile']);

Route::get('/storage/{file_path}', [FilesController::class, 'storageWrapper']);

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
