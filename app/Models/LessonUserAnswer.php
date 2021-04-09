<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Silber\Bouncer\Database\HasRolesAndAbilities;

class LessonUserAnswer extends Model
{
    use HasFactory, HasRolesAndAbilities;
    protected $attributes = [
        'answer_text' => ' '
    ];
    protected $fillable = ['lesson_id', 'user_id', 'answer_text', 'answer_quiz'];
    protected $primaryKey = 'answer_id';
}
