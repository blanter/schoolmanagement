<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\TeacherMonthlyEvaluation;
use App\Models\TeacherNonGuruEvaluation;
use Illuminate\Support\Facades\Auth;

class TeacherMonthlyEvaluationController extends Controller
{
    public function index($id)
    {
        $userguru = User::findOrFail($id);
        $user = Auth::user();

        return view('page.teacher-monthly-evaluation', compact('userguru', 'user'));
    }

    public function getData(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|integer',
            'year' => 'required|integer',
            'month' => 'required|integer',
        ]);

        $evaluation = TeacherMonthlyEvaluation::where('user_id', $validated['user_id'])
            ->where('year', $validated['year'])
            ->where('month', $validated['month'])
            ->first();

        $nonGuruEvaluations = TeacherNonGuruEvaluation::where('user_id', $validated['user_id'])
            ->where('year', $validated['year'])
            ->where('month', $validated['month'])
            ->get();

        return response()->json([
            'evaluation' => $evaluation,
            'nonGuruEvaluations' => $nonGuruEvaluations
        ]);
    }

    public function saveGuru(Request $request)
    {
        try {
            $validated = $request->validate([
                'user_id' => 'required|integer',
                'year' => 'required|integer',
                'month' => 'required|integer',
                'evaluasi' => 'nullable|string',
                'student_progress' => 'nullable|string',
                'review' => 'nullable|string',
                'berhasil' => 'nullable|string',
                'belum_berhasil' => 'nullable|string',
                'tauladan' => 'nullable|string',
            ]);

            if (Auth::id() != $validated['user_id']) {
                return response()->json(['success' => false, 'message' => 'Forbidden'], 403);
            }

            TeacherMonthlyEvaluation::updateOrCreate(
                [
                    'user_id' => $validated['user_id'],
                    'year' => $validated['year'],
                    'month' => $validated['month']
                ],
                $request->only(['evaluasi', 'student_progress', 'review', 'berhasil', 'belum_berhasil', 'tauladan'])
            );

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function saveNonGuru(Request $request)
    {
        try {
            $validated = $request->validate([
                'id' => 'nullable|integer',
                'user_id' => 'required|integer',
                'year' => 'required|integer',
                'month' => 'required|integer',
                'title' => 'required|string',
                'description' => 'nullable|string',
            ]);

            if (Auth::id() != $validated['user_id']) {
                return response()->json(['success' => false, 'message' => 'Forbidden'], 403);
            }

            if (isset($validated['id'])) {
                $item = TeacherNonGuruEvaluation::findOrFail($validated['id']);
                $item->update($request->only(['title', 'description']));
            } else {
                TeacherNonGuruEvaluation::create($validated);
            }

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function deleteNonGuru(Request $request)
    {
        try {
            $validated = $request->validate([
                'id' => 'required|integer',
                'user_id' => 'required|integer',
            ]);

            if (Auth::id() != $validated['user_id']) {
                return response()->json(['success' => false, 'message' => 'Forbidden'], 403);
            }

            $item = TeacherNonGuruEvaluation::where('id', $validated['id'])
                ->where('user_id', $validated['user_id'])
                ->firstOrFail();
            $item->delete();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
