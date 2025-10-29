<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MealBill extends Model
{
    protected $fillable = [
        'student_id',
        'month',
        'total_amount',
        'discount',
        'paid_amount',
        'paid_status',
        'due_amount',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'discount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'due_amount' => 'decimal:2',
    ];

    // Relationships
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id', 'student_id');
    }

    // Helper method to calculate due amount
    public function getCalculatedDueAmountAttribute(): float
    {
        return $this->total_amount - $this->discount - $this->paid_amount;
    }

    // Auto-update due_amount when model is saved
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($mealBill) {
            $mealBill->due_amount = $mealBill->total_amount - $mealBill->discount - $mealBill->paid_amount;

            // Auto-update payment status based on amounts
            if ($mealBill->due_amount <= 0) {
                $mealBill->paid_status = 'paid';
            } elseif ($mealBill->paid_amount > 0) {
                $mealBill->paid_status = 'partial';
            } else {
                $mealBill->paid_status = 'unpaid';
            }
        });
    }

    // Scope for getting bills by month
    public function scopeByMonth($query, string $month)
    {
        return $query->where('month', $month);
    }

    // Scope for getting unpaid bills
    public function scopeUnpaid($query)
    {
        return $query->where('paid_status', '!=', 'paid');
    }

    // Calculate total amount from student's meals for the month
    public function calculateTotalFromMeals(): float
    {
        $startDate = $this->month . '-01';
        $endDate = date('Y-m-t', strtotime($startDate)); // Last day of month

        return $this->student->meals()
            ->whereBetween('meal_date', [$startDate, $endDate])
            ->get()
            ->sum('total_cost');
    }
}
