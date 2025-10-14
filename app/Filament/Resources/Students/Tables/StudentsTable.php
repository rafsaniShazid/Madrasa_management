<?php

namespace App\Filament\Resources\Students\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class StudentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Full Name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->size('lg'),
                TextColumn::make('student_id')
                    ->label('Student ID')
                    ->searchable()
                    ->badge()
                    ->color('success'),
                TextColumn::make('class')
                    ->badge()
                    ->color('info'),
                TextColumn::make('gender')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'male' => 'blue',
                        'female' => 'pink',
                        default => 'gray',
                    }),
                TextColumn::make('student_type')
                    ->label('Type')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => ucfirst(str_replace('_', ' ', $state))),
                TextColumn::make('residence_status')
                    ->label('Residence')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'residential' => 'success',
                        'day_scholar' => 'warning',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => ucfirst(str_replace('_', ' ', $state))),
                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->sortable(),
                TextColumn::make('session')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('father_name')
                    ->label('Father\'s Name')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('date_of_birth')
                    ->label('Date of Birth')
                    ->date('d M Y')
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('guardian_phone')
                    ->label('Guardian Phone')
                    ->searchable()
                    ->toggleable()
                    ->copyable(),
                TextColumn::make('mother_name')
                    ->label('Mother\'s Name')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('blood_group')
                    ->label('Blood Group')
                    ->badge()
                    ->color('danger')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('nationality')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('nid_birth_no')
                    ->label('NID/Birth No')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('sms_number')
                    ->label('SMS Number')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label('Enrolled At')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Last Updated')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TernaryFilter::make('is_active')
                    ->label('Active Status')
                    ->placeholder('All students')
                    ->trueLabel('Active students only')
                    ->falseLabel('Inactive students only')
                    ->default(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
