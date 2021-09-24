<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Events\ClientEmailVerificationEvent; 
use App\Events\ClientWelcomeMailEvent;
use App\Events\ClientForgotPasswordMailEvent;
use App\Models\User;
use App\Models\BusinessAccount;
use App\Models\ResetPassword;
use Carbon\Carbon;
use OnexHelper;
use Session;
use Redirect;
use Hash;
use Auth;

class SignupController extends Controller
{
    public function __construct()
    {

    }
    
    public function index(Request $request)
    {
        Session::forget('signupOnBoarding');
        $DataBag = [];
        return view('clients/auth/signup', $DataBag);
    }

    public function initialSignupValidation($request)
    {
        $rules = [
            'first_name' => ['bail', 'required', 'min:3', 'max:30', 'regex:/^[a-z A-Z]+$/'],
            'last_name' => ['bail', 'required', 'min:3', 'max:30', 'regex:/^[a-z A-Z]+$/'],
            'email_id' => ['bail', 'required', 'email', 'max:60', 'unique:users,email_id'],
        ];
        $messages = [
            'first_name.required' => 'Please enter first name.',
            'first_name.min' => 'First name required atleast 3 characters.',
            'first_name.max' => 'First name should less than 30 characters.',
            'first_name.regex' => 'First name should be alphabetic characters',
            'last_name.required' => 'Please enter last name.',
            'last_name.min' => 'Last name required atleast 3 characters.',
            'last_name.max' => 'Last name should less than 30 characters.',
            'last_name.regex' => 'Last name should be alphabetic characters',
            'email_id.required' => 'Please enter email address.',
            'email_id.email' => 'Please enter an valid email address',
            'email_id.max' => 'Email address should less than 60 characters.',
            'email_id.unique' => 'Email address already exist, Please try another.',
        ];
        return OnexHelper::checkInputValidation($request->all(), $rules, $messages);
    }

    public function initialSignup(Request $request)
    {
        $rtnResponse = [];
        $DataBag = [];

        $email = $request->email_id;
        $isUserExist = OnexHelper::userByEmail($email);
        if (!empty($isUserExist)) {
            $DataBag['nextAction'] = $this->signupStepProgressChecking($isUserExist);
            $rtnResponse = OnexHelper::constructResponse($DataBag, '', '', 201);
            return response()->json($rtnResponse, 200);
        }

        $requestValidation = $this->initialSignupValidation($request);
        if (!empty($requestValidation)) {
            $DataBag['validationErrors'] = $requestValidation;
            $rtnResponse = OnexHelper::constructResponse($DataBag, '', 'error', 422, 'error');
            return response()->json($rtnResponse, 422);
        }

        $user = new User;
        $user->hash_id = OnexHelper::generateHashID();
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email_id = $email;
        $user->username = md5($user->hash_id);
        $user->password = md5($user->hash_id);
        $user->email_verify_token = OnexHelper::generateToken($user->hash_id);
        $user->email_verify_token_expire_at = Carbon::now()->addSeconds(86400);
        $user->agree_signup_terms = $request->agree_signup_terms ? 1 : 0;
        $user->is_owner = 1;
        $user->save();
        ClientEmailVerificationEvent::dispatch($user);
        //event(new ClientEmailVerificationEvent($user));
        Session::put('signupOnBoardingID', $user->id);
        $DataBag['user'] = $user;
        Session::put('signupOnBoarding.initSignup', $user->id);
        $rtnResponse = OnexHelper::constructResponse($DataBag);
        return response()->json($rtnResponse, 200);
    }

