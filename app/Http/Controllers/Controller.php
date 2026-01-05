<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\View;

abstract class Controller extends BaseController
{
    public function __construct()
    {
        // Ambil hanya 1 data Websetting (asumsi hanya 1 baris config)
        //$websetting = Websetting::first();
        // Share ke semua view
        //View::share('websetting', $websetting);
        // Kalau kamu mau simpan sebagai property di controller:
        //$this->websetting = $websetting;
    }
}