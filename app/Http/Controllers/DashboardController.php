<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;

// use Carbon\Carbon;
// use DB;
use Hash;
use Auth;
// use Image;
// use File;
use Session;
// use Artisan;
// use Redirect;

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
        $this->middleware('admin')->only('getUsers');
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
        $users = User::all();
        return view('users.index')->withUsers($users);
    }

    public function storeUser(Request $request)
    {
        $this->validate($request,array(
            'name'        => 'required|string|max:191',
            'mobile'      => 'required|string|max:191|unique:users',
            'role'        => 'required',
            'password'    => 'required|string|min:8|max:191',
        ));

        $user = new User;
        $user->name = $request->name;
        $user->mobile = $request->mobile;
        $user->role = $request->role;
        $user->password = Hash::make($request->newpassword);
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
