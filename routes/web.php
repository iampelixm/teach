<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\API\BOT\TelegramController;
use App\Http\Controllers\Auth\RegisterController;
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
use App\Http\Controllers\TelegramBotController;
use App\Http\Controllers\TelegramBotConversationChainController;
use App\Http\Controllers\TelegramBotConversationChainItemController;
use App\Http\Controllers\TelegramBotCommandController;
use App\Http\Controllers\TelegramBotCommandActionController;
use App\Http\Controllers\VideoController;
use Telegram\Bot\Laravel\Facades\Telegram;
use App\Models\TelegramBot;

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

// Route::get('/api/telegram/registerwebhook/{$bot}', [TelegramController::class, 'registerWebhook'])
//     ->name('admin.telegram_bot.setWebhook');
// Route::get('/bot/{$bot}/me', function () {
//     // echo '/' . config()->telegram;
//     //dd(config('telegram')['bots'][config('telegram')['default']]['token']);
//     $me = Telegram::getMe();
//     dd($me);
// });


Route::prefix('/admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'pageListCourses'])
        ->name('home');

    Route::get('/study/activity', [AdminController::class, 'pageStudyProcessActivity'])->name('studyprocess.activity');
    Route::get('/study/bystudent', [AdminController::class, 'pageStudyProcessByStudent'])->name('studyprocess.bystudent');
    Route::get('/study/progress', [AdminController::class, 'pageStudyProcessProgress'])->name('studyprocess.progress');

    Route::name('user.')->prefix('/user')->group(function () {
        Route::get('/', [AdminController::class, 'pageUserList'])
            ->name('list');
        Route::get('/new', [AdminController::class, 'pageAddUser'])
            ->name('new');
        Route::post('/updateLessonAccess', [UserAccessController::class, 'updateLessonAccess'])
            ->name('updatelessonaccess');
        Route::post('/addLessonAccess', [UserAccessController::class, 'addLessonAccess'])
            ->name('addlessonaccess');
        Route::post('/add', [UserAccessController::class, 'addUser'])
            ->name('add');
        Route::post('/update', [UserAccessController::class, 'updateUser'])
            ->name('update');
        Route::post('/disable', [UserAccessController::class, 'updateUser'])
            ->name('disable');
        Route::post('/delete', [UserAccessController::class, 'deleteUser'])
            ->name('delete');
        Route::get('/{user_id}', [AdminController::class, 'pageUser'])
            ->name('userpage');
        Route::get('/{user}/current_lessons', [AdminController::class, 'pageUserCurrentLessons'])
            ->name('current_lessons');
    });

    Route::get('/courses', [AdminController::class, 'pageListCourses']);
    Route::get('/courses/new', [AdminController::class, 'pageNewCourse']);
    Route::get('/courses/{course_id}', [AdminController::class, 'pageCourse']);

    Route::post('/courses/add', [CourseController::class, 'addCourse']);
    Route::post('/courses/update', [CourseController::class, 'updateCourse']);
    Route::post('/courses/delete', [CourseController::class, 'deleteCourse']);

    Route::post('/courses/setModuleOrder', [CourseController::class, 'setModuleOrder'])
        ->name('courses.setModuleOrder');

    Route::name('modules.')->group(function () {
        Route::get('/modules/{module_id}', [AdminController::class, 'pageModule'])
            ->name('modulePage');
        Route::post('/modules/add', [CourseModuleController::class, 'addCourseModule'])
            ->name('add');
        Route::post('/modules/update', [CourseModuleController::class, 'updateCourseModule'])
            ->name('update');
        Route::post('/modules/delete', [CourseModuleController::class, 'deleteCourseModule'])
            ->name('delete');
        Route::post('/modules/setLessonsOrder', [CourseModuleController::class, 'setLessonsOrder'])
            ->name('setLessonOrder');
    });

    Route::name('lesson.')->prefix('/lessons')->group(function () {
        Route::get('/deletefile', [ModuleLessonController::class, 'deleteFile']);
        Route::get('/{lesson_id}', [AdminController::class, 'pageLesson'])->name('edit');
        Route::post('/{lesson_id}/ckeditor-image', [ModuleLessonController::class, 'ckeditorUpload'])->name('ckeditor-image');

        Route::post('/add', [ModuleLessonController::class, 'addModuleLesson'])->name('add');
        Route::post('/update', [ModuleLessonController::class, 'updateModuleLesson'])->name('update');
        Route::post('/delete', [ModuleLessonController::class, 'deleteModuleLesson'])->name('delete');

        Route::post('/upload', [ModuleLessonController::class, 'uploadFiles'])->name('uploadFiles');
    });
    Route::get('/log', [AdminController::class, 'pageLog']);

    Route::post('/video/trim', [VideoController::class, 'trim'])->name('video.trim');

    Route::resource('/telegram_bot', TelegramBotController::class); //->name('index', 'telegram_bot');
    Route::get('/telegram_bot/{bot}/registerwebhook', [TelegramBotController::class, 'registerWebhook'])
        ->name('telegram_bot.register_webhook');
    Route::resource('/telegram_bot.conversation_chain', TelegramBotConversationChainController::class);
    Route::resource('/telegram_bot.conversation_chain.chain_item', TelegramBotConversationChainItemController::class);
    Route::resource('/telegram_bot.command', TelegramBotCommandController::class);
    Route::resource('/telegram_bot.command.action', TelegramBotCommandActionController::class);
});

Route::prefix('/')->name('web.')->group(
    function () {

        Route::get('/', [WebController::class, 'index']);
        Route::get('home', [WebController::class, 'index']);

        Route::get('/course/{course_id}', [WebController::class, 'pageCourse']);

        Route::name('module.')->group(function () {
            Route::get('/module/{module_id}', [WebController::class, 'pageModule']);
            Route::get('/module/{module_id}/end', [WebController::class, 'pageModuleEnd'])->name('endPage');
        });
        Route::get('/lesson/{lesson_id}/done', [WebController::class, 'checkDoneLesson'])->name('lesson.done');
        Route::get('/lesson/{lesson_id}', [WebController::class, 'pageLesson'])->name('lessonPage');


        Route::get('/lessontask/{lesson_id}', [WebController::class, 'pageLessonTask'])->name('lessonTask');
        Route::post('/userlessonanswer', [LessonUserAnswerController::class, 'saveUserAnswer']);
        Route::post('/userlessonquiz', [LessonUserAnswerController::class, 'saveUserQuiz']);

        Route::get('/quizresult/{lesson_id}/{user_id?}', [WebController::class, 'pageQuizResult'])->name('quizresult');
        Route::get('/lessonquiz/{lesson_id}', [WebController::class, 'pageLessonQuiz'])->name('lessonQuiz');

        Route::get('/file/get', [FilesController::class, 'getFile']);
        Route::get('/file/download', [FilesController::class, 'downloadFile']);

        Route::get('/storage/{file_path}', [FilesController::class, 'storageWrapper']);

        Route::get('/profile', [WebController::class, 'userProfile'])->name('profile');
        Route::post('/profile/update', [WebController::class, 'userProfileUpdate'])->name('profile.update');
    }
);
