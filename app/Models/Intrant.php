<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Intrant extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name_fr',
        'name_ar',
        'formulation',
        'score',
        'is_approved',
        'homologation_number',
        'firm_id',
        'intrant_sous_category_id',
        'distributor_id',
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
        'is_approved' => 'boolean',
        'intrant_sous_category_id' => 'integer',
        'distributor_id' => 'integer',
        'intrant_category_id' => 'integer',
    ];

    public function firm(): BelongsTo
    {
        return $this->belongsTo(Firm::class);
    }

    public function sousCategory(): BelongsTo
    {
        return $this->belongsTo(IntrantSousCategory::class, 'intrant_sous_category_id', 'id');
    }

    public function distributor(): BelongsTo
    {
        return $this->belongsTo(Distributor::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(IntrantCategory::class, 'intrant_category_id', 'id');
    }

    public function intrantsCultures()
    {
        return $this->hasMany(IntrantCulture::class, );
    }

    public function intrantsPrincipesActifs()
    {
        return $this->hasmAny(IntrantPrincipeActif::class);
    }

    public function getNameFrAttribute($value)
    {
        return strtoupper(substr($value, 0, 1)) . substr($value, 1);
    }
}
