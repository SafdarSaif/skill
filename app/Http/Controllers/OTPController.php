<?php

namespace App\Http\Controllers;

use App\Models\OTP;
use App\Models\Students;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class OTPController extends Controller
{
    public static function getOtp($mobileNo)
    {
        try {
            // $checkStudent = Students::where('mobile',$mobileNo)->first();
            $checkStudent = Students::where('mobile', $mobileNo)
                ->where('status', 1)
                ->first();


            if ($checkStudent !== null && $checkStudent->count()) {

                $otp = self::generateOtp($checkStudent);

                // $isOtpSend = self::sendOtpToUser($otp, $mobileNo);

                $isOtpSend=true;
                if ($isOtpSend) {
                    return response()->json([
                        'status' => 'success',
                        'message' => "Otp has been send to $mobileNo"
                    ]);
                } else {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Failed to send OTP',
                    ]);
                }
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Please enter valid mobile number',
                ]);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }


    public static function generateOtp($studentData)
    {
        $otpData['otp'] = random_int(1000, 9999);
        $otpData['mobile_number'] = $studentData->mobile;
        $otpData['expire_at'] = Carbon::now()->addMinutes(5);
        $otpData['students_id'] = $studentData->id;

        $storeOtp = OTP::create($otpData);
        if ($storeOtp) {
            return $otpData['otp'];
        } else {
            return false;
        }
    }

    public static function sendOtpToUser($otp, $mobileNo)
    {
        $otpMessage = "$otp is your one time password to log in. Please enter OTP to proceed. EdTech Innovate";
        $apiUrl = "http://103.225.76.43/blank/sms/user/urlsms.php?username=edinsv&pass=uMa8T4@$&senderid=edinsv&dest_mobileno=$mobileNo&message=$otpMessage&response=Y";

        $sendOtp = Http::get($apiUrl);

        if ($sendOtp->getReasonPhrase() == 'OK') {
            return true;
        }

        return false;
    }

    public function verifyOtp($otp,$mobileNo)
    {
        $checkOtp = OTP::where(['otp'=>$otp,'mobile_number'=>$mobileNo])->where('is_used',false)->where('expire_at','>',Carbon::now())->count();
        if($checkOtp)
        {
            OTP::where(['otp'=>$otp,'mobile_number'=>$mobileNo])->update(['is_used'=>true]);
            $stu_data = StudentsController::StudentAllDetaills($mobileNo);
            return response()->json(['status' =>'success','message'=>'Welcome!',
            'data' => json_decode($stu_data->content(),true)['data']]);
        }
        else
        {
            return response()->json(['status'=>'error','message'=>'Invalid OTP']);
        }
    }

    public function reSendOtp($mobileNo)
    {
        $studentOtp = OTP::where(['mobile_number' => $mobileNo])->where('is_used', false)->where('expire_at', '>', Carbon::now())->get('id');
        foreach ($studentOtp as $requestedOtp) {
            OTP::where('id', $requestedOtp->id)->update(['is_used' => true]);
        }
        return self::getOtp($mobileNo);
    }


    // public function verifyOtp($otp, $mobileNo, Request $request)
    // {
    //     $checkOtp = OTP::where([
    //         'otp' => $otp,
    //         'mobile_number' => $mobileNo
    //     ])->where('is_used', false)
    //         ->where('expire_at', '>', Carbon::now())
    //         ->count();

    //     if ($checkOtp) {
    //         // Mark OTP as used
    //         OTP::where([
    //             'otp' => $otp,
    //             'mobile_number' => $mobileNo
    //         ])->update(['is_used' => true]);

    //         // Fetch student data
    //         $stu_data = StudentsController::StudentAllDetaills($mobileNo);
    //         $student = Students::where('mobile', $mobileNo)->first();

    //         if ($student) {
    //             // Generate session token and identifiers
    //             $sessionId = Str::uuid(); // or you can use session()->getId()
    //             $mobileId = $request->header('Mobile-ID'); // sent from app
    //             $deviceToken = hash('sha256', $request->userAgent() . $mobileId);

    //             // Save to database
    //             $student->session_id = $sessionId;
    //             $student->mobile_id = $mobileId;
    //             $student->device_token = $deviceToken;
    //             $student->save();

    //             return response()->json([
    //                 'status' => 'success',
    //                 'message' => 'Welcome!',
    //                 'session_id' => $sessionId,
    //                 'device_token' => $deviceToken,
    //                 'mobile_id' => $mobileId,
    //                 'data' => json_decode($stu_data->content(), true)['data']
    //             ]);
    //         } else {
    //             return response()->json([
    //                 'status' => 'error',
    //                 'message' => 'Student not found'
    //             ]);
    //         }
    //     } else {
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => 'Invalid OTP'
    //         ]);
    //     }
    // }

    // public function logout(Request $request)
    // {
    //     $student = auth('student')->user();
    //     $student->update([
    //         'device_token' => null,
    //         'mobile_id' => null,
    //         'session_id' => null,
    //     ]);

    //     auth('student')->logout();

    //     return response()->json(['message' => 'Logged out successfully']);
    // }
    public function logout(Request $request)
    {
        $mobileId = $request->header('Mobile-ID');
        $sessionId = $request->header('Session-ID');
        $deviceToken = hash('sha256', $request->userAgent() . $mobileId);

        // Find the student by session and device
        $student = Students::where('mobile_id', $mobileId)
            ->where('session_id', $sessionId)
            ->where('device_token', $deviceToken)
            ->first();
            // dd($student);

        if (!$student) {
            return response()->json(['status' => false, 'message' => 'Invalid session or device'], 403);
        }

        // Clear session details
        $student->update([
            'device_token' => null,
            'mobile_id' => null,
            'session_id' => null,
        ]);

        return response()->json(['status' => true, 'message' => 'Logged out successfully']);
    }
}
