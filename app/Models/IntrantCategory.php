<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class IntrantCategory extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
    ];

    public function intrantSousCategories(): HasMany
    {
        return $this->hasMany(IntrantSousCategory::class);
    }

    public function getNameAttribute($value)
    {
        return strtoupper(substr($value, 0, 1)) . substr($value, 1);
    }
}
