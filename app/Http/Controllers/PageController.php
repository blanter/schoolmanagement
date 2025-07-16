<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class PageController extends Controller
{
    // TASKS
    public function tasks($id)
    {
        $userguru = User::find($id);
        return view('page.tasks',compact('userguru'));
    }    
}
