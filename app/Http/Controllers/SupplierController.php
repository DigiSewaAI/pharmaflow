<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    /**
     * Display a listing of suppliers.
     */
    public function index(Request $request)
    {
        $query = Supplier::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('contact_person', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
        }

        $suppliers = $query->latest()->paginate(15);
        return view('suppliers.index', compact('suppliers'));
    }

    /**
     * Store a newly created supplier.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255|unique:suppliers',
            'address' => 'nullable|string|max:500',
            'outstanding_balance' => 'nullable|numeric|min:0',
        ]);

        $supplier = Supplier::create($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Supplier added successfully.',
                'supplier' => $supplier
            ]);
        }

        return redirect()->route('suppliers.index')->with('success', 'Supplier added successfully.');
    }

    /**
     * Update the specified supplier.
     */
    public function update(Request $request, Supplier $supplier)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255|unique:suppliers,email,' . $supplier->id,
            'address' => 'nullable|string|max:500',
            'outstanding_balance' => 'nullable|numeric|min:0',
        ]);

        $supplier->update($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Supplier updated successfully.',
                'supplier' => $supplier->fresh()
            ]);
        }

        return redirect()->route('suppliers.index')->with('success', 'Supplier updated successfully.');
    }

    /**
     * Remove the specified supplier.
     */
    public function destroy(Supplier $supplier)
    {
        $supplier->delete();

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Supplier deleted successfully.'
            ]);
        }

        return redirect()->route('suppliers.index')->with('success', 'Supplier deleted successfully.');
    }

    /**
     * Get supplier data for edit modal (AJAX).
     */
    public function edit(Supplier $supplier)
    {
        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'supplier' => $supplier
            ]);
        }
        return redirect()->route('suppliers.index');
    }
}