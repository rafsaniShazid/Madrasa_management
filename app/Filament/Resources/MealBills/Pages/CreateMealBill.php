<?php

namespace App\Filament\Resources\MealBills\Pages;

use App\Filament\Resources\MealBills\MealBillResource;
use App\Models\Meal;
use App\Models\MealRate;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateMealBill extends CreateRecord
{
    protected static string $resource = MealBillResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Calculate meal bill automatically based on student's meals for the month
        $studentId = $data['student_id'];
        $month = $data['month'];
        
        // Get all meals for this student in this month
        $meals = Meal::where('student_id', $studentId)
            ->whereYear('meal_date', substr($month, 0, 4))
            ->whereMonth('meal_date', substr($month, 5, 2))
            ->get();
        
        // Calculate total based on meal types
        $totalAmount = 0;
        foreach ($meals as $meal) {
            if ($meal->breakfast) {
                $totalAmount += MealRate::getCurrentRate('breakfast');
            }
            if ($meal->lunch) {
                $totalAmount += MealRate::getCurrentRate('lunch');
            }
            if ($meal->dinner) {
                $totalAmount += MealRate::getCurrentRate('dinner');
            }
        }
        
        // Set calculated values
        $data['total_amount'] = $totalAmount;
        $data['discount'] = 0;
        $data['paid_amount'] = 0;
        $data['due_amount'] = $totalAmount;
        $data['paid_status'] = $totalAmount > 0 ? 'unpaid' : 'paid';
        
        // Show notification about calculation
        Notification::make()
            ->title('Meal Bill Calculated')
            ->body("Total amount: ৳{$totalAmount} based on {$meals->count()} meal entries")
            ->success()
            ->send();
        
        return $data;
    }
}
