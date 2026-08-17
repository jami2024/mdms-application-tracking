<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Company;
use App\Models\DeviceApplications;
use App\Models\Payment;
use App\Models\PaymentRequests;
use App\Models\ServiceApplication;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class SmsController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth');
    }

    public function notifyThroughSms($to='01779611966', $message='Hello Message'){

        $curl = curl_init();
        $new_message = curl_escape($curl, $message);
        $newto = '88' . $to;
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://sms.songbirdtelecom.com:8746/sendtext',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
            "apikey": "075b59787c645957",
            "secretkey": "c48c5a54",
            "callerID": "Ecourt",
            "toUser": "' . $newto . '",
            "messageContent": "' . $message . '"
            }',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        return  $response;
    }
}
