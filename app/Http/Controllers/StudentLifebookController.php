<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\StudentLifebook;
use Illuminate\Support\Facades\Auth;

class StudentLifebookController extends Controller
{
    public function index($id)
    {
        $userguru = User::findOrFail($id);
        $user = Auth::user();

        // Get months that have data for indicators
        $monthsWithData = StudentLifebook::where('user_id', $userguru->id)
            ->get(['year', 'month'])
            ->map(function ($item) {
                return $item->year . '-' . str_pad($item->month, 2, '0', STR_PAD_LEFT);
            })
            ->unique()
            ->toArray();

        return view('page.student-lifebook', compact('userguru', 'user', 'monthsWithData'));
    }

    public function getData(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|integer',
            'year' => 'required|integer',
            'month' => 'required|integer',
        ]);

        $data = StudentLifebook::where('user_id', $validated['user_id'])
            ->where('year', $validated['year'])
            ->where('month', $validated['month'])
            ->first();

        return response()->json([
            'data' => $data ?: [
                'goals_monthly' => '',
                'life_aspects' => '',
                'vision_yearly' => '',
                'vision_progress' => '',
                'gratitude' => ''
            ]
        ]);
    }

    public function saveData(Request $request)
    {
        try {
            $validated = $request->validate([
                'user_id' => 'required|integer',
                'year' => 'required|integer',
                'month' => 'required|integer',
                'goals_monthly' => 'nullable|string',
                'life_aspects' => 'nullable|string',
                'vision_yearly' => 'nullable|string',
                'vision_progress' => 'nullable|string',
                'gratitude' => 'nullable|string',
            ]);

            if (Auth::id() != $validated['user_id']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki hak akses untuk mengubah data ini.'
                ], 403);
            }

            StudentLifebook::updateOrCreate(
                [
                    'user_id' => $validated['user_id'],
                    'year' => $validated['year'],
                    'month' => $validated['month']
                ],
                [
                    'goals_monthly' => $validated['goals_monthly'],
                    'life_aspects' => $validated['life_aspects'],
                    'vision_yearly' => $validated['vision_yearly'],
                    'vision_progress' => $validated['vision_progress'],
                    'gratitude' => $validated['gratitude'],
                ]
            );

            return response()->json([
                'success' => true
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
