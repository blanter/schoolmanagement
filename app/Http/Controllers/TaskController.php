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
use App\Models\Laporan;
use App\Models\TeacherWeeklyPlan;
use App\Models\TeacherDailyDetail;
use App\Models\TeacherStudentProgress;
use App\Models\TeacherMonthlyEvaluation;
use App\Models\TeacherProcurement;
use App\Models\PemakmuranTeori;
use App\Models\PemakmuranCase;
use App\Models\PemakmuranProyek;
use App\Models\PemakmuranProblem;
use App\Models\PemakmuranCreative;
use App\Models\StudentLifebook;
use App\Models\TeacherResearchProject;
use App\Models\TeacherVideoProject;

class TaskController extends Controller
{
    // MY TASKS PAGE (GURU)
    public function myTasks($id)
    {
        $user = User::findOrFail($id);

        // Hitung persentase completion untuk user
        $calc = $user->getPlannerProgress();
        $completionPercentage = $calc['total'];
        $completionDetails = $calc['details'];

        // Kategori task dengan icon Phosphor dan warna
        $taskCategories = [
            [
                'id' => 1,
                'name' => 'Checklist Rutinitas',
                'description' => 'Daftar tugas harian yang diisi oleh guru terpilih.',
                'icon' => 'ph-bold ph-clipboard-text',
                'color' => '#FEB2D3', // Light Pink
                'route' => '/tasks/' . $user->id
            ],
            [
                'id' => 2,
                'name' => 'Teacher Planner & Reflection',
                'description' => 'Evaluasi harian, mingguan, dan bulanan.',
                'icon' => 'ph-bold ph-notebook',
                'color' => '#FFE7A0', // Light Yellow
                'route' => '/teacher-planner/' . $user->id
            ],
            [
                'id' => 3,
                'name' => 'Teacher Project',
                'description' => 'Jurnal penelitian, video, dan pengadaan barang.',
                'icon' => 'ph-bold ph-strategy',
                'color' => '#A0C4FF', // Light Blue
                'route' => '/teacher-project/' . $user->id
            ],
            [
                'id' => 4,
                'name' => 'Teacher Planner Pemakmuran',
                'description' => 'Perencanaan proyek pemakmuran berbasis laporan.',
                'icon' => 'ph-bold ph-leaf',
                'color' => '#B9FBC0', // Light Green
                'route' => '/teacher-planner-pemakmuran/' . $user->id
            ],
            [
                'id' => 5,
                'name' => 'Student Controlling My Lifebook',
                'description' => 'Monitoring progres karakter dan lifebook siswa.',
                'icon' => 'ph-bold ph-user-focus',
                'color' => '#FFD6A5', // Light Orange
                'route' => '/student-lifebook/' . $user->id
            ],
            [
                'id' => 6,
                'name' => 'Non Guru Task Note',
                'description' => 'Catatan tugas tambahan dan checklist personal.',
                'icon' => 'ph-bold ph-note-pencil',
                'color' => '#D4A5FF', // Light Purple
                'route' => '/non-guru-task-note/' . $user->id
            ],
        ];

        return view('page.my-tasks', compact('user', 'completionPercentage', 'completionDetails', 'taskCategories'));
    }

