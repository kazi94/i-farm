<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Culture extends Model
{
    use HasFactory;


    // TEST
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
        return $this->belongsToMany(Intrant::class, 'culture_intrant', 'culture_id', 'intrant_id');
    }

    public function getNameAttribute($value)
    {
        return strtoupper(substr($value, 0, 1)) . substr($value, 1);
    }
}
