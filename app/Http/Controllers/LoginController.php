<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class LoginController extends Controller
{
    public function index(){
        return view('auth.index');
    }

    public function registration()
    {
        return view('auth.registration');
    }

    public function login(LoginRequest $request): \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse|\Illuminate\Contracts\Foundation\Application
    {
        $credentials = $request->only('email','password');
        if (Auth::attempt($credentials)){
            session()->put('admin',true);
            writeLog('Logged in at');
            return redirect(route('team.searchTeam'));
        }
        Session::flash('message', config('messages.INCORRECT_CREDENTIALS'));
        return redirect(route('login-page'));
    }

    public function customRegistration(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        $data = $request->all();
        $check = $this->create($data);

        $credentials = $request->only('email','password');
        if(Auth::attempt($credentials))
        {
            writeLog('Logged in at');
            session()->put('admin', true);
            return redirect(route('team.searchTeam'));
        }
        $request->flash();
        Session::flash('message', config('messages.REGISTER_FAILED'));
        return redirect(route('register-user'));
    }

    public function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password'])
        ]);
    }

    public function logOut() {
        Storage::deleteDirectory('public/temp');
        Session::flush();
        Auth::logout();
        writeLog('Logged out at');
        return Redirect('auth');
    }
}
