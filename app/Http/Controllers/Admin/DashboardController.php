<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use App\Models\PosLog;
use App\Models\Terminal;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function admin_dashboard(request $request)
    {

        if (Auth::user()->role == 1  ) {

            $data['users'] = User::count();
            $data['successful_transactions'] = PosLog::latest()->where('transactionType', 'PURCHASE')->where('status', 1)->sum('amount');
            $data['failed_transactions'] = PosLog::latest()->where('transactionType', 'PURCHASE')->where('status', 0)->sum('amount');
            $data['all_terminals'] = Terminal::count();
            $data['all_customers'] = User::where('role', 2)->count();
            $data['all_admins'] = User::where('role', 1)->count();
            $data['suspended_users'] = User::where('status', 3)->count();
            $data['total_wallet'] = User::where('status', 2)->sum('main_wallet');

            if($request->date == "today"){
                $cdate = Carbon::today();
            }elseif ($request->date == "yesterday"){
                $cdate = Carbon::yesterday();
            }else{
                $cdate = null;
            }

            if($cdate == null){
                $data['inflow'] = Transaction::where('status', 2)->sum('credit');
                $data['outflow'] = Transaction::where('status', 2)->sum('debit');
                $data['all_transactions'] = Transaction::latest()->take(100)->get();
            }else{
                $data['inflow'] = Transaction::where('status', 2)->wheredate('created_at', $cdate)->sum('credit');
                $data['all_transactions'] = Transaction::latest()->wheredate('created_at', $cdate)->take(100)->get();
                $data['outflow'] = Transaction::where('status', 2)->wheredate('created_at', $cdate)->sum('debit');
            }









            return view('admin-dashboard', $data);
        }

        return back()->with('error', 'you do not have permission');



    }

}
