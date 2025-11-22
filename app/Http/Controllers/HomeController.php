<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
    /**
     * Display the homepage.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('home');
    }
}