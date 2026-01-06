<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\NonGuruNoteCategory;
use App\Models\NonGuruNoteItem;
use Illuminate\Support\Facades\Auth;

class NonGuruNoteController extends Controller
{
    public function index($id)
    {
        $userguru = User::findOrFail($id);
        return view('page.non-guru-task-note', compact('userguru'));
    }

    public function getData(Request $request)
    {
        $categories = NonGuruNoteCategory::where('user_id', $request->user_id)
            ->with('items')
            ->orderBy('created_at', 'desc')
            ->get();
        return response()->json($categories);
    }

    public function saveCategory(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'title' => 'required|string|max:255',
            'color' => 'required|string',
        ]);

        if (Auth::id() != $request->user_id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $category = NonGuruNoteCategory::updateOrCreate(
            ['id' => $request->id, 'user_id' => $request->user_id],
            ['title' => $request->title, 'color' => $request->color]
        );

        return response()->json(['success' => true, 'category' => $category]);
    }

    public function deleteCategory(Request $request)
    {
        $category = NonGuruNoteCategory::findOrFail($request->id);
        if (Auth::id() != $category->user_id) {
            return response()->json(['success' => false], 403);
        }
        $category->delete();
        return response()->json(['success' => true]);
    }

    public function saveItem(Request $request)
    {
        $request->validate([
            'category_id' => 'required',
            'content' => 'required|string',
        ]);

        $category = NonGuruNoteCategory::findOrFail($request->category_id);
        if (Auth::id() != $category->user_id) {
            return response()->json(['success' => false], 403);
        }

        $item = NonGuruNoteItem::create([
            'category_id' => $request->category_id,
            'content' => $request->input('content'),
            'is_checked' => false
        ]);

        return response()->json(['success' => true, 'item' => $item]);
    }

    public function checkItem(Request $request)
    {
        $item = NonGuruNoteItem::findOrFail($request->id);
        $category = NonGuruNoteCategory::findOrFail($item->category_id);
        if (Auth::id() != $category->user_id) {
            return response()->json(['success' => false], 403);
        }

        $item->update(['is_checked' => $request->input('is_checked')]);
        return response()->json(['success' => true]);
    }

    public function deleteItem(Request $request)
    {
        $item = NonGuruNoteItem::findOrFail($request->id);
        $category = NonGuruNoteCategory::findOrFail($item->category_id);
        if (Auth::id() != $category->user_id) {
            return response()->json(['success' => false], 403);
        }
        $item->delete();
        return response()->json(['success' => true]);
    }
}