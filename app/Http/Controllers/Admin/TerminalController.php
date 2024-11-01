<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Terminal;
use App\Models\TidConfig;
use App\Models\User;
use App\Models\VirtualAccount;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class TerminalController extends Controller
{

    public function create_terminal_view(request $request)
    {


        $data['terminalno'] = Terminal::latest()->first()->terminalNo;
        $data['users'] = User::latest()->where('role', 2)->get();
        return view('new-terminal', $data);

    }


    public function create_new_terminal(request $request)
    {



        if ($request->user_id == null) {
            return back()->with('error', 'Attach a customer to terminal');
        }


        if (Auth::user()->role == 1) {
            $ter = Terminal::where('serial_no', $request->deviceSN)->first() ?? null;
            if ($ter == null) {

                $v_number = VirtualAccount::where('user_id', $request->user_id)->first()->v_account_no ?? null;

                $term = new TidConfig();
                $term->user_id = $request->user_id;
                $term->save();


                $term = new Terminal();
                $term->user_id = $request->user_id;
                $term->serial_no = $request->deviceSN;
                $term->v_account_no = $v_number;
                $term->description = $request->merchantName;
                $term->terminalNo = $request->terminalNo;
                $term->merchantName = $request->merchantName;
                $term->deviceSN = $request->deviceSN;
                $term->save();






//                try {
//
//                    $curl = curl_init();
//                    $data = array(
//
//                        'tid' => $request->tid,
//                        'user_id' => $request->user_id,
//                        'bank_id' => $request->bank_id,
//                        'ip' => $request->ip,
//                        'port' => $request->port,
//                        'ssl' => $request->ssl,
//                        'compKey1' => $request->compKey1,
//                        'compKey2' => $request->compKey2,
//                        'baseUrl' => "http://etopagency.com:9001/",
//                        'logoUrl' => $request->logoUrl,
//                        'serialNumber' => $request->serialNumber,
//                        'merchantName' => $request->merchantName,
//                        'mid' => $request->mid,
//                        'merchantaddress' => $request->merchantaddress,
//                        'pin' => bcrypt($request->pin),
//
//                    );
//                    $post_data = json_encode($data);
//
//                    curl_setopt_array($curl, array(
//                        CURLOPT_URL => 'https://etopmerchant.com/api/store-terminal',
//                        CURLOPT_RETURNTRANSFER => true,
//                        CURLOPT_ENCODING => '',
//                        CURLOPT_MAXREDIRS => 10,
//                        CURLOPT_TIMEOUT => 0,
//                        CURLOPT_FOLLOWLOCATION => true,
//                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//                        CURLOPT_CUSTOMREQUEST => 'POST',
//                        CURLOPT_POSTFIELDS => $post_data,
//                        CURLOPT_HTTPHEADER => array(
//                            'Content-Type: application/json'
//                        ),
//                    ));
//
//                    $var = curl_exec($curl);
//                    curl_close($curl);
//
//
//                } catch (QueryException $e) {
//                    echo "$e";
//                }


                return back()->with('message', 'Terminal Create Successfully');


            }



            if ($ter != null) {
                return back()->with('error', 'Terminal Account Already  Exist');
            }

        } else {
            return back()->with('error', 'You dont have permission to create a terminal');
        }

        return back()->with('error', 'Something went wrong');
    }






    public function update_terminal(request $request)
    {


        if ($request->user_id == null || $request->serialNumber == null) {

            return response()->json([
                'status' => false,
                'message' => "User ID or Serial Number can not be null"
            ], 422);

        }

        if (Auth::user()->role == 1 || Auth::user()->role == 2) {

            $ter = Terminal::where('serialNumber', $request->serialNumber)->first() ?? null;
            if ($ter != null) {

                Terminal::where('serialNumber', $request->serialNumber)->update(['user_id' => $request->user_id, 'bank_id' => $request->bank_id]);

                try {

                    $curl = curl_init();
                    $data = array(
                        'user_id' => $request->user_id,
                        'serialNumber' => $request->serialNumber,
                        'bank_id' => $request->bank_id,
                    );
                    $post_data = json_encode($data);

                    curl_setopt_array($curl, array(
                        CURLOPT_URL => 'https://etopmerchant.com/api/update-terminal',
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => '',
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => 'POST',
                        CURLOPT_POSTFIELDS => $post_data,
                        CURLOPT_HTTPHEADER => array(
                            'Content-Type: application/json'
                        ),
                    ));

                    $var = curl_exec($curl);
                    curl_close($curl);


                } catch (QueryException $e) {
                    echo "$e";
                }

                return response()->json([
                    'status' => true,
                    'message' => "Terminal has been successfully updated"
                ], 200);

            } else {

                return response()->json([
                    'status' => false,
                    'message' => "Terminal Not Found"
                ], 422);

            }


        } else {

            return response()->json([
                'status' => false,
                'message' => "You dont have permission to update a terminal"
            ], 422);
        }
    }


    public function list_terminals(request $request)
    {



        if (Auth::user()->role == 1 || Auth::user()->role == 2 ) {

            $terminals = Terminal::latest()->paginate(10) ?? null;

            if($terminals != null){
                return  view('allterminals', compact('terminals'));
            }



        }

        if (Auth::user()->role == 3 ) {


            $ter = Terminal::latest()->where('bank_id', Auth::user()->bank_id)->paginate('10') ?? null;

            if ($ter == null) {
                return response()->json([
                    'status' => false,
                    'message' => "No Terminal Found"
                ], 422);

            }

            return response()->json([
                'status' => true,
                'data' => $ter
            ], 200);


        }

        if (Auth::user()->role == 4 ) {

            $ter = Terminal::latest()->where('user_id', Auth::id())->paginate(10) ?? null;
            if ($ter == null) {
                return response()->json([
                    'status' => false,
                    'message' => "No Terminal Found"
                ], 422);

            }

            return response()->json([
                'status' => true,
                'data' => $ter
            ], 200);


        }




        return response()->json([
            'status' => false,
            'message' => "No Terminal Found"
        ], 422);


    }


    public function search_terminal(request $request)
    {

        if (Auth::user()->role == 1 || Auth::user()->role == 2) {

            $keyword = $request->keyword;
            $results = Terminal::where('serialNumber', 'LIKE', "%$keyword%")->get();

            return response()->json([

                'status' => true,
                'data' => $results

            ], 200);



        } else {

            return response()->json([
                'status' => false,
                'message' => "You dont have permission to create a terminal"
            ], 422);
        }
    }






}
