<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLessonProccess extends Model
{
    use HasFactory;
    public $fillable = ['user_id', 'lesson_id'];
    protected $primaryKey = null;
}
