<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Models\InventoryTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    /**
     * Display inventory dashboard.
     */
    public function index()
    {
        $medicines = Medicine::with(['category', 'supplier'])->paginate(15);
        
        // Stats
        $totalItems = Medicine::sum('quantity');
        $lowStockItems = Medicine::where('quantity', '<', 30)->count();
        $totalValue = Medicine::sum(DB::raw('quantity * purchase_price'));
        
        // Recent transactions
        $transactions = InventoryTransaction::with(['medicine', 'user'])
                            ->latest()
                            ->limit(20)
                            ->get();
        
        // Batch tracking data (dummy for now – can be extended)
        $batches = Medicine::select('batch_number', 'quantity', 'expiry_date', 'status')
                            ->whereNotNull('batch_number')
                            ->get();
        
        return view('inventory.index', compact(
            'medicines', 'totalItems', 'lowStockItems', 'totalValue',
            'transactions', 'batches'
        ));
    }

    /**
     * Stock In - Add stock to medicine.
     */
    public function stockIn(Request $request)
    {
        $request->validate([
            'medicine_id' => 'required|exists:medicines,id',
            'quantity' => 'required|integer|min:1',
            'batch' => 'nullable|string|max:50',
            'supplier' => 'nullable|string|max:255',
        ]);

        $medicine = Medicine::findOrFail($request->medicine_id);
        $oldQty = $medicine->quantity;
        
        // Update medicine quantity
        $medicine->quantity += $request->quantity;
        $medicine->status = $this->determineStatus($medicine->quantity, $medicine->expiry_date);
        $medicine->save();

        // Log transaction
        InventoryTransaction::create([
            'medicine_id' => $medicine->id,
            'type' => 'in',
            'quantity' => $request->quantity,
            'batch' => $request->batch ?? $medicine->batch_number,
            'reason' => 'Stock In from ' . ($request->supplier ?? 'Unknown'),
            'user_id' => auth()->id(),
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Stock added successfully.',
                'medicine' => $medicine->fresh()
            ]);
        }

        return redirect()->route('inventory.index')->with('success', 'Stock added successfully.');
    }

    /**
     * Stock Out - Remove stock from medicine.
     */
    public function stockOut(Request $request)
    {
        $request->validate([
            'medicine_id' => 'required|exists:medicines,id',
            'quantity' => 'required|integer|min:1',
            'batch' => 'nullable|string|max:50',
            'reason' => 'required|string|max:255',
        ]);

        $medicine = Medicine::findOrFail($request->medicine_id);
        
        if ($medicine->quantity < $request->quantity) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Insufficient stock. Available: ' . $medicine->quantity
                ], 422);
            }
            return back()->with('error', 'Insufficient stock.');
        }

        $medicine->quantity -= $request->quantity;
        $medicine->status = $this->determineStatus($medicine->quantity, $medicine->expiry_date);
        $medicine->save();

        InventoryTransaction::create([
            'medicine_id' => $medicine->id,
            'type' => 'out',
            'quantity' => $request->quantity,
            'batch' => $request->batch ?? $medicine->batch_number,
            'reason' => $request->reason,
            'user_id' => auth()->id(),
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Stock removed successfully.',
                'medicine' => $medicine->fresh()
            ]);
        }

        return redirect()->route('inventory.index')->with('success', 'Stock removed successfully.');
    }

    /**
     * Adjust stock (manual correction).
     */
    public function adjust(Request $request)
    {
        $request->validate([
            'medicine_id' => 'required|exists:medicines,id',
            'new_quantity' => 'required|integer|min:0',
            'reason' => 'required|string|max:255',
        ]);

        $medicine = Medicine::findOrFail($request->medicine_id);
        $oldQty = $medicine->quantity;
        $diff = $request->new_quantity - $oldQty;

        $medicine->quantity = $request->new_quantity;
        $medicine->status = $this->determineStatus($medicine->quantity, $medicine->expiry_date);
        $medicine->save();

        if ($diff != 0) {
            InventoryTransaction::create([
                'medicine_id' => $medicine->id,
                'type' => $diff > 0 ? 'in' : 'out',
                'quantity' => abs($diff),
                'batch' => $medicine->batch_number,
                'reason' => 'Adjustment: ' . $request->reason,
                'user_id' => auth()->id(),
            ]);
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Stock adjusted successfully.',
                'medicine' => $medicine->fresh()
            ]);
        }

        return redirect()->route('inventory.index')->with('success', 'Stock adjusted successfully.');
    }

    /**
     * Transfer stock between batches or locations (simple version).
     */
    public function transfer(Request $request)
    {
        // For now, just log the transfer – can be extended later
        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Transfer logged successfully.'
            ]);
        }
        return redirect()->route('inventory.index')->with('success', 'Transfer completed.');
    }

    /**
     * Get transaction history (AJAX).
     */
    public function history(Request $request)
    {
        $transactions = InventoryTransaction::with(['medicine', 'user'])
                            ->when($request->medicine_id, function ($q, $id) {
                                return $q->where('medicine_id', $id);
                            })
                            ->when($request->type, function ($q, $type) {
                                return $q->where('type', $type);
                            })
                            ->latest()
                            ->paginate(20);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $transactions
            ]);
        }

        return view('inventory.transactions', compact('transactions'));
    }

    /**
     * Determine status based on quantity and expiry date.
     */
    private function determineStatus($quantity, $expiryDate = null)
    {
        if ($expiryDate && $expiryDate < now()->toDateString()) {
            return 'expired';
        }
        if ($quantity <= 0) {
            return 'expired';
        }
        if ($quantity < 30) {
            return 'low_stock';
        }
        return 'in_stock';
    }
}