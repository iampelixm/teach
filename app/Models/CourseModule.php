<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Silber\Bouncer\Database\HasRolesAndAbilities;
use Illuminate\Support\Facades\Auth;

class CourseModule extends Model
{
    use HasFactory, HasRolesAndAbilities;

    public $timestamps = false;
    protected $primaryKey = 'module_id';

    protected $attributes = [
        'module_order' => 0,
    ];    

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id', 'course_id');
    }

    public function lessons()
    {
        return $this->hasMany(ModuleLesson::class, 'module_id', 'module_id')->orderBy('lesson_order');
    }

    public function isDone(User $user=null)
    {
        if (!$user)
            $user = Auth::user();
        
        foreach($this->lessons as $lesson)
        {
            if(!$lesson->checkDone($user)) return false;
        }
        return true;
    }

    public function availableLessons($user = '')
    {
        if (!$user)
            $user = Auth::user();

        if ($this->course->is_access_listed) {
            //TODO - разберись уже с этими блядскими отношениями
            return $this->hasMany(ModuleLesson::class, 'module_id', 'module_id')
                ->whereIn('lesson_id', LessonUser::select(['lesson_id'])->where('user_id', $user->id))
                ->orderBy('lesson_order');
        } else {
            return $this->hasMany(ModuleLesson::class, 'module_id')->orderBy('lesson_order');
        }
    }

    public function doneAvailableLessons($user = '')
    {
        if (!$user)
            $user = Auth::user();

        if ($this->course->is_access_listed) {

            return $this->hasMany(ModuleLesson::class, 'module_id', 'module_id')
                ->whereIn('lesson_id', LessonUser::select(['lesson_id'])->where('user_id', $user->id))
                ->whereIn('lesson_id', UserLessonProccess::select(['lesson_id'])
                    ->where(['user_id' => $user->id, 'lesson_id' => $this->lesson_id, 'lesson_status' => 'done']))
                ->orderBy('lesson_order');
        } else {
            return $this->hasMany(ModuleLesson::class, 'module_id')->orderBy('lesson_order');
        }
    }

    public function notDoneAvailableLessons($user = '')
    {
        if (!$user)
            $user = Auth::user();

        if ($this->course->is_access_listed) {

            return $this->hasMany(ModuleLesson::class, 'module_id', 'module_id')
                ->whereIn('lesson_id', LessonUser::select(['lesson_id'])->where('user_id', $user->id))
                ->whereNotIn('lesson_id', UserLessonProccess::select(['lesson_id'])
                    ->where(['user_id' => $user->id, 'lesson_id' => $this->lesson_id, 'lesson_status' => 'done']))
                ->orderBy('lesson_order');
        } else {
            return $this->hasMany(ModuleLesson::class, 'module_id')->orderBy('lesson_order');
        }
    }

    public function doneLessons(User $user=null)
    {
        if (!$user)
            $user = Auth::user();
        //Доуступ к курсу только по списку доступа
        if ($this->course->is_access_listed) {

            return $this->hasMany(ModuleLesson::class, 'module_id', 'module_id')
                ->whereIn('lesson_id', UserLessonProccess::select(['lesson_id'])
                    ->where(['user_id' => $user->id, 'lesson_id' => $this->lesson_id, 'lesson_status' => 'done']))
                ->orderBy('lesson_order');
        } else {
            return $this->hasMany(ModuleLesson::class, 'module_id')->orderBy('lesson_order');
        }
    }

    public function notDoneLessons($user = '')
    {
        if (!$user)
            $user = Auth::user();

        if ($this->course->is_access_listed) {

            return $this->hasMany(ModuleLesson::class, 'module_id', 'module_id')
                ->whereNotIn('lesson_id', UserLessonProccess::select(['lesson_id'])
                    ->where(['user_id' => $user->id, 'lesson_id' => $this->lesson_id, 'lesson_status' => 'done']))
                ->orderBy('lesson_order');
        } else {
            return $this->hasMany(ModuleLesson::class, 'module_id')->orderBy('lesson_order');
        }
    }
}
