<?php

namespace App\Filament\Resources\MealBills;

use App\Filament\Resources\MealBills\Pages\CreateMealBill;
use App\Filament\Resources\MealBills\Pages\EditMealBill;
use App\Filament\Resources\MealBills\Pages\ListMealBills;
use App\Filament\Resources\MealBills\Schemas\MealBillForm;
use App\Filament\Resources\MealBills\Tables\MealBillsTable;
use App\Models\MealBill;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class MealBillResource extends Resource
{
    protected static ?string $model = MealBill::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $navigationLabel = 'Meal Bills';

    protected static ?string $modelLabel = 'Meal Bill';

    protected static ?string $pluralModelLabel = 'Meal Bills';

    protected static string|UnitEnum|null $navigationGroup = 'Meal Management';

    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return MealBillForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MealBillsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMealBills::route('/'),
            'create' => CreateMealBill::route('/create'),
            'edit' => EditMealBill::route('/{record}/edit'),
        ];
    }
}
