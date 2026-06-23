<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'category_id', 'supplier_id', 'batch_number',
        'purchase_price', 'selling_price', 'quantity', 'expiry_date',
        'status', 'barcode'
    ];

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

    // स्टक अपडेट गर्न helper
    public function updateStock($quantity, $type, $reason, $userId = null)
    {
        $this->quantity += ($type === 'in' ? $quantity : -$quantity);
        $this->status = $this->quantity <= 0 ? 'expired' : ($this->quantity < 30 ? 'low_stock' : 'in_stock');
        $this->save();

        $this->transactions()->create([
            'type' => $type,
            'quantity' => $quantity,
            'batch' => $this->batch_number,
            'reason' => $reason,
            'user_id' => $userId ?? auth()->id(),
        ]);
    }
}