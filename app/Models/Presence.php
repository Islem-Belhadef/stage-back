<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Presence extends Model
{
    use HasFactory;

    protected $fillable = [
        'internship_id',
        'presence',
    ];

    public function internship(): BelongsTo
    {
        return $this->belongsTo(Internship::class);
    }
}
