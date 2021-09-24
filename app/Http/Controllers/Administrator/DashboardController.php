<?php

namespace App\Http\Controllers\Administrator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
        return view('administrators/dashboard', $DataBag);
    }
}