    public function emailVerification(Request $request, $token)
    {
        $user = User::where('email_verify_token', $token)->first();
        if (!empty($user)) {
            $expiredDateTime = new Carbon($user->email_verify_token_expire_at);
            if (!$expiredDateTime->isPast()) {
                $user->email_verify_token = NULL;
                $user->email_verify_token_expire_at = NULL;
                $user->email_verified_at = Carbon::now()->format('Y-m-d H:i:s');
                $user->save();
                Session::put('signupOnBoarding.initSignup', $user->id);
                Session::put('signupOnBoarding.emailVerified', $user->id);
                session()->flash('msg', 'Hi, ' . $user->first_name .', <br/>Your email has been verified successfully, thankyou.');
                session()->flash('msg_class', 'alert alert-success');
                session()->flash('msg_title', 'Success!');
                $redirectLink = $this->signupStepProgressChecking($user);
                if (!empty($redirectLink)) {
                    return Redirect::to($redirectLink['actionLink']);
                }
                return redirect()->route('client.auth.signup');
            } else {
                if ($this->resendAccountEmailVerification($user, 'expired')) {
                    Session::put('resentEmail', 'success');
                    return redirect()->route('client.auth.signup.resendEmailSuccess')
                        ->with('resentUserEmail', $user->email_id)
                        ->with('resentUserFname', $user->first_name);
                }
            }
        }
        return redirect()->route('client.auth.signup');
    }

    public function resendAccountEmailVerification($userObj, $resendType = 'resend')
    {
        $userObj->email_verify_token = OnexHelper::generateToken($userObj->hash_id);
        $userObj->email_verify_token_expire_at = Carbon::now()->addSeconds(86400);
        $userObj->save();
        ClientEmailVerificationEvent::dispatch($userObj, $resendType);
        return true;
    }

    public function resendEmailVerification(Request $request)
    {
        $rtnResponse = [];
        $DataBag = [];

        $user = OnexHelper::userByEmail($request->email_id);
        if (!empty($user)) {
            $this->resendAccountEmailVerification($user);
            $DataBag['user'] = $user;
        }
        $rtnResponse = OnexHelper::constructResponse($DataBag);
        return response()->json($rtnResponse, 200);
    }

    public function resendEmailVerificationSuccess(Request $request)
    {
        $DataBag = [];
        if (Session::has('resentEmail') && Session::get('resentEmail') == 'success') {
            $DataBag['ufname'] = Session::get('resentUserFname') ?? '';
            $DataBag['uemail'] = Session::get('resentUserEmail') ?? '';
            Session::forget('resentEmail');
            return view('clients/auth/resend_email_success', $DataBag);
        }
        return redirect()->route('client.auth.signup');
    }

    public function signupStepProgressChecking($userObj)
    {
        Session::put('signupOnBoarding.initSignup', $userObj->id);
        $rtnAction = [];
        if ($userObj->email_verified_at == null || $userObj->email_verified_at == '') {
            $rtnAction['actionKey'] = 'askForResendVerificationMail';
            $rtnAction['actionLink'] = route('client.auth.signup.resendEmail');
            Session::put('signupOnBoarding.initSignup', $userObj->id);
        } else {
            Session::put('signupOnBoarding.emailVerified', $userObj->id);
            if ($userObj->username == null || $userObj->username == '' || $userObj->username == md5($userObj->hash_id)) {
                $rtnAction['actionKey'] = 'usernameRequired';
                $rtnAction['actionLink'] = route('client.auth.signup.stepTwo');
                Session::put('signupOnBoarding.emailVerified', $userObj->id);
                return $rtnAction;
            } elseif ($userObj->password == null || $userObj->password == '' || $userObj->password == md5($userObj->hash_id)) {
                $rtnAction['actionKey'] = 'passwordRequired';
                $rtnAction['actionLink'] = route('client.auth.signup.stepThree');
                Session::put('signupOnBoarding.usernameSet', $userObj->id);
                return $rtnAction;
            } elseif (empty($userObj->businessAccount)) {
                $rtnAction['actionKey'] = 'organizationRequired';
                $rtnAction['actionLink'] = route('client.auth.signup.stepFour');
                Session::put('signupOnBoarding.passwordSet', $userObj->id);
                return $rtnAction;
            } else {
                $rtnAction['actionKey'] = 'goSignIn';
                $rtnAction['actionLink'] = route('client.auth.signin');
                Session::forget('signupOnBoarding');
                return $rtnAction;
            }
        }
        return $rtnAction;
    }

