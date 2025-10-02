<?php

namespace App\Filament\Resources\Students\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class StudentInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name')
                    ->label('Full Name')
                    ->size('lg')
                    ->weight('bold')
                    ->color('primary'),
                TextEntry::make('student_id')
                    ->label('Student ID')
                    ->badge()
                    ->color('success'),
                TextEntry::make('class')
                    ->badge()
                    ->color('info'),
                TextEntry::make('session')
                    ->badge(),
                TextEntry::make('student_type')
                    ->label('Student Type')
                    ->badge()
                    ->formatStateUsing(fn (?string $state): string => $state ? ucfirst(str_replace('_', ' ', $state)) : 'N/A'),
                TextEntry::make('gender')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'male' => 'blue',
                        'female' => 'pink',
                        default => 'gray',
                    }),
                TextEntry::make('residence_status')
                    ->label('Residence Status')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'residential' => 'success',
                        'day_scholar' => 'warning',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (?string $state): string => $state ? ucfirst(str_replace('_', ' ', $state)) : 'N/A'),
                TextEntry::make('father_name')
                    ->label('Father\'s Name'),
                TextEntry::make('mother_name')
                    ->label('Mother\'s Name'),
                TextEntry::make('date_of_birth')
                    ->label('Date of Birth')
                    ->date('d M Y'),
                TextEntry::make('nationality'),
                TextEntry::make('blood_group')
                    ->label('Blood Group')
                    ->badge()
                    ->color('danger'),
                TextEntry::make('nid_birth_no')
                    ->label('NID/Birth Certificate'),
                TextEntry::make('guardian_phone')
                    ->label('Guardian Phone'),
                TextEntry::make('sms_number')
                    ->label('SMS Number'),
                TextEntry::make('created_at')
                    ->label('Created At')
                    ->dateTime('d M Y, h:i A'),
                TextEntry::make('updated_at')
                    ->label('Last Updated')
                    ->dateTime('d M Y, h:i A')
                    ->since(),
            ]);
    }
}
