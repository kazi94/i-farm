<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Farmer extends Model
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
        'fullname',
        'address',
        'website',
        'facebook_url',
        'twitter_url',
        'instagram_url',
        'linkedin_url',
        'image_url',
        'email',
        'note',
        'latitude',
        'longitude',
        'activity',
        'status',
        'commune_id',
        'daira_id',
        'wilaya_id',
        'created_by',
        'updated_by',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'latitude' => 'float',
        'longitude' => 'float',
        'commune_id' => 'integer',
        'daira_id' => 'integer',
        'wilaya_id' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp',
        'deleted_at' => 'timestamp',
    ];

    public function preconisations(): HasMany
    {
        return $this->hasMany(Preconisation::class);
    }
    public function farms(): HasMany
    {
        return $this->hasMany(Farm::class);
    }

    public function commune(): BelongsTo
    {
        return $this->belongsTo(Commune::class);
    }

    public function wilaya(): BelongsTo
    {
        return $this->belongsTo(Wilaya::class);
    }

    public function daira(): BelongsTo
    {
        return $this->belongsTo(Daira::class);
    }

    public function contacts()
    {
        return $this->hasMany(Contact::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function getActivityAttribute($value)
    {
        if ($value == 'culture') {
            return 'Culture';
        }
        if ($value == 'culture_livestock') {
            return 'Culture et Chaptel';
        }
    }

    public function getStatusAttribute($value)
    {
        if ($value == 'gold') {
            return 'Or';
        }
        if ($value == 'silver') {
            return 'Argent';
        }

        if ($value == 'bronze') {
            return 'Bronze';
        }
    }
}
