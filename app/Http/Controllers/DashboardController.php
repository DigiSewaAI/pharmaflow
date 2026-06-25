<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use App\Models\Sale;
use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // ─── STATS ───
        $totalMedicines = Medicine::count();
        $lowStock = Medicine::where('quantity', '<', 30)->count();
        $expiringSoon = Medicine::where('expiry_date', '<=', now()->addDays(30))->count();

        $todaySales = Sale::whereDate('created_at', today())->sum('grand_total') ?? 0;
        $monthlyRevenue = Sale::whereMonth('created_at', now()->month)->sum('grand_total') ?? 0;

        // ─── TOP SELLING MEDICINES ───
        $topMedicines = Medicine::withCount('saleItems')
                        ->orderBy('sale_items_count', 'desc')
                        ->limit(5)
                        ->get();

        // ─── RECENT ACTIVITIES (dynamic) ───
        $activities = collect();

        $recentSales = Sale::with('user')->latest()->limit(3)->get();
        foreach ($recentSales as $sale) {
            $activities->push((object) [
                'message' => "Sale #{$sale->invoice_number}",
                'detail' => 'Rs ' . number_format($sale->grand_total, 0) . ' by ' . ($sale->user->name ?? 'Unknown'),
                'time' => $sale->created_at->diffForHumans(),
                'timestamp' => $sale->created_at,
                'color' => '#10b981',
            ]);
        }

        $lowStockItems = Medicine::where('quantity', '<', 30)->latest('updated_at')->limit(2)->get();
        foreach ($lowStockItems as $item) {
            $activities->push((object) [
                'message' => 'Low Stock Alert',
                'detail' => "{$item->name} (only {$item->quantity} units left)",
                'time' => $item->updated_at->diffForHumans(),
                'timestamp' => $item->updated_at,
                'color' => '#f59e0b',
            ]);
        }

        $expiryItems = Medicine::where('expiry_date', '<=', now()->addDays(30))
                            ->where('quantity', '>', 0)
                            ->latest('expiry_date')
                            ->limit(2)
                            ->get();

        foreach ($expiryItems as $item) {
            $activities->push((object) [
                'message' => 'Expiry Alert',
                'detail' => "{$item->name} expires on " . $item->expiry_date->format('Y-m-d'),
                'time' => $item->expiry_date->diffForHumans(),
                'timestamp' => $item->expiry_date,
                'color' => '#ef4444',
            ]);
        }

        $recentActivities = $activities->sortByDesc('timestamp')->take(5)->values();

        if ($recentActivities->isEmpty()) {
            $recentActivities = collect([
                (object) [
                    'message' => 'Welcome to PharmaFlow',
                    'detail' => 'Start adding medicines and making sales',
                    'time' => 'Just now',
                    'timestamp' => now(),
                    'color' => '#2563EB',
                ]
            ]);
        }

        // ─── MEDICINES LIST (for page inside dashboard) ───
        $medicines = Medicine::with(['category', 'supplier'])->paginate(10);
        $categories = Category::all();
        $suppliers = Supplier::all();

        return view('dashboard.index', compact(
            'totalMedicines',
            'lowStock',
            'expiringSoon',
            'todaySales',
            'monthlyRevenue',
            'topMedicines',
            'recentActivities',
            'medicines',
            'categories',
            'suppliers'
        ));
    }
}