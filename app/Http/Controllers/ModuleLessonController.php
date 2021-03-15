<?php

namespace App\Http\Controllers;

use App\Models\ModuleLesson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\LessonUserAnswer;

class ModuleLessonController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function addModuleLesson(Request $request)
    {
        $valid = $request->validate([
            'module_id' => 'required',
            'lesson_caption' => 'required',
            'lesson_presc' => 'required',
            'lesson_text' => 'required'
        ]);
        if (!$valid) return back()->withInput();

        $modelModuleLesson = new ModuleLesson;
        $modelModuleLesson->module_id = $request->input('module_id');
        $modelModuleLesson->lesson_caption = $request->input('lesson_caption');
        $modelModuleLesson->lesson_presc = $request->input('lesson_presc');
        $modelModuleLesson->lesson_text = $request->input('lesson_text');

        $current_max_order = ModuleLesson::where(['module_id' => $request->input('module_id')])->max('lesson_order');
        if (!$current_max_order) $current_max_order = 0;

        $modelModuleLesson->lesson_order = ++$current_max_order;

        if ($request->input('lesson_task')) {
            $modelModuleLesson->lesson_task = $request->input('lesson_task');
        }
        $modelModuleLesson->lesson_additional = '[]';

        if (!$modelModuleLesson->save()) {
            return back()->withInput();
        }

        Log::create([
            'log_message' => 'Создан урок ' .
                $request->input('lesson_caption') . ' (' . $modelModuleLesson->lesson_id . ')
            пользователем ' .
                Auth::user()->name . ' (' . Auth::user()->id . ')'
        ]);

        return redirect()->action(
            [AdminController::class, 'pageLesson'],
            ['lesson_id' => $modelModuleLesson->lesson_id]
        );
    }

    public function updateModuleLesson(Request $request)
    {
        $valid = $request->validate([
            'lesson_id' => 'required'
        ]);
        if (!$valid) return back()->withInput();

        $lesson_id = $request->input('lesson_id');
        $modelModuleLesson = ModuleLesson::find($lesson_id);
        if (!$modelModuleLesson->save()) {
            return back()->withInput();
        }

        foreach (array_keys($modelModuleLesson->toArray()) as $field) {

            if ($request->has($field)) {
                $modelModuleLesson->$field = $request->input($field);
            }
        }
        $modelModuleLesson->save();

        Log::create([
            'log_message' => 'Изменен урок ' .
                $request->input('lesson_caption') . ' (' . $modelModuleLesson->lesson_id . ')
            пользователем ' .
                Auth::user()->name . ' (' . Auth::user()->id . ')'
        ]);

        return redirect()->action(
            [AdminController::class, 'pageLesson'],
            ['lesson_id' => $modelModuleLesson->lesson_id]
        );
    }

    public function deleteModuleLesson(Request $request)
    {
        $valid = $request->validate(['lesson_id' => 'required']);
        if (!$valid) {
            return 'no';
        }

        $modelModuleLesson = ModuleLesson::find($request->input('lesson_id'));
        if ($modelModuleLesson) {
            $modelModuleLesson->delete();
            Log::create([
                'log_message' => 'Удален урок ' .
                    $modelModuleLesson->lesson_caption . ' (' . $modelModuleLesson->lesson_id . ')
            пользователем ' .
                    Auth::user()->name . ' (' . Auth::user()->id . ')'
            ]);
            return 'ok';
        } else {
            return 'not found';
        }
    }

    public function uploadFiles(Request $request)
    {
        $request->validate([
            'lesson_id' => 'required',
            'type' => 'required'
        ]);

        $lesson_id = $request->input('lesson_id');
        $type = $request->input('type');
        $modelModuleLesson = ModuleLesson::find($lesson_id);

        $path = 'lessons/' . $lesson_id . '/' . $type;
        $name = $request->file('file')->getClientOriginalName();

        $file_path = Storage::putFileAs($path, $request->file('file'), $name);
        Storage::setVisibility($file_path, 'public');
        return redirect()->action(
            [AdminController::class, 'pageLesson'],
            ['lesson_id' => $lesson_id]
        );
    }

    public function deleteFile(Request $request)
    {
        $request->validate([
            'file' => 'required'
        ]);

        $lesson_id = $request->input('lesson_id');
        $file = $request->input('file');
        Storage::delete($file);
        return back();
    }

    public function checkQuiz($lesson_id, $user_id = 0)
    {
        $minimal_percentage = 70;
        if (!$user_id) $user_id = Auth::user()->id;

        $lesson = ModuleLesson::find($lesson_id);
        if (!$lesson) return false;
        $answer = LessonUserAnswer::where(['lesson_id' => $lesson_id, 'user_id' => $user_id])->first();
        if (!$answer) return false;
        $quiz = json_decode($lesson->lesson_quiz);
        $answered = json_decode($answer->answer_quiz);

        $result = [];
        $result['total_answers'] = 1;
        $result['correct_answers'] = 1;
        foreach ($quiz as $question_i => $question) {
            $question_answer = $answered[$question_i];
            //Считаем правильные ответы в вопросе только если таковые указаны
            if (in_array('yes', $question->answer_correct)) {
                $result['total_answers']++;
                //echo "<br> Q HAS CORRECT ANSWER";
                foreach ($question_answer->answered as $answer_i => $answer_value) {
                    //print_r($answer_value);
                    //echo "<BR> CHECKING ANSWER $answer_value->value";
                    $correct_aswer = array_search('yes', $question->answer_correct);
                    $answer_question_index = array_search($answer_value->value, $question->answer_variant);
                    //echo "<br>ANSWER INDEX: $answer_question_index OF VALUE $answer_value->value WHERE CORRECT IS $correct_aswer";
                    if ($correct_aswer == $answer_question_index) {
                        $result['correct_answers']++;
                    }
                }
            }
            //$correct_aswer = array_search('yes', $question->answer_correct);
            //$answered_index=array_search()
            //echo "<br>C: $correct_aswer";
        }
        $per = ($result['correct_answers'] / $result['total_answers']) * 100;
        echo "PER: $per";
        if ($per >= $minimal_percentage) return true;

        return false;
        /*
        print_r($quiz);
        echo '<hr>';
        dd($answered);
        */
    }
}
