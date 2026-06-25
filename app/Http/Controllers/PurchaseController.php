<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\Supplier;
use App\Models\Medicine;
use App\Models\InventoryTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    /**
     * Display a listing of purchase orders.
     */
    public function index(Request $request)
    {
        $query = PurchaseOrder::with(['supplier', 'createdBy']);

        if ($request->filled('search')) {
            $query->where('po_number', 'like', "%{$request->search}%");
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $purchases = $query->latest()->paginate(15);
        $suppliers = Supplier::all();
        $medicines = Medicine::all();

        return view('purchases.index', compact('purchases', 'suppliers', 'medicines'));
    }

    /**
     * Store a newly created purchase order.
     */
    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'order_date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.medicine_id' => 'required|exists:medicines,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        $total = 0;
        foreach ($request->items as $item) {
            $total += $item['quantity'] * $item['unit_price'];
        }

        // Generate PO number
        $poNumber = 'PO-' . date('Ymd') . '-' . str_pad(PurchaseOrder::count() + 1, 4, '0', STR_PAD_LEFT);

        $purchase = PurchaseOrder::create([
            'po_number' => $poNumber,
            'supplier_id' => $request->supplier_id,
            'order_date' => $request->order_date,
            'total' => $total,
            'status' => 'draft',
            'created_by' => auth()->id(),
        ]);

        foreach ($request->items as $item) {
            $purchase->items()->create([
                'medicine_id' => $item['medicine_id'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'total' => $item['quantity'] * $item['unit_price'],
            ]);
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Purchase Order created successfully.',
                'purchase' => $purchase->load('items')
            ]);
        }

        return redirect()->route('purchases.index')->with('success', 'Purchase Order created.');
    }

    /**
     * Receive purchase order → stock in.
     */
    public function receive(Request $request, PurchaseOrder $purchase)
    {
        if ($purchase->status === 'received') {
            return response()->json([
                'success' => false,
                'message' => 'This PO has already been received.'
            ], 422);
        }

        DB::beginTransaction();
        try {
            foreach ($purchase->items as $item) {
                $medicine = Medicine::find($item->medicine_id);
                $oldQty = $medicine->quantity;
                $medicine->quantity += $item->quantity;
                $medicine->status = $this->determineStatus($medicine->quantity, $medicine->expiry_date);
                $medicine->save();

                // Log transaction
                InventoryTransaction::create([
                    'medicine_id' => $medicine->id,
                    'type' => 'in',
                    'quantity' => $item->quantity,
                    'batch' => $medicine->batch_number,
                    'reason' => 'Purchase Order #' . $purchase->po_number,
                    'user_id' => auth()->id(),
                ]);
            }

            $purchase->status = 'received';
            $purchase->save();

            DB::commit();

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'PO received and stock updated successfully.'
                ]);
            }

            return redirect()->route('purchases.index')->with('success', 'PO received successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to receive PO: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cancel purchase order.
     */
    public function destroy(PurchaseOrder $purchase)
    {
        if ($purchase->status === 'received') {
            return response()->json([
                'success' => false,
                'message' => 'Cannot cancel a received PO.'
            ], 422);
        }

        $purchase->delete();

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Purchase Order cancelled.'
            ]);
        }

        return redirect()->route('purchases.index')->with('success', 'PO cancelled.');
    }

    /**
     * Determine status helper.
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