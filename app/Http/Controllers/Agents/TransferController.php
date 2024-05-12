<?php

namespace App\Http\Controllers\Agents;

use App\Http\Controllers\Controller;
use App\Models\Beneficiary;
use App\Models\Feature;
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
            CURLOPT_URL => "$Url/merchant/transfer/getbanks",
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


        if ($history != null) {

            $history = [];
            foreach ($var->BankList as $key => $value) {
                $history[] = array(
                    "bankName" => $value->BankName,
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
            CURLOPT_URL => "$Url/merchant/account/enquiry",
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
            $name = $var->customer->account->name;

            return response()->json([
                'status' => true,
                'customer_name' => $name,
            ], 200);


        }else {

            $name = "Invalid Account, Check information again";

            return response()->json([
                'status' => true,
                'customer_name' => $name,
            ], 200);
        }




    }



//{
//"transaction": {
//"reference": "VT202401101628000000000"
//},
//"order": {
//    "amount": 249.25,
//    "description": "Virtual Settlement",
//    "currency": "NGN",
//    "country": "NGA"
//  },
//  "customer": {
//    "account": {
//        "number": "8134943416",
//      "bank": "120001",
//      "name": "Merchant Name",
//      "senderaccountnumber": "1100000309",
//      "sendername": "9PSB Agent/Oyenike Adeola"
//    }
//  },
//  "hash":"C53B53F7A8024E7283B14006E24F9E14927FCC7DA6E66491A447FE6224ECF6F49DB79BD6746AB7898E31105AAA20C36E2B4241083874786C4078B02F73FCDFDC"
//}


    public function transfer(request $request)
{


    if (Auth::user()->status == 7) {


        return response()->json([

            'status' => false,
            'message' => 'You can not make transfer at the moment, Please contact support',

        ], 500);
    }




    $Url = env('9PSTRANSFERURL');
    $token = psb_token();


    $data = array(
        'transaction' => [
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
        CURLOPT_URL => "$Url/merchant/account/enquiry",
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
        $name = $var->customer->account->name;

        return response()->json([
            'status' => true,
            'customer_name' => $name,
        ], 200);


    }else {

        $name = "Invalid Account, Check information again";

        return response()->json([
            'status' => true,
            'customer_name' => $name,
        ], 200);
    }




}



}
