<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\TeacherDailyDetail;
use Illuminate\Support\Facades\Auth;

class TeacherDailyDetailController extends Controller
{
    public function index($id)
    {
        $userguru = User::findOrFail($id);
        $user = Auth::user();

        // Get months that have details for indicators
        $monthsWithDetails = TeacherDailyDetail::where('user_id', $userguru->id)
            ->whereNotNull('note')
            ->where('note', '!=', '')
            ->get(['year', 'month'])
            ->map(function ($item) {
                return $item->year . '-' . str_pad($item->month, 2, '0', STR_PAD_LEFT);
            })
            ->toArray();

        return view('page.teacher-daily-detail', compact('userguru', 'user', 'monthsWithDetails'));
    }

    public function getNote(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|integer',
            'year' => 'required|integer',
            'month' => 'required|integer',
        ]);

        $detail = TeacherDailyDetail::where('user_id', $validated['user_id'])
            ->where('year', $validated['year'])
            ->where('month', $validated['month'])
            ->first();

        return response()->json([
            'note' => $detail ? $detail->note : ''
        ]);
    }

    public function saveNote(Request $request)
    {
        try {
            $validated = $request->validate([
                'user_id' => 'required|integer',
                'year' => 'required|integer',
                'month' => 'required|integer',
                'note' => 'nullable|string',
            ]);

            // Security: Only owner can save
            if (Auth::id() != $validated['user_id']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki hak akses untuk mengubah data ini.'
                ], 403);
            }

            TeacherDailyDetail::updateOrCreate(
                [
                    'user_id' => $validated['user_id'],
                    'year' => $validated['year'],
                    'month' => $validated['month']
                ],
                ['note' => $validated['note']]
            );

            $monthsWithDetails = TeacherDailyDetail::where('user_id', $validated['user_id'])
                ->whereNotNull('note')
                ->where('note', '!=', '')
                ->get(['year', 'month'])
                ->map(function ($item) {
                    return $item->year . '-' . str_pad($item->month, 2, '0', STR_PAD_LEFT);
                })
                ->toArray();

            return response()->json([
                'success' => true,
                'monthsWithDetails' => $monthsWithDetails
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
