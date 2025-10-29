<?php

namespace App\Filament\Resources\MealRates\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;

class MealRatesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('meal_type')
                    ->label('Meal Type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'breakfast' => 'warning',
                        'lunch' => 'success',
                        'dinner' => 'info',
                    }),

                TextColumn::make('rate')
                    ->label('Rate')
                    ->money('BDT')
                    ->sortable(),

                TextColumn::make('effective_from')
                    ->label('Effective From')
                    ->date()
                    ->sortable(),

                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('meal_type')
                    ->options([
                        'breakfast' => 'Breakfast',
                        'lunch' => 'Lunch',
                        'dinner' => 'Dinner',
                    ]),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('effective_from', 'desc');
    }
}
