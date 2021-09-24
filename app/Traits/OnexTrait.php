<?php

namespace App\Traits;
use App\Models\User;
use App\Models\ResetPassword;
use Validator;
use DB;

trait OnexTrait
{
    public static function constructResponse($response = [], $message = '', $messageType = 'success', $status = 200, $type = 'success')
    {
        $responseArray = [];
        $responseArray['status'] = $status;
        $responseArray['type'] = $type;
        $responseArray['body']['message'] = $message;
        $responseArray['body']['type'] = $messageType;
        $responseArray['body']['content'] = $response;
        return $responseArray;
    }

    public static function checkInputValidation($formData, $rules, $messages)
    {
        $errors = [];
        $validation = Validator::make($formData, $rules, $messages);
        if ($validation->fails()) {
            $validationErrors = $validation->errors();
            $validationErrorsArr = $validation->errors()->toArray();
            foreach($validationErrorsArr as $errs) {
                foreach($errs as $err) {
                    array_push($errors, $err);
                }
            }
        }
        return $errors;
    }

    public static function userByEmail($email)
    {
        return User::where('email_id', $email)->first();
    }

    public static function userByMobile($mobile)
    {
        return User::where('mobile_number', $mobile)->whereNotNull('mobile_verified_at')->first();
    }

    public static function userByUsername($username)
    {
        return User::where('username', $username)->first();
    }

    public static function deleteResetPassword($email, $userType)
    {
        ResetPassword::where('email_id', $email)->where('user_type', $userType)->delete();
        return true;
    }
}