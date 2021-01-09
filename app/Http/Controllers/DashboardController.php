<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
use App\Balance;

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
        $this->middleware('admin')->only('getUsers', 'getBalance');
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
        $users = User::where('name', '!=', null)->paginate(5);
        return view('users.index')->withUsers($users);
    }

    public function storeUser(Request $request)
    {
        $this->validate($request,array(
            'name'        => 'required|string|max:191',
            'mobile'      => 'required|string|max:191|unique:users,mobile',
            'role'        => 'required',
            'password'    => 'required|string|min:8|max:191',
        ));

        $user = new User;
        $user->name = $request->name;
        $user->mobile = $request->mobile;
        $user->role = $request->role;
        $user->password = Hash::make($request->password);
        $user->save();

        Session::flash('success', 'User created successfully!');
        return redirect()->route('dashboard.users');
    }

    public function updateUser(Request $request, $id)
    {
        $this->validate($request,array(
            'name'        => 'required|string|max:191',
            'mobile'      => 'required|string|max:191|unique:users,mobile,'.$id,
            'role'        => 'required',
            'password'    => 'nullable|string|min:8|max:191',
        ));

        $user = User::find($id);
        $user->name = $request->name;
        $user->mobile = $request->mobile;
        $user->role = $request->role;
        if(!empty($request->password)) {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        Session::flash('success', 'User updated successfully!');
        return redirect()->route('dashboard.users');
    }

    public function deleteUser($id)
    {
        $user = User::find($id);
        $user->delete();

        Session::flash('success', 'User deleted successfully!');
        return redirect()->route('dashboard.users');
    }

    public function getBalance()
    {
        $balances = Balance::where('amount', '>', 0)->paginate(10);
        return view('balances.index')->withBalances($balances);
    }

    public function storeBalance(Request $request)
    {
        $this->validate($request,array(
            'amount'        => 'required|number|max:191',
        ));

        $balance = new Balance;
        $balance->user_id = Auth::user()->id;
        $balance->amount = $request->amount;
        $balance->save();

        Session::flash('success', 'Amount added successfully!');
        return redirect()->route('dashboard.balance');
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
