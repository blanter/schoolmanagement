<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use App\Models\Point;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class LaporanController extends Controller
{
    public function storeOrUpdate(Request $request)
    {
        $request->validate([
            'link' => 'required|string|max:255',
        ]);

        $userId = auth()->id();
        
        // Date
        $tanggal = $request->input('tanggal', now()->toDateString()); 
        $selectedDate = Carbon::parse($tanggal);

        // Cari laporan bulan ini untuk user ini
        $laporan = Laporan::where('user_id', $userId)
            ->where('month', $selectedDate->month)
            ->where('year', $selectedDate->year)
            ->first();

        if ($laporan) {
            // UPDATE
            $laporan->update([
                'link' => $request->link,
            ]);
            return back()->with('success', 'Laporan bulan ini berhasil diperbarui.');
        } else {
            // CREATE
            $laporan = Laporan::create([
                'user_id' => $userId,
                'link' => $request->link,
                'month' => $selectedDate->month,
                'year' => $selectedDate->year,
                'point' => 0,
            ]);
            Point::create([
                'user_id' => $userId,
                'check_id' => $laporan->id,
                'amount' => 50,
                'tipe' => 'guru',
                'source' => 'month',
                'tanggal' => $selectedDate,
            ]);
            return back()->with('success', 'Laporan baru berhasil dibuat.');
        }
    }
    
    // ADMIN INDEX LAPORAN
    public function laporanall(Request $request)
    {
        $query = Laporan::with('user');
        
        // Filter berdasarkan bulan
        if ($request->filled('month')) {
            $query->where('month', $request->month);
        }
        
        // Filter berdasarkan tahun
        if ($request->filled('year')) {
            $query->where('year', $request->year);
        }
        
        // Filter berdasarkan user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        
        // Pencarian berdasarkan nama user
        if ($request->filled('search')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('name', 'LIKE', '%' . $request->search . '%');
            });
        }
        
        $laporans = $query->orderBy('year', 'desc')
                         ->orderBy('month', 'desc')
                         ->paginate(10);
        
        // Data untuk dropdown filter
        $users = User::where('role','guru')->orderBy('name')->get();
        $years = Laporan::distinct()->orderBy('year', 'desc')->pluck('year');
        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];
        
        return view('page.laporan', compact('laporans', 'users', 'years', 'months'));
    }
    
    // ADMIN HALAMAN NILAI LAPORAN
    public function nilailapor(Request $request,$id)
    {
        $laporans = Laporan::findOrFail($id);
        $users = User::findOrFail($laporans->user_id);
        return view('page.laporan-nilai', compact('laporans', 'users'));
    }
    
    // ADMIN SIMPAN NILAI LAPORAN
    public function storenilai(Request $request,$id)
    {
        if(Auth::user()->role == "admin"){
            $request->validate([
                'point' => 'required|integer|min:1|max:100',
                'comment' => '',
            ]);
            
            $laporan = Laporan::findOrFail($id);
            
            // UPDATE
            $laporan->update([
                'point' => $request->point,
                'comment' => $request->comment,
            ]);
            
            // ADD SCORE POINT
            //$customDate = Carbon::now();
            //Point::create([
            //    'user_id' => $laporan->user_id,
            //    'check_id' => $laporan->id,
            //    'amount' => 50,
            //    'tipe' => 'guru',
            //    'source' => 'month',
            //    'tanggal' => $customDate,
            //]);
            
            return back()->with('success', 'Nilai Berhasil Disimpan.');
        } else {
            return back();
        }
    }
    
    // HAPUS LAPORAN
    public function hapuslaporan(Request $request, $id)
    {
        if(Auth::user()->role == "admin"){
            $destroy = Laporan::findOrFail($id);
            $destroy->delete();
            return back()->with('success', 'Laporan berhasil dihapus!');
        } else {
            return back();
        }
    }
}
