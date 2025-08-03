<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Point;

class DashboardController extends Controller
{
    // INDEX DASHBOARD (LEADERBOARD)
    public function index()
    {
        $currentUserId = Auth::id();

        // ===================== GURU =====================
        $pointsGuru = Point::where('tipe', 'guru')
            ->select('user_id', DB::raw('SUM(amount) as total_amount'))
            ->groupBy('user_id')
            ->get()
            ->keyBy('user_id');

        $userguru = User::where('role', 'guru')
            ->get()
            ->map(function ($user) use ($pointsGuru) {
                $user->total_amount = $pointsGuru[$user->id]->total_amount ?? 0;
                return $user;
            })
            ->sortByDesc('total_amount')
            ->values();

        // Ranking guru
        $rankGuru = $userguru->search(fn($user) => $user->id === $currentUserId);
        $rankNumberGuru = $rankGuru !== false ? $rankGuru + 1 : null;
        $betterThanGuru = $rankNumberGuru ? round((($userguru->count() - $rankNumberGuru) / $userguru->count()) * 100) : 0;

        // ===================== NONGURU =====================
        $pointsNonguru = Point::where('tipe', 'nonguru')
            ->select('user_id', DB::raw('SUM(amount) as total_amount'))
            ->groupBy('user_id')
            ->get()
            ->keyBy('user_id');

        $usernonguru = User::where('role', 'guru') // <-- ini sepertinya salah di kode kamu, harusnya 'nonguru'
            ->get()
            ->map(function ($user) use ($pointsNonguru) {
                $user->total_amount = $pointsNonguru[$user->id]->total_amount ?? 0;
                return $user;
            })
            ->sortByDesc('total_amount')
            ->values();

        // Ranking nonguru
        $rankNonguru = $usernonguru->search(fn($user) => $user->id === $currentUserId);
        $rankNumberNonguru = $rankNonguru !== false ? $rankNonguru + 1 : null;
        $betterThanNonguru = $rankNumberNonguru ? round((($usernonguru->count() - $rankNumberNonguru) / $usernonguru->count()) * 100) : 0;

        return view('dashboard', compact(
            'userguru',
            'usernonguru',
            'rankNumberGuru',
            'betterThanGuru',
            'rankNumberNonguru',
            'betterThanNonguru'
        ));
    }
}
