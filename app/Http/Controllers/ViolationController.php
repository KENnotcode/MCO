<?php

namespace App\Http\Controllers;

use App\Models\Violation;
use App\Models\ViolationCategory;
use Illuminate\Http\Request;

class ViolationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $categories = ViolationCategory::all();
        $selectedCategoryId = $request->input('category_id');

        $violations = Violation::when($selectedCategoryId, function ($query, $selectedCategoryId) {
            return $query->where('violation_category_id', $selectedCategoryId);
        })->latest()->paginate(10);

        return view('violations.index', compact('violations', 'categories', 'selectedCategoryId'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('violations.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'violation_category_id' => 'required|exists:violation_categories,id',
            'first_offense' => 'nullable|string',
            'second_offense' => 'nullable|string',
            'third_offense' => 'nullable|string',
            'fourth_offense' => 'nullable|string',
        ]);

        Violation::create($request->all());

        return redirect()->route('violations.index', ['category_id' => $request->violation_category_id])
            ->with('success', 'Violation created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Violation $violation)
    {
        return view('violations.show', compact('violation'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Violation $violation)
    {
        $categories = ViolationCategory::all();
        return view('violations.edit', compact('violation', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Violation $violation)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'violation_category_id' => 'required|exists:violation_categories,id',
            'first_offense' => 'nullable|string',
            'second_offense' => 'nullable|string',
            'third_offense' => 'nullable|string',
            'fourth_offense' => 'nullable|string',
        ]);

        $violation->update($request->all());

        return redirect()->route('violations.index', ['category_id' => $request->violation_category_id])
            ->with('success', 'Violation updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Violation $violation)
    {
        $violation->delete();

        return redirect()->route('violations.index')
            ->with('success', 'Violation deleted successfully.');
    }

    public function storeCategory(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255|unique:violation_categories']);
        ViolationCategory::create($request->all());
        return redirect()->route('violations.index')->with('success', 'Category created successfully.');
    }

    public function destroyCategory(ViolationCategory $category)
    {
        // Delete all associated violations
        $category->violations()->delete();

        // Delete the category
        $category->delete();

        return redirect()->route('violations.index')->with('success', 'Category and all associated violations have been deleted successfully.');
    }

    public function updateCategory(Request $request, ViolationCategory $category)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:violation_categories,name,' . $category->id,
        ]);

        $category->update($request->all());

        return redirect()->route('violations.index')->with('success', 'Category updated successfully.');
    }

    public function getViolationsByCategory(Request $request)
    {
        $violations = Violation::where('violation_category_id', $request->category_id)->get();
        return response()->json($violations);
    }

    public function getOffensesForViolation(Request $request)
    {
        $violation = Violation::find($request->violation_id);
        $offenses = [];
        if ($violation) {
            if ($violation->first_offense) $offenses[] = ['key' => 'first_offense', 'value' => '1st Offense - ' . $violation->first_offense];
            if ($violation->second_offense) $offenses[] = ['key' => 'second_offense', 'value' => '2nd Offense - ' . $violation->second_offense];
            if ($violation->third_offense) $offenses[] = ['key' => 'third_offense', 'value' => '3rd Offense - ' . $violation->third_offense];
            if ($violation->fourth_offense) $offenses[] = ['key' => 'fourth_offense', 'value' => '4th Offense - ' . $violation->fourth_offense];
        }
        return response()->json($offenses);
    }
}
