<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon;
use App\Models\User;
use App\Models\Task;
use App\Models\TaskCheck;

class TaskController extends Controller
{
    // TASKS (GURU)
    public function tasks($id)
    {
        $userguru = User::findOrFail($id);
        $dailyTasks = Task::where('user_id', $id)->where('jenis', 'days')->get();
        $weeklyTasks = Task::where('user_id', $id)->where('jenis', 'week')->get();
        $monthlyTasks = Task::where('user_id', $id)->where('jenis', 'month')->get();
        // Ambil semua task check hari ini, minggu ini, bulan ini
        $taskChecksToday = TaskCheck::where('user_id', $id)
            ->whereDate('created_at', now()->toDateString())
            ->get()
            ->keyBy(fn ($item) => 'days|' . $item->tipe . '|' . $item->judul_task);
        $startOfWeek = now()->startOfWeek();
        $endOfWeek = now()->endOfWeek();
        $taskChecksThisWeek = TaskCheck::where('user_id', $id)
            ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->get()
            ->keyBy(fn ($item) => 'week|' . $item->tipe . '|' . $item->judul_task);
        $taskChecksThisMonth = TaskCheck::where('user_id', $id)
            ->whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->get()
            ->keyBy(fn ($item) => 'month|' . $item->tipe . '|' . $item->judul_task);
        return view('page.tasks', compact(
            'userguru',
            'dailyTasks',
            'weeklyTasks',
            'monthlyTasks',
            'taskChecksToday',
            'taskChecksThisWeek',
            'taskChecksThisMonth'
        ));
    }

    // TASK CHECK (GURU)
    public function toggleCheck(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|integer',
            'jenis' => 'required|string',
            'tipe' => 'required|string',
            'judul_task' => 'required|string',
        ]);
        $today = now();
        $tahun = $today->year;
        $bulan = $today->month;
        // Cek apakah data sudah ada
        $existing = TaskCheck::where([
            'user_id' => $validated['user_id'],
            'jenis' => $validated['jenis'],
            'tipe' => $validated['tipe'],
            'judul_task' => $validated['judul_task'],
            'tahun' => $tahun,
            'bulan' => $bulan,
        ])
        ->whereDate('created_at', $today->toDateString())
        ->first();
        if ($existing) {
            $existing->delete(); // Uncheck
            return response()->json(['status' => 'undone']);
        } else {
            TaskCheck::create([
                'user_id' => $validated['user_id'],
                'jenis' => $validated['jenis'],
                'tipe' => $validated['tipe'],
                'judul_task' => $validated['judul_task'],
                'tahun' => $tahun,
                'bulan' => $bulan,
            ]);
            return response()->json(['status' => 'done']);
        }
    }

    // EDIT USER TASKS (ADMIN)
    public function editUserTasks($id)
    {
        $userguru = User::findOrFail($id);
        $dailyTasks = Task::where('user_id', $id)->where('jenis', 'days')->get();
        $weeklyTasks = Task::where('user_id', $id)->where('jenis', 'week')->get();
        $monthlyTasks = Task::where('user_id', $id)->where('jenis', 'month')->get();
        return view('page.user-tasks', compact('userguru', 'dailyTasks', 'weeklyTasks', 'monthlyTasks'));
    }

    // SIMPAN TASK BARU (ADMIN)
    public function storeUserTasks(Request $request, $id)
    {
        Task::create([
            'user_id' => $id,
            'jenis' => $request->jenis,
            'tipe' => $request->tipe,
            'judul_task' => $request->judul_task,
        ]);
        return response()->json(['success' => true]);
    }

    // UPDATE TASK (ADMIN)
    public function updateUserTask(Request $request, Task $task)
    {
        $task->update([
            'judul_task' => $request->judul_task,
        ]);
        return response()->json(['success' => true]);
    }

    // HAPUS TASK (ADMIN)
    public function deleteUserTask(Task $task)
    {
        $task->delete();
        return response()->json(['success' => true]);
    }

    // STATISTIK
    public function statistik($year, $month, $user_id)
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
            ->whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
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
            $summary[$jenis]['total'] = match ($jenis) {
                'daily' => $workingDays->count() * $jenisTasks->count(),
                'weekly' => ceil($workingDays->count() / 5) * $jenisTasks->count(), // asumsi 1 minggu kerja = 5 hari
                'monthly' => 1 * $jenisTasks->count(),
            };
            $done = 0;
            foreach ($jenisTasks as $task) {
                $filtered = $checks->where('judul_task', $task->judul_task)->where('jenis', $task->jenis);
                $done += match ($jenis) {
                    'daily' => $filtered->count(),
                    'weekly' => $filtered->groupBy(fn($i) => Carbon::parse($i->created_at)->week)->count(),
                    'monthly' => $filtered->groupBy(fn($i) => Carbon::parse($i->created_at)->month)->count(),
                };
            }
            $summary[$jenis]['done'] = $done;
            $summary[$jenis]['missed'] = $summary[$jenis]['total'] - $done;
            $summary[$jenis]['score'] = $done; // poin
            $summary[$jenis]['percentage'] = $summary[$jenis]['total'] > 0
                ? round(($done / $summary[$jenis]['total']) * 100, 1)
                : 0;
        }
        return view('page.statistik', compact('summary', 'year', 'month', 'user_id', 'thisuser'));
    }

}
