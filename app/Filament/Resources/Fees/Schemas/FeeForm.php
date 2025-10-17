<?php

namespace App\Filament\Resources\Fees\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class FeeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('student_id')
                    ->required()
                    ->numeric()
                    ->rules(['exists:students,student_id'])
                    ->validationMessages([
                        'exists' => 'Student not found with this ID.',
                    ]),
                TextInput::make('admit_form_fee')
                    ->required()
                    ->numeric()
                    ->default(100.0),
                TextInput::make('id_card')
                    ->required()
                    ->numeric()
                    ->default(150.0),
                TextInput::make('admission_fee')
                    ->required()
                    ->numeric()
                    ->default(2000.0),
            ]);
    }
}
