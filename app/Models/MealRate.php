<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MealRate extends Model
{
    protected $fillable = [
        'meal_type',
        'rate',
        'effective_from',
    ];

    protected $casts = [
        'rate' => 'decimal:2',
        'effective_from' => 'date',
    ];

    // Helper method to get current rate for a meal type
    public static function getCurrentRate(string $mealType): float
    {
        return static::where('meal_type', $mealType)
            ->where('effective_from', '<=', now()->toDateString())
            ->orderBy('effective_from', 'desc')
            ->first()
            ?->rate ?? 0;
    }

    // Helper method to get rate for a specific date
    public static function getRateForDate(string $mealType, string $date): float
    {
        return static::where('meal_type', $mealType)
            ->where('effective_from', '<=', $date)
            ->orderBy('effective_from', 'desc')
            ->first()
            ?->rate ?? 0;
    }
}
