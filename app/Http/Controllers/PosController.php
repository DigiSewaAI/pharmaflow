<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Events\SaleCompleted; // ✅ Event for real-time notification

class PosController extends Controller
{
    /**
     * Display POS page.
     */
    public function index()
    {
        $medicines = Medicine::where('status', 'in_stock')
                            ->orWhere('status', 'low_stock')
                            ->get(['id', 'name', 'batch_number', 'selling_price', 'quantity']);
        $customers = Customer::all(['id', 'name', 'phone']);
        
        return view('pos.index', compact('medicines', 'customers'));
    }

    /**
     * Add item to cart (AJAX).
     */
    public function addToCart(Request $request)
    {
        $request->validate([
            'medicine_id' => 'required|exists:medicines,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $medicine = Medicine::findOrFail($request->medicine_id);
        
        if ($medicine->quantity < $request->quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Insufficient stock. Available: ' . $medicine->quantity
            ], 422);
        }

        // Cart stored in session as array
        $cart = session()->get('pos_cart', []);
        
        if (isset($cart[$medicine->id])) {
            // Check if adding more exceeds stock
            $newQty = $cart[$medicine->id]['quantity'] + $request->quantity;
            if ($newQty > $medicine->quantity) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot add more than available stock. Available: ' . $medicine->quantity
                ], 422);
            }
            $cart[$medicine->id]['quantity'] = $newQty;
        } else {
            $cart[$medicine->id] = [
                'id' => $medicine->id,
                'name' => $medicine->name,
                'price' => $medicine->selling_price,
                'quantity' => $request->quantity,
                'batch' => $medicine->batch_number,
                'max_qty' => $medicine->quantity
            ];
        }

        session()->put('pos_cart', $cart);

        return response()->json([
            'success' => true,
            'message' => 'Added to cart.',
            'cart' => $this->getCartData(),
            'cart_count' => array_sum(array_column($cart, 'quantity'))
        ]);
    }

    /**
     * Remove item from cart (AJAX).
     */
    public function removeFromCart(Request $request)
    {
        $request->validate(['medicine_id' => 'required|exists:medicines,id']);
        
        $cart = session()->get('pos_cart', []);
        unset($cart[$request->medicine_id]);
        session()->put('pos_cart', $cart);

        return response()->json([
            'success' => true,
            'message' => 'Removed from cart.',
            'cart' => $this->getCartData(),
            'cart_count' => array_sum(array_column($cart, 'quantity'))
        ]);
    }

    /**
     * Update cart item quantity (AJAX).
     */
    public function updateCart(Request $request)
    {
        $request->validate([
            'medicine_id' => 'required|exists:medicines,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $cart = session()->get('pos_cart', []);
        
        if (!isset($cart[$request->medicine_id])) {
            return response()->json([
                'success' => false,
                'message' => 'Item not in cart.'
            ], 404);
        }

        $medicine = Medicine::find($request->medicine_id);
        if ($request->quantity > $medicine->quantity) {
            return response()->json([
                'success' => false,
                'message' => 'Not enough stock. Available: ' . $medicine->quantity
            ], 422);
        }

        $cart[$request->medicine_id]['quantity'] = $request->quantity;
        session()->put('pos_cart', $cart);

        return response()->json([
            'success' => true,
            'message' => 'Cart updated.',
            'cart' => $this->getCartData(),
            'cart_count' => array_sum(array_column($cart, 'quantity'))
        ]);
    }

    /**
     * Clear entire cart (AJAX).
     */
    public function clearCart()
    {
        session()->forget('pos_cart');
        return response()->json([
            'success' => true,
            'message' => 'Cart cleared.',
            'cart' => $this->getCartData(),
            'cart_count' => 0
        ]);
    }

    /**
     * Process checkout and create sale.
     */
    public function checkout(Request $request)
    {
        $cart = session()->get('pos_cart', []);
        
        if (empty($cart)) {
            return response()->json([
                'success' => false,
                'message' => 'Cart is empty.'
            ], 422);
        }

        $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'discount' => 'nullable|numeric|min:0|max:100',
            'tax' => 'nullable|numeric|min:0|max:100',
        ]);

        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        $discountPercent = $request->discount ?? 0;
        $taxPercent = $request->tax ?? 10; // default tax 10%
        
        $discount = $subtotal * ($discountPercent / 100);
        $tax = ($subtotal - $discount) * ($taxPercent / 100);
        $grandTotal = $subtotal - $discount + $tax;

        DB::beginTransaction();
        try {
            // Generate invoice number
            $invoiceNumber = 'INV-' . date('Ymd') . '-' . str_pad(Sale::count() + 1, 4, '0', STR_PAD_LEFT);

            // Create sale
            $sale = Sale::create([
                'invoice_number' => $invoiceNumber,
                'user_id' => auth()->id(),
                'customer_id' => $request->customer_id,
                'subtotal' => $subtotal,
                'discount' => $discount,
                'tax' => $tax,
                'grand_total' => $grandTotal,
                'status' => 'completed',
                'created_at' => now()
            ]);

            // Create sale items & update stock
            foreach ($cart as $item) {
                SaleItem::create([
                    'sale_id' => $sale->id,
                    'medicine_id' => $item['id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'total' => $item['price'] * $item['quantity']
                ]);

                // Update medicine stock
                $medicine = Medicine::find($item['id']);
                $medicine->quantity -= $item['quantity'];
                $medicine->status = $this->determineStatus($medicine->quantity, $medicine->expiry_date);
                $medicine->save();

                // Log inventory transaction
                \App\Models\InventoryTransaction::create([
                    'medicine_id' => $medicine->id,
                    'type' => 'out',
                    'quantity' => $item['quantity'],
                    'batch' => $medicine->batch_number,
                    'reason' => 'Sale #' . $invoiceNumber,
                    'user_id' => auth()->id(),
                ]);
            }

            // 🔔 Broadcast real-time notification for this sale
            event(new SaleCompleted($sale, auth()->id()));

            // Clear cart
            session()->forget('pos_cart');

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Sale completed successfully.',
                'sale_id' => $sale->id,
                'invoice_number' => $invoiceNumber,
                'grand_total' => number_format($grandTotal, 2)
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Sale failed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show invoice (view).
     */
    public function invoice(Sale $sale)
    {
        $sale->load(['items.medicine', 'customer', 'user']);
        return view('pos.invoice', compact('sale'));
    }

    /**
     * Helper: get cart data for JSON response.
     */
    private function getCartData()
    {
        $cart = session()->get('pos_cart', []);
        $items = [];
        $subtotal = 0;
        foreach ($cart as $id => $item) {
            $items[] = [
                'id' => $id,
                'name' => $item['name'],
                'price' => $item['price'],
                'quantity' => $item['quantity'],
                'total' => $item['price'] * $item['quantity'],
                'batch' => $item['batch'] ?? null
            ];
            $subtotal += $item['price'] * $item['quantity'];
        }
        return [
            'items' => $items,
            'subtotal' => $subtotal,
            'count' => array_sum(array_column($cart, 'quantity'))
        ];
    }

    /**
     * Determine status based on quantity and expiry.
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