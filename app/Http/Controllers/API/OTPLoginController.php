<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Validator;


class OTPLoginController extends BaseController
{
    //OTP login
    public function index(Request $request)
    {
        $APIKey = 'ec52f202-24b7-11eb-83d4-0200cd936042';//avijit
        $validator = Validator::make($request->all(), [
            'mobile' => 'required'
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
        $success['sms'] = $VerificationSessionId;
        return $this->sendResponse($success, 'sms sent.');
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
        $API_Response_json=json_decode(file_get_contents("https://2factor.in/API/V1/$APIKey/SMS/VERIFY/$sms_id/$OTP"),false);
        $VerificationStatus= $API_Response_json->Details;
        // return $VerificationStatus;

        if ( $VerificationStatus == 'OTP Matched')
        {
            $success['OTP'] = $OTP;
            $success['sms_id'] = $sms_id;
            return $this->sendResponse($success, 'OTP Matched.');
        }else{
            return $this->sendError('OTP Error', 'OTP is invalid');
        }
    }
}
