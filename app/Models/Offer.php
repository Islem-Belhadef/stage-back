<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Offer extends Model
{
    use HasFactory;

    //public $timestamps = false;

    protected $fillable = [
        'supervisor_id',
        'start_date',
        'end_date',
        'duration',
        'available_spots',
        'title',
        'level',
        'description',
    ];

    public function supervisor(): BelongsTo
    {
        return $this->belongsTo(Supervisor::class);
    }
    public function applications(): HasMany
    {
        return $this->hasMany(OfferApplication::class);
    }
}
