<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseModule extends Model
{
    use HasFactory;

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
}
