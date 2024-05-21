<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function getNameAttribute($value)
    {
        return strtoupper(substr($value, 0, 1)) . substr($value, 1);
    }
}
