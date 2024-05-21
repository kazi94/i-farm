<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;


class IntrantPrincipeActif extends Model
{
    public $table = 'intrant_principe_actif';

    protected $fillable = [
        'intrant_id',
        'principe_actif_id',
        'unit_id',
        'concentration',
    ];

    public function intrant()
    {
        return $this->belongsTo(Intrant::class);
    }

    public function principeActif()
    {
        return $this->belongsTo(PrincipeActif::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

}
