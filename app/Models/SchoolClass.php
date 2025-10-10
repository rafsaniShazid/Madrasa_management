<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class SchoolClass extends Model
{
    protected $fillable = [
        'name',
    ];

    // Relationship: A class has many students
    public function students(): HasMany
    {
        return $this->hasMany(Student::class, 'class_id');
    }

    // Relationship: A class has many exams
    public function exams(): HasMany
    {
        return $this->hasMany(Exam::class, 'class_id');
    }

    // Relationship: A class has many subjects through pivot table
    public function subjects(): BelongsToMany
    {
        return $this->belongsToMany(Subject::class, 'class_subject', 'class_id', 'subject_id')
            ->withPivot('is_active')
            ->withTimestamps();
    }

    // Accessor for title (used by Filament)
    public function getTitleAttribute(): string
    {
        return $this->name;
    }
}
