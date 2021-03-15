<?php

namespace App\Http\Controllers;

use App\Models\LessonUserAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Log;
use App\Models\ModuleLesson;
use App\Models\UserLessonProccess;
use App\Models\LessonUser;

class LessonUserAnswerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function saveUserAnswer(Request $request)
    {

        $valid = $request->validate(
            [
                'lesson_id' => 'required'
            ]
        );
        if (!$valid) return back()->withInput();

        $lesson = ModuleLesson::find($request->input('lesson_id'));

        if (!$lesson) abort(404);

        //UPDATE RECORD
        if ($request->input('answer_id')) {
            $userAnswer = LessonUserAnswer::find($request->input('answer_id'));
        } else {
            $userAnswer = new LessonUserAnswer;
            $userAnswer->user_id = Auth::user()->id;
            $userAnswer->lesson_id = $request->input('lesson_id');
            $userAnswer->answer_quiz = '[]';
        }
        $userAnswer->answer_text = $request->input('answer_text');
        $userAnswer->save();

        Log::create([
            'log_message' => 'Ответ ученика ' .
                Auth::user()->name . ' (' . Auth::user()->id . ')'
                . ' на задание урока ' . $userAnswer->lesson_id
        ]);

        if (!$lesson->lesson_quiz) {
            $proccess = UserLessonProccess::where(['lesson_id' => $lesson->lesson_id, 'user_id' => Auth::user()->id])->first();
            if (!$proccess) {
                $proccess = UserLessonProccess::create(['lesson_id' => $lesson->lesson_id, 'user_id' => Auth::user()->id, 'lesson_status' => 'done']);
            }
            if ($proccess->lesson_status != 'done') {
                $proccess->lesson_status = 'done';
                $proccess->save();
                Log::create([
                    'log_message' => 'Урок занятия ' . $lesson->lesson_caption . '(' . $lesson->lesson_id . ') помечен как выполненный для ученика' .
                        Auth::user()->name . ' (' . Auth::user()->id . ')'
                ]);
            }
            $next_lesson = $lesson->module->availableLessons->where('lesson_order', '>', $lesson->lesson_order)->first();
            if ($next_lesson) {
                if (!$next_lesson->userHasAccess) {
                    LessonUser::firstOrCreate(['lesson_id' => $next_lesson->lesson_id, 'user_id' => Auth::user()->id]);
                    Log::create([
                        'log_message' => 'Открыт доступ к следующему уроку ' . $firstOrCreate->lesson_caption . '(' . $firstOrCreate->lesson_id . ') для ученика' .
                            Auth::user()->name . ' (' . Auth::user()->id . ')'
                    ]);
                }
            } else {
                return redirect(route('web.module.endPage', ['module_id' => $lesson->module->module_id]));
            }
        }
        return back();
    }

    public function saveUserQuiz(Request $request)
    {
        $valid = $request->validate(
            [
                'lesson_id' => 'required'
            ]
        );
        if (!$valid) return back()->withInput();
        $lesson_id = $request->input('lesson_id');
        $lessonUserAnswer = LessonUserAnswer::where(['lesson_id' => $lesson_id, 'user_id' => Auth::user()->id])->first();
        if (!$lessonUserAnswer) {
            $lessonUserAnswer = LessonUserAnswer::create(
                [
                    'lesson_id' => $lesson_id,
                    'user_id' => Auth::user()->id,
                    'answer_text' => '',
                    'answer_quiz' => '[]'
                ]
            );
        }

        $lessonUserAnswer->answer_quiz = json_encode($request->input('answers'), JSON_UNESCAPED_UNICODE);
        $lessonUserAnswer->save();

        Log::create([
            'log_message' => 'Ответ ученика ' .
                Auth::user()->name . ' (' . Auth::user()->id . ')'
                . ' на квиз урока ' . $lessonUserAnswer->lesson_id
        ]);
        return back();
    }
}
