<?php

namespace App\Models;

use App\Http\Controllers\ModuleLessonController;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Silber\Bouncer\Database\HasRolesAndAbilities;
use Illuminate\Support\Facades\Auth;

class ModuleLesson extends Model
{
    use HasFactory, HasRolesAndAbilities;

    public $timestamps = false;
    protected $primaryKey = 'lesson_id';
    protected $fillable = ['lesson_order'];

    public function module()
    {
        return $this->belongsTo(CourseModule::class, 'module_id', 'module_id');
    }

    public function status(User $user = null)
    {
        if (!$user) {
            $user = Auth::user();
            return $this->hasOne(UserLessonProccess::class, 'lesson_id', 'lesson_id')->where('user_id', $user->id);
        } else {
            return $this->hasOne(UserLessonProccess::class, 'lesson_id', 'lesson_id')->where('user_id', $user->id)->first() 
                    ??
                    UserLessonProccess::create(['lesson_id'=>$this->lesson_id, 'user_id'=>$user->id, 'lesson_status'=>'notseen']);
        }
    }

    public function isDone($user_id = 0)
    {
        if (!$user_id) $user_id = Auth::user()->id;
        return $this->hasOne(UserLessonProccess::class, 'lesson_id', 'lesson_id')->where(['user_id' => $user_id, 'lesson_status' => 'done']);
    }

    public function checkDone(User $user = null)
    {
        if($this->lesson_task)
        {
            if(!$this->userAnswer || !$this->userAnswer->answer_text)
            {
                return false;
            }
        }

        if($this->lesson_quiz)
        {
            $ModuleLessonController=new ModuleLessonController();
            if(!$ModuleLessonController->checkQuiz($this, $user)) return false;
        }

        return true;
    }

    public function userHasAccess($user_id = 0)
    {
        if (!$user_id) $user_id = Auth::user()->id;
        return $this->hasOne(LessonUser::class, 'lesson_id', 'lesson_id')->where(['user_id' => $user_id]);
    }

    public function userAnswer($user_id = 0)
    {
        if (!$user_id) $user_id = Auth::user()->id;
        return $this->hasOne(LessonUserAnswer::class, 'lesson_id', 'lesson_id')->where(['user_id' => $user_id]);
    }
}
