<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
use App\Balance;
use App\Site;
use App\Category;
use App\Expense;

use Carbon\Carbon;
use DB;
use Hash;
use Auth;
use Image;
use File;
use Session;
use Artisan;
// use Redirect;
use OneSignal;

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
        // if user is a manager, redirect him to his profile
        // if user is a manager, redirect him to his profile
        if(Auth::user()->role != 'admin') {
            return redirect()->route('dashboard.users.single', Auth::user()->id);
        }

        $totalsites = Site::count();
        $totalusers = User::count();

        $totalbalance = Balance::sum('amount');
        $totalexpense = Expense::sum('amount');

        $todaystotalexpense = DB::table('expenses')
                                ->select(DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d') as created_at"), DB::raw('SUM(amount) as totalamount'))
                                ->where(DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"), "=", Carbon::now()->format('Y-m-d'))
                                ->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"))
                                ->first();

        return view('dashboard')
                    ->withTotalsites($totalsites)
                    ->withTotalusers($totalusers)
                    ->withTotalbalance($totalbalance)
                    ->withTotalexpense($totalexpense)
                    ->withTodaystotalexpense($todaystotalexpense);
    }

    public function getUsers()
    {
        $users = User::where('name', '!=', null)->paginate(10);
        $sites = Site::all();
        return view('users.index')
                    ->withUsers($users)
                    ->withSites($sites);
    }

    public function getUser($id)
    {
        $user = User::find($id);
        $balances = Balance::where('receiver_id', $id)
                           ->orderBy('id', 'desc')
                           ->paginate(10);
        $todaystotalexpense = DB::table('expenses')
                                ->select(DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d') as created_at"), DB::raw('SUM(amount) as totalamount'))
                                ->where('user_id', $id)
                                ->where(DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"), "=", Carbon::now()->format('Y-m-d'))
                                ->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"))
                                ->first();

        $monthlytotalbalance = DB::table('balances')
                                        ->select(DB::raw("DATE_FORMAT(created_at, '%Y-%m') as created_at"), DB::raw('SUM(amount) as totalamount'))
                                        ->where('receiver_id', $id)
                                        ->where(DB::raw("DATE_FORMAT(created_at, '%Y-%m')"), "=", Carbon::now()->format('Y-m'))
                                        ->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m')"))
                                        ->first();
        $monthlytotalexpense = DB::table('expenses')
                                        ->select(DB::raw("DATE_FORMAT(created_at, '%Y-%m') as created_at"), DB::raw('SUM(amount) as totalamount'))
                                        ->where('user_id', $id)
                                        ->where(DB::raw("DATE_FORMAT(created_at, '%Y-%m')"), "=", Carbon::now()->format('Y-m'))
                                        ->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m')"))
                                        ->first();
                                        // dd($monthlytotalexpense->totalamount);

        $totalbalance = Balance::where('receiver_id', $id)->sum('amount');
        $totalexpense = Expense::where('user_id', $id)->sum('amount');
        // dd($totalexpense);

        return view('users.single')
                    ->withUser($user)
                    ->withBalances($balances)
                    ->withTodaystotalexpense($todaystotalexpense)
                    ->withMonthlytotalbalance($monthlytotalbalance)
                    ->withMonthlytotalexpense($monthlytotalexpense)
                    ->withTotalbalance($totalbalance)
                    ->withTotalexpense($totalexpense);
    }

    public function getUserWithOtherPage($id)
    {
        $user = User::find($id);

        $expenses = Expense::where('user_id', $id)
                           ->orderBy('id', 'desc')
                           ->paginate(10);

        $todaystotalexpense = DB::table('expenses')
                                ->select(DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d') as created_at"), DB::raw('SUM(amount) as totalamount'))
                                ->where('user_id', $id)
                                ->where(DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"), "=", Carbon::now()->format('Y-m-d'))
                                ->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"))
                                ->first();

        $monthlytotalbalance = DB::table('balances')
                                        ->select(DB::raw("DATE_FORMAT(created_at, '%Y-%m') as created_at"), DB::raw('SUM(amount) as totalamount'))
                                        ->where('receiver_id', $id)
                                        ->where(DB::raw("DATE_FORMAT(created_at, '%Y-%m')"), "=", Carbon::now()->format('Y-m'))
                                        ->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m')"))
                                        ->first();
        $monthlytotalexpense = DB::table('expenses')
                                        ->select(DB::raw("DATE_FORMAT(created_at, '%Y-%m') as created_at"), DB::raw('SUM(amount) as totalamount'))
                                        ->where('user_id', $id)
                                        ->where(DB::raw("DATE_FORMAT(created_at, '%Y-%m')"), "=", Carbon::now()->format('Y-m'))
                                        ->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m')"))
                                        ->first();
                                        // dd($monthlytotalexpense->totalamount);
        $totalbalance = Balance::where('receiver_id', $id)->sum('amount');
        $totalexpense = Expense::where('user_id', $id)->sum('amount');
        // dd($totalexpense);

        return view('users.singleother')
                    ->withUser($user)
                    ->withExpenses($expenses)
                    ->withTodaystotalexpense($todaystotalexpense)
                    ->withMonthlytotalbalance($monthlytotalbalance)
                    ->withMonthlytotalexpense($monthlytotalexpense)
                    ->withTotalbalance($totalbalance)
                    ->withTotalexpense($totalexpense);
    }

    public function storeUser(Request $request)
    {
        // dd(serialize($request->sitecheck));
        $this->validate($request,array(
            'name'        => 'required|string|max:191',
            'mobile'      => 'required|string|max:191|unique:users,mobile',
            'role'        => 'required',
            'sitecheck'   => 'required',
            'password'    => 'required|string|min:8|max:191',
        ));

        $user = new User;
        $user->name = $request->name;
        $user->mobile = $request->mobile;
        $user->role = $request->role;
        $user->sites = implode(',', $request->sitecheck);
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
            'sitecheck'   => 'required',
            'password'    => 'nullable|string|min:8|max:191',
        ));

        $user = User::find($id);
        $user->name = $request->name;
        $user->mobile = $request->mobile;
        $user->role = $request->role;
        $user->sites = implode(',', $request->sitecheck);
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
        $users = User::whereNotIn('mobile', ['01751398392', '01837409842'])->get();
        $totalbalance = Balance::sum('amount');
        $totalexpense = Expense::sum('amount');

        $balances = Balance::where('amount', '>', 0)
                           ->orderBy('id', 'desc')
                           ->paginate(5);

        return view('balances.index')
                    ->withUsers($users)
                    ->withBalances($balances)
                    ->withTotalbalance($totalbalance)
                    ->withTotalexpense($totalexpense);
    }

    public function storeBalance(Request $request)
    {
        $this->validate($request,array(
            'amount'         => 'required|integer',
            'medium'         => 'sometimes|max:50',
            'description'    => 'sometimes|max:50'
        ));

        $balance = new Balance;
        $balance->user_id = Auth::user()->id;
        $balance->receiver_id = $request->receiver_id;
        $balance->amount = $request->amount;
        $balance->medium = $request->medium;
        $balance->description = $request->description;
        $balance->save();

        // OneSignal::sendNotificationToAll(
        //     "অর্থ যোগ করেছেনঃ " . Auth::user()->name,
        //     $url = null, 
        //     $data = null, // array("answer" => $charioteer->answer), // to send some variable
        //     $buttons = null, 
        //     $schedule = null,
        //     $headings = "৳ " . bangla($request->amount) . " যোগ করা হয়েছে!"
        // );

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
                     ->paginate(5);

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
        $monthlyexpensetotalcurrent = DB::table('expenses')
                                        ->select(DB::raw("DATE_FORMAT(created_at, '%Y-%m') as created_at"), DB::raw('SUM(amount) as totalamount'))
                                        ->where('site_id', $id)
                                        ->where(DB::raw("DATE_FORMAT(created_at, '%Y-%m')"), "=", Carbon::now()->format('Y-m'))
                                        ->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m')"))
                                        ->first();

        $monthlyexpenses = DB::table('expenses')
                                 ->select(DB::raw("DATE_FORMAT(created_at, '%Y-%m') as created_at"), DB::raw('SUM(amount) as totalamount'))
                                 ->where('site_id', $id)
                                 ->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m')"))
                                 ->orderBy('created_at', 'DESC')
                                 ->get();

        $totalbalance = Balance::where('receiver_id', Auth::user()->id)->sum('amount');
        $totalexpense = Expense::where('user_id', Auth::user()->id)->sum('amount');

        // dd($monthlyexpensetotal);
        return view('sites.single')
                    ->withSite($site)
                    ->withExpenses($expenses)
                    ->withCategories($categories)
                    ->withMonthlyexpensetotalcurrent($monthlyexpensetotalcurrent)
                    ->withMonthlyexpenses($monthlyexpenses)
                    ->withTotalbalance($totalbalance)
                    ->withTotalexpense($totalexpense);
    }

    public function getExpensePage()
    {
        $sites = Site::orderBy('id', 'desc')->get();
        $categories = Category::orderBy('id', 'desc')->get();
        
        $totalbalance = Balance::where('receiver_id', Auth::user()->id)->sum('amount');
        $totalexpense = Expense::where('user_id', Auth::user()->id)->sum('amount');

        return view('sites.expense')
                    ->withSites($sites)
                    ->withCategories($categories)
                    ->withTotalbalance($totalbalance)
                    ->withTotalexpense($totalexpense);
    }

    public function storeExpense(Request $request)
    {
        dd($request->file('image'));
        $this->validate($request,array(
            'site_data'       => 'required',
            'category_data'   => 'required',
            'amount'          => 'required|integer',
            'qty'             => 'sometimes',
            'description'     => 'sometimes',
            'image'           => 'sometimes|image|mimes:jpeg,bmp,png'
        ));

        // dd($request->all());
        // parse data
        $site_data = explode(',', $request->site_data);
        $category_data = explode(',', $request->category_data);

        $expense = new Expense;
        $expense->user_id = Auth::user()->id;
        $expense->site_id = $site_data[0];
        $expense->category_id = $category_data[0];
        $expense->amount = $request->amount;
        $expense->qty = $request->qty;
        $expense->description = $request->description;

        // upload image

        if($request->hasFile('image')) {
            $receipt      = $request->file('image');
            $filename   = Auth::user()->id.'_receipt_' . time() .'.' . $receipt->getClientOriginalExtension();
            $location   = public_path('/images/expenses/'. $filename);
            Image::make($receipt)->resize(600, null, function ($constraint) { $constraint->aspectRatio(); })->save($location);
            $expense->image = $filename;
        }
        // upload image

        $expense->save();

        // OneSignal::sendNotificationToUser(
        //     "ব্যয় করেছেনঃ " . Auth::user()->name . ', খাতঃ ' . $category_data[1],
        //     "a1050399-4f1b-4bd5-9304-47049552749c", 
        //     $url = null, 
        //     $data = null, // array("answer" => $charioteer->answer), // to send some variable
        //     $buttons = null, 
        //     $schedule = null,
        //     $headings = $site_data[1] ."-এ ৳ " . bangla($request->amount) . " ব্যয় করা হয়েছে!"
        // );
        // OneSignal::sendNotificationToUser(
        //     "Test",
        //     "a1050399-4f1b-4bd5-9304-47049552749c", 
        //     $url = null, 
        //     $data = null, // array("answer" => $charioteer->answer), // to send some variable
        //     $buttons = null, 
        //     $schedule = null,
        //     $headings = "Test 2"
        // );

        Session::flash('success', 'Expense added successfully!');
        return redirect()->route('dashboard.sites.single', $site_data[0]);
    }

    public function deleteExpense($id)
    {
        $expense = Expense::find($id);
        $image_path = public_path('images/expenses/'. $expense->image);
        if(File::exists($image_path)) {
            File::delete($image_path);
        }
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
