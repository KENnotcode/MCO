<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Violation;
use App\Models\Citation;
use App\Models\ViolationCategory;
use Illuminate\Http\Request;

class CitationController extends Controller
{
    public function index()
    {
        $users = User::latest()->get();
        $violations = Violation::all();
        $categories = ViolationCategory::all();
        return view('citations.index', compact('users', 'violations', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'citations' => 'required|array',
            'citations.*.user_id' => 'required|exists:users,id',
            'citations.*.violation_id' => 'required|exists:violations,id',
            'citations.*.offense' => 'required|string',
            'citations.*.date_committed' => 'required|date',
        ]);

        foreach ($request->citations as $citationData) {
            $offenseLevel = 0;
            switch ($citationData['offense']) {
                case 'first_offense':
                    $offenseLevel = 1;
                    break;
                case 'second_offense':
                    $offenseLevel = 2;
                    break;
                case 'third_offense':
                    $offenseLevel = 3;
                    break;
                case 'fourth_offense':
                    $offenseLevel = 4;
                    break;
            }

            Citation::create([
                'user_id' => $citationData['user_id'],
                'violation_id' => $citationData['violation_id'],
                'offense' => $citationData['offense'],
                'offense_level' => $offenseLevel,
                'date_committed' => $citationData['date_committed'],
            ]);
        }

        return response()->json(['success' => 'Citations saved successfully.']);
    }

    public function showUserCitations(User $user)
    {
        $citations = $user->citations()->with('violation')->get();
        return response()->json($citations);
    }

    public function markAsPaid(Citation $citation)
    {
        $citation->update([
            'status' => 'paid',
            'paid_at' => now(),
        ]);

        return response()->json(['success' => 'Citation marked as paid.']);
    }

    public function destroy(Citation $citation)
    {
        $citation->delete();
        return response()->json(['success' => 'Citation deleted successfully.']);
    }
}
