<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CultureVariante extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'culture_setting_id',
    ];

    public function cultureSetting()
    {
        return $this->belongsTo(CultureSetting::class);
    }
}
