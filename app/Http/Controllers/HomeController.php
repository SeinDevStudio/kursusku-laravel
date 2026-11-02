<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $siteName = 'KursusKu';
        $courses = Course::latest()->take(3)->get();
        $featured = $courses;

        return view('home', compact('siteName', 'courses', 'featured'));
    }
}
