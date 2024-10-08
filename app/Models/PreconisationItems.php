<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PreconisationItems extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'quantity',
        'price',
        'note',
        'preconisation_id',
        'unit_id',
        'intrant_id',
        'usage_mode',
        'ar_usage_mode',
        'dose',
        'dose_ar',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'intrant_id' => 'integer',
        'price' => 'float',
        'unit_id' => 'integer',
        'preconisation_id' => 'integer',
    ];

    public $appends = ['fr_usage_mode', 'ar_usage_mode'];
    public function intrant(): BelongsTo
    {
        return $this->belongsTo(Intrant::class);
    }

    public function preconisation(): BelongsTo
    {
        return $this->belongsTo(Preconisation::class);
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function getFrUsageModeAttribute()
    {
        if ($this->usage_mode === 'foliaire_application') {
            return 'Application Foliaire';
        }

        if ($this->usage_mode === 'root_application') {
            return 'Application raçinaire';
        }

        return $this->usage_mode;
    }

    public function getArUsageModeAttribute()
    {
        if ($this->usage_mode === 'foliaire_application') {
            return 'تطبيق فولير';
        }

        if ($this->usage_mode === 'root_application') {
            return 'تطبيق جذري';
        }

        return $this->usage_mode;
    }
}
