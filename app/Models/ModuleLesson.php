<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModuleLesson extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $primaryKey = 'lesson_id';

    public function module()
    {
        return $this->belongsTo(CourseModule::class, 'module_id', 'module_id');
    }
}
