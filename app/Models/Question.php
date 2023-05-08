<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'first_name',
        'last_nam',
        'email',
        'subject',
        'content',
        'answer',
        'frequent'
    ];

    use HasFactory;
}
