<?php

namespace App\Filament\Resources\SchoolClasses\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SchoolClassForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Class Name')
                    ->placeholder('e.g., Play, Nursery, First, Second, etc.')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(50)
                    ->helperText('The name of the class as it will appear throughout the system')
                    ->columnSpanFull(),
            ]);
    }
}
