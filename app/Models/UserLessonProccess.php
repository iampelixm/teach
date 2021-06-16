<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLessonProccess extends Model
{
    use HasFactory;
    public $fillable = ['user_id', 'lesson_id', 'lesson_status'];

    protected $dates = [
        'created_at',
        'updated_at'
    ];

    //protected $dateFormat = 'U';

    public function lesson()
    {
        return $this->belongsTo(ModuleLesson::class, 'lesson_id', 'lesson_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
