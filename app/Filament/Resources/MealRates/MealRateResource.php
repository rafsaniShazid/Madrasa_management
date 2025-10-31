<?php

namespace App\Filament\Resources\MealRates;

use App\Filament\Resources\MealRates\Pages\CreateMealRate;
use App\Filament\Resources\MealRates\Pages\EditMealRate;
use App\Filament\Resources\MealRates\Pages\ListMealRates;
use App\Filament\Resources\MealRates\Schemas\MealRateForm;
use App\Filament\Resources\MealRates\Tables\MealRatesTable;
use App\Models\MealRate;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class MealRateResource extends Resource
{
    protected static ?string $model = MealRate::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCurrencyBangladeshi;

    protected static ?string $recordTitleAttribute = 'meal_type';

    protected static ?string $navigationLabel = 'Meal Rates';

    protected static string|UnitEnum|null $navigationGroup = 'Meal Management';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return MealRateForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MealRatesTable::configure($table);
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
            'index' => ListMealRates::route('/'),
            'create' => CreateMealRate::route('/create'),
            'edit' => EditMealRate::route('/{record}/edit'),
        ];
    }
}
