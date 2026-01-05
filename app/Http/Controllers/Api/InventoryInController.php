<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\InventoryIn;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InventoryInController extends Controller
{
    /**
     * Display a listing of inventory in records
     */
    public function index(Request $request)
    {
        $companyId = Auth::user()->company_id;

        $query = InventoryIn::with(['product', 'supplier', 'user'])
            ->where('company_id', $companyId)
            ->orderBy('date', 'desc');

        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('product', function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%');
            });
        }

        $inventoryIn = $query->get();

        return response()->json([
            'success' => true,
            'data' => $inventoryIn,
        ], 200);
    }

    /**
     * Store a newly created inventory in record
     */
    public function store(Request $request)
    {
        $companyId = Auth::user()->company_id;

        $request->validate([
            'product_id' => 'required|exists:products,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'quantity' => 'required|integer|min:1',
            'date' => 'required|date',
            'description' => 'nullable|string',
        ]);

        // Verify product belongs to user's company
        $product = Product::where('id', $request->product_id)
            ->where('company_id', $companyId)
            ->firstOrFail();

        $inventoryIn = InventoryIn::create([
            'company_id' => $companyId,
            'product_id' => $request->product_id,
            'supplier_id' => $request->supplier_id,
            'quantity' => $request->quantity,
            'date' => $request->date,
            'description' => $request->description,
            'user_id' => Auth::id(),
        ]);

        // Update product stock
        $product->increment('stock', $request->quantity);

        return response()->json([
            'success' => true,
            'message' => 'Stock in recorded successfully',
            'data' => $inventoryIn->load(['product', 'supplier', 'user']),
        ], 201);
    }

    /**
     * Display the specified inventory in record
     */
    public function show($id)
    {
        $companyId = Auth::user()->company_id;

        $inventoryIn = InventoryIn::with(['product', 'supplier', 'user'])
            ->where('company_id', $companyId)
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $inventoryIn,
        ], 200);
    }

    /**
     * Get inventory in history
     */
    public function history(Request $request)
    {
        $companyId = Auth::user()->company_id;

        $query = InventoryIn::with(['product', 'supplier', 'user'])
            ->where('company_id', $companyId)
            ->orderBy('date', 'desc');

        if ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        }

        $history = $query->get();

        return response()->json([
            'success' => true,
            'data' => $history,
        ], 200);
    }
}
