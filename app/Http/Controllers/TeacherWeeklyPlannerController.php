<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\TeacherWeeklyPlan;
use Illuminate\Support\Facades\Auth;

class TeacherWeeklyPlannerController extends Controller
{
    public function index($id)
    {
        $userguru = User::findOrFail($id);
        $user = Auth::user();

        // Get dates that have plans for this user to show indicators
        $datesWithPlans = TeacherWeeklyPlan::where('user_id', $userguru->id)
            ->pluck('tanggal')
            ->unique()
            ->map(function ($date) {
                return is_string($date) ? $date : (is_object($date) ? $date->format('Y-m-d') : $date);
            })
            ->values()
            ->toArray();

        return view('page.teacher-weekly-planner', compact('userguru', 'user', 'datesWithPlans'));
    }

    public function getPlans(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|integer',
            'tanggal' => 'required|date',
        ]);

        $plans = TeacherWeeklyPlan::where('user_id', $validated['user_id'])
            ->where('tanggal', $validated['tanggal'])
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'plans' => $plans
        ]);
    }

    public function savePlan(Request $request)
    {
        try {
            $validated = $request->validate([
                'id' => 'nullable|integer',
                'user_id' => 'required|integer',
                'tanggal' => 'required|date',
                'subject' => 'required|string',
                'note' => 'nullable|string',
            ]);

            // Security: Only owner can save
            if (Auth::id() != $validated['user_id']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki hak akses untuk mengubah data ini.'
                ], 403);
            }

            if (isset($validated['id'])) {
                $plan = TeacherWeeklyPlan::findOrFail($validated['id']);
                $plan->update([
                    'subject' => $validated['subject'],
                    'note' => $validated['note']
                ]);
            } else {
                $plan = TeacherWeeklyPlan::create([
                    'user_id' => $validated['user_id'],
                    'tanggal' => $validated['tanggal'],
                    'subject' => $validated['subject'],
                    'note' => $validated['note']
                ]);
            }

            $datesWithPlans = TeacherWeeklyPlan::where('user_id', $validated['user_id'])
                ->pluck('tanggal')
                ->unique()
                ->map(function ($date) {
                    return is_string($date) ? $date : $date->format('Y-m-d');
                })
                ->toArray();

            return response()->json([
                'success' => true,
                'datesWithPlans' => $datesWithPlans
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function deletePlan(Request $request)
    {
        try {
            $validated = $request->validate([
                'id' => 'required|integer',
                'user_id' => 'required|integer',
            ]);

            // Security: Only owner can delete
            if (Auth::id() != $validated['user_id']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki hak akses untuk menghapus data ini.'
                ], 403);
            }

            $plan = TeacherWeeklyPlan::where('id', $validated['id'])
                ->where('user_id', $validated['user_id'])
                ->firstOrFail();

            $tanggal = $plan->tanggal;
            $plan->delete();

            $datesWithPlans = TeacherWeeklyPlan::where('user_id', $validated['user_id'])
                ->pluck('tanggal')
                ->unique()
                ->map(function ($date) {
                    return is_string($date) ? $date : $date->format('Y-m-d');
                })
                ->toArray();

            return response()->json([
                'success' => true,
                'datesWithPlans' => $datesWithPlans
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
