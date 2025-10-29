<?php

namespace App\Filament\Resources\MealBills\Pages;

use App\Filament\Resources\MealBills\MealBillResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditMealBill extends EditRecord
{
    protected static string $resource = MealBillResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
