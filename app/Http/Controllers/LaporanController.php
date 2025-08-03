<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function storeOrUpdate(Request $request)
    {
        $request->validate([
            'link' => 'required|string|max:255',
        ]);

        $userId = auth()->id();
        $month = Carbon::now()->month;
        $year = Carbon::now()->year;

        // Cari laporan bulan ini untuk user ini
        $laporan = Laporan::where('user_id', $userId)
            ->where('month', $month)
            ->where('year', $year)
            ->first();

        if ($laporan) {
            // UPDATE
            $laporan->update([
                'link' => $request->link,
            ]);
            return back()->with('success', 'Laporan bulan ini berhasil diperbarui.');
        } else {
            // CREATE
            Laporan::create([
                'user_id' => $userId,
                'link' => $request->link,
                'month' => $month,
                'year' => $year,
            ]);
            return back()->with('success', 'Laporan baru berhasil dibuat.');
        }
    }
}
