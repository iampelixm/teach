<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ReminderController extends Controller
{
    public function UserActiveLessonRemind(User $user)
    {
        $user->lessons();
    }
}
