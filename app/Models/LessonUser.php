<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Silber\Bouncer\Database\HasRolesAndAbilities;

class LessonUser extends Model
{
    use HasFactory, HasRolesAndAbilities;
    protected $fillable = ['user_id', 'lesson_id'];
}
