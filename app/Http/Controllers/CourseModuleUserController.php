<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CourseModuleUserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
}
