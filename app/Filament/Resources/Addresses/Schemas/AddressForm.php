<?php

namespace App\Filament\Resources\Addresses\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class AddressForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('student_id')
                    ->relationship('student', 'name')
                    ->required(),
                Select::make('address_type')
                    ->options(['permanent' => 'Permanent', 'present' => 'Present'])
                    ->required(),
                TextInput::make('village')
                    ->required(),
                TextInput::make('post_office')
                    ->required(),
                TextInput::make('thana')
                    ->required(),
                TextInput::make('district')
                    ->required(),
            ]);
    }
}
