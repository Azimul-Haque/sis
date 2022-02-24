<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
use App\Balance;
use App\Site;
use App\Category;
use App\Expense;
use App\Creditor;
use App\Due;

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
        $this->middleware(['admin'])->only('getUsers', 'storeUser', 'updateUser', 'deleteUser', 'deleteBalance', 'getCreditors', 'getSingleCreditor', 'getAddDuePage', 'deleteCreditorDue', 'getSiteCategorywise');

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
        if(Auth::user()->role != 'admin' && Auth::user()->role != 'accountant') {
            return redirect()->route('dashboard.users.single', Auth::user()->id);
        } elseif (Auth::user()->role == 'accountant') {
            return redirect()->route('dashboard.balance');
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
        $todaystotaldeposit = DB::table('balances')
                                ->select(DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d') as created_at"), DB::raw('SUM(amount) as totalamount'))
                                ->where(DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"), "=", Carbon::now()->format('Y-m-d'))
                                ->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"))
                                ->first();

        return view('dashboard')
                    ->withTotalsites($totalsites)
                    ->withTotalusers($totalusers)
                    ->withTotalbalance($totalbalance)
                    ->withTotalexpense($totalexpense)
                    ->withTodaystotalexpense($todaystotalexpense)
                    ->withTodaystotaldeposit($todaystotaldeposit);
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
        $monthlytotalexpense = DB::table('expenses')
                                        ->select(DB::raw("DATE_FORMAT(created_at, '%Y-%m') as created_at"), DB::raw('SUM(amount) as totalamount'))
                                        ->where('user_id', $id)
                                        ->where(DB::raw("DATE_FORMAT(created_at, '%Y-%m')"), "=", Carbon::now()->format('Y-m'))
                                        ->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m')"))
                                        ->first();
        $totalexpense = Expense::where('user_id', $id)->sum('amount');

        $todaystotaldeposit = DB::table('balances')
                                ->select(DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d') as created_at"), DB::raw('SUM(amount) as totalamount'))
                                ->where('receiver_id', $id)
                                ->where(DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"), "=", Carbon::now()->format('Y-m-d'))
                                ->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"))
                                ->first();
        $monthlytotaldeposit = DB::table('balances')
                                        ->select(DB::raw("DATE_FORMAT(created_at, '%Y-%m') as created_at"), DB::raw('SUM(amount) as totalamount'))
                                        ->where('receiver_id', $id)
                                        ->where(DB::raw("DATE_FORMAT(created_at, '%Y-%m')"), "=", Carbon::now()->format('Y-m'))
                                        ->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m')"))
                                        ->first();
        $totaldeposit = Balance::where('receiver_id', $id)->sum('amount');
        
        // dd($totaldeposit);

        return view('users.single')
                    ->withUser($user)
                    ->withBalances($balances)
                    ->withTodaystotalexpense($todaystotalexpense)
                    ->withMonthlytotalexpense($monthlytotalexpense)
                    ->withTotalexpense($totalexpense)
                    ->withTodaystotaldeposit($todaystotaldeposit)
                    ->withMonthlytotaldeposit($monthlytotaldeposit)
                    ->withTotaldeposit($totaldeposit);
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
        $monthlytotalexpense = DB::table('expenses')
                                        ->select(DB::raw("DATE_FORMAT(created_at, '%Y-%m') as created_at"), DB::raw('SUM(amount) as totalamount'))
                                        ->where('user_id', $id)
                                        ->where(DB::raw("DATE_FORMAT(created_at, '%Y-%m')"), "=", Carbon::now()->format('Y-m'))
                                        ->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m')"))
                                        ->first();
        $totalexpense = Expense::where('user_id', $id)->sum('amount');

        $todaystotaldeposit = DB::table('balances')
                                ->select(DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d') as created_at"), DB::raw('SUM(amount) as totalamount'))
                                ->where('receiver_id', $id)
                                ->where(DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"), "=", Carbon::now()->format('Y-m-d'))
                                ->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"))
                                ->first();
        $monthlytotaldeposit = DB::table('balances')
                                        ->select(DB::raw("DATE_FORMAT(created_at, '%Y-%m') as created_at"), DB::raw('SUM(amount) as totalamount'))
                                        ->where('receiver_id', $id)
                                        ->where(DB::raw("DATE_FORMAT(created_at, '%Y-%m')"), "=", Carbon::now()->format('Y-m'))
                                        ->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m')"))
                                        ->first();
        $totaldeposit = Balance::where('receiver_id', $id)->sum('amount');
        // dd($totalexpense);

        return view('users.singleother')
                    ->withUser($user)
                    ->withExpenses($expenses)
                    ->withTodaystotalexpense($todaystotalexpense)
                    ->withMonthlytotalexpense($monthlytotalexpense)
                    ->withTotalexpense($totalexpense)
                    ->withTodaystotaldeposit($todaystotaldeposit)
                    ->withMonthlytotaldeposit($monthlytotaldeposit)
                    ->withTotaldeposit($totaldeposit);
    }

    public function storeUser(Request $request)
    {
        // dd(serialize($request->sitecheck));
        $this->validate($request,array(
            'name'        => 'required|string|max:191',
            'mobile'      => 'required|string|max:191|unique:users,mobile',
            'role'        => 'required',
            'sitecheck'   => 'sometimes',
            'password'    => 'required|string|min:8|max:191',
        ));

        $user = new User;
        $user->name = $request->name;
        $user->mobile = $request->mobile;
        $user->role = $request->role;
        if(!empty($request->sitecheck)) {
            $user->sites = implode(',', $request->sitecheck);
        }
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
            'sitecheck'   => 'sometimes',
            'password'    => 'nullable|string|min:8|max:191',
        ));

        $user = User::find($id);
        $user->name = $request->name;
        $user->mobile = $request->mobile;
        $user->role = $request->role;
        if(!empty($request->sitecheck)) {
            $user->sites = implode(',', $request->sitecheck);
        }
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
        if(!(Auth::user()->role == 'admin' || Auth::user()->role == 'accountant')) {
            abort(403, 'Access Denied');
        }
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

        $receiver = User::findOrFail($request->receiver_id);

        if(Auth::user()->role == 'accountant') {
            OneSignal::sendNotificationToUser(
                "অর্থ প্রদানকারীঃ একাউন্টেন্ট, গ্রহণকারীঃ " . $receiver->name,
                ["a1050399-4f1b-4bd5-9304-47049552749c", "82e84884-917e-497d-b0f5-728aff4fe447"],
                $url = null, 
                $data = null, // array("answer" => $charioteer->answer), // to send some variable
                $buttons = null, 
                $schedule = null,
                $headings = "অর্থ প্রদান করা হয়েছে। পরিমাণঃ ৳ " . bangla($request->amount)
            );
        }

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
                     ->orderBy('progress', 'asc')
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

    public function deleteSite(Request $request, $id)
    {
        $this->validate($request,array(
            'contact_sum_result_hidden'   => 'required',
            'contact_sum_result'   => 'required'
        ));

        if($request->contact_sum_result_hidden == $request->contact_sum_result) {
            $site = Site::find($id);

            foreach ($site->expenses as $expense) {
                $expense->delete();
            }
            $site->delete();

            Session::flash('success', 'Site deleted successfully!');
            return redirect()->route('dashboard.sites');
        } else {
            Session::flash('warning', 'যোগফল সঠিক নয়, আবার চেষ্টা করুন।');
            return redirect()->route('dashboard.sites');
        }
    }

    public function getSingleSite($id)
    {
        if(Auth::user()->role == 'manager') {
            $sitesarray = explode(',', Auth::user()->sites);
            // dd($sitesarray);
            if(!in_array($id, $sitesarray)) {
                abort(403);
            } 
        }
        $site = Site::find($id);
        $expenses = Expense::where('site_id', $id)->orderBy('id', 'desc')->paginate(10);
        $categories = Category::orderBy('id', 'desc')->get();
        $todaytotalcurrent = DB::table('expenses')
                               ->select(DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d') as created_at"), DB::raw('SUM(amount) as totalamount'))
                               ->where('site_id', $id)
                               ->where(DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"), "=", Carbon::now()->format('Y-m-d'))
                               ->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"))
                               ->first();
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

        $intotalexpense = DB::table('expenses')
                             ->select(DB::raw('SUM(amount) as totalamount'))
                             ->where('site_id', $id)
                             ->orderBy('created_at', 'DESC')
                             ->first();

        $totalbalance = Balance::where('receiver_id', Auth::user()->id)->sum('amount'); // to calculate user balance
        $totalexpense = Expense::where('user_id', Auth::user()->id)->sum('amount'); // to calculate user balance

        // dd($monthlyexpensetotal);
        return view('sites.single')
                    ->withSite($site)
                    ->withExpenses($expenses)
                    ->withCategories($categories)
                    ->withTodaytotalcurrent($todaytotalcurrent)
                    ->withMonthlyexpensetotalcurrent($monthlyexpensetotalcurrent)
                    ->withMonthlyexpenses($monthlyexpenses)
                    ->withTotalbalance($totalbalance)
                    ->withTotalexpense($totalexpense)
                    ->withIntotalexpense($intotalexpense);
    }

    public function getSiteCategorywise($id)
    {
        $site = Site::find($id);
        $categories = Category::orderBy('id', 'desc')->get();
        $categorywises = DB::table('expenses')
                          ->select('category_id', DB::raw('SUM(amount) as totalamount'))
                          ->where('site_id', $id)
                          ->groupBy('category_id')
                          ->get();
        $intotalexpense = DB::table('expenses')
                             ->select(DB::raw('SUM(amount) as totalamount'))
                             ->where('site_id', $id)
                             ->orderBy('created_at', 'DESC')
                             ->first();
        // dd($categorywise);
        return view('sites.categorywise')
                    ->withSite($site)
                    ->withCategories($categories)
                    ->withCategorywises($categorywises)
                    ->withIntotalexpense($intotalexpense);
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

        OneSignal::sendNotificationToUser(
            "ব্যয় করেছেনঃ " . Auth::user()->name . ', খাতঃ ' . $category_data[1],
            ["a1050399-4f1b-4bd5-9304-47049552749c", "82e84884-917e-497d-b0f5-728aff4fe447"],
            $url = null, 
            $data = null, // array("answer" => $charioteer->answer), // to send some variable
            $buttons = null, 
            $schedule = null,
            $headings = $site_data[1] ."-এ ৳ " . bangla($request->amount) . " ব্যয় করা হয়েছে!"
        );

        // OneSignal::sendNotificationToUser(
        //     "Test",
        //     ["a1050399-4f1b-4bd5-9304-47049552749c", "82e84884-917e-497d-b0f5-728aff4fe447"],
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

    public function getSingleCategory($id)
    {
        $category = Category::findOrFail($id);

        $expenses = DB::table('expenses')
                      ->select(DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d') as created_at"), DB::raw('SUM(amount) as totalamount'))
                      ->where('category_id', $id)
                      ->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"))
                      ->orderBy('created_at', 'desc')
                      ->paginate(10);

        // dd($expenses);

        return view('sites.singlecategory')
                            ->withExpenses($expenses)
                            ->withCategory($category);
    }

    public function getSingleCategoryDate($id, $selecteddate)
    {
        $category = Category::findOrFail($id);

        $expenses = Expense::select('site_id', DB::raw('SUM(amount) as totalamount'))
                      ->where('category_id', $id)
                      ->where(DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"), $selecteddate)
                      ->groupBy('site_id')
                      ->get();
        // dd($expenses);

        return view('sites.singlecategorydate')
                            ->withExpenses($expenses)
                            ->withCategory($category)
                            ->withSelecteddate($selecteddate);
    }    

    public function getSingleCategoryDateSite($id, $selecteddate, $site_id)
    {
        $category = Category::findOrFail($id);
        $site = Site::findOrFail($site_id);

        $expenses = Expense::where('category_id', $id)
                           ->where(DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"), $selecteddate)
                           ->where('site_id', $site_id)
                           ->get();
        // dd($expenses);

        return view('sites.singlecategorydatesite')
                            ->withExpenses($expenses)
                            ->withCategory($category)
                            ->withSite($site)
                            ->withSelecteddate($selecteddate);
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

    public function getMonthly()
    {
        $monthlyexpenses = DB::table('expenses')
                             ->select(DB::raw("DATE_FORMAT(created_at, '%Y-%m') as created_at"), DB::raw('SUM(amount) as totalamount'))
                             ->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m')"))
                             ->orderBy('created_at', 'DESC')
                             ->paginate(10);
        $intotalexpense = DB::table('expenses')
                             ->select(DB::raw('SUM(amount) as totalamount'))
                             ->orderBy('created_at', 'DESC')
                             ->first();

        return view('monthly.index')
                        ->withMonthlyexpenses($monthlyexpenses)
                        ->withIntotalexpense($intotalexpense);
    }

    public function getCreditors()
    {
        $creditors = Creditor::orderBy('id', 'desc')->paginate(10);

        return view('creditors.index')->withCreditors($creditors);
    }

    public function getSingleCreditor($id)
    {
        $creditor = Creditor::find($id);
        $dues = Due::where('creditor_id', $id)
                   ->orderBy('id', 'desc')
                   ->paginate(10);
        return view('creditors.single')
                    ->withCreditor($creditor)
                    ->withDues($dues);
    }

    public function storeCreditor(Request $request)
    {
        $this->validate($request,array(
            'name'         => 'required|string|max:191'
        ));

        $creditor = new Creditor;
        $creditor->name = $request->name;
        $creditor->save();

        Session::flash('success', 'Creditor created successfully!');
        return redirect()->route('dashboard.creditors');
    }

    public function updateCreditor(Request $request, $id)
    {
        $this->validate($request,array(
            'name'         => 'required|string|max:191'
        ));

        $creditor = Creditor::find($id);
        $creditor->name = $request->name;
        $creditor->save();

        Session::flash('success', 'Creditor updated successfully!');
        return redirect()->route('dashboard.creditors');
    }

    public function getAddDuePage()
    {
        $creditors = Creditor::orderBy('id', 'desc')->get();

        return view('creditors.adddue')->withCreditors($creditors);
    }

    public function storeCreditorDue(Request $request)
    {
        $this->validate($request,array(
            'creditor_id'           => 'required',
            'description'           => 'sometimes',
            'amount'                => 'required|integer'
        ));

        $due = new Due;
        $due->creditor_id = $request->creditor_id;
        $due->description = $request->description;
        $due->amount = $request->amount;
        $due->save();

        Session::flash('success', 'Due added successfully!');
        return redirect()->route('dashboard.creditors.single', $request->creditor_id);
    }

    public function updateCreditorDue(Request $request, $id)
    {
        $this->validate($request,array(
            'description'           => 'sometimes',
            'amount'                => 'required|integer'
        ));

        $due = Due::find($id);
        $due->description = $request->description;
        $due->amount = $request->amount;
        $due->save();

        Session::flash('success', 'Due updated successfully!');
        return redirect()->route('dashboard.creditors.single', $due->creditor_id);
    }

    public function deleteCreditorDue($id)
    {
        $due = Due::find($id);
        $due->delete();

        Session::flash('success', 'Due deleted successfully!');
        return redirect()->route('dashboard.creditors.single', $due->creditor_id);
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
