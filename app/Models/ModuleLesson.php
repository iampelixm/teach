<?php

namespace App\Models;

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

    public function status($user_id = 0)
    {
        if (!$user_id) $user_id = Auth::user()->id;
        return $this->hasOne(UserLessonProccess::class, 'lesson_id', 'lesson_id')->where('user_id', $user_id);
    }

    public function isDone($user_id = 0)
    {
        if (!$user_id) $user_id = Auth::user()->id;
        return $this->hasOne(UserLessonProccess::class, 'lesson_id', 'lesson_id')->where(['user_id' => $user_id, 'lesson_status' => 'done']);
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
