<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Silber\Bouncer\Database\HasRolesAndAbilities;

class CourseModuleUser extends Model
{
    use HasFactory, HasRolesAndAbilities;
    protected $fillable = ['user_id', 'module_id'];
}
