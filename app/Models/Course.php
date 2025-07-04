<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Silber\Bouncer\Database\HasRolesAndAbilities;
use Illuminate\Support\Facades\Auth;

class Course extends Model
{
    use HasFactory, HasRolesAndAbilities;

    public $timestamps = false;
    protected $primaryKey = 'course_id';

    public function modules()
    {
        return $this->hasMany(CourseModule::class, 'course_id', 'course_id')->orderBy('module_order');
    }

    public function availableModules()
    {
        $user = Auth::user();

        if ($this->is_access_listed) {
            //TODO - разберись уже с этими блядскими отношениями
            return $this->hasMany(CourseModule::class, 'course_id', 'course_id')
                ->whereIn('module_id', CourseModuleUser::select(['module_id'])->where('user_id', $user->id))
                ->orderBy('module_order');
        } else {
            return $this->hasMany(CourseModule::class, 'course_id')->orderBy('module_order');
        }
    }

    public function lessons()
    {
        return $this->hasManyThrough(ModuleLesson::class, CourseModule::class, 'course_modules.course_id', 'module_lessons.module_id', 'course_id', 'module_id');
    }

    public function users()
    {
        //return $this->hasManyThrough(User::class, CourseUser::class, 'id', 'user_id');
    }
}
