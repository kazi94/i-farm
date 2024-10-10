<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Farm extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code',
        'classification',
        'area',
        'name',
        'unit_id',
        'culture_id',
        'culture_setting_id',
        'culture_variante_id',
        'farmer_id',
        'density',
        'age',
        'distance_tree',
        'distance_line',
        'number_of_feet',
        'n',
        'p',
        'k',
        'ca',
        's',
        'so3',
        'mgo',
        'b',
        'cu',
        'fe',
        'mn',
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
        'area' => 'float',
        'name' => 'string',
        'distance_tree' => 'float',
        'distance_line' => 'float',
        'number_of_feet' => 'integer',
        'n' => 'float',
        'p' => 'float',
        'k' => 'float',
        'ca' => 'float',
        's' => 'float',
        'so3' => 'float',
        'mgo' => 'float',
        'b' => 'float',
        'cu' => 'float',
        'fe' => 'float',
        'mn' => 'float',
        'farmer_id' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp',
    ];

    public $append = ['custom_area'];

    public function culture(): BelongsTo
    {
        return $this->belongsTo(Culture::class);
    }

    public function cultureSetting(): BelongsTo
    {
        return $this->belongsTo(CultureSetting::class);
    }
    public function cultureVariante(): BelongsTo
    {
        return $this->belongsTo(CultureVariante::class);
    }

    public function farmer(): BelongsTo
    {
        return $this->belongsTo(Farmer::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function getCustomAreaAttribute()
    {
        return $this->area . ' ' . $this->unit->name;
    }
}
