<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Silber\Bouncer\Database\HasRolesAndAbilities;

class CourseUser extends Model
{
    use HasFactory, HasRolesAndAbilities;

    protected $table = 'course_users';
}
