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
use function PHPUnit\Framework\isEmpty;

class DashboardController extends Controller
{
    public function admin_dashboard(request $request)
    {


        if (auth()->check()) {

            if (Auth::user()->role == 1  ) {

                $data['users'] = User::count();
                $data['successful_transactions'] = PosLog::latest()->where('transactionType', 'PURCHASE')->where('status', 1)->sum('amount');
                $data['failed_transactions'] = PosLog::latest()->where('transactionType', 'PURCHASE')->where('status', 0)->sum('amount');
                $data['all_terminals'] = Terminal::count();
                $data['all_customers'] = User::where('role', 2)->count();
                $data['all_admins'] = User::where('role', 1)->count();
                $data['suspended_users'] = User::where('status', 3)->count();
                $data['total_wallet'] = User::sum('main_wallet');
                $data['ninepsb_wallet_balance'] = wallet_balance() ?? 0;
                $data['settlement'] = settlement() ?? 0;





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
                    $data['all_transactions'] = Transaction::latest()->take(100)->paginate(20);
                    $data['pending'] = Transaction::where('status', 0)->sum('amount');
                    $data['transfer_in_total'] = Transaction::where('status', 2)->where('transaction_type', 'TRANSFERIN')->sum('amount');
                    $data['pos'] = Transaction::where('status', 2)->where('transaction_type', 'PURCHASE')->sum('amount');



                }else{
                    $data['inflow'] = Transaction::where('status', 2)->wheredate('created_at', $cdate)->sum('credit');
                    $data['all_transactions'] = Transaction::latest()->wheredate('created_at', $cdate)->take(100)->paginate(20);
                    $data['outflow'] = Transaction::where('status', 2)->wheredate('created_at', $cdate)->sum('debit');
                    $data['pending'] = Transaction::where('status', 0)->wheredate('created_at', $cdate)->sum('amount');
                    $data['transfer_in_total'] = Transaction::wheredate('created_at', $cdate)->where('transaction_type', 'TRANSFERIN')->sum('amount');
                    $data['pos'] = Transaction::where('status', 2)->where('transaction_type', 'PURCHASE')->sum('amount');

                }

                return view('admin-dashboard', $data);
            }

            return back()->with('error', 'you do not have permission');
        } else {
            return redirect('login');
        }







    }



    public function new_user(request $request)
    {
        $data['users'] = User::where('role', 2)->get();
        return view('addUser', $data);
    }


    public function create_new_customer(request $request)
    {


        $phone = User::where('phone', $request->phone)->first()->phone ?? null;
        $email = User::where('email', $request->email)->first()->email ?? null;

        if($request->email == $email){
            return back()->with('error', 'User with email already exist');
        }

        if($request->phone == $phone){
            return back()->with('error', 'User with phone no already exist');
        }

        $usr = new User();
        $usr->first_name = $request->first_name;
        $usr->last_name = $request->last_name;
        $usr->phone = $request->phone;
        $usr->email = $request->email;
        $usr->password =bcrypt($request->password);
        $usr->pin =bcrypt($request->pin);
        $usr->hos_no = $request->hos_no;
        $usr->address_line1 = $request->address_line1;
        $usr->state = $request->state;
        $usr->city = $request->city;
        $usr->gender = $request->gender;
        $usr->lga = $request->lga;
        $usr->role = 2;
        $usr->status = 2;


        $usr->save();



        return back()->with('message', 'User has been successfully added');

    }


    public function all_customer(request $request){

       $data['user'] = User::where('role', 2)->paginate('20');
       return view ('allUsers', $data);


    }

    public function search_user(request $request){

        $user = User::where('email', $request->email)->paginate(20) ?? null;

        return view ('allUsers', compact('user'));

    }








}
