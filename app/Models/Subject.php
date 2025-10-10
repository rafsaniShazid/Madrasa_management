<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

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
    public function schoolClasses(): BelongsToMany
    {
        return $this->belongsToMany(SchoolClass::class, 'class_subject', 'subject_id', 'class_id')
            ->withPivot('is_active')
            ->withTimestamps();
    }

    // Backward compatibility - keep old relationship name
    public function classes()
    {
        return $this->schoolClasses();
    }

    // Scope for active subjects
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Get subjects for a specific class
    public function scopeForClass($query, $classId)
    {
        return $query->whereHas('schoolClasses', function($q) use ($classId) {
            $q->where('class_id', $classId)->where('is_active', true);
        });
    }

    // Get pass percentage
    public function getPassPercentageAttribute(): float
    {
        return ($this->pass_marks / $this->total_marks) * 100;
    }

    // Accessor for title (used by Filament)
    public function getTitleAttribute(): string
    {
        return $this->name;
    }
}
