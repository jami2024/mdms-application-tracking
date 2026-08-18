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
use App\Models\WorkflowStep;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    public function __construct()
    {
        // $this->middleware('auth');
    }

    public function processPayment($applicationId=null, $serviceId=null)
    {

        $application_data = Application::select('id', 'status', 'applicable_id')->where('application_no', $applicationId)->first();

        $application_id = null;
        if (!$application_data) {
            return response()->json([
                'success' => false,
                'message' => 'Application not found',
            ]);
        } else {
            $application_id = $application_data->id;
            if ($application_data->status == 'rejected') {
                return response()->json([
                    'success' => false,
                    'message' => 'Your application has been rejected by the Admin.',
                ]);
            }
        }

        // dd($application_data);
        $service_application_data = ServiceApplication::find($application_data->applicable_id)->first();

        if (!$service_application_data) {
            return response()->json([
                'success' => false,
                'message' => 'Service Application not found',
            ]);
        }

        $invoice_number = 'DGDA-INV' . time() . '-0' . rand(1000, 9999);
        $reference = 'Service Application ' . $invoice_number;
        $app_fee = 0; // Example app fee, you can replace this with the actual app fee from the request or database
        $platform_fee = 0; // Example platform fee, you can replace this with the actual platform fee from the request or database
        $amount = env('PAY_AMOUNT_SERVICE_APPLICATION'); // Example amount, you can replace this with the actual amount from the request or database

        $cust_name = $service_application_data->applicant_name ?? null;
        $cust_address = null;
        $cust_phone = $service_application_data->mobile_number ?? null;
        $cust_email = $service_application_data->email ?? null;

        $base_url = env('APP_ENV') === 'local' ? 'https://dgda-tracking.mysoftheaven.com' : env('APP_URL');

        $callback_url = $base_url . '/pay-station/callback';
        $cancel_url = $base_url . '/pay-station/cancel';
        $notify_url = $base_url . '/pay-station/notify';

        $merchantId = env('PAY_STATION_MERCHANT_ID');
        $merchantPassword = env('PAY_STATION_MERCHANT_PASSWORD');


        $user = Auth::user();

        // $payment_data = Payment::where('payment_type', 'account_application')->where('user_id', $user->id)->first();

        // if ($payment_data) {

        //     if ($payment_data->status == 'pending' && $payment_data->payment_complete_response == null && $payment_data->payment_url != null) {

        //         // check :: payment_url_expiry
        //         // check created_at is less than 2 minutes
        //         if (time() < strtotime($payment_data->created_at->addMinutes(2))) {
        //             return redirect($payment_data->payment_url);
        //         }
        //     }

        //     if ($payment_data->status == 'paid' && $payment_data->payment_complete_response != null && $payment_data->payment_complete_datetime != null) {

        //         return view('admin.payment_success')->with(['error_message' => 'Your payment has already been processed.']);
        //     }
        // }

        $payment_ini_response = $this->callPayStationPaymentInitiate([

            'invoice_number' => $invoice_number,
            'currency'       => 'BDT',
            'payment_amount' => $amount,
            'reference'      => $reference,

            'cust_name'      => $cust_name,
            'cust_address'   => $cust_address,
            'cust_phone'     => $cust_phone,
            'cust_email'     => $cust_email,

            'callback_url'   => $callback_url,
            'cancel_url'     => $cancel_url,
            'notify_url'     => $notify_url,
            'checkout_items' =>  null,

            'merchantId'     => $merchantId,             // 2233-1771313076
            'password'       => $merchantPassword,         // J6g$d3@1
        ]);

        // dd($payment_ini_response);

        if (!$payment_ini_response || !isset($payment_ini_response->status) || $payment_ini_response->status !== 'success' || !isset($payment_ini_response->status_code) || $payment_ini_response->status_code !== '200') {
            return response()->json([
                'success' => false,
                'message' => 'Payment initiation failed',
                'details' => $payment_ini_response
            ], 500);
        } else {
            if (isset($payment_ini_response->status_code) && $payment_ini_response->status_code == '200') {

                // create payment initialization record in database
                try {
                    $payment = Payment::create([
                        'payment_type' => 'service_application',
                        'applicant_id' => Auth::user()->id,
                        // 'company_id'   => $company_id ?? null,
                        'invoice_number' => $invoice_number,
                        'currency' => 'BDT',
                        'payment_amount' => $amount,
                        'reference' => $reference,
                        'cust_name' => $cust_name,
                        'cust_address' => $cust_address,
                        'cust_phone' => $cust_phone,
                        'cust_email' => $cust_email,
                        'callback_url' => $callback_url,
                        'cancel_url' => $cancel_url,
                        'notify_url' => $notify_url,
                        'checkout_items' => null,
                        'payment_code' => $payment_ini_response->status_code,
                        'payment_data' => json_encode($payment_ini_response),
                        'app_fee' => $app_fee,
                        'platform_fee' => $platform_fee,
                        'total_amount' => $amount,
                        'payment_url' => $payment_ini_response->payment_url,
                        'payment_url_expiry' => $payment_ini_response->payment_url_expiry ?? null,
                        'status' => 'pending',
                        'application_id' => $application_id,
                        'applicable_id' => $serviceId,
                        'user_id' => Auth::user()->id,
                        'description' => 'Service Application Payment',
                        'method' => 'SSLCOMMERZ',
                        'amount' => $amount
                    ]);

                    if (!$payment) {
                        dd('payment failed');
                    }
                } catch (\Exception $e) {
                    dd($e->getMessage());
                }

                // redirect user to the payment gateway URL
                return $payment_ini_response->payment_url;
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment initiation failed',
                    'details' => $payment_ini_response
                ], 500);
            }
        }
    }


    public function processDeviceApplicationPayment(Request $request)
    {

        $device_application_data = DeviceApplications::where('status', 'incomplete')->where('applicant_id', Auth::user()->id)->first();
        // $company_id = Company::where('owner_user_id', Auth::user()->id)->pluck('id')->first();
        $device_application_id = null;

        if (!$device_application_data) {
            return response()->json([
                'success' => false,
                'message' => 'Application not found',
            ]);
        } else {

            if ($device_application_data->is_payment_completed == 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'Application payment already completed.',
                ]);
            }

            $device_application_id = $device_application_data->id;

            if ($device_application_data->status == 'Approved') {
                return response()->json([
                    'success' => false,
                    'message' => 'Application already approved.',
                ]);
            }
        }

        $application_data = Application::where('applicable_type', 'App\Models\DeviceApplications')->where('applicable_id', $device_application_id)->first();
        $applicationId = null;

        if (!$application_data) {

            // Create new applciation
            $applicationId = Application::insertGetId([
                'application_no' => $device_application_data->application_no,
                'applicable_type' => 'App\\Models\\DeviceApplications',
                'applicable_id' => $device_application_id,
                'workflow_config_id' => '3',
                'current_step_id' => '1',
                'applicant_id' => Auth::user()->id,
                'status' => 'submitted',
                'submitted_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            $applicationId = $application_data->id;
        }

        $invoice_number = 'DGDA-INV-DEV-' . time() . '-0' . rand(100, 999);
        $reference = 'Device Application ' . $invoice_number;
        $app_fee = 0; // Example app fee, you can replace this with the actual app fee from the request or database
        $platform_fee = 0; // Example platform fee, you can replace this with the actual platform fee from the request or database
        $amount = $device_application_data->total_amount; // Example amount, you can replace this with the actual amount from the request or database

        $cust_name = Auth::user()->name ?? null;
        $cust_address = null;
        $cust_phone = Auth::user()->phone ?? null;
        $cust_email = Auth::user()->email ?? null;

        $base_url = env('APP_ENV') === 'local' ? 'http://mdms_dgda.test' : env('APP_URL');

        $callback_url = $base_url . '/pay-station/callback';
        $cancel_url = $base_url . '/pay-station/cancel';
        $notify_url = $base_url . '/pay-station/notify';

        $merchantId = env('PAY_STATION_MERCHANT_ID');
        $merchantPassword = env('PAY_STATION_MERCHANT_PASSWORD');

        $user = Auth::user();



        $payment_ini_response = $this->callPayStationPaymentInitiate([

            'invoice_number' => $invoice_number,
            'currency'       => 'BDT',
            'payment_amount' => $amount,
            'reference'      => $reference,

            'cust_name'      => $cust_name,
            'cust_address'   => $cust_address,
            'cust_phone'     => $cust_phone,
            'cust_email'     => $cust_email,

            'callback_url'   => $callback_url,
            'cancel_url'     => $cancel_url,
            'notify_url'     => $notify_url,
            'checkout_items' =>  null,

            'merchantId'     => $merchantId,             // 2233-1771313076
            'password'       => $merchantPassword,         // J6g$d3@1
        ]);

        if (!$payment_ini_response || !isset($payment_ini_response->status) || $payment_ini_response->status !== 'success' || !isset($payment_ini_response->status_code) || $payment_ini_response->status_code !== '200') {
            return response()->json([
                'success' => false,
                'message' => 'Payment initiation failed',
                'details' => $payment_ini_response
            ], 500);
        } else {
            if (isset($payment_ini_response->status_code) && $payment_ini_response->status_code == '200') {

                // create payment initialization record in database
                Payment::create([
                    'payment_type' => 'device_application',
                    'applicant_id' => Auth::user()->id,
                    'company_id'   => $company_id ?? null,
                    'invoice_number' => $invoice_number,
                    'currency' => 'BDT',
                    'payment_amount' => $amount,
                    'reference' => $reference,
                    'cust_name' => $cust_name,
                    'cust_address' => $cust_address,
                    'cust_phone' => $cust_phone,
                    'cust_email' => $cust_email,
                    'callback_url' => $callback_url,
                    'cancel_url' => $cancel_url,
                    'notify_url' => $notify_url,
                    'checkout_items' => null,
                    'payment_code' => $payment_ini_response->status_code,
                    'payment_data' => json_encode($payment_ini_response),
                    'app_fee' => $app_fee,
                    'platform_fee' => $platform_fee,
                    'total_amount' => $amount,
                    'payment_url' => $payment_ini_response->payment_url,
                    'payment_url_expiry' => $payment_ini_response->payment_url_expiry ?? null,
                    'status' => 'pending',
                    'application_id' => $applicationId,
                    'applicable_id' => $device_application_id,
                    'user_id' => Auth::user()->id,
                    'description' => 'Device Application Payment',
                    'method' => 'SSLCOMMERZ',
                    'amount' => $amount
                ]);

                // redirect user to the payment gateway URL
                return redirect($payment_ini_response->payment_url);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment initiation failed',
                    'details' => $payment_ini_response
                ], 500);
            }
        }
    }



    public function callPayStationPaymentInitiate($payment_data)
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://sandbox.paystation.com.bd/initiate-payment',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => array(

                'invoice_number' => $payment_data['invoice_number'],
                'currency' => $payment_data['currency'],
                'payment_amount' => $payment_data['payment_amount'],
                'reference' => $payment_data['reference'],

                'cust_name' => $payment_data['cust_name'],
                'cust_phone' => $payment_data['cust_phone'],
                'cust_email' => $payment_data['cust_email'],
                'cust_address' => $payment_data['cust_address'],

                'callback_url' => $payment_data['callback_url'],
                'checkout_items' => $payment_data['checkout_items'],
                'merchantId' => $payment_data['merchantId'],
                'password' => $payment_data['password']
            ),
        ));

        $response = json_decode(curl_exec($curl));
        curl_close($curl);
        dd($response);
        return $response;
    }

    public function callbackPaymentProcess(Request $request)
    {

        try{
            // DB::beginTransaction();
            if ($request->has('status') && $request->has('invoice_number') && $request->has('trx_id')) {
                if ($request->status === 'Successful') {
                    if ($request->invoice_number && $request->trx_id) {

                        $payment = Payment::where('invoice_number', $request->invoice_number)->first();

                        if ($payment) {

                            $payment->status = 'paid';
                            $payment->gateway_transaction_id = $request->trx_id;
                            $payment->payment_complete_response = json_encode($request->all());
                            $payment->payment_complete_datetime = now();
                            $payment->paid_at = now();
                            $payment->save();

                            if ($payment->payment_type == 'service_application') {

                                $application = Application::where('id', $payment->application_id)->first();
                                $firstStepId = WorkflowStep::where('workflow_config_id', $application->workflow_config_id)->where('step_order', 2)->pluck('id')->first();

                                $application->current_step_id = $firstStepId;
                                $application->assigned_to = 2;
                                $application->save();


                                $payment = [
                                    'trx_id'         => $payment->invoice_number?? '--',
                                    'paid_at'        => date('d-m-Y h:i A')?? '--',
                                    'payer_name'     => $payment->cust_name?? '--',
                                    'tracking_no'    => $application->application_no?? '--',
                                    'service_name'   => '--',
                                    'amount'         => number_format($payment->amount?? 0, 2),
                                    'method'         => 'PayStation', // e.g. 'bKash', 'Nagad', 'Card?? '--''
                                    'gateway_ref'    => $request->trx_id?? '--',
                                    'application_id' => $payment->invoice_number?? '--',
                                ];

                                return view('auth.payment_success', compact('payment'));
                            }
                        } else {
                            return view('admin.payment_success')->with(['error_message' => 'Invalid Payment Data, Please Try Again with Valid Invoice Number.']);
                        }
                    } else {
                        return view('admin.payment_success')->with(['error_message' => 'Invalid Response from Payment Gateway, Please Try Again. ' . json_encode($request->all())]);
                    }
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Payment failed or canceled',
                        'invoice_number' => $request->invoice_number,
                        'transaction_id' => $request->trx_id
                    ]);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid callback data'
                ], 400);
            }
        }catch(\Exception $e){
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while processing the payment callback',
                'error' => $e->getMessage()
            ], 500);
        }

    }

    public function showTransactionStatus(Request $request)
    {
        $payment = Payment::where('invoice_number', $request->invoice_number)->first();
        return view('admin.payment_success', compact('payment'));
    }

    public function paymentSuccess(Request $request)
    {
        // Adjust this to however you're actually storing/retrieving the payment record
        $payment = [
            'trx_id'         => $transaction->trx_id?? '--',
            'paid_at'        => date('d-m-Y h:i A')?? '--',
            'payer_name'     => $transaction->application->applicant_name?? '--',
            'tracking_no'    => $transaction->application->tracking_no?? '--',
            'service_name'   => $transaction->application->serviceType->name?? '--',
            'amount'         => number_format($transaction->amount?? 0, 2),
            'method'         => $transaction->payment_method?? '--', // e.g. 'bKash', 'Nagad', 'Card?? '--''
            'gateway_ref'    => $transaction->gateway_ref?? '--',
            'application_id' => $transaction->application_id?? '--',
        ];

        return view('auth.payment_success', compact('payment'));
    }
}
