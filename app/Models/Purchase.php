<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'purchase_date',
        'supplier_name',
        'total_amount',
    ];

    public function items()
    {
        return $this->hasMany(PurchaseItem::class);
    }

    protected static function booted()
    {
        static::created(function ($purchase) {
            $total = 0;

            foreach ($purchase->items as $item) {
                $product = $item->product;
                if ($product) {
                    $product->stock += $item->quantity;
                    $product->save();
                }

                $total += $item->quantity * $item->price;
            }

            $purchase->update(['total' => $total]);
        });
    }


}

