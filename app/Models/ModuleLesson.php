<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModuleLesson extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $publicKey = 'lesson_id';

    public function module()
    {
        return $this->belongsTo('App\CourseModule');
    }
}
