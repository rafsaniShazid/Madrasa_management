<?php

namespace App\Filament\Resources\Fees\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class FeeInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('student.name')
                    ->numeric(),
                TextEntry::make('admit_form_fee')
                    ->numeric(),
                TextEntry::make('id_card')
                    ->numeric(),
                TextEntry::make('admission_fee')
                    ->numeric(),
                TextEntry::make('created_at')
                    ->dateTime(),
                TextEntry::make('updated_at')
                    ->dateTime(),
            ]);
    }
}
