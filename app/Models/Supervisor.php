<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Supervisor extends Model
{
    use HasFactory;

    public $timestamps = false;

    public function offers(): HasMany
    {
        return $this->hasMany(Offer::class);
    }

    public function internships(): HasMany
    {
        return $this->hasMany(Internship::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Internship::class);
    }
}