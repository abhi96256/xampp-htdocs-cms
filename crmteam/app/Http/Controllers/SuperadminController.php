<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class SuperadminController extends Controller
{
    public function login() {
        if(session('superadmin_logged_in')){
            return redirect()->route('superadmin.dashboard');
        }
        return view('superadmin.login');
    }

    public function postLogin(Request $request) {
        // Simple hardcoded superadmin login for separate URL
        if($request->username == 'superadmin' && $request->password == 'superadmin@123') {
            session(['superadmin_logged_in' => true]);
            return redirect()->route('superadmin.dashboard');
        }
        return back()->with('error', 'Invalid Credentials');
    }

    public function dashboard() {
        if(!session('superadmin_logged_in')){
            return redirect()->route('superadmin.login');
        }
        $users = clone User::all();
        return view('superadmin.dashboard', compact('users'));
    }

    public function logout() {
        session()->forget('superadmin_logged_in');
        return redirect()->route('superadmin.login');
    }
}
