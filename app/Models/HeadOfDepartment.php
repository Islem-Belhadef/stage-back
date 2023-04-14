<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class HeadOfDepartment extends Model
{
    use HasFactory;


    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }
}
