<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{

    public function login()
    {
        if(Auth::user()){
            return redirect('/');
        }
        return view('welcome');
    }

    public function init()
    {
        return response()->json(['user' => Auth::user()], 200);
    }

    public function index()
    {
        $this->middleware('auth');
        return view('welcome');
    }
}
