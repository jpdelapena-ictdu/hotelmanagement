<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Session;

class AdminLoginController extends Controller
{
    public function __construct() {
    	$guard = backpack_guard_name();

        return $this->middleware("guest:$guard", ['except' => ['adminLogout']]);
    }

    public function redirect() {
        return redirect()->route('admin.login');
    }

    public function showLoginForm() {
        if(Auth::guard()->check()){
            return redirect()->route('admin.dashboard'); 
        }
        else {
            return view('auth.login');
        }
    }

    public function login(Request $request) {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);

        if (Auth::guard()->attempt(['email' => $request->email, 'password' => $request->password], $request->remember)) {
            // show a success message
            \Alert::success('Successfully logged in')->flash();

            return redirect()->intended(route('admin.dashboard'));
        } else {
            Session::flash('userNotFound', 'Invalid email/password');
            return redirect()->back()->withInput($request->only('email', 'remember'));
        }
    }

    public function adminLogout() { 
        Auth::guard()->logout();

        return redirect()->route('admin.login');
    }
}