    // TASKS (ADMIN & GURU)
    public function tasks($id, Request $request)
    {
        // Ambil tanggal dari URL atau default hari ini
        $day = $request->input('day', now()->day);
        $month = $request->input('month', now()->month);
        $year = $request->input('year', now()->year);
        if (!is_numeric($day)) {
            return redirect('/tasks/' . $id);
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
            ->keyBy(fn($item) => 'days|' . $item->tipe . '|' . $item->judul_task);
        // Ambil task check minggu dari tanggal pilihan
        $startOfWeek = $selectedDate->copy()->startOfWeek();
        $endOfWeek = $selectedDate->copy()->endOfWeek();
        $taskChecksThisWeek = TaskCheck::where('user_id', $id)
            ->whereBetween('tanggal', [$startOfWeek, $endOfWeek])
            ->get()
            ->keyBy(fn($item) => 'week|' . $item->tipe . '|' . $item->judul_task);
        // Ambil task check bulan dari tanggal pilihan
        $taskChecksThisMonth = TaskCheck::where('user_id', $id)
            ->whereYear('tanggal', $selectedDate->year)
            ->whereMonth('tanggal', $selectedDate->month)
            ->get()
            ->keyBy(fn($item) => 'month|' . $item->tipe . '|' . $item->judul_task);
        // ✅ Ambil task skip sesuai periode
        $taskSkipsToday = TaskSkip::where('user_id', $id)
            ->whereDate('tanggal', $selectedDate->toDateString())
            ->get()
            ->keyBy(fn($item) => 'days|' . $item->tipe . '|' . $item->judul_task);
        $taskSkipsThisWeek = TaskSkip::where('user_id', $id)
            ->whereBetween('tanggal', [$startOfWeek, $endOfWeek])
            ->get()
            ->keyBy(fn($item) => 'week|' . $item->tipe . '|' . $item->judul_task);
        $taskSkipsThisMonth = TaskSkip::where('user_id', $id)
            ->whereYear('tanggal', $selectedDate->year)
            ->whereMonth('tanggal', $selectedDate->month)
            ->get()
            ->keyBy(fn($item) => 'month|' . $item->tipe . '|' . $item->judul_task);
        // If custom date
        $customDate = \Carbon\Carbon::createFromDate($year, $month, $day)->startOfDay();
        // Monthly Report
        $laporanBulanIni = Laporan::where('user_id', $userguru->id)
            ->where('month', $selectedDate->month)
            ->where('year', $selectedDate->year)
            ->first();
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
            'customDate',
            'laporanBulanIni'
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
        if (Auth::user()->role == "admin" || Auth::user()->id != $id) {
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

    // TEACHER PLANNER PAGE (GURU)
    public function teacherPlanner(Request $request, $id)
    {
        $userguru = User::findOrFail($id);
        $user = Auth::user();

        $p = now()->subMonth();
        $month = (int) $request->input('month', $p->month);
        $year = (int) $request->input('year', $p->year);

        // --- Calculation for Teacher Planner Progress ---
        // We calculate each module out of 100%, then average them (total / 4).

        // 1. Weekly Planner (Days filled in target month vs total weekdays in target month)
        $targetDate = \Carbon\Carbon::createFromDate($year, $month, 1);
        $startOfMonth = $targetDate->copy()->startOfMonth();
        $endOfMonth = $targetDate->copy()->endOfMonth();

        $totalWeekdays = 0;
        $filledWeekdays = 0;

        for ($date = $startOfMonth->copy(); $date->lte($endOfMonth); $date->addDay()) {
            if ($date->isWeekday()) {
                $totalWeekdays++;
                $hasPlan = TeacherWeeklyPlan::where('user_id', $userguru->id)
                    ->whereDate('tanggal', $date->toDateString())
                    ->exists();
                if ($hasPlan)
                    $filledWeekdays++;
            }
        }
        $weeklyProgress = $totalWeekdays > 0 ? ($filledWeekdays / $totalWeekdays) * 100 : 0;

        // 2. Daily Details (Target Month - Boolean 0 or 100)
        $hasDaily = TeacherDailyDetail::where('user_id', $userguru->id)
            ->where('year', $year)
            ->where('month', $month)
            ->whereNotNull('note')
            ->where('note', '!=', '')
            ->exists();
        $dailyProgress = $hasDaily ? 100 : 0;

        // 3. Student Progress (Target Month - Boolean 0 or 100)
        $hasStudentProgress = TeacherStudentProgress::where('user_id', $userguru->id)
            ->where('year', $year)
            ->where('month', $month)
            ->exists();
        $studentProgressValue = $hasStudentProgress ? 100 : 0;

        // 4. Monthly Evaluation (Target Month - Boolean 0 or 100)
        $hasEvaluation = TeacherMonthlyEvaluation::where('user_id', $userguru->id)
            ->where('year', $year)
            ->where('month', $month)
            ->exists();
        $evaluationProgress = $hasEvaluation ? 100 : 0;

        // Final Average
        $completionPercentage = round(($weeklyProgress + $dailyProgress + $studentProgressValue + $evaluationProgress) / 4);

        $params = "?month=$month&year=$year";
        $plannerItems = [
            [
                'name' => 'Calendar Note',
                'icon' => 'ph-bold ph-calendar',
                'color' => '#FEB2D3', // Light Pink
                'route' => '/teacher-calendar/' . $userguru->id . $params
            ],
            [
                'name' => 'Weekly Planner',
                'icon' => 'ph-bold ph-calendar-check',
                'color' => '#FFE7A0', // Light Yellow
                'route' => '/teacher-weekly-planner/' . $userguru->id . $params
            ],
            [
                'name' => 'Daily Details',
                'icon' => 'ph-bold ph-article',
                'color' => '#A0C4FF', // Light Blue
                'route' => '/teacher-daily-detail/' . $userguru->id . $params
            ],
            [
                'name' => 'Student Progress',
                'icon' => 'ph-bold ph-chart-line-up',
                'color' => '#B9FBC0', // Light Green
                'route' => '/teacher-student-progress/' . $userguru->id . $params
            ],
            [
                'name' => 'Monthly Evaluation',
                'icon' => 'ph-bold ph-clipboard-text',
                'color' => '#D4A5FF', // Light Purple
                'route' => '/teacher-monthly-evaluation/' . $userguru->id . $params
            ],
        ];

        return view('page.teacher-planner', compact('userguru', 'user', 'completionPercentage', 'plannerItems', 'month', 'year'));
    }

    // TEACHER PLANNER PEMAKMURAN PAGE (GURU)
    public function teacherPlannerPemakmuran(Request $request, $id)
    {
        $userguru = User::findOrFail($id);
        $user = Auth::user();

        $p = now()->subMonth();
        $month = (int) $request->input('month', $p->month);
        $year = (int) $request->input('year', $p->year);

        // --- Calculation for Pemakmuran Progress ---
        $models = [
            PemakmuranTeori::class,
            PemakmuranCase::class,
            PemakmuranProyek::class,
            PemakmuranProblem::class,
            PemakmuranCreative::class
        ];

        $filledCount = 0;
        foreach ($models as $model) {
            $exists = $model::where('user_id', $userguru->id)
                ->where('year', $year)
                ->where('month', $month)
                ->whereNotNull('content')
                ->where('content', '!=', '')
                ->exists();
            if ($exists)
                $filledCount++;
        }

        $completionPercentage = round(($filledCount / count($models)) * 100);

        $params = "?month=$month&year=$year";
        $plannerItems = [
            [
                'name' => 'Teori Dipelajari',
                'icon' => 'ph-bold ph-books',
                'color' => '#FEB2D3', // Light Pink
                'route' => '/teacher-pemakmuran-detail/' . $userguru->id . '/teori'
            ],
            [
                'name' => 'Theory by Case',
                'icon' => 'ph-bold ph-briefcase-metal',
                'color' => '#FFE7A0', // Light Yellow
                'route' => '/teacher-pemakmuran-detail/' . $userguru->id . '/case'
            ],
            [
                'name' => 'Proyek',
                'icon' => 'ph-bold ph-strategy',
                'color' => '#A0C4FF', // Light Blue
                'route' => '/teacher-pemakmuran-detail/' . $userguru->id . '/proyek'
            ],
            [
                'name' => 'Problem Solving',
                'icon' => 'ph-bold ph-puzzle-piece',
                'color' => '#B9FBC0', // Light Green
                'route' => '/teacher-pemakmuran-detail/' . $userguru->id . '/problem'
            ],
            [
                'name' => 'Creativity & Critical Thinking',
                'icon' => 'ph-bold ph-lightbulb',
                'color' => '#D4A5FF', // Light Purple
                'route' => '/teacher-pemakmuran-detail/' . $userguru->id . '/creative'
            ],
        ];

        return view('page.teacher-planner-pemakmuran', compact('userguru', 'user', 'completionPercentage', 'plannerItems', 'month', 'year'));
    }

    // TEACHER PROJECT PAGE (GURU)
    public function teacherProject(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $currentYear = now()->year;
        $currentMonth = now()->month;

        // Default semester based on current month
        $defaultSemester = ($currentMonth <= 6) ? 2 : 1;
        $defaultBaseYear = ($currentMonth <= 6) ? $currentYear - 1 : $currentYear;

        $semester = (int) $request->input('semester', $defaultSemester);
        $baseYear = (int) $request->input('year', $defaultBaseYear);

        if ($semester == 1) {
            $academicYearLabel = $baseYear . '/' . ($baseYear + 1);
        } else {
            $academicYearLabel = $baseYear . '/' . ($baseYear + 1);
        }

        $project = \App\Models\TeacherResearchProject::firstOrCreate(
            ['user_id' => $user->id, 'year' => $baseYear, 'semester' => $semester],
            []
        );

        $videoProjects = \App\Models\TeacherVideoProject::where('user_id', $user->id)
            ->where('year', $baseYear)
            ->where('semester', $semester)
            ->get();

        $procurements = \App\Models\TeacherProcurement::where('user_id', $user->id)
            ->where('year', $baseYear)
            ->where('semester', $semester)
            ->orderBy('tanggal', 'desc')
            ->get();

        return view('page.teacher-project', compact('user', 'project', 'videoProjects', 'procurements', 'baseYear', 'semester', 'academicYearLabel'));
    }

    public function saveResearchProject(Request $request)
    {
        try {
            $validated = $request->validate([
                'user_id' => 'required|integer',
                'field' => 'required|string',
                'value' => 'required'
            ]);

            // Map field names if they come from the UI differently, but I'll use DB names directly in JS

            $month = now()->month;
            $currentYear = now()->year;

            if ($month <= 6) {
                $semester = 2;
                $baseYear = $currentYear - 1;
            } else {
                $semester = 1;
                $baseYear = $currentYear;
            }

            if (Auth::id() != $validated['user_id']) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }

            $project = \App\Models\TeacherResearchProject::updateOrCreate(
                ['user_id' => $validated['user_id'], 'year' => $baseYear, 'semester' => $semester],
                [$validated['field'] => $validated['value']]
            );

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function saveVideoProject(Request $request)
    {
        try {
            $validated = $request->validate([
                'user_id' => 'required|integer',
                'name' => 'required|string',
                'link' => 'nullable|string'
            ]);

            $month = now()->month;
            $currentYear = now()->year;

            if ($month <= 6) {
                $semester = 2;
                $baseYear = $currentYear - 1;
            } else {
                $semester = 1;
                $baseYear = $currentYear;
            }

            if (Auth::id() != $validated['user_id']) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }

            $project = \App\Models\TeacherVideoProject::create([
                'user_id' => $validated['user_id'],
                'year' => $baseYear,
                'semester' => $semester,
                'name' => $validated['name'],
                'link' => $validated['link']
            ]);

            return response()->json(['success' => true, 'data' => $project]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function deleteVideoProject(Request $request)
    {
        try {
            $validated = $request->validate([
                'id' => 'required|integer',
                'user_id' => 'required|integer'
            ]);

            if (Auth::id() != $validated['user_id']) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }

            $project = \App\Models\TeacherVideoProject::findOrFail($validated['id']);
            $project->delete();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function saveProcurement(Request $request)
    {
        try {
            $validated = $request->validate([
                'user_id' => 'required|integer',
                'tanggal' => 'required|date',
                'tipe' => 'required|in:pemasukan,pengeluaran',
                'nominal' => 'required|numeric',
                'nama_barang' => 'required|string',
                'url' => 'nullable|string',
                'bukti_pembayaran' => 'nullable|file|mimes:jpeg,png,jpg,gif|max:5000'
            ]);

            $month = now()->month;
            $currentYear = now()->year;

            if ($month <= 6) {
                $semester = 2;
                $baseYear = $currentYear - 1;
            } else {
                $semester = 1;
                $baseYear = $currentYear;
            }

            if (Auth::id() != $validated['user_id']) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }

            $imagePath = null;
            if ($request->hasFile('bukti_pembayaran')) {
                $image = $request->file('bukti_pembayaran');
                $filename = time() . '.jpg'; // Convert to jpg for compression
                $directory = public_path('storage/procurements');
                $path = $directory . '/' . $filename;

                if (!file_exists($directory)) {
                    mkdir($directory, 0777, true);
                }

                // Compress using Intervention Image v3
                $manager = new \Intervention\Image\ImageManager(new \Intervention\Image\Drivers\Gd\Driver());
                $img = $manager->read($image->getRealPath());

                // Compress 50%
                $img->toJpeg(50)->save($path);

                $imagePath = 'storage/procurements/' . $filename;
            }

            $procurement = TeacherProcurement::create([
                'user_id' => $validated['user_id'],
                'year' => $baseYear,
                'semester' => $semester,
                'tanggal' => $validated['tanggal'],
                'tipe' => $validated['tipe'],
                'nominal' => $validated['nominal'],
                'nama_barang' => $validated['nama_barang'],
                'url' => $validated['url'],
                'bukti_pembayaran' => $imagePath
            ]);

            return response()->json(['success' => true, 'data' => $procurement]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function deleteProcurement(Request $request)
    {
        try {
            $validated = $request->validate([
                'id' => 'required|integer',
                'user_id' => 'required|integer'
            ]);

            if (Auth::id() != $validated['user_id']) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }

            $procurement = TeacherProcurement::findOrFail($validated['id']);

            if ($procurement->bukti_pembayaran && file_exists(public_path($procurement->bukti_pembayaran))) {
                unlink(public_path($procurement->bukti_pembayaran));
            }

            $procurement->delete();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
