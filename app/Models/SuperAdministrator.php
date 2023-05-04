<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class SuperAdministrator extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'user_id'
    ];
}