    public function signupStepTwo(Request $request)
    {
        $DataBag = [];
        if (Session::has('signupOnBoarding') && !empty(Session::get('signupOnBoarding')) && Session::get('signupOnBoarding')['emailVerified'] != '' && Session::get('signupOnBoarding')['emailVerified'] != null) {
            $uid = Session::get('signupOnBoarding')['emailVerified'];
            $user = User::find($uid);
            $DataBag['user'] = $user;
            return view('clients/auth/signup_step_two', $DataBag);
        }
        session()->flash('msg', 'Please first complete your initial signup and then proceed, thankyou.');
        session()->flash('msg_class', 'alert alert-danger');
        session()->flash('msg_title', 'Unauthorized access');
        return redirect()->route('client.auth.signup');
    }

    public function signupStepTwoSave(Request $request)
    {
        $rtnResponse = [];
        $DataBag = [];

        $username = $request->input('username');
        $isUserExist = OnexHelper::userByUsername($username);
        if (!empty($isUserExist)) {
            $DataBag['isExistUserName'] = true;
            $rtnResponse = OnexHelper::constructResponse($DataBag, '', '', 201);
            return response()->json($rtnResponse, 200);
        }

        $mobileNumber = $request->input('mobile_number');
        $isUserExist = OnexHelper::userByMobile($mobileNumber);
        if (!empty($isUserExist)) {
            $DataBag['isExistMobile'] = true;
            $rtnResponse = OnexHelper::constructResponse($DataBag, '', '', 201);
            return response()->json($rtnResponse, 200);
        }
        
        $rules = [
            'username' => ['bail', 'required', 'min:6', 'max:25', 'regex:/^[a-zA-Z0-9]+$/'],
            'mobile_number' => ['bail', 'required', 'digits_between:10,12']
        ];
        $messages = [
            'username.required' => 'Please enter an username.',
            'username.min' => 'Username required atleast 6 characters.',
            'username.max' => 'Username should less than 25 characters.',
            'username.regex' => 'Username should be alpha-numeric characters',
            'mobile_number.required' => 'Please enter mobile number.',
            'mobile_number.digits_between' => 'Please enter valid 10-12 digits mobile number.'
        ];
        $requestValidation = OnexHelper::checkInputValidation($request->all(), $rules, $messages);
        if (!empty($requestValidation)) {
            $DataBag['validationErrors'] = $requestValidation;
            $rtnResponse = OnexHelper::constructResponse($DataBag, '', 'error', 422, 'error');
            return response()->json($rtnResponse, 422);
        }

        $user = User::find(Session::get('signupOnBoarding')['emailVerified']);
        $user->username = $username;
        $user->mobile_number = $mobileNumber;
        $user->save();
        $DataBag['user'] = $user;
        Session::put('signupOnBoarding.usernameSet', $user->id);
        $rtnResponse = OnexHelper::constructResponse($DataBag);
        return response()->json($rtnResponse, 200);
    }

    public function signupStepThree(Request $request)
    {
        $DataBag = [];
        if (Session::has('signupOnBoarding') && !empty(Session::get('signupOnBoarding')) && Session::get('signupOnBoarding')['usernameSet'] != '' && Session::get('signupOnBoarding')['usernameSet'] != null) {
            $uid = Session::get('signupOnBoarding')['usernameSet'];
            $user = User::find($uid);
            $DataBag['user'] = $user;
            return view('clients/auth/signup_step_three', $DataBag);
        }
        session()->flash('msg', 'Please first complete your initial signup and then proceed, thankyou.');
        session()->flash('msg_class', 'alert alert-danger');
        session()->flash('msg_title', 'Unauthorized access');
        return redirect()->route('client.auth.signup');
    }

