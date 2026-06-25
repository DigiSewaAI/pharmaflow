<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExpiryController extends Controller
{
    /**
     * Display expiry center dashboard.
     */
    public function index(Request $request)
    {
        $query = Medicine::with(['category', 'supplier']);

        // Filters
        if ($request->filled('search')) {
            $query->where('name', 'like', "%{$request->search}%")
                  ->orWhere('batch_number', 'like', "%{$request->search}%");
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Expiry range filter (7, 15, 30 days, or all)
        $days = $request->days ?? 30;
        if ($days) {
            $query->where('expiry_date', '<=', now()->addDays($days))
                  ->where('expiry_date', '>=', now()->toDateString());
        } else {
            // Show all including expired
            $query->whereNotNull('expiry_date');
        }

        // Also include expired medicines
        if ($request->show_expired) {
            $query->orWhere('expiry_date', '<', now()->toDateString());
        }

        $expiringMedicines = $query->orderBy('expiry_date', 'asc')->paginate(15);

        // Stats
        $expiringIn7Days = Medicine::where('expiry_date', '<=', now()->addDays(7))
                                   ->where('expiry_date', '>=', now()->toDateString())
                                   ->count();

        $expiringIn15Days = Medicine::where('expiry_date', '<=', now()->addDays(15))
                                    ->where('expiry_date', '>=', now()->toDateString())
                                    ->count();

        $expiringIn30Days = Medicine::where('expiry_date', '<=', now()->addDays(30))
                                    ->where('expiry_date', '>=', now()->toDateString())
                                    ->count();

        $expiredCount = Medicine::where('expiry_date', '<', now()->toDateString())->count();

        // Total medicines with expiry date
        $totalWithExpiry = Medicine::whereNotNull('expiry_date')->count();

        return view('expiry.index', compact(
            'expiringMedicines',
            'expiringIn7Days',
            'expiringIn15Days',
            'expiringIn30Days',
            'expiredCount',
            'totalWithExpiry'
        ));
    }

    /**
     * Mark medicine as expired (AJAX)
     */
    public function markExpired(Request $request)
    {
        $request->validate([
            'medicine_id' => 'required|exists:medicines,id',
        ]);

        $medicine = Medicine::findOrFail($request->medicine_id);
        $medicine->status = 'expired';
        $medicine->save();

        // Log transaction (optional)
        \App\Models\InventoryTransaction::create([
            'medicine_id' => $medicine->id,
            'type' => 'out',
            'quantity' => $medicine->quantity,
            'batch' => $medicine->batch_number,
            'reason' => 'Marked as expired',
            'user_id' => auth()->id(),
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Medicine marked as expired.',
            ]);
        }

        return back()->with('success', 'Medicine marked as expired.');
    }

    /**
     * Export expiring medicines to Excel (optional)
     */
    public function export()
    {
        // This would use Laravel Excel – you can implement later
        return back()->with('info', 'Export functionality coming soon.');
    }
}