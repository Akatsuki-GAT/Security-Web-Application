<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function signin(Request $request) {
    $incomingFields = $request->validate([
        'signinusername' => 'required',
        'signpassword' => 'required'
    ]);

    if (Auth::attempt([
        'username' => $incomingFields['signinusername'],
        'password' => $incomingFields['signpassword']
    ])) {
        $request->session()->regenerate();
        //admin still not fully implemented. Just testing for protected route pages
        if (auth()->user()->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }

        return redirect()->route('index');
    }  

    return back()->withErrors([
        'signinusername' => 'Invalid credentials.',
    ])->withInput();
}



    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }

    public function register(Request $request) {
        $incomingFields = $request->validate([
            'username' => 'required|min:6|max:255|unique:users,username', //input validations
            'email' => ['required', 'email','max:255', Rule::unique('users', 'email')],
            'full_name'  => ['required', 'regex:/^[A-Za-z\s]+$/', 'max:255'],
            'contact'      => ['nullable', 'regex:/^\+?[0-9]+$/'],
            'password' => ['required','min:8' ,'max:255']
            
        ]);

        $incomingFields['password'] =bcrypt($incomingFields['password']); //this is the password hash
        $user = User::create($incomingFields);
        Auth::login($user); 
        
        return redirect()->route('index');
       /* return redirect('/');*/
    }
}

