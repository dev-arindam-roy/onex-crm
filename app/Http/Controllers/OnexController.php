<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;

class OnexController extends Controller
{
    public function __construct()
    {

    }
    
    public function index(Request $request)
    {
        $DataBag = [];
        return view('index');
    }

    public function locale(Request $request, $lang = 'en') 
    {
        Session::put('locale', $lang);
        return redirect()->back();
    }
}
