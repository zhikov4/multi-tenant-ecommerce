<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = [
        'user_id',
        'tenant_id',
        'product_id',
        'product_name',
        'product_store',
        'product_price',
        'quantity',
    ];

    protected $casts = [
        'product_price' => 'decimal:2',
        'quantity'      => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}