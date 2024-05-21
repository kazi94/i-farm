<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Depredateur extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
    ];

    public function intrants()
    {
        return $this->belongsToMany(Intrant::class, 'culture_intrant', 'depredateur_id', 'intrant_id');
    }

    public function intrantsCultures()
    {
        return $this->belongsToMany(Culture::class, 'culture_intrant', 'culture_id', 'depredateur_id');
    }

    public function getNameAttribute($value)
    {
        return strtoupper(substr($value, 0, 1)) . substr($value, 1);
    }
}
