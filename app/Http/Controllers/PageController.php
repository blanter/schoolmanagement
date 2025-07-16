<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\DataPs;

class PageController extends Controller
{
    // RIWAYAT
    public function riwayat()
    {
        return view('page.riwayat');
    }    

    // SCAN QR CODE
    public function scanqr()
    {
        return view('page.scan');
    }    

    // SEARCH DATA FROM QR CODE
    public function searchdata(Request $request)
    {
        $request->validate([
            'zona_id' => 'required|numeric',
        ]);
        $dataZona = DataPs::where('zona_id', $request->zona_id)->first();
        if ($dataZona) {
            return redirect()->route('detailunit', ['zona_id' => $dataZona->zona_id]);
        } else {
            return redirect()->route('scanqr')->withErrors(['error' => 'QR Code tidak valid atau data zona tidak ditemukan.']);
        }
    }

    // HALAMAN DETAIL UNIT
    public function detailunit($zona_id)
    {
        return view('page.detailunit');
    }   
}
