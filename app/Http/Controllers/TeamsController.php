<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class TeamsController extends Controller
{
    public function searchTeam(){
        var_dump(Auth::check());
        die;
        if(Auth::check()){
            return view('teams/searchTeam');
        }
        return redirect('login')->with('success', 'You are not allow to access this page.');
    }
}
