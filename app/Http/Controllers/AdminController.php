<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\User;

class AdminController extends Controller
{
    // Security
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (Auth::user()->role == 'murid') {
                abort(403, 'Unauthorized');
            }
            return $next($request);
        });
    }
}
