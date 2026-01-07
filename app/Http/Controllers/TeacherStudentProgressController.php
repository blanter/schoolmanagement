<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\TeacherStudentProgress;
use Illuminate\Support\Facades\Auth;

class TeacherStudentProgressController extends Controller
{
    public function index($id)
    {
        $userguru = User::findOrFail($id);
        $user = Auth::user();

        // Get months that have records for indicators
        $monthsWithProgress = TeacherStudentProgress::where('user_id', $userguru->id)
            ->get(['year', 'month'])
            ->map(function ($item) {
                return $item->year . '-' . str_pad($item->month, 2, '0', STR_PAD_LEFT);
            })
            ->unique()
            ->values()
            ->toArray();

        // Get all students for the dropdown search
        // Assuming role 'murid' exists in the database
        $students = User::where('role', 'murid')->where('lulus', 0)->orderBy('name', 'asc')->get(['id', 'name']);

        return view('page.teacher-student-progress', compact('userguru', 'user', 'monthsWithProgress', 'students'));
    }

    public function getRecords(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|integer',
            'year' => 'required|integer',
            'month' => 'required|integer',
        ]);

        $records = TeacherStudentProgress::where('user_id', $validated['user_id'])
            ->where('year', $validated['year'])
            ->where('month', $validated['month'])
            ->orderBy('created_at', 'desc')
            ->get();

        // We need to attach student names to each record
        foreach ($records as $record) {
            $studentIds = is_array($record->student_ids) ? $record->student_ids : json_decode($record->student_ids, true);
            $record->student_names = User::whereIn('id', $studentIds)->pluck('name')->toArray();
        }

        return response()->json([
            'records' => $records
        ]);
    }

    public function saveRecord(Request $request)
    {
        try {
            $validated = $request->validate([
                'id' => 'nullable|integer',
                'user_id' => 'required|integer',
                'year' => 'required|integer',
                'month' => 'required|integer',
                'student_ids' => 'required|array',
                'subject' => 'required|string',
                'score' => 'nullable|string',
                'description' => 'nullable|string',
            ]);

            // Security: Only owner can save
            if (Auth::id() != $validated['user_id']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki hak akses untuk mengubah data ini.'
                ], 403);
            }

            if (isset($validated['id'])) {
                $record = TeacherStudentProgress::findOrFail($validated['id']);
                $record->update([
                    'student_ids' => $validated['student_ids'],
                    'subject' => $validated['subject'],
                    'score' => $validated['score'],
                    'description' => $validated['description']
                ]);
            } else {
                TeacherStudentProgress::create($validated);
            }

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function deleteRecord(Request $request)
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

            $record = TeacherStudentProgress::where('id', $validated['id'])
                ->where('user_id', $validated['user_id'])
                ->firstOrFail();

            $record->delete();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
