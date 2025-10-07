<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Result extends Model
{
    protected $fillable = [
        'student_id',
        'exam_id',
        'subject_id',
        'obtained_marks',
        'total_marks',
        'percentage',
        'status',
        'remarks',
    ];

    protected $casts = [
        'percentage' => 'decimal:2',
        'obtained_marks' => 'integer',
        'total_marks' => 'integer',
    ];

    // Relationships
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id', 'student_id');
    }

    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    // Scopes
    public function scopeByStudent($query, $studentId)
    {
        return $query->where('student_id', $studentId);
    }

    public function scopeByExam($query, $examId)
    {
        return $query->where('exam_id', $examId);
    }

    public function scopeBySubject($query, $subjectId)
    {
        return $query->where('subject_id', $subjectId);
    }

    public function scopePassed($query)
    {
        return $query->where('status', 'pass');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'fail');
    }

    // Helper methods
    public function isPassed(): bool
    {
        return $this->status === 'pass';
    }

    public function isFailed(): bool
    {
        return $this->status === 'fail';
    }

    // Calculate percentage
    public function calculatePercentage(): float
    {
        if ($this->total_marks <= 0) {
            return 0;
        }
        return round(($this->obtained_marks / $this->total_marks) * 100, 2);
    }

    // Calculate pass/fail status
    public function calculateStatus(): string
    {
        $percentage = $this->percentage ?? $this->calculatePercentage();
        return $percentage >= 40 ? 'pass' : 'fail'; // 40% passing criteria
    }



    // Automatically calculate and save percentage and status
    public function updateCalculatedFields(): void
    {
        $this->percentage = $this->calculatePercentage();
        $this->status = $this->calculateStatus();
        $this->save();
    }
}
