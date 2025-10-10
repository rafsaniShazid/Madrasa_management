<?php

namespace App\Filament\Resources\Exams\Schemas;

use App\Models\SchoolClass;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ExamForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Exam Name')
                    ->placeholder('e.g., First Term Exam 2024')
                    ->required()
                    ->maxLength(255),
                    
                Select::make('class_id')
                    ->label('Class')
                    ->options(SchoolClass::all()->pluck('name', 'id'))
                    ->required()
                    ->searchable()
                    ->preload()
                    ->native(false),
                    
                TextInput::make('session')
                    ->label('Academic Session')
                    ->placeholder('e.g., 2024-2025')
                    ->required()
                    ->maxLength(50),
                    
                DatePicker::make('exam_date')
                    ->label('Exam Date')
                    ->required()
                    ->native(false),
                    
                Select::make('type')
                    ->label('Exam Type')
                    ->options([
                        'first_term' => 'First Term', 
                        'second_term' => 'Second Term', 
                        'final' => 'Final'
                    ])
                    ->required()
                    ->native(false),
                    
                Select::make('status')
                    ->label('Status')
                    ->options([
                        'scheduled' => 'Scheduled',
                        'ongoing' => 'Ongoing',
                        'completed' => 'Completed',
                        'cancelled' => 'Cancelled',
                    ])
                    ->default('scheduled')
                    ->required()
                    ->native(false),
                    
                Textarea::make('description')
                    ->label('Description')
                    ->placeholder('Optional exam description...')
                    ->rows(3)
                    ->columnSpanFull(),
            ]);
    }
}
