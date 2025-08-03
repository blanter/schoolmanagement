<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\User;

class DashboardController extends Controller
{
    // INDEX DASHBOARD (LEADERBOARD)
    public function index()
    {
        $userguru = User::where('role','guru')->get();
        return view('dashboard',compact('userguru'));
    }    
}
