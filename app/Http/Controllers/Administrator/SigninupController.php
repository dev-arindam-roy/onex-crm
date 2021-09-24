<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\AdminHelperTrait as AdminHelper;
use App\Events\AdminEmailVerificationEvent;
use App\Events\AdminForgotPasswordMailEvent;
use App\Models\Admin;
use App\Models\ResetPassword;
use Carbon\Carbon;
use OnexHelper;
use Session;
use Redirect;
use Hash;
use Auth;

class SigninupController extends Controller
{
    public function __construct()
    {

    }
    
    public function index(Request $request)
    {

    }

    public function login(Request $request)
    {
        $DataBag = [];
        return view('administrators/auth/login', $DataBag);
    }

    public function loginProcess(Request $request)
    {
        $rtnResponse = [];
        $DataBag = [];
        $statusCode = 401;

        $rules = [
            'email_id' => ['required', 'email', 'max:60'],
            'password' => ['required', 'max:20']
        ];
        $messages = [
            'email_id.required' => 'Please enter email address.',
            'email_id.email' => 'Please enter valid email address.',
            'password.required' => 'Please enter password.'
        ];
        $requestValidation = OnexHelper::checkInputValidation($request->all(), $rules, $messages);
        if (!empty($requestValidation)) {
            $DataBag['validationErrors'] = $requestValidation;
            $rtnResponse = OnexHelper::constructResponse($DataBag, '', 'error', 422, 'error');
            return response()->json($rtnResponse, 422);
        }

        $email = $request->input('email_id');
        $password = $request->input('password');
        $rememberMe = $request->input('remember_me');

        $admin = AdminHelper::adminByEmail($email);
        if (!empty($admin)) {
            if ($admin->email_verified_at != '' && $admin->email_verified_at != NULL) {
                if ($admin->status == 1) {
                    if (Auth::guard('admin')->attempt(['email_id' => $email, 'password' => $password])) {
                        $statusCode = 200;
                        $DataBag['admin'] = Auth::guard('admin')->user();
                        $rtnResponse = OnexHelper::constructResponse($DataBag);
                        $this->setRememberMe($email, $password, $rememberMe);
                    } else {
                        $statusCode = 401;
                        $rtnResponse = OnexHelper::constructResponse($DataBag, '', 'error', $statusCode, 'invalid_credentials');
                    }
                } else {
                    $statusCode = 201;
                    $rtnResponse = OnexHelper::constructResponse($DataBag, '', 'error', $statusCode, 'account_blocked');
                }
            } else {
                $statusCode = 201;
                $rtnResponse = OnexHelper::constructResponse($DataBag, '', 'error', $statusCode, 'email_not_verified');
            }
        }
        return response()->json($rtnResponse, $statusCode);
    }

    public function setRememberMe($email, $password, $rememberMe)
    {
        if ($rememberMe) {
            setcookie("onexMasterLogID", $email, time() + (86400 * 30));
            setcookie("onexMasterLogPwd", $password, time() + (86400 * 30));
        } else {
            unset($_COOKIE['onexMasterLogID']);
            unset($_COOKIE['onexMasterLogPwd']);
            setcookie("onexMasterLogID", '', time() - 3600);
            setcookie("onexMasterLogPwd", '', time() - 3600);
        }
    }

    public function sendVerifyEmail(Request $request)
    {
        $rtnResponse = [];
        $DataBag = [];

        $email = $request->input('email_id');
        $admin = AdminHelper::adminByEmail($email);
        if (!empty($admin)) {
            $admin->email_verify_token = OnexHelper::generateToken(time());
            $admin->email_verify_token_expire_at = Carbon::now()->addSeconds(86400);
            $admin->save();
            AdminEmailVerificationEvent::dispatch($admin);
            $DataBag['admin'] = $admin;
            $rtnResponse = OnexHelper::constructResponse($DataBag);
            return response()->json($rtnResponse);
        }
        return response()->json($rtnResponse, 401);
    }

    public function verifyEmail(Request $request, $token)
    {
        $admin = Admin::where('email_verify_token', $token)->first();
        if (!empty($admin)) {
            $expiredDateTime = new Carbon($admin->email_verify_token_expire_at);
            if (!$expiredDateTime->isPast()) {
                $admin->email_verify_token = NULL;
                $admin->email_verify_token_expire_at = NULL;
                $admin->email_verified_at = now();
                $admin->save();
                session()->flash('msg', 'Hi, ' . $admin->first_name .', <br/>Your email has been verified successfully. Please login, thankyou.');
                session()->flash('msg_class', 'alert alert-success');
                session()->flash('msg_title', 'Success!');
            } else {
                session()->flash('msg', 'Email verification link has been expired. Please try login and get the new verification link, thankyou.');
                session()->flash('msg_class', 'alert alert-danger');
                session()->flash('msg_title', 'Link Expired!');
            }
        }
        return redirect()->route('administrator.auth.signin');
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        Session::flush();
        $DataBag = [];
        $rtnResponse = OnexHelper::constructResponse($DataBag);
        return response()->json($rtnResponse);
    }

