<?php

namespace App\Filament\Pages;

use App\Models\MealBill;
use BackedEnum;
use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Components\Section;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class MonthlyBilling extends Page implements HasForms, HasTable
{
    use InteractsWithForms, InteractsWithTable;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-document-chart-bar';

    protected static ?string $navigationLabel = 'Monthly Billing';

    protected static string|UnitEnum|null $navigationGroup = 'Meal Management';

    protected static ?int $navigationSort = 4;

    protected string $view = 'filament.pages.monthly-billing';

    public ?string $selectedMonth = null;

    public function mount(): void
    {
        $this->selectedMonth = now()->format('Y-m');
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Select Month')
                    ->schema([
                        DatePicker::make('selectedMonth')
                            ->label('Billing Month')
                            ->displayFormat('M Y')
                            ->format('Y-m')
                            ->default(now()->format('Y-m'))
                            ->required(),
                    ])
                    ->compact(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(function () {
                $month = $this->selectedMonth ?? now()->format('Y-m');
                return MealBill::query()->where('month', $month);
            })
            ->columns([
                TextColumn::make('student.name')
                    ->label('Student')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('student.class.name')
                    ->label('Class')
                    ->sortable(),

                TextColumn::make('total_amount')
                    ->label('Total Amount')
                    ->money('BDT')
                    ->sortable(),

                TextColumn::make('discount')
                    ->label('Discount')
                    ->money('BDT')
                    ->sortable(),

                TextColumn::make('paid_amount')
                    ->label('Paid Amount')
                    ->money('BDT')
                    ->sortable(),

                TextColumn::make('due_amount')
                    ->label('Due Amount')
                    ->money('BDT')
                    ->sortable(),

                TextColumn::make('paid_status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'paid' => 'success',
                        'partial' => 'warning',
                        'unpaid' => 'danger',
                    }),
            ])
            ->defaultSort('student.name')
            ->emptyStateHeading('No meal bills found for this month')
            ->emptyStateDescription('Select a different month or create meal bills first.');
    }

    public function updatedSelectedMonth(): void
    {
        $this->resetTable();
    }

    protected function getHeaderActions(): array
    {
        return [
            // Could add export actions here in the future
        ];
    }
}
