<?php

namespace App\Filament\Resources\MealRates\Pages;

use App\Filament\Resources\MealRates\MealRateResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditMealRate extends EditRecord
{
    protected static string $resource = MealRateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
