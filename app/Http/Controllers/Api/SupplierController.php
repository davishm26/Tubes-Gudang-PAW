<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class SupplierController extends Controller
{
    /**
     * Display a listing of suppliers
     */
    public function index(Request $request)
    {
        $companyId = $request->input('company_id');
        $query = Supplier::query();
        if ($companyId) {
            $query->where('company_id', $companyId);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('contact', 'like', '%' . $search . '%')
                  ->orWhere('address', 'like', '%' . $search . '%');
            });
        }

        $suppliers = $query->get();

        return response()->json([
            'success' => true,
            'data' => $suppliers,
        ], 200);
    }

    /**
     * Store a newly created supplier
     */
    public function store(Request $request)
    {
        $companyId = $request->input('company_id');

        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('suppliers', 'name')->where(fn($q) => $q->where('company_id', $companyId)),
            ],
            'contact' => 'nullable|string|max:255',
            'address' => 'nullable|string',
        ]);

        $supplier = Supplier::create([
            'name' => $request->name,
            'contact' => $request->contact,
            'address' => $request->address,
            'company_id' => $companyId,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Supplier created successfully',
            'data' => $supplier,
        ], 201);
    }

    /**
     * Display the specified supplier
     */
    public function show($id, Request $request)
    {
        $companyId = $request->input('company_id');
        $supplier = Supplier::query();
        if ($companyId) {
            $supplier->where('company_id', $companyId);
        }
        $supplier = $supplier->findOrFail($id);
        return response()->json([
            'success' => true,
            'data' => $supplier,
        ], 200);
    }

    /**
     * Update the specified supplier
     */
    public function update(Request $request, $id)
    {
        $companyId = $request->input('company_id');

        $supplier = Supplier::query();
        if ($companyId) {
            $supplier->where('company_id', $companyId);
        }
        $supplier = $supplier->findOrFail($id);

        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('suppliers', 'name')
                    ->where(fn($q) => $q->where('company_id', $companyId))
                    ->ignore($supplier->id),
            ],
            'contact' => 'nullable|string|max:255',
            'address' => 'nullable|string',
        ]);

        $supplier->update([
            'name' => $request->name,
            'contact' => $request->contact,
            'address' => $request->address,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Supplier updated successfully',
            'data' => $supplier,
        ], 200);
    }

    /**
     * Remove the specified supplier
     */
    public function destroy($id, Request $request)
    {
        $companyId = $request->input('company_id');

        $supplier = Supplier::query();
        if ($companyId) {
            $supplier->where('company_id', $companyId);
        }
        $supplier = $supplier->findOrFail($id);

        // Check if supplier has products
        if ($supplier->products()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete supplier that has products',
            ], 422);
        }

        $supplier->delete();

        return response()->json([
            'success' => true,
            'message' => 'Supplier deleted successfully',
        ], 200);
    }
}
