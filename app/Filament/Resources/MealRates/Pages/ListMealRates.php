<?php

namespace App\Filament\Resources\MealRates\Pages;

use App\Filament\Resources\MealRates\MealRateResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListMealRates extends ListRecords
{
    protected static string $resource = MealRateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
