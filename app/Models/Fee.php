<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Fee extends Model
{
    protected $fillable = [
        'student_id',
        'admit_form_fee',
        'id_card',
        'admission_fee',
    ];

    protected $casts = [
        'admit_form_fee' => 'decimal:2',
        'id_card' => 'decimal:2',
        'admission_fee' => 'decimal:2',
    ];

    // Relationships
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id', 'student_id');
    }

    // Accessor for total fees
    public function getTotalFeesAttribute(): float
    {
        return $this->admit_form_fee + $this->id_card + $this->admission_fee;
    }

    // Scope for paid fees
    public function scopePaid($query)
    {
        return $query->whereNotNull('created_at');
    }
}
