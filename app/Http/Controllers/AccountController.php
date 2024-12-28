<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Channel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{

    public function index()
    {
        return view('account', [
            ]);
    }
    public function login(Request $request){
        if (Auth::attempt(['email'=>$request->email,'password'=>$request->password])) {
            if(Auth::user()->active == 0){
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return redirect()->back()->withErrors(['Auth'=>'Account is disabled!']);
            }
            return redirect()->intended('account');
        }
        return redirect()->back()->withErrors(['Authentication failed. Check credentials!']);
    }
    public function logout(Request $request){
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->back();
    }
    public function changePassword(Request $request){
        if(Auth::check()){
        $request->validate([
            'password' => 'required|min:6|max:32',
        ]);
        $user = Auth::user();
        $user->password = bcrypt($request->password);
        $user->save();
        }
        return redirect()->back()->withSuccess(['Your password has been set']);
    }
    public function create(Request $request)
    {
        $request->merge(['ip'=> $request->ip()]);
        $request->validate([
            //'ip' => 'unique:users',
            'nickname' => 'required|min:3|max:16|alpha_num|unique:users,name',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|max:32',
        ], [
//            'email.required' => 'Email je povinný',
//            'email.email' => 'Email musí být validní',
//            'email.unique' => 'Email je již registrován',
//            'password.required' => 'Heslo je vyžadováno',
//            'password.min' => 'Heslo vyžaduje alespoň 6 znaků',
        ]);

        $user = User::create([
            'name'=>$request->nickname,
            'password'=>bcrypt($request->password),
            'email' => $request->email,
            'ip' => $request->ip
        ]);
        Auth::login($user);
        return redirect()->back();
    }
}
