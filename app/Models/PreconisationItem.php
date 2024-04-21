<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class PreconisationItem extends Pivot
{
    protected $table = 'preconisation_items';

    protected $fillable = [
        'preconisation_id',
        'product_id',
        'quantity',
        'unit',
        'price',
        'created_at',
        'updated_at',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
