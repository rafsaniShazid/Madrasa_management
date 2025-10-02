<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Address extends Model
{
    protected $primaryKey = 'address_id';
    
    protected $fillable = [
        'student_id',
        'address_type',
        'village',
        'post_office',
        'thana',
        'district',
    ];

    protected $casts = [
        'address_type' => 'string',
    ];

    // Relationships
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id', 'student_id');
    }

    // Accessor for full address
    public function getFullAddressAttribute(): string
    {
        return "{$this->village}, {$this->post_office}, {$this->thana}, {$this->district}";
    }
}
