<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Exam extends Model
{
    protected $fillable = [
        'name',
        'class',
        'session',
        'exam_date',
        'type',
        'status',
        'description',
    ];

    protected $casts = [
        'exam_date' => 'date',
        'type' => 'string',
        'status' => 'string',
        'class' => 'string',
    ];

    // Relationship with results
    public function results(): HasMany
    {
        return $this->hasMany(Result::class);
    }

    // Scopes
    public function scopeByClass($query, $class)
    {
        return $query->where('class', $class);
    }

    public function scopeBySession($query, $session)
    {
        return $query->where('session', $session);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('exam_date', '>', now())
                    ->where('status', 'scheduled');
    }

    // Helper methods
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function isUpcoming(): bool
    {
        return $this->exam_date > now() && $this->status === 'scheduled';
    }

    public function isPast(): bool
    {
        return $this->exam_date < now();
    }

    // Get formatted exam date
    public function getFormattedDateAttribute(): string
    {
        return $this->exam_date->format('d M Y');
    }

    // Get exam type label
    public function getTypeLabel(): string
    {
        return match($this->type) {
            'first_term' => 'First Term Exam',
            'second_term' => 'Second Term Exam',
            'final' => 'Final Exam',
            default => ucfirst(str_replace('_', ' ', $this->type))
        };
    }

    // Get status label with color
    public function getStatusLabel(): array
    {
        return match($this->status) {
            'scheduled' => ['label' => 'Scheduled', 'color' => 'warning'],
            'ongoing' => ['label' => 'Ongoing', 'color' => 'info'],
            'completed' => ['label' => 'Completed', 'color' => 'success'],
            'cancelled' => ['label' => 'Cancelled', 'color' => 'danger'],
            default => ['label' => ucfirst($this->status), 'color' => 'gray']
        };
    }
}