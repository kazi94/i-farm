<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Intrant extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name_fr',
        'name_ar',
        'formulation',
        'homologation_number',
        'firm_id',
        'intrant_sous_category_id',
        'distrubutor_id',
        'intrant_category_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'firm_id' => 'integer',
        'intrant_sous_category_id' => 'integer',
        'distrubutor_id' => 'integer',
        'intrant_category_id' => 'integer',
    ];

    public function firm(): BelongsTo
    {
        return $this->belongsTo(Firm::class);
    }

    public function sousCategory(): BelongsTo
    {
        return $this->belongsTo(IntrantSousCategory::class);
    }

    public function distrubutor(): BelongsTo
    {
        return $this->belongsTo(Distrubutor::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(IntrantCategory::class);
    }

    public function cultures(): BelongsToMany
    {
        return $this->belongsToMany(Culture::class, 'intrant_culture', 'intrant_id', 'culture_id');
    }

    public function principeActifs(): BelongsToMany
    {
        return $this->belongsToMany(PrincipeActif::class);
    }
}
