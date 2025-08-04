<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Task;
use App\Models\TaskCheck;
use App\Models\TaskSkip;
use App\Models\Point;

class TaskController extends Controller
{
    // TASKS (ADMIN & GURU)
    public function tasks($id, Request $request)
    {
        // Ambil tanggal dari URL atau default hari ini
        $day   = $request->input('day', now()->day);
        $month = $request->input('month', now()->month);
        $year  = $request->input('year', now()->year);
        if (!is_numeric($day)) {
            return redirect('/tasks/'.$id);
        }
        // Carbon date sesuai input
        $selectedDate = Carbon::createFromDate($year, $month, $day);
        $userguru = User::findOrFail($id);
        $dailyTasks = Task::where('user_id', $id)
            ->where('jenis', 'days')
            ->get();
        $weeklyTasks = Task::where('user_id', $id)
            ->where('jenis', 'week')
            ->get();
        $monthlyTasks = Task::where('user_id', $id)
            ->where('jenis', 'month')
            ->get();
        // Ambil semua task check sesuai tanggal pilihan
        $taskChecksToday = TaskCheck::where('user_id', $id)
            ->whereDate('tanggal', $selectedDate->toDateString())
            ->get()
            ->keyBy(fn ($item) => 'days|' . $item->tipe . '|' . $item->judul_task);
        // Ambil task check minggu dari tanggal pilihan
        $startOfWeek = $selectedDate->copy()->startOfWeek();
        $endOfWeek   = $selectedDate->copy()->endOfWeek();
        $taskChecksThisWeek = TaskCheck::where('user_id', $id)
            ->whereBetween('tanggal', [$startOfWeek, $endOfWeek])
            ->get()
            ->keyBy(fn ($item) => 'week|' . $item->tipe . '|' . $item->judul_task);
        // Ambil task check bulan dari tanggal pilihan
        $taskChecksThisMonth = TaskCheck::where('user_id', $id)
            ->whereYear('tanggal', $selectedDate->year)
            ->whereMonth('tanggal', $selectedDate->month)
            ->get()
            ->keyBy(fn ($item) => 'month|' . $item->tipe . '|' . $item->judul_task);
        // ✅ Ambil task skip sesuai periode
        $taskSkipsToday = TaskSkip::where('user_id', $id)
            ->whereDate('tanggal', $selectedDate->toDateString())
            ->get()
            ->keyBy(fn ($item) => 'days|' . $item->tipe . '|' . $item->judul_task);
        $taskSkipsThisWeek = TaskSkip::where('user_id', $id)
            ->whereBetween('tanggal', [$startOfWeek, $endOfWeek])
            ->get()
            ->keyBy(fn ($item) => 'week|' . $item->tipe . '|' . $item->judul_task);
        $taskSkipsThisMonth = TaskSkip::where('user_id', $id)
            ->whereYear('tanggal', $selectedDate->year)
            ->whereMonth('tanggal', $selectedDate->month)
            ->get()
            ->keyBy(fn ($item) => 'month|' . $item->tipe . '|' . $item->judul_task);
        // If custom date
        $customDate = \Carbon\Carbon::createFromDate($year, $month, $day)->startOfDay();
        return view('page.tasks', compact(
            'userguru',
            'dailyTasks',
            'weeklyTasks',
            'monthlyTasks',
            'taskChecksToday',
            'taskChecksThisWeek',
            'taskChecksThisMonth',
            'taskSkipsToday',
            'taskSkipsThisWeek',
            'taskSkipsThisMonth',
            'selectedDate',
            'customDate'
        ));
    }

