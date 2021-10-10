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

class ExpenseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except('clear');
        $this->middleware('admin')->only('getTodaysExpenseList');
    }

    public function getTodaysExpenseList($transactiondate)
    {
    	$todaystotalexpense = DB::table('expenses')
    	                        ->select(DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d') as created_at"), DB::raw('SUM(amount) as totalamount'))
    	                        ->where(DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"), "=", Carbon::now()->format('Y-m-d'))
    	                        ->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"))
    	                        ->first();

        $expenses = Expense::where(DB::raw("DATE_FORMAT(created_at, '%Y-%m-%d')"), "=", Carbon::now()->format('Y-m-d'))
                        ->paginate(10);

        return view('expenses.todaystotalexpense')
                    ->withExpenses($expenses)
                    ->withTodaystotalexpense($todaystotalexpense)
                    ->withTransactiondate($transactiondate);
    }
}
