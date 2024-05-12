<?php

namespace App\Http\Controllers\Agents;

use App\Http\Controllers\Controller;
use App\Models\Beneficiary;
use App\Models\Setting;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransferController extends Controller
{


    public function transfer_properties(request $request)
    {

        $Url = env('9PSTRANSFERURL');
        $token = psb_token();
        $currentDateTime = Carbon::now();
        $formattedDateTime = $currentDateTime->format('Y-m-d\TH:i:s.uO');


        $data = array(
            'RequestDateTime' => $formattedDateTime,
        );

        $post_data = json_encode($data);

        if ($token == 0) {
            return response()->json([
                'status' => false,
                'message' => "Please try again later",
            ], 500);

        }

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "$Url/transfer/getbanks",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $post_data,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                "Authorization: Bearer $token"
            ),
        ));

        $var = curl_exec($curl);
        curl_close($curl);
        $var = json_decode($var);
        $history = $var->BankList ?? null;

        dd($var, $post_data);

        if ($history != null) {

            $history = [];
            foreach ($var->BankList as $key => $value) {
                $history[] = array(
                    "bank_name" => $value->BankName,
                    "code" => $value->BankCode,
                );
            }


            $account = select_account();
            $transfer_charge = Setting::where('id', 1)->first()->transfer_charge;
            $bens = Beneficiary::select('id', 'name', 'bank_code', 'acct_no')->where('user_id', Auth::id())->get() ?? [];


            return response()->json([
                'account' => $account,
                'transfer_charge' => $transfer_charge,
                'banks' => $history,
                'beneficariy' => $bens,
            ], 200);


        }


    }


    public function validate_account(request $request)
    {


        $Url = env('9PSTRANSFERURL');
        $token = psb_token();


        $data = array(
            'customer' => [
                'account' =>[
                    'number' => $request->account_number,
                    'bank' => $request->bank_code
                ],
            ],
        );

        $post_data = json_encode($data);

        if ($token == 0) {
            return response()->json([
                'status' => false,
                'message' => "Please try again later",
            ], 500);

        }

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "$Url/account/enquiry",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $post_data,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                "Authorization: Bearer $token"
            ),
        ));

        $var = curl_exec($curl);
        curl_close($curl);
        $var = json_decode($var);
        $code = $var->code ?? null;

        if($code == 00){
            $name = $var->customer->name;
        }else {
            $name = "Invalid Account, Check information again";
        }

            return response()->json([
                'status' => true,
                'customer_name' => $name,
            ], 200);



    }


}
