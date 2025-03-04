<?php

namespace App\Http\Controllers;

use App\Models\OTP;
use App\Models\Students;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;

class OTPController extends Controller
{
    public static function getOtp($mobileNo)
    {
        $checkStudent = Students::where('mobile',$mobileNo)->first();
        
        if($checkStudent->count())
        {
            
            $otp = self::generateOtp($checkStudent);
            
            $isOtpSend = self::sendOtpToUser($otp,$mobileNo);
            
            if($isOtpSend)
            {
                return response()->json([
                    'status' => 'success',
                    'message' => "Otp has been send to $mobileNo"
                ]);
            }
            else
            {
                return response()->json([
                    'status'=>'error',
                   'message'=>'Failed to send OTP',
                ]);
            }
        }
        else
        {
            return response()->json([
                'status'=>'error',
                'message'=>'Please enter valid mobile number',
            ]);
        }
    }

    public static function generateOtp($studentData)
    {
        $otpData['otp'] = random_int(1000,9999);
        $otpData['mobile_number'] = $studentData->mobile;
        $otpData['expire_at'] = Carbon::now()->addMinutes(5);
        $otpData['students_id'] = $studentData->id;
       
        $storeOtp = OTP::create($otpData);
        if($storeOtp)
        {
            return $otpData['otp'];
        } else
        {
            return false;
        }
    }

    public static function sendOtpToUser($otp,$mobileNo)
    {
        $otpMessage = "$otp is your one time password to log in. Please enter OTP to proceed. EdTech Innovate";
        $apiUrl = "http://103.225.76.43/blank/sms/user/urlsms.php?username=edinsv&pass=uMa8T4@$&senderid=edinsv&dest_mobileno=$mobileNo&message=$otpMessage&response=Y";

        $sendOtp = Http::get($apiUrl);

        if($sendOtp->getReasonPhrase()=='OK')
        {
            return true;
        }
        
        return false;
    }

    public function verifyOtp($otp,$mobileNo)
    {
        $checkOtp = OTP::where(['otp'=>$otp,'mobile'=>$mobileNo])->where('is_used',false)->count();
        if($checkOtp)
        {
            return response()->json(['status' =>'success','message'=>'Welcome!']);
        }
        else
        {
            return response()->json(['status'=>'error','message'=>'Invalid OTP']);
        }
    }
}
