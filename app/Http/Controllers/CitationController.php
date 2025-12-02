<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Violation;
use App\Models\Citation;
use App\Models\ViolationCategory;
use Illuminate\Http\Request;

class CitationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::latest()->get();
        $violations = Violation::all();
        $categories = ViolationCategory::all();
        return view('citations.index', compact('users', 'violations', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'citations' => 'required|array',
            'citations.*.user_id' => 'required|exists:users,id',
            'citations.*.violation_id' => 'required|exists:violations,id',
            'citations.*.offense' => 'required|string',
        ]);

        foreach ($request->citations as $citationData) {
            Citation::create([
                'user_id' => $citationData['user_id'],
                'violation_id' => $citationData['violation_id'],
                'offense' => $citationData['offense'],
            ]);
        }

        return response()->json(['success' => 'Citations saved successfully.']);
    }

    /**
     * Display the specified resource.
     */
    public function showUserCitations(User $user)
    {
        $citations = $user->citations()->with('violation')->get();
        return response()->json($citations);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Citation $citation)
    {
        $citation->delete();
        return response()->json(['success' => 'Citation deleted successfully.']);
    }
}
