<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;
use Carbon\CarbonPeriod;
use App\Models\User;
use App\Models\Task;
use App\Models\TaskCheck;
use App\Models\TaskSkip;

class DashboardController extends Controller
{
    // INDEX DASHBOARD (LEADERBOARD) -Updated: 4 Nov (Copilot)-
    public function index(Request $request)
    {
        $currentUserId = Auth::id();

        // ======================== TANGGAL MERAH MANUAL =================
        $holidays = [
            '2025-08-17', // Hari Kemerdekaan
            '2025-09-01', // Cuti bersama
            '2025-10-02', // Hari Batik
            '2025-12-25', // Natal
        ];

        /**
         * Hitung jumlah hari kerja efektif antara dua tanggal
         */
        $countWorkingDays = function (Carbon $start, Carbon $end) use ($holidays) {
            return collect(CarbonPeriod::create($start, $end))
                ->filter(fn($date) => $date->isWeekday() && !in_array($date->toDateString(), $holidays))
                ->count();
        };

        /**
         * Kalkulasi skor untuk 1 user berdasarkan tipe (guru/nonguru)
         */
        $calcScoreForUser = function (User $user, string $tipe) use ($countWorkingDays) {
            $result = [
                'days' => ['total_tasks' => 0, 'expected' => 0, 'checked' => 0, 'skipped' => 0, 'percent' => 0],
                'week' => ['total_tasks' => 0, 'expected' => 0, 'checked' => 0, 'skipped' => 0, 'percent' => 0],
                'month' => ['total_tasks' => 0, 'expected' => 0, 'checked' => 0, 'skipped' => 0, 'percent' => 0],
                'overall_percent' => 0,
            ];

            $taskTypes = ['days', 'week', 'month'];

            foreach ($taskTypes as $jenis) {
                $tasks = Task::where('user_id', $user->id)
                    ->where('tipe', $tipe)
                    ->where('jenis', $jenis)
                    ->get();

                $totalTasks = $tasks->count();
                $expected = 0;

                foreach ($tasks as $task) {
                    $start = Carbon::parse($task->created_at)->startOfDay();
                    $end = Carbon::today()->endOfDay();

                    if ($jenis === 'days') {
                        $expected += $countWorkingDays($start, $end);
                    } elseif ($jenis === 'week') {
                        $expected += max(1, $start->copy()->startOfWeek()->diffInWeeks($end->copy()->startOfWeek()) + 1);
                    } elseif ($jenis === 'month') {
                        $expected += max(1, $start->copy()->startOfMonth()->diffInMonths($end->copy()->startOfMonth()) + 1);
                    }
                }

                $checked = TaskCheck::where('user_id', $user->id)
                    ->where('tipe', $tipe)
                    ->where('jenis', $jenis)
                    ->count();

                $skipped = TaskSkip::where('user_id', $user->id)
                    ->where('tipe', $tipe)
                    ->where('jenis', $jenis)
                    ->count();

                $result[$jenis] = [
                    'total_tasks' => $totalTasks,
                    'expected' => $expected,
                    'checked' => $checked,
                    'skipped' => $skipped,
                    'percent' => ($expected > 0)
                        ? round((($checked + $skipped) / $expected) * 100, 2)
                        : null,
                ];
            }

            // Hitung rata-rata dari yang punya expected > 0
            $percents = collect($result)->pluck('percent')->filter()->all();
            $result['overall_percent'] = count($percents)
                ? round(array_sum($percents) / count($percents), 2)
                : 0;

            return $result;
        };

        /**
         * Proses ranking untuk role tertentu
         */
        $processRanking = function (string $role, string $tipe) use ($calcScoreForUser, $currentUserId) {
            $users = User::where('role', $role)
                ->where('lulus', 0)
                ->get()
                ->map(function ($user) use ($calcScoreForUser, $tipe) {
                    $score = $calcScoreForUser($user, $tipe);
                    $user->total_amount = $score['overall_percent'];
                    $user->task_stats = $score;
                    return $user;
                })
                ->sortByDesc('total_amount')
                ->values();

            $rankIndex = $users->search(fn($user) => $user->id === $currentUserId);
            $rankNumber = $rankIndex !== false ? $rankIndex + 1 : null;
            $betterThan = $rankNumber ? round((($users->count() - $rankNumber) / $users->count()) * 100) : 0;

            return compact('users', 'rankNumber', 'betterThan');
        };

        $guruData = $processRanking('guru', 'guru');
        $nonguruData = $processRanking('guru', 'nonguru'); // Tetap ambil dari role guru, tapi tipe 'nonguru'

        return view('dashboard', [
            'userguru' => $guruData['users'],
            'usernonguru' => $nonguruData['users'],
            'rankNumberGuru' => $guruData['rankNumber'],
            'betterThanGuru' => $guruData['betterThan'],
            'rankNumberNonguru' => $nonguruData['rankNumber'],
            'betterThanNonguru' => $nonguruData['betterThan'],
            'holidays' => $holidays,
        ]);
    }
}