<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Silber\Bouncer\Database\HasRolesAndAbilities;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRolesAndAbilities;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function courses()
    {
        return $this->hasManyThrough(Course::class, CourseUser::class, 'user_id', 'course_id', 'id', 'course_id');
    }

    public function hasCourseAccess($course_id)
    {
        return $this->hasManyThrough(Course::class, CourseUser::class, 'user_id', 'course_id', 'id', 'course_id')
            ->where(['courses.course_id' => $course_id]);
    }

    public function modules()
    {
        return $this->hasManyThrough(CourseModule::class, CourseModuleUser::class, 'user_id', 'module_id', 'id', 'module_id');
    }

    public function lessons()
    {
        return $this->hasManyThrough(ModuleLesson::class, LessonUser::class, 'user_id', 'lesson_id', 'id', 'lesson_id');
    }
}
