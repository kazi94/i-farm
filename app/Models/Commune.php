<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Commune extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'name_ar',
        'daira_id:',
        'daira_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'daira_id:' => 'integer',
        'daira_id' => 'integer',
    ];

    public function daira(): BelongsTo
    {
        return $this->belongsTo(Daira::class);
    }

    public function daira(): BelongsTo
    {
        return $this->belongsTo(Daira::class);
    }
}