    public function signupStepThreeSave(Request $request)
    {
        $rtnResponse = [];
        $DataBag = [];

        $rules = [
            'password' => ['bail', 'required', 'min:8', 'max:20'],
            'confirm_password' => ['bail', 'required', 'same:password'],
        ];
        $messages = [
            'password.required' => 'Please enter password.',
            'password.min' => 'Password required atleast 8 characters.',
            'password.max' => 'Password should less than 20 characters.',
            'confirm_password.required' => 'Please enter confirm password.',
            'confirm_password.same' => 'Confirm password not match with password.',
        ];
        $requestValidation = OnexHelper::checkInputValidation($request->all(), $rules, $messages);
        if (!empty($requestValidation)) {
            $DataBag['validationErrors'] = $requestValidation;
            $rtnResponse = OnexHelper::constructResponse($DataBag, '', 'error', 422, 'error');
            return response()->json($rtnResponse, 422);
        }

        $user = User::find(Session::get('signupOnBoarding')['usernameSet']);
        $user->password = Hash::make($request->input('password'));
        $user->save();
        $DataBag['user'] = $user;
        Session::put('signupOnBoarding.passwordSet', $user->id);
        $rtnResponse = OnexHelper::constructResponse($DataBag);
        return response()->json($rtnResponse, 200);
    }

    public function signupStepFour(Request $request)
    {
        $DataBag = [];
        if (Session::has('signupOnBoarding') && !empty(Session::get('signupOnBoarding')) && Session::get('signupOnBoarding')['passwordSet'] != '' && Session::get('signupOnBoarding')['passwordSet'] != null) {
            $uid = Session::get('signupOnBoarding')['passwordSet'];
            $user = User::find($uid);
            $DataBag['user'] = $user;
            $DataBag['orgInfo'] = $user->businessAccount;
            return view('clients/auth/signup_step_four', $DataBag);
        }
        session()->flash('msg', 'Please first complete your initial signup and then proceed, thankyou.');
        session()->flash('msg_class', 'alert alert-danger');
        session()->flash('msg_title', 'Unauthorized access');
        return redirect()->route('client.auth.signup');
    }

    public function signupStepFourSave(Request $request)
    {
        $rtnResponse = [];
        $DataBag = [];

        $rules = [
            'organization_name' => ['bail', 'required', 'min:3', 'max:60', 'regex:/^[A-Za-z 0-9.]+$/'],
            'business_name' => ['bail', 'min:3', 'max:30', 'regex:/^[A-Za-z 0-9.@&]+$/']
        ];
        $messages = [
            'organization_name.required' => 'Please enter your organization name.',
            'organization_name.min' => 'Organization name required atleast 3 characters.',
            'organization_name.max' => 'Organization name should less than 60 characters.',
            'organization_name.regex' => 'Please enter valid organization name.',
            'business_name.min' => 'Business name required atleast 3 characters.',
            'business_name.max' => 'Business name should less than 60 characters.',
            'business_name.regex' => 'Please enter valid business name.'
        ];
        $requestValidation = OnexHelper::checkInputValidation($request->all(), $rules, $messages);
        if (!empty($requestValidation)) {
            $DataBag['validationErrors'] = $requestValidation;
            $rtnResponse = OnexHelper::constructResponse($DataBag, '', 'error', 422, 'error');
            return response()->json($rtnResponse, 422);
        }

        $user = User::find(Session::get('signupOnBoarding')['passwordSet']);
        if (!empty($user)) {
            $businessAccount = new BusinessAccount;
            $businessAccount->user_id = Session::get('signupOnBoarding')['passwordSet'];
            $businessAccount->account_id = OnexHelper::generateAccountID();
            $businessAccount->organization_name = $request->input('organization_name');
            $businessAccount->business_name = $request->input('business_name');
            $businessAccount->save();
            $user->signup_completed_at = Carbon::now();
            $user->status = 1;
            $user->save();
            ClientWelcomeMailEvent::dispatch($user);
            Session::forget('signupOnBoarding');
            $DataBag['user'] = $user;
            $DataBag['orgInfo'] = $businessAccount;
            $rtnResponse = OnexHelper::constructResponse($DataBag);
            return response()->json($rtnResponse, 200);
        }
        $rtnResponse = OnexHelper::constructResponse($DataBag, '', 'error', 401, 'error');
        return response()->json($rtnResponse, 401);
    }

    public function login(Request $request)
    {
        Session::forget('signupOnBoarding');
        $DataBag = [];
        return view('clients/auth/signin', $DataBag);
    }

