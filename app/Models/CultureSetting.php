<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CultureSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'culture_id',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
