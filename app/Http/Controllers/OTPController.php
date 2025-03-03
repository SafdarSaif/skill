<?php

namespace App\Http\Controllers;

use App\Models\OTP;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;

class OTPController extends Controller
{
    public function getOtp($mobileNo)
    {
        $checkStudent = Student::where('mobile_no',$mobileNo)->count();
        if($checkStudent)
        {
            $otp = self::generateOtp($checkStudent);
            $isOtpSend = self::sendOtpToUser($otp,$mobileNo);
            if($isOtpSend)
            {
                return response()->json([
                    'status' => 'error',
                    'message' => "Otp has been send to $mobileNo"
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
        $otpData['mobileNo'] = $studentData->mobile;
        $otpData['expireAt'] = Carbon::now()->addMinutes(5);
        $otpData['studentId'] = $studentData->id;
        $storeOtp = OTP::create($otpData);
        if($storeOtp)
        {
            return $otpData['otp'];
        } else
        {
            return false;
        }
    }

    public function sendOtpToUser($otp,$mobileNo)
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
        $checkOtp = OTP::where(['otp'=>$otp,'mobile_no'=>$mobileNo])->where('is_used',false)->count();
        if($checkOtp)
        {
            
        }
        else
        {
            return response()->json(['status'=>'error','message'=>'Invalid OTP']);
        }
    }
}