    public function forgotPassword(Request $request)
    {
        $DataBag = [];
        return view('administrators/auth/forgot-password', $DataBag);
    }

    public function forgotPasswordProcess(Request $request)
    {
        $rtnResponse = [];
        $DataBag = [];

        $rules = [
            'email_id' => ['required', 'email', 'max:60']
        ];
        $messages = [
            'email_id.required' => 'Please enter email address.',
            'email_id.email' => 'Please enter valid email address.'
        ];
        $requestValidation = OnexHelper::checkInputValidation($request->all(), $rules, $messages);
        if (!empty($requestValidation)) {
            $DataBag['validationErrors'] = $requestValidation;
            $rtnResponse = OnexHelper::constructResponse($DataBag, '', 'error', 422, 'error');
            return response()->json($rtnResponse, 422);
        }

        $email = $request->input('email_id');
        $admin = AdminHelper::adminByEmail($email);
        if (empty($admin)) {
            $rtnResponse = OnexHelper::constructResponse($DataBag, '', 'error', 401, 'error');
            return response()->json($rtnResponse, 401);
        }

        OnexHelper::deleteResetPassword($email, 'admin');
        $resetPassword = new ResetPassword;
        $resetPassword->user_type = 'admin';
        $resetPassword->email_id = $email;
        $resetPassword->token = OnexHelper::generateToken(time());
        $resetPassword->token_expire_at = Carbon::now()->addSeconds(86400);
        $resetPassword->save();
        AdminForgotPasswordMailEvent::dispatch($resetPassword);
        $DataBag['resetPassword'] = $resetPassword; 
        $rtnResponse = OnexHelper::constructResponse($DataBag);
        return response()->json($rtnResponse);
    }

    public function resetPassword(Request $request, $token)
    {
        $DataBag = [];

        $resetPassword = ResetPassword::where('token', $token)->first();
        if (empty($resetPassword)) {
            session()->flash('msg', 'Reset password link not exist in our system or may it expired.');
            session()->flash('msg_class', 'alert alert-danger');
            session()->flash('msg_title', 'Invalid Link!');
            return redirect()->route('administrator.auth.signin');
        }
        $expiredDateTime = new Carbon($resetPassword->token_expire_at);
        if ($expiredDateTime->isPast()) {
            session()->flash('msg', 'Reset password link expired, Please proceed with new link, thankyou.');
            session()->flash('msg_class', 'alert alert-danger');
            session()->flash('msg_title', 'Link Expired!');
            return redirect()->route('administrator.auth.forgot.password');
        }
        $DataBag['reset_password'] = $resetPassword;
        return view('administrators/auth/reset-password', $DataBag);
    }

    public function resetPasswordSave(Request $request, $token)
    {
        $rtnResponse = [];
        $DataBag = [];

        $rules = [
            'email_id' => ['required', 'email', 'max:60'],
            'password' => ['bail', 'required', 'min:8', 'max:20'],
            'confirm_password' => ['bail', 'required', 'same:password']
        ];
        $messages = [
            'email_id.required' => 'Please enter email address.',
            'email_id.email' => 'Please enter valid email address.',
            'password.required' => 'Please enter password.',
            'password.min' => 'Password required atleast 8 characters.',
            'password.max' => 'Password should less than 20 characters.',
            'confirm_password.required' => 'Please enter confirm password.',
            'confirm_password.same' => 'Confirm password not match with password.'
        ];
        $requestValidation = OnexHelper::checkInputValidation($request->all(), $rules, $messages);
        if (!empty($requestValidation)) {
            $DataBag['validationErrors'] = $requestValidation;
            $rtnResponse = OnexHelper::constructResponse($DataBag, '', 'error', 422, 'error');
            return response()->json($rtnResponse, 422);
        }

        $resetPassword = ResetPassword::where('token', $token)->first();
        if (empty($resetPassword)) {
            $rtnResponse = OnexHelper::constructResponse($DataBag, '', 'error', 404, 'error');
            return response()->json($rtnResponse, 404);
        }
        
        $admin = AdminHelper::adminByEmail($resetPassword->email_id);
        if (empty($admin)) {
            $rtnResponse = OnexHelper::constructResponse($DataBag, '', 'error', 404, 'error');
            return response()->json($rtnResponse, 404);
        }
        $admin->password = Hash::make($request->password);
        $admin->save();
        OnexHelper::deleteResetPassword($admin->email_id, 'admin');
        $DataBag['admin'] = $admin;
        $rtnResponse = OnexHelper::constructResponse($DataBag);
        return response()->json($rtnResponse);    
    }
}
