<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Medicine;
use App\Models\Category;
use App\Models\Supplier;

class DashboardController extends Controller
{
    public function index()
    {
        $totalMedicines = Medicine::count();
        $totalCategories = Category::count();
        $totalSuppliers = Supplier::count();
        $lowStockMedicines = Medicine::where('quantity', '<', 30)->count();
        $expiredMedicines = Medicine::where('expiry_date', '<', now())->count();

        return view('dashboard', compact(
            'totalMedicines',
            'totalCategories',
            'totalSuppliers',
            'lowStockMedicines',
            'expiredMedicines'
        ));
    }
}