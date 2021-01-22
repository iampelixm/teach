<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $primaryKey = 'course_id';

    public function modules()
    {
        return $this->hasMany(CourseModule::class, 'course_id', 'course_id');
    }
}