    public function loginProcess(Request $request)
    {
        $rtnResponse = [];
        $DataBag = [];
        $statusCode = 401;

        $rules = [
            'loginid' => ['required'],
            'password' => ['required']
        ];
        $messages = [
            'loginid.required' => 'Please enter login id.',
            'password.required' => 'Please enter password.'
        ];
        $requestValidation = OnexHelper::checkInputValidation($request->all(), $rules, $messages);
        if (!empty($requestValidation)) {
            $DataBag['validationErrors'] = $requestValidation;
            $rtnResponse = OnexHelper::constructResponse($DataBag, '', 'error', 422, 'error');
            return response()->json($rtnResponse, 422);
        }

        $signinId = $request->input('loginid');
        $password = $request->input('password');
        $isRemember = $request->input('remember_me');

        $user = User::where('email_id', $signinId)
            ->orWhere('username', $signinId)
            ->orWhere('mobile_number', $signinId)
            ->first();

        if (!empty($user)) {
            if ($user->signup_completed_at != null && $user->signup_completed_at != '') {
                if ($user->status == 1) {
                    $signinResponse = $this->signin($signinId, $password);
                    if ($signinResponse != '') {
                        if ($signinResponse == 'unverifiedMobile') {
                            $statusCode = 202;
                            $rtnResponse = OnexHelper::constructResponse($DataBag, '', '', $statusCode);
                        } else {
                            $DataBag['user'] = $user;
                            $statusCode = 200;
                            $rtnResponse = OnexHelper::constructResponse($DataBag);
                            $this->setRememberMe($signinId, $password, $isRemember);
                        }
                    } else {
                        $rtnResponse = OnexHelper::constructResponse($DataBag, '', 'error', $statusCode, 'error');
                    }
                } else {
                    $statusCode = 203;
                    $rtnResponse = OnexHelper::constructResponse($DataBag, '', '', $statusCode);
                }
            } else {
                $statusCode = 201;
                $DataBag['nextAction'] = $this->signupStepProgressChecking($user);
                $rtnResponse = OnexHelper::constructResponse($DataBag, '', '', $statusCode);
            }
        } else {
            $rtnResponse = OnexHelper::constructResponse($DataBag, '', 'error', $statusCode, 'error');
        }
        return response()->json($rtnResponse, $statusCode);
    }

    public function signin($loginId, $loginPwd)
    {
        $signInSuccessBy = '';
        if (Auth::attempt(['email_id' => $loginId, 'password' => $loginPwd])) {
            $signInSuccessBy = 'email';
            return $signInSuccessBy;
        } else if (Auth::attempt(['username' => $loginId, 'password' => $loginPwd])) {
            $signInSuccessBy = 'username';
            return $signInSuccessBy;
        } else {
            $userMobile = User::where('mobile_number', $loginId)->first();
            if (!empty($userMobile) && Hash::check($loginPwd, $userMobile->password)) {
                if ($userMobile->mobile_verified_at != null && $userMobile->mobile_verified_at != '') {
                    Auth::login($userMobile);
                    $signInSuccessBy = 'mobile';
                    return $signInSuccessBy;
                } else {
                    $signInSuccessBy = 'unverifiedMobile';
                    return $signInSuccessBy;
                }
            }
        }
        return $signInSuccessBy;
    }

    public function setRememberMe($loginId, $loginPwd, $isRemember)
    {
        if ($isRemember) {
            setcookie("onexClientLogID", $loginId, time() + (86400 * 30));
            setcookie("onexClientLogPwd", $loginPwd, time() + (86400 * 30));
        } else {
            unset($_COOKIE['onexClientLogID']);
            unset($_COOKIE['onexClientLogPwd']);
            setcookie("onexClientLogID", '', time() - 3600);
            setcookie("onexClientLogPwd", '', time() - 3600);
        }
    }

    public function forgotPassword(Request $request)
    {
        $DataBag = [];
        return view('clients/auth/forgot_password', $DataBag);
    }

