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
    public function index(Request $request)
    {
        $query = Medicine::with(['category', 'supplier']);

        if ($request->search) {
            $query->where('name', 'like', "%{$request->search}%")
                  ->orWhere('batch_number', 'like', "%{$request->search}%");
        }
        if ($request->category_id) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->status) {
            $query->where('status', $request->status);
        }

        $medicines = $query->paginate(15);
        $categories = Category::all();
        $suppliers = Supplier::all();

        return view('medicines.index', compact('medicines', 'categories', 'suppliers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'batch_number' => 'nullable|string|max:50',
            'purchase_price' => 'nullable|numeric|min:0',
            'selling_price' => 'nullable|numeric|min:0',
            'quantity' => 'nullable|integer|min:0',
            'expiry_date' => 'nullable|date',
            'barcode' => 'nullable|string|max:50',
        ]);

        $medicine = Medicine::create($validated);
        // स्टक ट्रान्जेक्सन थप्नुहोस् यदि quantity > 0 भए
        if ($medicine->quantity > 0) {
            $medicine->updateStock($medicine->quantity, 'in', 'initial_stock');
        }

        return redirect()->route('medicines.index')->with('success', 'औषधि सफलतापूर्वक थपियो।');
    }

    public function update(Request $request, Medicine $medicine)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'supplier_id' => 'nullable|exists:suppliers,id',
            'batch_number' => 'nullable|string|max:50',
            'purchase_price' => 'nullable|numeric|min:0',
            'selling_price' => 'nullable|numeric|min:0',
            'quantity' => 'nullable|integer|min:0',
            'expiry_date' => 'nullable|date',
            'barcode' => 'nullable|string|max:50',
        ]);

        $oldQty = $medicine->quantity;
        $medicine->update($validated);

        // यदि quantity परिवर्तन भएमा ट्रान्जेक्सन थप्नुहोस्
        if ($medicine->quantity != $oldQty) {
            $diff = $medicine->quantity - $oldQty;
            $type = $diff > 0 ? 'in' : 'out';
            $medicine->updateStock(abs($diff), $type, 'adjustment');
        }

        return redirect()->route('medicines.index')->with('success', 'औषधि अद्यावधिक गरियो।');
    }

    public function destroy(Medicine $medicine)
    {
        $medicine->delete();
        return redirect()->route('medicines.index')->with('success', 'औषधि हटाइयो।');
    }

    // Import/Export
    public function import(Request $request)
    {
        $request->validate(['file' => 'required|mimes:xlsx,csv']);
        Excel::import(new MedicinesImport, $request->file('file'));
        return back()->with('success', 'Import सफल भयो।');
    }

    public function export()
    {
        return Excel::download(new MedicinesExport, 'medicines.xlsx');
    }
}