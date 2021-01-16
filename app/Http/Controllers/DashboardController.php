<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
use App\Balance;
use App\Site;
use App\Category;
use App\Expense;

// use Carbon\Carbon;
use DB;
use Hash;
use Auth;
// use Image;
// use File;
use Session;
use Artisan;
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
        $this->middleware('auth')->except('clear');
        $this->middleware('admin')->only('getUsers', 'storeUser', 'updateUser', 'deleteUser', 'deleteBalance');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $totalsites = Site::count();
        $totalusers = User::count();

        $totalbalance = Balance::sum('amount');
        $totalexpense = Expense::sum('amount');

        return view('dashboard')
                    ->withTotalsites($totalsites)
                    ->withTotalusers($totalusers)
                    ->withTotalbalance($totalbalance)
                    ->withTotalexpense($totalexpense);
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
        $totalbalance = Balance::sum('amount');
        $totalexpense = Expense::sum('amount');

        $balances = Balance::where('amount', '>', 0)
                           ->orderBy('id', 'desc')
                           ->paginate(5);

        return view('balances.index')
                    ->withBalances($balances)
                    ->withTotalbalance($totalbalance)
                    ->withTotalexpense($totalexpense);
    }

    public function storeBalance(Request $request)
    {
        $this->validate($request,array(
            'amount'        => 'required|integer'
        ));

        $balance = new Balance;
        $balance->user_id = Auth::user()->id;
        $balance->amount = $request->amount;
        $balance->save();

        Session::flash('success', 'Amount added successfully!');
        return redirect()->route('dashboard.balance');
    }

    public function deleteBalance($id)
    {
        $balance = Balance::find($id);
        $balance->delete();

        Session::flash('success', 'Amount deleted successfully!');
        return redirect()->route('dashboard.balance');
    }

    public function getSites()
    {
        $sites = Site::where('name', '!=', null)
                     ->orderBy('id', 'desc')
                     ->paginate(10);
        return view('sites.index')->withSites($sites);
    }

    public function storeSite(Request $request)
    {
        $this->validate($request,array(
            'name'         => 'required|string|max:191',
            'address'      => 'required|string|max:191',
            'progress'     => 'required|integer'
        ));

        $site = new Site;
        $site->name = $request->name;
        $site->address = $request->address;
        $site->progress = $request->progress;
        $site->save();

        Session::flash('success', 'Site created successfully!');
        return redirect()->route('dashboard.sites');
    }

    public function updateSite(Request $request, $id)
    {
        $this->validate($request,array(
            'name'         => 'required|string|max:191',
            'address'      => 'required|string|max:191',
            'progress'     => 'required|integer'
        ));

        $site = Site::find($id);
        $site->name = $request->name;
        $site->address = $request->address;
        $site->progress = $request->progress;
        $site->save();

        Session::flash('success', 'Site updated successfully!');
        return redirect()->route('dashboard.sites');
    }

    public function deleteSite($id)
    {
        $site = Site::find($id);

        foreach ($site->expenses as $expense) {
            $expense->delete();
        }
        $site->delete();

        Session::flash('success', 'Site deleted successfully!');
        return redirect()->route('dashboard.sites');
    }

    public function getSingleSite($id)
    {
        $site = Site::find($id);
        $expenses = Expense::where('site_id', $id)->orderBy('id', 'desc')->paginate(10);
        $categories = Category::orderBy('id', 'desc')->get();
        $monthlyexpensetotal = DB::table('expenses')
                                 ->select(DB::raw("DATE_FORMAT(created_at, '%Y-%m') as created_at"), DB::raw('SUM(amount) as totalamount'))
                                 ->where('site_id', $id)
                                 ->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m')"))
                                 ->first();
        // dd($monthlyexpensetotal);
        return view('sites.single')
                    ->withSite($site)
                    ->withExpenses($expenses)
                    ->withCategories($categories)
                    ->withMonthlyexpensetotal($monthlyexpensetotal);
    }

    public function getExpensePage()
    {
        $sites = Site::orderBy('id', 'desc')->get();
        $categories = Category::orderBy('id', 'desc')->get();
        
        return view('sites.expense')
                    ->withSites($sites)
                    ->withCategories($categories);
    }

    public function storeExpense(Request $request)
    {
        $this->validate($request,array(
            'user_id'       => 'required',
            'site_id'       => 'required',
            'category_id'   => 'required',
            'amount'        => 'required|integer'
        ));

        $expense = new Expense;
        $expense->user_id = $request->user_id;
        $expense->site_id = $request->site_id;
        $expense->category_id = $request->category_id;
        $expense->amount = $request->amount;
        $expense->save();

        Session::flash('success', 'Expense added successfully!');
        return redirect()->route('dashboard.sites.single', $request->site_id);
    }

    public function deleteExpense($id)
    {
        $expense = Expense::find($id);
        $expense->delete();

        Session::flash('success', 'Expense deleted successfully!');
        return redirect()->route('dashboard.sites.single', $expense->site_id);
    }

    public function getCategories()
    {
        $categories = Category::where('name', '!=', null)
                              ->orderBy('id', 'desc')
                              ->paginate(10);
        return view('sites.categories')->withCategories($categories);
    }

    public function storeCategory(Request $request)
    {
        $this->validate($request,array(
            'name'         => 'required|string|max:191'
        ));

        $category = new Category;
        $category->name = $request->name;
        $category->save();

        Session::flash('success', 'Category created successfully!');
        return redirect()->route('dashboard.categories');
    }

    public function updateCategory(Request $request, $id)
    {
        $this->validate($request,array(
            'name'         => 'required|string|max:191'
        ));

        $category = Category::find($id);
        $category->name = $request->name;
        $category->save();

        Session::flash('success', 'Category updated successfully!');
        return redirect()->route('dashboard.categories');
    }



    public function getComponents()
    {
        return view('components');
    }

    // clear configs, routes and serve
    public function clear()
    {
        Artisan::call('route:clear');
        // Artisan::call('optimize');
        Artisan::call('cache:clear');
        Artisan::call('view:clear');
        Artisan::call('key:generate');
        Artisan::call('config:clear');
        Session::flush();
        return 'Config and Route Cached. All Cache Cleared';
    }
}