    public function forgotPasswordProcess(Request $request)
    {
        $rtnResponse = [];
        $DataBag = [];

        $rules = [
            'email_id' => ['bail', 'required', 'email'],
        ];
        $messages = [
            'email_id.required' => 'Please enter email address.',
            'email_id.email' => 'Please enter an valid email address'
        ];
        $requestValidation = OnexHelper::checkInputValidation($request->all(), $rules, $messages);
        if (!empty($requestValidation)) {
            $DataBag['validationErrors'] = $requestValidation;
            $rtnResponse = OnexHelper::constructResponse($DataBag, '', 'error', 422, 'error');
            return response()->json($rtnResponse, 422);
        }

        $email = $request->input('email_id');
        $user = OnexHelper::userByEmail($email);

        if (!empty($user)) {
            if ($user->signup_completed_at != null && $user->signup_completed_at != '') {
                OnexHelper::deleteResetPassword($email, 'client');
                $resetPassword = new ResetPassword;
                $resetPassword->user_type = 'client';
                $resetPassword->email_id = $email;
                $resetPassword->token = OnexHelper::generateToken(time());
                $resetPassword->token_expire_at = Carbon::now()->addSeconds(86400);
                $resetPassword->save();
                ClientForgotPasswordMailEvent::dispatch($resetPassword);
                $DataBag['reset_password'] = $resetPassword;
                $rtnResponse = OnexHelper::constructResponse($DataBag);
                return response()->json($rtnResponse, 200);
            } else {
                $rtnResponse = OnexHelper::constructResponse($DataBag, '', '', 201);
                return response()->json($rtnResponse, 201);
            }
        }
        $rtnResponse = OnexHelper::constructResponse($DataBag, '', '', 401);
        return response()->json($rtnResponse, 401);
    }

    public function resetPassword(Request $request, $token)
    {
        $DataBag = [];
        $resetPassword = ResetPassword::where('token', $token)->first();
        if (!empty($resetPassword)) {
            $expiredDateTime = new Carbon($resetPassword->token_expire_at);
            if (!$expiredDateTime->isPast()) {
                $DataBag['reset_token'] = $token;
                return view('clients/auth/reset_password', $DataBag);
            } else {
                session()->flash('msg', 'Reset password link expired, Please proceed with new link, thankyou.');
                session()->flash('msg_class', 'alert alert-danger');
                session()->flash('msg_title', 'Link Expired!');
                return redirect()->route('client.auth.forgot.password');
            }
        }
        return redirect()->route('client.auth.signin');
    }

    public function resetPasswordSave(Request $request, $token)
    {
        $rtnResponse = [];
        $DataBag = [];

        $rules = [
            'password' => ['bail', 'required', 'min:8', 'max:20'],
            'confirm_password' => ['bail', 'required', 'same:password'],
        ];
        $messages = [
            'password.required' => 'Please enter password.',
            'password.min' => 'Password required atleast 8 characters.',
            'password.max' => 'Password should less than 20 characters.',
            'confirm_password.required' => 'Please enter confirm password.',
            'confirm_password.same' => 'Confirm password not match with password.',
        ];
        $requestValidation = OnexHelper::checkInputValidation($request->all(), $rules, $messages);
        if (!empty($requestValidation)) {
            $DataBag['validationErrors'] = $requestValidation;
            $rtnResponse = OnexHelper::constructResponse($DataBag, '', 'error', 422, 'error');
            return response()->json($rtnResponse, 422);
        }

        $resetPassword = ResetPassword::where('token', $token)->first();
        if (!empty($resetPassword)) {
            $user = User::where('email_id', $resetPassword->email_id)->first();
            if (!empty($user)) {
                $user->password = Hash::make($request->password);
                $user->save();
                OnexHelper::deleteResetPassword($user->email_id, 'client');
                $DataBag['user'] = $user;
                $rtnResponse = OnexHelper::constructResponse($DataBag);
                return response()->json($rtnResponse);
            }
        }
        $rtnResponse = OnexHelper::constructResponse($DataBag, '', 'error', 404, 'error');
        return response()->json($rtnResponse, 404);
    }
}
