<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use App\Models\Point;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\TeacherMonthlyEvaluation;
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
        $now = now();
        $p = now()->subMonth();
        $defaultMonth = $p->month;
        $defaultYear = $p->year;

        $month = (int) $request->input('month', $defaultMonth);
        $year = (int) $request->input('year', $defaultYear);
        $userId = $request->input('user_id');

        $userDb = config('database.connections.users_db.database');

        $query = TeacherMonthlyEvaluation::query()
            ->where('teacher_monthly_evaluations.month', $month)
            ->where('teacher_monthly_evaluations.year', $year)
            ->join($userDb . '.users', 'teacher_monthly_evaluations.user_id', '=', $userDb . '.users.id')
            ->leftJoin('laporans', function ($join) use ($month, $year) {
                $join->on('teacher_monthly_evaluations.user_id', '=', 'laporans.user_id')
                    ->where('laporans.month', '=', $month)
                    ->where('laporans.year', '=', $year);
            })
            ->select(
                'teacher_monthly_evaluations.user_id',
                'teacher_monthly_evaluations.month',
                'teacher_monthly_evaluations.year',
                $userDb . '.users.name as user_name',
                'laporans.point',
                'laporans.comment',
                'laporans.id as laporan_id'
            );

        if ($userId) {
            $query->where('teacher_monthly_evaluations.user_id', $userId);
        }



        if ($request->filled('search')) {
            $query->where($userDb . '.users.name', 'LIKE', '%' . $request->search . '%');
        }

        $laporans = $query->orderBy($userDb . '.users.name')
            ->paginate(15);

        // Data untuk dropdown filter
        $users = User::where('role', 'guru')->orderBy('name')->get();
        $years = DB::table('teacher_monthly_evaluations')->distinct()->orderBy('year', 'desc')->pluck('year');
        if ($years->isEmpty())
            $years = [now()->year];

        $months = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        ];

        // Navigation Data
        $currentDate = \Carbon\Carbon::create($year, $month, 1);
        $prevMonthObj = $currentDate->copy()->subMonth();
        $nextMonthObj = $currentDate->copy()->addMonth();

        return view('page.laporan', compact(
            'laporans',
            'users',
            'years',
            'months',
            'month',
            'year',
            'prevMonthObj',
            'nextMonthObj'
        ));
    }

    // ADMIN HALAMAN NILAI LAPORAN
    public function nilailapor(Request $request, $user_id)
    {
        $month = $request->query('month');
        $year = $request->query('year');

        $users = User::findOrFail($user_id);
        $evaluation = TeacherMonthlyEvaluation::where('user_id', $user_id)
            ->where('month', $month)
            ->where('year', $year)
            ->firstOrFail();

        $laporans = Laporan::where('user_id', $user_id)
            ->where('month', $month)
            ->where('year', $year)
            ->first();

        return view('page.laporan-nilai', compact('laporans', 'users', 'evaluation', 'month', 'year'));
    }

    // ADMIN SIMPAN NILAI LAPORAN
    public function storenilai(Request $request, $user_id)
    {
        if (Auth::user()->role == "admin") {
            $request->validate([
                'point' => 'required|integer|min:1|max:100',
                'comment' => 'nullable|string',
                'month' => 'required|integer',
                'year' => 'required|integer',
            ]);

            $laporan = Laporan::updateOrCreate(
                [
                    'user_id' => $user_id,
                    'month' => $request->month,
                    'year' => $request->year,
                ],
                [
                    'point' => $request->point,
                    'comment' => $request->comment,
                    'link' => '-' // or some default
                ]
            );

            return redirect()->route('laporanall', ['month' => $request->month, 'year' => $request->year])
                ->with('success', 'Nilai Berhasil Disimpan.');
        } else {
            return back();
        }
    }

    // HAPUS LAPORAN
    public function hapuslaporan(Request $request, $id)
    {
        if (Auth::user()->role == "admin") {
            $destroy = Laporan::findOrFail($id);
            $destroy->delete();
            return back()->with('success', 'Laporan berhasil dihapus!');
        } else {
            return back();
        }
    }
}
