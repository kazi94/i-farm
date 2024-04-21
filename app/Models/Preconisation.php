<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Preconisation extends Pivot
{
    use SoftDeletes, HasFactory;

    protected $table = 'preconisations';

    protected $fillable = [
        'date_preconisation',
        'farmer_id',
        'farm_id',
        'deleted_at',
        'created_at',
        'updated_at',
        'created_by',
        'updated_by',
        'note'
    ];

    protected $casts = [
        'deleted_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];


    public function farm()
    {
        return $this->belongsTo(Farm::class);
    }

    public function farmer()
    {
        return $this->belongsTo(Farmer::class);
    }

    public function preconisationItems()
    {
        return $this->hasMany(PreconisationItem::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
