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
                // Personal Information
                TextInput::make('name')
                    ->required()
                    ->label('Full Name'),
                
                TextInput::make('session')
                    ->required()
                    ->placeholder('e.g., 2024-2025'),
                
                Select::make('class')
                    ->options([
                        'play' => 'Play',
                        'nursery' => 'Nursery', 
                        'first' => 'First',
                        'second' => 'Second',
                        'third' => 'Third',
                        'fourth' => 'Fourth',
                        'nazira' => 'Nazira',
                        'hifzul_quran' => 'Hifzul Quran',
                    ])
                    ->required(),
                
                Select::make('student_type')
                    ->options(['new' => 'New', 'old' => 'Old'])
                    ->required(),
                
                Select::make('gender')
                    ->options(['male' => 'Male', 'female' => 'Female'])
                    ->required(),
                
                Select::make('residence_status')
                    ->options([
                        'resident' => 'Residential', 
                        'non-resident' => 'Day Scholar'
                    ])
                    ->required(),
                
                DatePicker::make('date_of_birth')
                    ->required()
                    ->label('Date of Birth'),
                
                TextInput::make('blood_group')
                    ->placeholder('e.g., A+, B-, O+')
                    ->label('Blood Group'),
                
                TextInput::make('nationality')
                    ->required()
                    ->default('Bangladeshi'),
                
                TextInput::make('nid_birth_no')
                    ->label('NID/Birth Certificate No.')
                    ->placeholder('Enter NID or Birth Certificate Number'),

                // Family Information
                TextInput::make('father_name')
                    ->required()
                    ->label('Father\'s Name'),
                
                TextInput::make('mother_name')
                    ->required()
                    ->label('Mother\'s Name'),
                
                TextInput::make('guardian_phone')
                    ->tel()
                    ->required()
                    ->label('Guardian Phone')
                    ->placeholder('+880XXXXXXXXX'),
                
                TextInput::make('sms_number')
                    ->tel()
                    ->required()
                    ->label('SMS Number')
                    ->placeholder('+880XXXXXXXXX'),

                // Present Address Fields
                TextInput::make('present_village')
                    ->required()
                    ->label('Present Address - Village/House')
                    ->placeholder('Enter village or house details'),
                
                TextInput::make('present_post_office')
                    ->required()
                    ->label('Present Address - Post Office')
                    ->placeholder('Enter post office'),
                
                TextInput::make('present_thana')
                    ->required()
                    ->label('Present Address - Thana/Upazila')
                    ->placeholder('Enter thana or upazila'),
                
                TextInput::make('present_district')
                    ->required()
                    ->label('Present Address - District')
                    ->placeholder('Enter district'),

                // Permanent Address Fields
                TextInput::make('permanent_village')
                    ->required()
                    ->label('Permanent Address - Village/House')
                    ->placeholder('Enter village or house details'),
                
                TextInput::make('permanent_post_office')
                    ->required()
                    ->label('Permanent Address - Post Office')
                    ->placeholder('Enter post office'),
                
                TextInput::make('permanent_thana')
                    ->required()
                    ->label('Permanent Address - Thana/Upazila')
                    ->placeholder('Enter thana or upazila'),
                
                TextInput::make('permanent_district')
                    ->required()
                    ->label('Permanent Address - District')
                    ->placeholder('Enter district'),
            ]);
    }
}
