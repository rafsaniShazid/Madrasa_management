<?php

namespace App\Filament\Resources\MealRates\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class MealRateForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('meal_type')
                    ->options([
                        'breakfast' => 'Breakfast',
                        'lunch' => 'Lunch',
                        'dinner' => 'Dinner',
                    ])
                    ->required()
                    ->label('Meal Type'),

                TextInput::make('rate')
                    ->numeric()
                    ->required()
                    ->minValue(0)
                    ->step(0.01)
                    ->prefix('৳')
                    ->label('Rate per Meal'),

                DatePicker::make('effective_from')
                    ->required()
                    ->default(now()->toDateString())
                    ->label('Effective From'),
            ]);
    }
}
