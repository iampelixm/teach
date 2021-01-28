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

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id', 'course_id');
    }

    public function lessons()
    {
        return $this->hasMany(ModuleLesson::class, 'module_id', 'module_id');
    }

    public function availableLessons()
    {
        $user = Auth::user();

        if ($this->course->is_access_listed) {
            //TODO - разберись уже с этими блядскими отношениями
            return $this->hasMany(ModuleLesson::class, 'module_id', 'module_id')
                ->whereIn('lesson_id', LessonUser::select(['lesson_id'])->where('user_id', $user->id));
        } else {
            return $this->hasMany(ModuleLesson::class, 'module_id');
        }
    }
}
