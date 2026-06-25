<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\MedicinesImport;
use App\Exports\MedicinesExport;

class MedicineController extends Controller
{
    /**
     * Display a listing of medicines with filters.
     */
    public function index(Request $request)
    {
        $query = Medicine::with(['category', 'supplier']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('batch_number', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $medicines = $query->paginate(15);
        $categories = Category::all();
        $suppliers = Supplier::all();

        return view('medicines.index', compact('medicines', 'categories', 'suppliers'));
    }

    /**
     * Store a newly created medicine (AJAX or form).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'supplier_id' => 'nullable|exists:suppliers,id',      // from form
            'supplier_name' => 'nullable|string|max:255',        // from dashboard
            'batch_number' => 'nullable|string|max:50',
            'purchase_price' => 'nullable|numeric|min:0',
            'selling_price' => 'nullable|numeric|min:0',
            'quantity' => 'nullable|integer|min:0',
            'expiry_date' => 'nullable|date',
            'manufacture_date' => 'nullable|date',               // 👈 new
            'barcode' => 'nullable|string|max:50',
        ]);

        // ─── Supplier handling ───
        $supplierId = null;
        if (!empty($validated['supplier_id'])) {
            $supplierId = $validated['supplier_id'];
        } elseif (!empty($validated['supplier_name'])) {
            $supplier = Supplier::firstOrCreate(
                ['name' => $validated['supplier_name']],
                ['contact' => null, 'address' => null]
            );
            $supplierId = $supplier->id;
        }

        $quantity = $validated['quantity'] ?? 0;
        $status = $this->determineStatus($quantity, $validated['expiry_date'] ?? null);

        $medicine = Medicine::create([
            'name' => $validated['name'],
            'category_id' => $validated['category_id'] ?? null,
            'supplier_id' => $supplierId,
            'batch_number' => $validated['batch_number'] ?? null,
            'purchase_price' => $validated['purchase_price'] ?? 0,
            'selling_price' => $validated['selling_price'] ?? 0,
            'quantity' => $quantity,
            'expiry_date' => $validated['expiry_date'] ?? null,
            'manufacture_date' => $validated['manufacture_date'] ?? null,
            'barcode' => $validated['barcode'] ?? null,
            'status' => $status,
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Medicine added successfully.',
                'medicine' => $medicine->load(['category', 'supplier'])
            ]);
        }

        return redirect()->route('medicines.index')
                         ->with('success', 'Medicine added successfully.');
    }

    /**
     * Update the specified medicine (AJAX or form).
     */
    public function update(Request $request, Medicine $medicine)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'supplier_name' => 'nullable|string|max:255',
            'batch_number' => 'nullable|string|max:50',
            'purchase_price' => 'nullable|numeric|min:0',
            'selling_price' => 'nullable|numeric|min:0',
            'quantity' => 'nullable|integer|min:0',
            'expiry_date' => 'nullable|date',
            'manufacture_date' => 'nullable|date',
            'barcode' => 'nullable|string|max:50',
        ]);

        // ─── Supplier handling ───
        if (!empty($validated['supplier_id'])) {
            $validated['supplier_id'] = $validated['supplier_id'];
        } elseif (!empty($validated['supplier_name'])) {
            $supplier = Supplier::firstOrCreate(
                ['name' => $validated['supplier_name']],
                ['contact' => null, 'address' => null]
            );
            $validated['supplier_id'] = $supplier->id;
        }
        unset($validated['supplier_name']);

        // ─── Auto‑calculate status if quantity or expiry changed ───
        if (isset($validated['quantity']) || isset($validated['expiry_date'])) {
            $quantity = $validated['quantity'] ?? $medicine->quantity;
            $expiry = $validated['expiry_date'] ?? $medicine->expiry_date;
            $validated['status'] = $this->determineStatus($quantity, $expiry);
        }

        $medicine->update($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Medicine updated successfully.',
                'medicine' => $medicine->fresh()->load(['category', 'supplier'])
            ]);
        }

        return redirect()->route('medicines.index')
                         ->with('success', 'Medicine updated successfully.');
    }

    /**
     * Remove the specified medicine (AJAX or form).
     */
    public function destroy(Medicine $medicine)
    {
        $medicine->delete();

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Medicine deleted successfully.'
            ]);
        }

        return redirect()->route('medicines.index')
                         ->with('success', 'Medicine deleted successfully.');
    }

    /**
     * Import medicines from Excel/CSV.
     */
    public function import(Request $request)
    {
        $request->validate(['file' => 'required|mimes:xlsx,csv']);
        Excel::import(new MedicinesImport, $request->file('file'));
        return back()->with('success', 'Import completed successfully.');
    }

    /**
     * Export medicines to Excel.
     */
    public function export()
    {
        return Excel::download(new MedicinesExport, 'medicines.xlsx');
    }

    /**
     * Determine status based on quantity and expiry date.
     */
    private function determineStatus($quantity, $expiryDate = null)
    {
        // If expired
        if ($expiryDate && $expiryDate < now()->toDateString()) {
            return 'expired';
        }
        // If out of stock
        if ($quantity <= 0) {
            return 'expired';
        }
        // If low stock
        if ($quantity < 30) {
            return 'low_stock';
        }
        // Otherwise in stock
        return 'in_stock';
    }
    /**
 * Show medicine data for AJAX edit modal.
 */
public function edit(Medicine $medicine)
{
    if (request()->wantsJson()) {
        return response()->json([
            'success' => true,
            'medicine' => $medicine->load(['category', 'supplier'])
        ]);
    }
    return redirect()->route('medicines.index');
}
}