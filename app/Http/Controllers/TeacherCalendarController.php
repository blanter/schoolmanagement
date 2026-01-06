<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\TeacherNote;
use Illuminate\Support\Facades\Auth;

class TeacherCalendarController extends Controller
{
    public function index($id)
    {
        $userguru = User::findOrFail($id);
        $user = Auth::user();

        // Get dates that have notes for this user to show indicators
        $datesWithNotes = TeacherNote::where('user_id', $userguru->id)
            ->whereNotNull('note')
            ->pluck('tanggal')
            ->toArray();

        // Get all notes for this user
        $allNotes = TeacherNote::where('user_id', $userguru->id)
            ->whereNotNull('note')
            ->where('note', '!=', '')
            ->orderBy('tanggal', 'desc')
            ->get()
            ->map(function ($note) {
                return [
                    'tanggal' => $note->tanggal,
                    'note' => $note->note,
                    'preview' => \Str::limit($note->note, 100)
                ];
            });

        return view('page.teacher-calendar', compact('userguru', 'user', 'datesWithNotes', 'allNotes'));
    }

    public function getAllNotes(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|integer',
        ]);

        $notes = TeacherNote::where('user_id', $validated['user_id'])
            ->whereNotNull('note')
            ->where('note', '!=', '')
            ->orderBy('tanggal', 'desc')
            ->get()
            ->map(function ($note) {
                return [
                    'tanggal' => is_string($note->tanggal) ? $note->tanggal : $note->tanggal->format('Y-m-d'),
                    'note' => $note->note,
                    'preview' => \Str::limit($note->note, 100)
                ];
            });

        return response()->json([
            'notes' => $notes
        ]);
    }

    public function getNote(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required',
            'tanggal' => 'required|date',
        ]);

        $note = TeacherNote::where('user_id', $validated['user_id'])
            ->where('tanggal', $validated['tanggal'])
            ->first();

        return response()->json([
            'note' => $note ? $note->note : ''
        ]);
    }

    public function saveNote(Request $request)
    {
        try {
            $validated = $request->validate([
                'user_id' => 'required|integer',
                'tanggal' => 'required|date',
                'note' => 'nullable|string',
            ]);

            $note = TeacherNote::updateOrCreate(
                ['user_id' => $validated['user_id'], 'tanggal' => $validated['tanggal']],
                ['note' => $validated['note']]
            );

            // Also return all dates with notes for the current user to update indicators
            $datesWithNotes = TeacherNote::where('user_id', $validated['user_id'])
                ->whereNotNull('note')
                ->where('note', '!=', '')
                ->pluck('tanggal')
                ->map(function ($date) {
                    return is_string($date) ? $date : $date->format('Y-m-d');
                })
                ->toArray();

            return response()->json([
                'success' => true,
                'datesWithNotes' => $datesWithNotes
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
