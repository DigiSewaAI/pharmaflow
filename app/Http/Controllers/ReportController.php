<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Medicine;
use App\Models\InventoryTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Display reports dashboard.
     */
    public function index(Request $request)
    {
        // Default date range: last 30 days
        $startDate = $request->start_date ?? now()->subDays(30)->toDateString();
        $endDate = $request->end_date ?? now()->toDateString();

        // Sales report data
        $salesData = Sale::whereDate('created_at', '>=', $startDate)
                         ->whereDate('created_at', '<=', $endDate)
                         ->select(
                             DB::raw('DATE(created_at) as date'),
                             DB::raw('SUM(grand_total) as total'),
                             DB::raw('COUNT(*) as count')
                         )
                         ->groupBy('date')
                         ->orderBy('date', 'asc')
                         ->get();

        // Top selling medicines
        $topMedicines = Medicine::withCount(['saleItems as total_sold' => function ($q) use ($startDate, $endDate) {
                $q->whereHas('sale', function ($sq) use ($startDate, $endDate) {
                    $sq->whereDate('created_at', '>=', $startDate)
                       ->whereDate('created_at', '<=', $endDate);
                });
            }])
            ->orderBy('total_sold', 'desc')
            ->limit(10)
            ->get();

        // Inventory value
        $inventoryValue = Medicine::sum(DB::raw('quantity * purchase_price'));

        // Total revenue (grand_total sum)
        $totalRevenue = Sale::whereDate('created_at', '>=', $startDate)
                            ->whereDate('created_at', '<=', $endDate)
                            ->sum('grand_total');

        // Total sales count
        $totalSales = Sale::whereDate('created_at', '>=', $startDate)
                          ->whereDate('created_at', '<=', $endDate)
                          ->count();

        // Profit calculation (using purchase_price vs selling_price per sale item – approximated)
        // For simplicity, we'll compute profit = grand_total - (sum of quantity * purchase_price)
        $profit = 0;
        // More complex: loop through sales items and compute cost.
        // We'll do a raw query for better performance.
        $profitData = DB::table('sale_items')
            ->join('sales', 'sale_items.sale_id', '=', 'sales.id')
            ->join('medicines', 'sale_items.medicine_id', '=', 'medicines.id')
            ->whereDate('sales.created_at', '>=', $startDate)
            ->whereDate('sales.created_at', '<=', $endDate)
            ->select(DB::raw('SUM(sale_items.quantity * (sale_items.price - medicines.purchase_price)) as profit'))
            ->first();

        $profit = $profitData->profit ?? 0;

        // Inventory transactions (recent)
        $transactions = InventoryTransaction::with(['medicine', 'user'])
                            ->whereDate('created_at', '>=', $startDate)
                            ->whereDate('created_at', '<=', $endDate)
                            ->latest()
                            ->limit(20)
                            ->get();

        // Pass data to view
        return view('reports.index', compact(
            'salesData',
            'topMedicines',
            'inventoryValue',
            'totalRevenue',
            'totalSales',
            'profit',
            'transactions',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Generate report (AJAX or POST) – can be used for export.
     */
    public function generate(Request $request)
    {
        // Similar to index but returns JSON for AJAX
        return response()->json(['message' => 'Generate report endpoint']);
    }

    /**
     * Export report to PDF/Excel
     */
    public function export($type, Request $request)
    {
        // You can implement PDF/Excel export here using packages like DomPDF or Laravel Excel
        // For now, just redirect with info
        return back()->with('info', 'Export functionality coming soon.');
    }
}