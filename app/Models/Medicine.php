<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category_id',
        'supplier_id',
        'batch_number',
        'purchase_price',
        'selling_price',
        'quantity',
        'expiry_date',
        'manufacture_date', // 👈 added
        'status',
        'barcode'
    ];

    // ─── Relationships ───
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function transactions()
    {
        return $this->hasMany(InventoryTransaction::class);
    }

    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }

    // ─── Stock Helper ───
    public function updateStock($quantity, $type, $reason, $userId = null)
    {
        $this->quantity += ($type === 'in' ? $quantity : -$quantity);
        $this->status = $this->determineStatus();
        $this->save();

        $this->transactions()->create([
            'type' => $type,
            'quantity' => $quantity,
            'batch' => $this->batch_number,
            'reason' => $reason,
            'user_id' => $userId ?? auth()->id(),
        ]);
    }

    // ─── Helper: Determine status based on quantity and expiry ───
    public function determineStatus()
    {
        if ($this->quantity <= 0) {
            return 'expired';
        }

        // If expiry date is in the past, mark as expired
        if ($this->expiry_date && $this->expiry_date < now()->toDateString()) {
            return 'expired';
        }

        if ($this->quantity < 30) {
            return 'low_stock';
        }

        return 'in_stock';
    }

    // ─── Accessor: Get days until expiry ───
    public function getDaysUntilExpiryAttribute()
    {
        if (!$this->expiry_date) {
            return null;
        }
        return now()->diffInDays($this->expiry_date, false);
    }

    // ─── Scope: Filter by status ───
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    // ─── Scope: Expiring within given days ───
    public function scopeExpiringWithin($query, $days = 30)
    {
        return $query->where('expiry_date', '<=', now()->addDays($days))
                     ->where('expiry_date', '>', now());
    }
}