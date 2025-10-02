<?php

namespace App\Filament\Resources\Students\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class StudentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('session')
                    ->required(),
                Select::make('class')
                    ->options([
            'play' => 'Play',
            'nursery' => 'Nursery',
            'first' => 'First',
            'second' => 'Second',
            'third' => 'Third',
            'fourth' => 'Fourth',
            'nazira' => 'Nazira',
            'hifzul_quran' => 'Hifzul quran',
        ])
                    ->required(),
                Select::make('student_type')
                    ->options(['new' => 'New', 'old' => 'Old'])
                    ->required(),
                Select::make('gender')
                    ->options(['male' => 'Male', 'female' => 'Female'])
                    ->required(),
                Select::make('residence_status')
                    ->options(['resident' => 'Resident', 'non-resident' => 'Non resident'])
                    ->required(),
                TextInput::make('name')
                    ->required(),
                TextInput::make('father_name')
                    ->required(),
                TextInput::make('mother_name')
                    ->required(),
                DatePicker::make('date_of_birth')
                    ->required(),
                TextInput::make('nid_birth_no')
                    ->default(null),
                TextInput::make('nationality')
                    ->required()
                    ->default('Bangladeshi'),
                TextInput::make('blood_group')
                    ->default(null),
                TextInput::make('guardian_phone')
                    ->tel()
                    ->required(),
                TextInput::make('sms_number')
                    ->required(),
            ]);
    }
}
