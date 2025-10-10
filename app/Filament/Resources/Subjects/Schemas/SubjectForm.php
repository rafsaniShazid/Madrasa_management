<?php

namespace App\Filament\Resources\Subjects\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class SubjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('code')
                    ->required(),
                Select::make('type')
                    ->options(['theory' => 'Theory', 'practical' => 'Practical'])
                    ->default('theory')
                    ->required(),
                TextInput::make('total_marks')
                    ->required()
                    ->numeric()
                    ->default(100),
                TextInput::make('pass_marks')
                    ->required()
                    ->numeric()
                    ->default(35),
                Toggle::make('is_active')
                    ->required(),
            ]);
    }
}
