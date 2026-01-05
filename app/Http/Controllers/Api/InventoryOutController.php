<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\InventoryOut;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InventoryOutController extends Controller
{
    /**
     * Display a listing of inventory out records
     */
    public function index(Request $request)
    {
        $companyId = Auth::user()->company_id;

        $query = InventoryOut::with(['product', 'user'])
            ->where('company_id', $companyId)
            ->orderBy('date', 'desc');

        if ($request->has('search')) {
            $search = $request->search;
            $query->whereHas('product', function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%');
            });
        }

        $inventoryOut = $query->get();

        return response()->json([
            'success' => true,
            'data' => $inventoryOut,
        ], 200);
    }

    /**
     * Store a newly created inventory out record
     */
    public function store(Request $request)
    {
        $companyId = Auth::user()->company_id;

        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'date' => 'required|date',
            'description' => 'nullable|string',
        ]);

        // Verify product belongs to user's company
        $product = Product::where('id', $request->product_id)
            ->where('company_id', $companyId)
            ->firstOrFail();

        // Check if stock is sufficient
        if ($product->stock < $request->quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Insufficient stock. Available: ' . $product->stock,
            ], 422);
        }

        $inventoryOut = InventoryOut::create([
            'company_id' => $companyId,
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
            'date' => $request->date,
            'description' => $request->description,
            'user_id' => Auth::id(),
        ]);

        // Update product stock
        $product->decrement('stock', $request->quantity);

        return response()->json([
            'success' => true,
            'message' => 'Stock out recorded successfully',
            'data' => $inventoryOut->load(['product', 'user']),
        ], 201);
    }

    /**
     * Display the specified inventory out record
     */
    public function show($id)
    {
        $companyId = Auth::user()->company_id;

        $inventoryOut = InventoryOut::with(['product', 'user'])
            ->where('company_id', $companyId)
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $inventoryOut,
        ], 200);
    }

    /**
     * Get inventory out history
     */
    public function history(Request $request)
    {
        $companyId = Auth::user()->company_id;

        $query = InventoryOut::with(['product', 'user'])
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
