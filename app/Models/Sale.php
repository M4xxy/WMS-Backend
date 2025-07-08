<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;



class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'sale_date',
        'customer_name',
        'total_amount',
    ];

    public function items()
    {
        return $this->hasMany(SalesItem::class);
    }

    protected static function booted()
    {
        static::created(function ($sale) {
            $total = $sale->items->sum(function ($item) {
                return $item->price * $item->quantity;
            });

            $sale->update([
                'total_amount' => $total,
            ]);
        });
        static::updated(function ($sale) {
            $total = $sale->items->sum(function ($item) {
                return $item->price * $item->quantity;
            });

            $sale->updateQuietly([
                'total_amount' => $total,
            ]);
        });

    }
}
