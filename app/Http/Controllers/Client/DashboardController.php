<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\BusinessAccount;
use Carbon\Carbon;
use OnexHelper;
use Session;
use Redirect;
use Hash;
use Auth;

class DashboardController extends Controller
{
    
    public function __construct()
    {

    }
    
    public function index(Request $request)
    {
        
    }

    public function dashboard(Request $request)
    {
        $DataBag = [];
        return view('clients/dashboard', $DataBag);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        Session::flush();
        $DataBag = [];
        $rtnResponse = OnexHelper::constructResponse($DataBag);
        return response()->json($rtnResponse);
    }
}
