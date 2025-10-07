<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subject extends Model
{
    protected $fillable = [
        'name',
        'code',
        'type',
        'total_marks',
        'pass_marks',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'total_marks' => 'integer',
        'pass_marks' => 'integer',
    ];

    // Relationships
    public function results(): HasMany
    {
        return $this->hasMany(Result::class);
    }

    // Many-to-many relationship with classes through pivot table
    public function classes()
    {
        return $this->hasMany(ClassSubject::class);
    }

    // Scope for active subjects
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Get subjects for a specific class
    public function scopeForClass($query, $class)
    {
        return $query->whereHas('classes', function($q) use ($class) {
            $q->where('class', $class)->where('is_active', true);
        });
    }

    // Get pass percentage
    public function getPassPercentageAttribute(): float
    {
        return ($this->pass_marks / $this->total_marks) * 100;
    }
}
