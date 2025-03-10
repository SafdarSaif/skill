<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\StudentCourse;
use App\Models\StudentPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
class EasebuzzPaymentController extends Controller
{
    public static $merchantKey;
    public static $salt;
    public static $baseUrl;
    public static function init()
    {
        self::$merchantKey = env('EASEBUZZ_MERCHANT_KEY');
        self::$salt = env('EASEBUZZ_SALT');
        self::$baseUrl = env('EASEBUZZ_ENV') === 'production'
            ? 'https://pay.easebuzz.in'
            : 'https://testpay.easebuzz.in';
    }

    public static function initiatePayment($data){
        self::init();
        $postdata = [
            'key'=>self::$merchantKey,
            'txnid'=> $data['txnid'],
            'amount'=> $data['amount'],
            'productinfo'=>$data['proinfo'],  
            'firstname'=> $data['name'],
            'email'=> $data['email'],
            'phone'=> $data['mobile'],
            'surl' => route('easebuzz.success'),
            'furl' => route('easebuzz.failure'),
            'udf1' => '', 'udf2' => '', 'udf3' => '', 'udf4' => '', 'udf5' => ''
        ];
        $baseUrl = self::$baseUrl;
           
        $postdata['hash'] = self::generateHash($postdata);
        $postdata['split_payments'] = json_encode(array("Edtech Innovate Pvt Ltd." => $data['amount']));
        
        $response = Http::asForm()->post('https://pay.easebuzz.in/payment/initiateLink', $postdata);
        return $response->json();
    }

    private static function generateHash($data)
    {
        self::init();

        $hashSequence = $data['key'] . '|' . $data['txnid'] . '|' . $data['amount'] . '|' .
            $data['productinfo'] . '|' . $data['firstname'] . '|' . $data['email'] .
            '|||||||||||' . self::$salt;
        return hash('sha512', $hashSequence);
    }
    public function paymentSuccess(Request $request)
    {
        $payment = StudentPayment::where('transaction_id',$request->txnid)->first();
        if($payment){
            $payment->update([
                'payment_status' => "completed",
                'payment_confirmation_date' => now(),
            ]);
        }else{
            $payment = StudentPayment::create([
                'student_id' => $request->student_id,
                'course_id' => $request->course_id,
                'amount' => $request->amount ?? 0,
                'payment_status' => "completed",
                'transaction_id' => $request->txnid,
                'payment_confirmation_date' => now(),
            ]);
        }
        
        if ($payment) {
            StudentCourse::updateOrCreate(
                [
                    'student_id' => $payment->student_id,
                    'course_id' => $payment->course_id,
                ],
                [
                    'student_payment_id' => $payment->id,
                    'status' => 1,
                ]
            );
        }
 
        return response()->json([
            'status' => 'success',
            'message' => 'Payment successful!',
            'data' => $payment,
        ]);
        
    }

    public function paymentFailure(Request $request)
    {
        return response()->json(['status' => 'failed', 'message' => 'Payment failed!', 'data' => $request->all()]);
    }
}
