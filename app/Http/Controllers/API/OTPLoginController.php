<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Validator;
use App\Models\Usersession;
// use Request;

class OTPLoginController extends BaseController
{
    //OTP login
    public function index(Request $request)
    {
        $APIKey = 'ec52f202-24b7-11eb-83d4-0200cd936042';//avijit
        $validator = Validator::make($request->all(), [
            'mobile' => 'required|regex:/[0-9]{10}/|digits:10'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $input = $request->all();
        $success['input'] = $input;
        $mobile = $input['mobile'];

        ### Send OTP
        $API_Response_json=json_decode(file_get_contents("https://2factor.in/API/V1/$APIKey/SMS/$mobile/AUTOGEN"),false);
        $VerificationSessionId= $API_Response_json->Details;
        $success['sms_session_id'] = $VerificationSessionId;
        return $this->sendResponse($success, 'sms sent.');
    }

    public function saveUserSessions(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customer_id' => 'required',
            'login_time' => 'required',
            'ip_address' => 'required',
            'browser' => 'required',
            'geolocation' => 'required',
            'device' => 'required',
            'login_type' => 'required',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $input = $request->all();
        $user = Usersession::create($input);
        $success['user'] = $user;
        return $this->sendResponse($success, 'User session saved successfully.');

    }

    public function removeUserSessions($user_id)
    {
        $result = Usersession::where('customer_id', $user_id)->delete();
        $success['result'] = $result;
        return $this->sendResponse($success, 'session removed successfully.');
    }

    public function verifyOTP(Request $request)
    {
        $APIKey = 'ec52f202-24b7-11eb-83d4-0200cd936042';//avijit
        $validator = Validator::make($request->all(), [
            'OTP' => 'required',
            'sms_id' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $input = $request->all();
        // $VerificationSessionId=$_REQUEST['smsID'];
        $OTP = $input['OTP'];
        $sms_id = $input['sms_id'];
        $mobile = $input['mobile'];
        $API_Response_json=json_decode(file_get_contents("https://2factor.in/API/V1/$APIKey/SMS/VERIFY/$sms_id/$OTP"),false);
        $VerificationStatus= $API_Response_json->Details;
        // return $VerificationStatus;

        if ( $VerificationStatus == 'OTP Matched')
        {
            $success['OTP'] = $OTP;
            $success['sms_id'] = $sms_id;
            // Get user record
            $user = User::where('mobile', $mobile)->first();
            // $success['user'] = $user;
            // return $this->sendResponse($success, 'user data');
            if($user == null){
                return $this->sendError('Invalid', 'invalid Mobile Numer');
            }else{
                // Check Condition Mobile No. Found or Not
                if($mobile != $user->mobile) {
                    return $this->sendError('Invalid', 'invalid Mobile Numer');
                    // \Session::put('errors', 'Your mobile number not match in our system..!!');
                    // return back();
                }else{
                    // Set Auth Details
                    \Auth::login($user);
                    $success['token'] =  $user->createToken('MyApp')->accessToken;
                    $success['name'] =  $user->name;
                    return $this->sendResponse($success, 'User login successfully.');
                }
            }
            // return $this->sendResponse($success, 'OTP Matched.');
        }else{
            return $this->sendError('OTP Error', 'OTP is invalid');
        }
    }
}
