<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    /**
     * Display a listing of categories
     */
    public function index(Request $request)
    {
        $companyId = Auth::user()->company_id;

        $query = Category::where('company_id', $companyId);

        if ($request->has('search')) {
            $search = $request->search;
            $query->where('name', 'like', '%' . $search . '%');
        }

        $categories = $query->get();

        return response()->json([
            'success' => true,
            'data' => $categories,
        ], 200);
    }

    /**
     * Store a newly created category
     */
    public function store(Request $request)
    {
        $companyId = Auth::user()->company_id;

        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories', 'name')->where(fn($q) => $q->where('company_id', $companyId)),
            ],
        ]);

        $category = Category::create([
            'name' => $request->name,
            'company_id' => $companyId,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Category created successfully',
            'data' => $category,
        ], 201);
    }

    /**
     * Display the specified category
     */
    public function show($id)
    {
        $companyId = Auth::user()->company_id;

        $category = Category::where('company_id', $companyId)->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $category,
        ], 200);
    }

    /**
     * Update the specified category
     */
    public function update(Request $request, $id)
    {
        $companyId = Auth::user()->company_id;

        $category = Category::where('company_id', $companyId)->findOrFail($id);

        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories', 'name')
                    ->where(fn($q) => $q->where('company_id', $companyId))
                    ->ignore($category->id),
            ],
        ]);

        $category->update([
            'name' => $request->name,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Category updated successfully',
            'data' => $category,
        ], 200);
    }

    /**
     * Remove the specified category
     */
    public function destroy($id)
    {
        $companyId = Auth::user()->company_id;

        $category = Category::where('company_id', $companyId)->findOrFail($id);

        // Check if category has products
        if ($category->products()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete category that has products',
            ], 422);
        }

        $category->delete();

        return response()->json([
            'success' => true,
            'message' => 'Category deleted successfully',
        ], 200);
    }
}
