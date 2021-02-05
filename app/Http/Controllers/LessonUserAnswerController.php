<?php

namespace App\Http\Controllers;

use App\Models\LessonUserAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LessonUserAnswerController extends Controller
{
    public function saveUserAnswer(Request $request)
    {
        $valid = $request->validate(
            [
                'lesson_id' => 'required'
            ]
        );
        if (!$valid) return back()->withInput();

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

        //UPDATE RECORD
        if ($request->input('answer_id')) {
            $userAnswer = LessonUserAnswer::find($request->input('answer_id'));
        } else {
            $userAnswer = new LessonUserAnswer;
            $userAnswer->user_id = Auth::user()->id;
            $userAnswer->lesson_id = $request->input('lesson_id');
            $userAnswer->answer_text = '';
        }
        $userAnswer->answer_quiz = json_encode($request->input('answers'), JSON_UNESCAPED_UNICODE);
        $userAnswer->save();
        return back();
    }
}
