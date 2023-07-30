<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TeachersController extends Controller
{
    //
    public function index()
    {
        $courses = auth()->user()->courses;
        return view('teacher.teacher')->with('courses', $courses);
    }
}