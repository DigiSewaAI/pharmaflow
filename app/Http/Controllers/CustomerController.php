<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of customers.
     */
    public function index(Request $request)
    {
        $query = Customer::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
        }

        $customers = $query->latest()->paginate(15);
        return view('customers.index', compact('customers'));
    }

    /**
     * Store a newly created customer.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255|unique:customers',
            'address' => 'nullable|string|max:500',
            'loyalty_points' => 'nullable|integer|min:0',
        ]);

        $customer = Customer::create($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Customer added successfully.',
                'customer' => $customer
            ]);
        }

        return redirect()->route('customers.index')->with('success', 'Customer added successfully.');
    }

    /**
     * Update the specified customer.
     */
    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255|unique:customers,email,' . $customer->id,
            'address' => 'nullable|string|max:500',
            'loyalty_points' => 'nullable|integer|min:0',
        ]);

        $customer->update($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Customer updated successfully.',
                'customer' => $customer->fresh()
            ]);
        }

        return redirect()->route('customers.index')->with('success', 'Customer updated successfully.');
    }

    /**
     * Remove the specified customer.
     */
    public function destroy(Customer $customer)
    {
        $customer->delete();

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Customer deleted successfully.'
            ]);
        }

        return redirect()->route('customers.index')->with('success', 'Customer deleted successfully.');
    }

    /**
     * Show customer purchase history (AJAX for modal).
     */
    public function purchases(Customer $customer)
    {
        $customer->load('sales.items.medicine');
        return response()->json([
            'success' => true,
            'data' => $customer
        ]);
    }

    /**
     * Get customer data for edit modal (AJAX).
     */
    public function edit(Customer $customer)
    {
        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'customer' => $customer
            ]);
        }
        return redirect()->route('customers.index');
    }
}