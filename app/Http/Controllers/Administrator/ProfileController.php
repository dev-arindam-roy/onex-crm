<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\AdminHelperTrait as AdminHelper;
use App\Events\AdminEmailVerificationEvent;
use App\Models\Admin;
use Carbon\Carbon;
use OnexHelper;
use Session;
use Hash;
use Auth;

class ProfileController extends Controller
{
    public function __construct()
    {

    }
    
    public function index(Request $request)
    {

    }

    public function myProfile(Request $request)
    {
        $DataBag = [];
        return view('administrators/pages/myprofile', $DataBag);
    }

    public function myProfileUpdate(Request $request)
    {
        $rtnResponse = [];
        $DataBag = [];
        $mobile = $request->input('mobile_number');

        $rules = [
            'first_name' => ['bail', 'required', 'min:3', 'max:30', 'regex:/^[a-z A-Z]+$/'],
            'last_name' => ['bail', 'required', 'min:3', 'max:30', 'regex:/^[a-z A-Z]+$/']
        ];

        if ($mobile != '') {
            $rules['mobile_number'] = ['digits_between:10,12'];
        }
        
        $messages = [
            'first_name.required' => 'Please enter first name.',
            'first_name.min' => 'First name required atleast 3 characters.',
            'first_name.max' => 'First name should less than 30 characters.',
            'first_name.regex' => 'First name should be alphabetic characters',
            'last_name.required' => 'Please enter last name.',
            'last_name.min' => 'Last name required atleast 3 characters.',
            'last_name.max' => 'Last name should less than 30 characters.',
            'last_name.regex' => 'Last name should be alphabetic characters',
            'mobile_number.digits_between' => 'Please enter valid 10-12 digits mobile number.'
        ];
        $requestValidation = OnexHelper::checkInputValidation($request->all(), $rules, $messages);
        if (!empty($requestValidation)) {
            $DataBag['validationErrors'] = $requestValidation;
            $rtnResponse = OnexHelper::constructResponse($DataBag, '', 'error', 422, 'error');
            return response()->json($rtnResponse, 422);
        }

        if ($mobile != '') {
            $isMobileExist = AdminHelper::adminByMobile($mobile, Auth::guard('admin')->user()->id);
            if (!empty($isMobileExist)) {
                $rtnResponse = OnexHelper::constructResponse($DataBag, '', 'error', 201, 'error');
                return response()->json($rtnResponse, 201);
            }
        }

        $admin = Admin::find(Auth::guard('admin')->user()->id);
        if (empty($admin)) {
            $rtnResponse = OnexHelper::constructResponse($DataBag, '', 'error', 401, 'error');
            return response()->json($rtnResponse, 401);
        }
        $admin->first_name = $request->input('first_name');
        $admin->last_name = $request->input('last_name');
        $admin->mobile_number = $request->input('mobile_number');
        $admin->save();
        $DataBag['admin'] = $admin;
        $rtnResponse = OnexHelper::constructResponse($DataBag);
        return response()->json($rtnResponse);
    }

    public function changePassword(Request $request)
    {
        $DataBag = [];
        return view('administrators/pages/change_password', $DataBag);
    }

    public function changePasswordSave(Request $request)
    {
        $rtnResponse = [];
        $DataBag = [];

        $rules = [
            'current_password' => ['required'],
            'new_password' => ['bail', 'required', 'min:8', 'max:20'],
            'confirm_password' => ['bail', 'required', 'same:new_password']
        ];
        $messages = [
            'current_password.required' => 'Please enter current password.',
            'new_password.required' => 'Please enter new password.',
            'new_password.min' => 'New password required atleast 8 characters.',
            'new_password.max' => 'New password should less than 20 characters.',
            'confirm_password.required' => 'Please enter confirm password.',
            'confirm_password.same' => 'Confirm password not match with password.'
        ];
        $requestValidation = OnexHelper::checkInputValidation($request->all(), $rules, $messages);
        if (!empty($requestValidation)) {
            $DataBag['validationErrors'] = $requestValidation;
            $rtnResponse = OnexHelper::constructResponse($DataBag, '', 'error', 422, 'error');
            return response()->json($rtnResponse, 422);
        }

        $admin = Admin::find(Auth::guard('admin')->user()->id);
        if (empty($admin)) {
            $rtnResponse = OnexHelper::constructResponse($DataBag, '', 'error', 401, 'error');
            return response()->json($rtnResponse, 401);
        }
        if (!Hash::check($request->input('current_password'), $admin->password)) {
            $rtnResponse = OnexHelper::constructResponse($DataBag, '', 'error', 201, 'error');
            return response()->json($rtnResponse, 201);
        }
        $admin->password = Hash::make($request->input('new_password'));
        $admin->save();
        $rtnResponse = OnexHelper::constructResponse($DataBag);
        Auth::guard('admin')->logout();
        Session::flush();
        return response()->json($rtnResponse);
    }

    public function changeEmail(Request $request)
    {
        $DataBag = [];
        return view('administrators/pages/change_email', $DataBag);
    }

    public function changeEmailSave(Request $request)
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
        $isEmailExist = AdminHelper::adminByEmail($email);
        if (!empty($isEmailExist)) {
            $rtnResponse = OnexHelper::constructResponse($DataBag, '', 'error', 201, 'error');
            return response()->json($rtnResponse, 201);
        }

        $admin = Admin::find(Auth::guard('admin')->user()->id);
        if (empty($admin)) {
            $rtnResponse = OnexHelper::constructResponse($DataBag, '', 'error', 401, 'error');
            return response()->json($rtnResponse, 401);
        }
        $admin->email_verify_token = OnexHelper::generateToken(time());
        $admin->email_verify_token_expire_at = Carbon::now()->addSeconds(86400);
        $admin->email_id = $email; 
        $admin->email_verified_at = null; 
        $admin->save();
        AdminEmailVerificationEvent::dispatch($admin);
        $DataBag['admin'] = $admin;
        $rtnResponse = OnexHelper::constructResponse($DataBag);
        Auth::guard('admin')->logout();
        Session::flush();
        session()->flash('msg', 'Email verification link has been sent to your new email address. Please verify and signin with the new email.');
        session()->flash('msg_class', 'alert alert-success');
        session()->flash('msg_title', 'Please Verify New Email');
        return response()->json($rtnResponse);
    }
}
