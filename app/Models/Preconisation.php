<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Preconisation extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code',
        'date_preconisation',
        'note',
        'farmer_id',
        'farm_id',
        'created_by',
        'updated_by',
        'deleted_at',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'farmer_id' => 'integer',
        'farm_id' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'deleted_at' => 'timestamp',
    ];

    public $appends = ['total_amount'];

    public function preconisationItems(): HasMany
    {
        return $this->hasMany(PreconisationItems::class);
    }

    public function farmer(): BelongsTo
    {
        return $this->belongsTo(Farmer::class);
    }

    public function farm(): BelongsTo
    {
        return $this->belongsTo(Farm::class);
    }



    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }


    public function getTotalAmountAttribute()
    {
        return $this->preconisationItems()->sum('price');
    }


    public function scopeTotalAmount()
    {
        return $this->preconisationItems()->sum('price');
    }

    public function scopeAverageAmount()
    {
        return $this->preconisationItems()->sum('price');

        // return $this->preconisationItems()->sum('price');
    }

    public function scopeLastOne()
    {
        return $this->orderBy('id', 'desc')->first();
    }


}
