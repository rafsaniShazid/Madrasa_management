<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Meal extends Model
{
    protected $fillable = [
        'student_id',
        'class_id',
        'meal_date',
        'breakfast',
        'lunch',
        'dinner',
    ];

    protected $casts = [
        'meal_date' => 'date',
        'breakfast' => 'boolean',
        'lunch' => 'boolean',
        'dinner' => 'boolean',
    ];

    // Relationships
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id', 'student_id');
    }

    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    // Helper methods to get rates for each meal type
    public function getBreakfastRateAttribute(): float
    {
        return MealRate::getRateForDate('breakfast', $this->meal_date);
    }

    public function getLunchRateAttribute(): float
    {
        return MealRate::getRateForDate('lunch', $this->meal_date);
    }

    public function getDinnerRateAttribute(): float
    {
        return MealRate::getRateForDate('dinner', $this->meal_date);
    }

    // Calculate total cost for this meal record
    public function getTotalCostAttribute(): float
    {
        $total = 0;
        if ($this->breakfast) $total += $this->breakfast_rate;
        if ($this->lunch) $total += $this->lunch_rate;
        if ($this->dinner) $total += $this->dinner_rate;
        return $total;
    }

    // Helper method to get total meals for the day
    public function getTotalMealsAttribute(): int
    {
        return (int)$this->breakfast + (int)$this->lunch + (int)$this->dinner;
    }
}
