<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = [
        'user_id',         // or customer_id, depending on your setup
        'invoice_number',
        'total',
        'discount',
        'tax',
        'grand_total',
        'sale_date',       // optional, could use created_at
        'status',          // e.g., 'completed', 'pending', 'cancelled'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }

    // If you have a customer model, add:
    // public function customer() { return $this->belongsTo(Customer::class); }
}