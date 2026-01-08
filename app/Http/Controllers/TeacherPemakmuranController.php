<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\PemakmuranTeori;
use App\Models\PemakmuranCase;
use App\Models\PemakmuranProyek;
use App\Models\PemakmuranProblem;
use App\Models\PemakmuranCreative;
use Illuminate\Support\Facades\Auth;

class TeacherPemakmuranController extends Controller
{
    private function getModel($type)
    {
        switch ($type) {
            case 'teori':
                return new PemakmuranTeori();
            case 'case':
                return new PemakmuranCase();
            case 'proyek':
                return new PemakmuranProyek();
            case 'problem':
                return new PemakmuranProblem();
            case 'creative':
                return new PemakmuranCreative();
            default:
                abort(404);
        }
    }

    private function getTitle($type)
    {
        switch ($type) {
            case 'teori':
                return 'Teori Dipelajari';
            case 'case':
                return 'Theory by Case';
            case 'proyek':
                return 'Proyek';
            case 'problem':
                return 'Problem Solving';
            case 'creative':
                return 'Creativity & Critical Thinking';
            default:
                return 'Pemakmuran';
        }
    }

    public function index($id, $type)
    {
        $userguru = User::findOrFail($id);
        $user = Auth::user();
        $title = $this->getTitle($type);
        $model = $this->getModel($type);

        $monthsWithDetails = $model::where('user_id', $userguru->id)
            ->whereNotNull('content')
            ->where('content', '!=', '')
            ->get(['year', 'month'])
            ->map(function ($item) {
                return $item->year . '-' . str_pad($item->month, 2, '0', STR_PAD_LEFT);
            })
            ->toArray();

        return view('page.teacher-pemakmuran-detail', compact('userguru', 'user', 'monthsWithDetails', 'type', 'title'));
    }

    public function getContent(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|integer',
            'year' => 'required|integer',
            'month' => 'required|integer',
            'type' => 'required|string',
        ]);

        $model = $this->getModel($validated['type']);
        $detail = $model::where('user_id', $validated['user_id'])
            ->where('year', $validated['year'])
            ->where('month', $validated['month'])
            ->first();

        return response()->json([
            'content' => $detail ? $detail->content : ''
        ]);
    }

    public function saveContent(Request $request)
    {
        try {
            $validated = $request->validate([
                'user_id' => 'required|integer',
                'year' => 'required|integer',
                'month' => 'required|integer',
                'type' => 'required|string',
                'content' => 'nullable|string',
            ]);

            if (Auth::id() != $validated['user_id']) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak memiliki hak akses untuk mengubah data ini.'
                ], 403);
            }

            $model = $this->getModel($validated['type']);
            $model::updateOrCreate(
                [
                    'user_id' => $validated['user_id'],
                    'year' => $validated['year'],
                    'month' => $validated['month']
                ],
                ['content' => $validated['content']]
            );

            $monthsWithDetails = $model::where('user_id', $validated['user_id'])
                ->whereNotNull('content')
                ->where('content', '!=', '')
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
