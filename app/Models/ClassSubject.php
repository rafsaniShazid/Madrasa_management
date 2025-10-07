<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClassSubject extends Model
{
    protected $table = 'class_subject';
    
    protected $fillable = [
        'class',
        'subject_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relationships
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForClass($query, $class)
    {
        return $query->where('class', $class);
    }

    // Get subjects for a specific class
    public static function getSubjectsForClass($class)
    {
        return static::where('class', $class)
            ->where('is_active', true)
            ->with('subject')
            ->get();
    }
}
