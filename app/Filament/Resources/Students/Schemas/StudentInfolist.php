<?php

namespace App\Filament\Resources\Students\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class StudentInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                
                // Basic Information Section
                Section::make('🎓 Basic Information')
                    ->description('Primary student details')
                    ->schema([
                        TextEntry::make('name')
                            ->label('Full Name')
                            ->size('lg')
                            ->weight('bold')
                            ->color('primary')
                            ->icon('heroicon-o-user')
                            ->copyable()
                            ->placeholder('No name provided'),
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
                        TextEntry::make('residence_status')
                            ->label('Residence Status')
                            ->badge()
                            ->color(fn (?string $state): string => match ($state) {
                                'resident' => 'success',
                                'non-resident' => 'warning',
                                default => 'gray',
                            })
                            ->formatStateUsing(fn (?string $state): string => match ($state) {
                                'resident' => 'Residential',
                                'non-resident' => 'Day Scholar',
                                default => 'N/A',
                            }),
                    ])
                    ->columns(2),
                
                // Personal Information Section
                Section::make('👤 Personal Information')
                    ->description('Family and personal details')
                    ->schema([
                        TextEntry::make('father_name')
                            ->label('Father\'s Name')
                            ->icon('heroicon-o-user-group')
                            ->color('blue'),
                        TextEntry::make('mother_name')
                            ->label('Mother\'s Name')
                            ->icon('heroicon-o-heart')
                            ->color('pink'),
                        TextEntry::make('gender')
                            ->badge()
                            ->color(fn (?string $state): string => match ($state) {
                                'male' => 'blue',
                                'female' => 'pink',
                                default => 'gray',
                            })
                            ->icon(fn (?string $state): string => match ($state) {
                                'male' => 'heroicon-o-user',
                                'female' => 'heroicon-o-user',
                                default => 'heroicon-o-question-mark-circle',
                            }),
                        TextEntry::make('date_of_birth')
                            ->label('Date of Birth')
                            ->date('d M Y')
                            ->icon('heroicon-o-cake'),
                        TextEntry::make('nationality')
                            ->icon('heroicon-o-flag'),
                        TextEntry::make('blood_group')
                            ->label('Blood Group')
                            ->badge()
                            ->color('danger')
                            ->icon('heroicon-o-heart'),
                        TextEntry::make('nid_birth_no')
                            ->label('NID/Birth Certificate')
                            ->icon('heroicon-o-identification')
                            ->copyable(),
                    ])
                    ->columns(2),
                
                // Contact Information Section
                Section::make('📞 Contact Information')
                    ->description('Phone numbers and communication details')
                    ->schema([
                        TextEntry::make('guardian_phone')
                            ->label('Guardian Phone')
                            ->icon('heroicon-o-phone')
                            ->color('success')
                            ->copyable(),
                        TextEntry::make('sms_number')
                            ->label('SMS Number')
                            ->icon('heroicon-o-chat-bubble-left-right')
                            ->color('info')
                            ->copyable(),
                    ])
                    ->columns(2),
                
                // Address Information Section
                Section::make('🏠 Address Information')
                    ->description('Present and permanent address details')
                    ->schema([
                        // Present Address Information
                        TextEntry::make('present_address')
                            ->label('Present Address')
                            ->formatStateUsing(function ($record) {
                                $presentAddress = $record->addresses->where('address_type', 'present')->first();
                                if ($presentAddress) {
                                    return $presentAddress->full_address;
                                }
                                return 'Not provided';
                            })
                            ->icon('heroicon-o-map-pin')
                            ->color(fn ($state) => $state === 'Not provided' ? 'danger' : 'success')
                            ->weight('bold')
                            ->copyable(),
                        
                        
                        // Permanent Address Information
                        TextEntry::make('permanent_address')
                            ->label('Permanent Address')
                            ->formatStateUsing(function ($record) {
                                $permanentAddress = $record->addresses->where('address_type', 'permanent')->first();
                                if ($permanentAddress) {
                                    return $permanentAddress->full_address;
                                }
                                return 'Not provided';
                            })
                            ->icon('heroicon-o-home')
                            ->color(fn ($state) => $state === 'Not provided' ? 'danger' : 'warning')
                            ->weight('bold')
                            ->copyable(),
                        

                    ])
                    ->columns(1),
                
                // System Information Section
                Section::make('⚙️ System Information')
                    ->description('Record timestamps and system data')
                    ->schema([
                        TextEntry::make('created_at')
                            ->label('Created At')
                            ->dateTime('d M Y, h:i A')
                            ->icon('heroicon-o-plus-circle')
                            ->color('success'),
                        TextEntry::make('updated_at')
                            ->label('Last Updated')
                            ->dateTime('d M Y, h:i A')
                            ->since()
                            ->icon('heroicon-o-arrow-path')
                            ->color('warning'),
                    ])
                    ->columns(2)
                    ->collapsed(), // This section starts collapsed
            ]);
    }
}
