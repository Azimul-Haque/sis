<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('dashboard');
    }

    public function getUsers()
    {
        return view('components');
    }

    public function getBalance()
    {
        return view('components');
    }

    public function getSites()
    {
        return view('components');
    }

    

    public function getComponents()
    {
        return view('components');
    }
}
