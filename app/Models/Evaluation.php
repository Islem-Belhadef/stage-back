<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Evaluation extends Model
{
    use HasFactory;

    protected $fillable = [
        'internship_id' ,
        'discipline' ,
        'aptitude' ,
        'initiative' ,
        'innovation' ,
        'acquired_knowledge',
        'global_appreciation'

    ];


    public function internship(): BelongsTo
    {
        return $this->belongsTo(Internship::class);
    }
}
