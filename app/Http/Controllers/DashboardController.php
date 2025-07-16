<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\User;

class DashboardController extends Controller
{
    // INDEX DASHBOARD
    public function index()
    {
        return view('dashboard');
    }    
}