    // TASK CHECK (GURU CHECKLIST ADMIN(2), B.SITO(15), B.VINA(27))
    public function toggleCheck(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|integer',
            'jenis' => 'required|string',
            'tipe' => 'required|string',
            'judul_task' => 'required|string',
            'proyek' => 'required',
            'tanggal' => 'required',
        ]);
        // Buat date sesuai input
        $customDate = Carbon::parse($validated['tanggal']);
        $tahun = $customDate->year;
        $bulan = $customDate->month;
        // Cek apakah data sudah ada di tanggal tersebut
        $existing = TaskCheck::where([
            'user_id' => $validated['user_id'],
            'jenis' => $validated['jenis'],
            'tipe' => $validated['tipe'],
            'judul_task' => $validated['judul_task'],
            'tahun' => $tahun,
            'bulan' => $bulan,
            'proyek' => $validated['proyek'],
        ])
        ->whereDate('tanggal', $customDate->toDateString())
        ->first();
        if ($existing) {
            $expoints = Point::where('check_id', $existing->id)->first();
            if ($expoints) {
                $expoints->delete();
            }
            $existing->delete();
            return response()->json(['status' => 'undone']);
        } else {
            // Save task check dengan tanggal custom
            $taskcheck = TaskCheck::create([
                'user_id' => $validated['user_id'],
                'jenis' => $validated['jenis'],
                'tipe' => $validated['tipe'],
                'judul_task' => $validated['judul_task'],
                'tahun' => $tahun,
                'bulan' => $bulan,
                'proyek' => $validated['proyek'],
                'tanggal' => $customDate,
            ]);
            // New Point
            $newpoint = 0;
            if ($validated['jenis'] == "days") {
                $newpoint = 10;
            }
            if ($validated['jenis'] == "week") {
                $newpoint = 30;
            }
            if ($validated['jenis'] == "month") {
                $newpoint = 50;
            }
            Point::create([
                'user_id' => $validated['user_id'],
                'check_id' => $taskcheck->id,
                'amount' => $newpoint,
                'tipe' => $validated['tipe'],
                'source' => $validated['jenis'],
                'tanggal' => $customDate,
            ]);
            return response()->json(['status' => 'done']);
        }
    }

    // TASK SKIPPED (GURU CHECKLIST ADMIN(2), B.SITO(15), B.VINA(27))
    public function toggleSkip(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|integer',
            'jenis' => 'required|string',
            'tipe' => 'required|string',
            'judul_task' => 'required|string',
            'proyek' => 'required',
            'tanggal' => 'required',
        ]);
        // Buat date sesuai input
        $customDate = Carbon::parse($validated['tanggal']);
        $tahun = $customDate->year;
        $bulan = $customDate->month;
        // Cek apakah data sudah ada di tanggal tersebut
        $existing = TaskSkip::where([
            'user_id' => $validated['user_id'],
            'jenis' => $validated['jenis'],
            'tipe' => $validated['tipe'],
            'judul_task' => $validated['judul_task'],
            'proyek' => $validated['proyek'],
        ])
        ->whereDate('tanggal', $customDate->toDateString())
        ->first();
        if ($existing) {
            $existing->delete();
            return response()->json(['status' => 'undone']);
        } else {
            // Save task skipped
            $taskskip = TaskSkip::create([
                'user_id' => $validated['user_id'],
                'jenis' => $validated['jenis'],
                'tipe' => $validated['tipe'],
                'judul_task' => $validated['judul_task'],
                'proyek' => $validated['proyek'],
                'tanggal' => $customDate,
            ]);
            return response()->json(['status' => 'done']);
        }
    }

    // EDIT USER TASKS (ADMIN & GURU)
    public function editUserTasks($id)
    {
        $userguru = User::findOrFail($id);
        $dailyTasks = Task::where('user_id', $id)->where('jenis', 'days')->get();
        $weeklyTasks = Task::where('user_id', $id)->where('jenis', 'week')->get();
        $monthlyTasks = Task::where('user_id', $id)->where('jenis', 'month')->get();
        return view('page.user-tasks', compact('userguru', 'dailyTasks', 'weeklyTasks', 'monthlyTasks'));
    }

    // SIMPAN TASK BARU (ADMIN & GURU)
    public function storeUserTasks(Request $request, $id)
    {
        if(Auth::user()->role == "admin" || Auth::user()->id != $id){
            $proyek = "wajib";
        } else {
            $proyek = "pribadi";
        }
        Task::create([
            'user_id' => $id,
            'jenis' => $request->jenis,
            'tipe' => $request->tipe,
            'judul_task' => $request->judul_task,
            'proyek' => $proyek,
        ]);
        return response()->json(['success' => true]);
    }

    // UPDATE TASK (ADMIN & GURU)
    public function updateUserTask(Request $request, Task $task)
    {
        $task->update([
            'judul_task' => $request->judul_task,
        ]);
        return response()->json(['success' => true]);
    }

    // HAPUS TASK (ADMIN & GURU)
    public function deleteUserTask(Task $task)
    {
        $task->delete();
        return response()->json(['success' => true]);
    }

    // STATISTIK PAGE (ADMIN & GURU)
    public function statistik($user_id)
    {
        $thisuser = User::find($user_id);
        return view('page.statistik', compact('thisuser'));
    }

    // STATISTIK DATA (ADMIN & GURU)
    public function statistikdata($year, $month, $user_id)
    {
        $thisuser = User::find($user_id);
        $start = Carbon::create($year, $month, 1)->startOfMonth();
        $end = Carbon::create($year, $month, 1)->endOfMonth();
        // Filter weekday (Senin–Jumat) saja
        $workingDays = collect();
        for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
            if ($date->isWeekday()) {
                $workingDays->push($date->toDateString());
            }
        }
        // Ambil semua task milik user
        $tasks = Task::where('user_id', $user_id)->get();
        // Ambil semua checklist pada bulan tersebut
        $checks = TaskCheck::where('user_id', $user_id)
            ->whereYear('tanggal', $year)
            ->whereMonth('tanggal', $month)
            ->get();
        // ✅ Ambil semua skip pada bulan tersebut
        $skips = TaskSkip::where('user_id', $user_id)
            ->whereYear('tanggal', $year)
            ->whereMonth('tanggal', $month)
            ->get();
        $summary = [
            'daily' => ['total' => 0, 'done' => 0, 'missed' => 0],
            'weekly' => ['total' => 0, 'done' => 0, 'missed' => 0],
            'monthly' => ['total' => 0, 'done' => 0, 'missed' => 0],
        ];
        foreach (['daily', 'weekly', 'monthly'] as $jenis) {
            $jenisName = match ($jenis) {
                'daily' => 'days',
                'weekly' => 'week',
                'monthly' => 'month'
            };
            $jenisTasks = $tasks->where('jenis', $jenisName);
            // Hitung total sebelum skip
            $total = match ($jenis) {
                'daily' => $workingDays->count() * $jenisTasks->count(),
                'weekly' => ceil($workingDays->count() / 5) * $jenisTasks->count(),
                'monthly' => 1 * $jenisTasks->count(),
            };
            // ✅ Hitung skip untuk jenis ini
            $skipCount = 0;
            foreach ($jenisTasks as $task) {
                $filteredSkip = $skips->where('judul_task', $task->judul_task)
                    ->where('jenis', $task->jenis)
                    ->where('proyek', $task->proyek);
                $skipCount += match ($jenis) {
                    'daily' => $filteredSkip->count(),
                    'weekly' => $filteredSkip->groupBy(fn($i) => Carbon::parse($i->tanggal)->week)->count(),
                    'monthly' => $filteredSkip->groupBy(fn($i) => Carbon::parse($i->tanggal)->month)->count(),
                };
            }
            // Kurangi total dengan skip
            $totalAfterSkip = max($total - $skipCount, 0);
            // Hitung done
            $done = 0;
            foreach ($jenisTasks as $task) {
                $filteredCheck = $checks->where('judul_task', $task->judul_task)
                ->where('jenis', $task->jenis)
                ->where('proyek', $task->proyek);
                $done += match ($jenis) {
                    'daily' => $filteredCheck->count(),
                    'weekly' => $filteredCheck->groupBy(fn($i) => Carbon::parse($i->tanggal)->week)->count(),
                    'monthly' => $filteredCheck->groupBy(fn($i) => Carbon::parse($i->tanggal)->month)->count(),
                };
            }
            $summary[$jenis]['total'] = $totalAfterSkip;
            $summary[$jenis]['done'] = $done;
            $summary[$jenis]['missed'] = $totalAfterSkip - $done;
            $summary[$jenis]['score'] = $done; // poin
            $summary[$jenis]['percentage'] = $totalAfterSkip > 0
                ? round(($done / $totalAfterSkip) * 100, 1)
                : 0;
        }
        return view('page.statistikdata', compact('summary', 'year', 'month', 'user_id', 'thisuser'));
    }

}
