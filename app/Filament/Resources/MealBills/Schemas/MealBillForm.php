<?php

namespace App\Filament\Resources\MealBills\Schemas;

use App\Models\Student;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class MealBillForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('student_id')
                    ->label('Student')
                    ->options(Student::all()->pluck('name', 'student_id'))
                    ->required()
                    ->searchable(),

                DatePicker::make('month')
                    ->label('Month')
                    ->displayFormat('Y-m')
                    ->format('Y-m')
                    ->required()
                    ->default(now()->format('Y-m')),

                TextInput::make('total_amount')
                    ->label('Total Amount')
                    ->numeric()
                    ->prefix('৳')
                    ->required()
                    ->minValue(0)
                    ->step(0.01),

                TextInput::make('discount')
                    ->label('Discount')
                    ->numeric()
                    ->prefix('৳')
                    ->default(0)
                    ->minValue(0)
                    ->step(0.01),

                TextInput::make('paid_amount')
                    ->label('Paid Amount')
                    ->numeric()
                    ->prefix('৳')
                    ->default(0)
                    ->minValue(0)
                    ->step(0.01),
            ]);
    }
}
