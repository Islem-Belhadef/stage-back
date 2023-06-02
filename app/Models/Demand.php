<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Demand extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'start_date',
        'end_date',
        'duration',
        // 'supervisor_email',
        'company',
        'date',
        'title',
        'student_id',
        'supervisor_id',
        'motivational_letter'
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}
