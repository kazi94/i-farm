<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;


class IntrantCulture extends Model
{

    protected $table = 'culture_intrant';

    protected $fillable = [
        'intrant_id',
        'culture_id',
        'depredateur_id',
        'unit_id',
        'dose_min',
        'dose_max',
        'dar_min',
        'dar_max',
        'observation',
    ];

    public function intrant()
    {
        return $this->belongsTo(Intrant::class);
    }

    public function culture()
    {
        return $this->belongsTo(Culture::class);
    }

    public function depredateur()
    {
        return $this->belongsTo(Depredateur::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

}
