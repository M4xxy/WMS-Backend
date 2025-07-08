<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class PurchaseItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_id',
        'product_id',
        'quantity',
        'price',
    ];

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    protected static function booted()
    {
        static::created(function ($item) {
            // tambah stok ini
            $product = $item->product;
            if ($product) {
                $product->stock += $item->quantity;
                $product->save();
            }
            // buat update total yang dibeli
            $purchase = $item->purchase;
            $total = $purchase->items()->sum(\DB::raw('quantity * price'));
            $purchase->total_amount = $total;
            $purchase->save();
        });
    }
}
