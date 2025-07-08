<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'sku',
        'price',
        'stock',
        'description',
        // Tambah kolom lain kalau ada
    ];

    public function purchaseItems()
    {
        return $this->hasMany(PurchaseItem::class);
    }
}

