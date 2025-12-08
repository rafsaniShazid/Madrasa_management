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
                    ->options(Student::all()->mapWithKeys(function ($student) {
                        return [$student->student_id => "ID: {$student->student_id} - {$student->name}"];
                    }))
                    ->required()
                    ->searchable()
                    ->placeholder('Search by Student ID or Name'),

                DatePicker::make('month')
                    ->label('Month')
                    ->displayFormat('M Y')
                    ->format('Y-m')
                    ->required()
                    ->default(now()->format('Y-m'))
                    ->helperText('Meal bill will be created for this month'),
            ]);
    }
}
