<?php

namespace App\Filament\Pages;

use App\Models\MealBill;
use App\Models\Student;
use App\Models\Meal;
use App\Models\MealRate;
use BackedEnum;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
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
    public ?string $selectedStudent = null;

    public function mount(): void
    {
        $this->selectedMonth = now()->format('Y-m');
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Filter Options')
                    ->description('Filter meal bills by month and optionally by specific student')
                    ->schema([
                        DatePicker::make('selectedMonth')
                            ->label('Billing Month & Year')
                            ->displayFormat('F Y')
                            ->format('Y-m')
                            ->default(now()->format('Y-m'))
                            ->required()
                            ->columnSpan(1),
                            
                        Select::make('selectedStudent')
                            ->label('Select Student (Optional)')
                            ->options(['' => 'All Students'] + Student::all()->mapWithKeys(function ($student) {
                                return [$student->student_id => "ID: {$student->student_id} - {$student->name}"];
                            })->toArray())
                            ->searchable()
                            ->placeholder('All Students')
                            ->columnSpan(1),
                    ])
                    ->columns(2)
                    ->compact(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(function () {
                $month = $this->selectedMonth ?? now()->format('Y-m');
                $query = MealBill::query()->where('month', $month);
                
                if ($this->selectedStudent) {
                    $query->where('student_id', $this->selectedStudent);
                }
                
                return $query;
            })
            ->columns([
                TextColumn::make('student_id')
                    ->label('Student ID')
                    ->sortable()
                    ->searchable(),

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
            ->emptyStateHeading('No meal bills found')
            ->emptyStateDescription('No meal bills found for the selected criteria. Try changing the month or student filter.')
            ->actions([
                EditAction::make()
                    ->label('Edit')
                    ->icon('heroicon-o-pencil')
                    ->modalHeading('Edit Meal Bill')
                    ->modalDescription('Update the meal bill details for this student')
                    ->modalSubmitActionLabel('Update Bill')
                    ->modalWidth('md')
                    ->form([
                        TextInput::make('student_info')
                            ->label('Student')
                            ->formatStateUsing(fn ($record) => "ID: {$record->student_id} - {$record->student->name}")
                            ->disabled()
                            ->columnSpan(2),
                            
                        TextInput::make('total_amount')
                            ->label('Total Amount')
                            ->numeric()
                            ->prefix('৳')
                            ->required()
                            ->inputMode('decimal')
                            ->columnSpan(1),
                            
                        TextInput::make('discount')
                            ->label('Discount')
                            ->numeric()
                            ->prefix('৳')
                            ->default(0)
                            ->inputMode('decimal')
                            ->columnSpan(1),
                            
                        TextInput::make('paid_amount')
                            ->label('Paid Amount')
                            ->numeric()
                            ->prefix('৳')
                            ->default(0)
                            ->inputMode('decimal')
                            ->columnSpan(1),
                            
                        Select::make('paid_status')
                            ->label('Payment Status')
                            ->options([
                                'unpaid' => 'Unpaid',
                                'partial' => 'Partial',
                                'paid' => 'Paid',
                            ])
                            ->required()
                            ->columnSpan(1),
                    ])
                    ->action(function (array $data, $record) {
                        // Calculate due amount
                        $dueAmount = $data['total_amount'] - $data['discount'] - $data['paid_amount'];
                        
                        // Update the record
                        $record->update([
                            'total_amount' => $data['total_amount'],
                            'discount' => $data['discount'],
                            'paid_amount' => $data['paid_amount'],
                            'paid_status' => $data['paid_status'],
                            'due_amount' => $dueAmount,
                        ]);
                        
                        Notification::make()
                            ->title('Bill Updated Successfully')
                            ->body("Updated meal bill for {$record->student->name} - Due: ৳" . number_format($dueAmount, 2))
                            ->success()
                            ->send();
                    }),
            ]);
    }

    public function updatedSelectedMonth(): void
    {
        $this->resetTable();
    }

    public function updatedSelectedStudent(): void
    {
        $this->resetTable();
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('generateBills')
                ->label('🧮 Generate Bills')
                ->color('success')
                ->icon('heroicon-o-calculator')
                ->requiresConfirmation()
                ->modalHeading('Generate Monthly Bills')
                ->modalDescription('This will automatically calculate and generate meal bills for all students based on their actual meal consumption for the selected month.')
                ->modalSubmitActionLabel('Generate Bills')
                ->action(function () {
                    $this->generateMonthlyBills();
                }),
        ];
    }

    public function generateMonthlyBills(): void
    {
        if (!$this->selectedMonth) {
            Notification::make()
                ->title('Error')
                ->body('Please select a month first.')
                ->danger()
                ->send();
            return;
        }

        $month = $this->selectedMonth;
        $year = substr($month, 0, 4);
        $monthNum = substr($month, 5, 2);

        // Get all students who have meal records for this month
        $studentsWithMeals = Meal::whereYear('meal_date', $year)
            ->whereMonth('meal_date', $monthNum)
            ->select('student_id')
            ->distinct()
            ->get()
            ->pluck('student_id');

        if ($studentsWithMeals->isEmpty()) {
            Notification::make()
                ->title('No Meal Data Found')
                ->body('No meal records found for the selected month. Please add meal entries first.')
                ->warning()
                ->send();
            return;
        }

        $createdCount = 0;
        $updatedCount = 0;
        $errors = [];

        foreach ($studentsWithMeals as $studentId) {
            try {
                // Get all meals for this student in this month
                $meals = Meal::where('student_id', $studentId)
                    ->whereYear('meal_date', $year)
                    ->whereMonth('meal_date', $monthNum)
                    ->get();

                // Calculate total amount based on actual consumption
                $totalAmount = 0;
                foreach ($meals as $meal) {
                    if ($meal->breakfast) {
                        $totalAmount += MealRate::getRateForDate('breakfast', $meal->meal_date);
                    }
                    if ($meal->lunch) {
                        $totalAmount += MealRate::getRateForDate('lunch', $meal->meal_date);
                    }
                    if ($meal->dinner) {
                        $totalAmount += MealRate::getRateForDate('dinner', $meal->meal_date);
                    }
                }

                // Create or update meal bill
                $existingBill = MealBill::where('student_id', $studentId)
                    ->where('month', $month)
                    ->first();

                if ($existingBill) {
                    // Update existing bill
                    $existingBill->update([
                        'total_amount' => $totalAmount,
                        'due_amount' => $totalAmount - $existingBill->discount - $existingBill->paid_amount,
                    ]);
                    $updatedCount++;
                } else {
                    // Create new bill
                    MealBill::create([
                        'student_id' => $studentId,
                        'month' => $month,
                        'total_amount' => $totalAmount,
                        'discount' => 0,
                        'paid_amount' => 0,
                        'paid_status' => 'unpaid',
                        'due_amount' => $totalAmount,
                    ]);
                    $createdCount++;
                }
            } catch (\Exception $e) {
                $student = Student::where('student_id', $studentId)->first();
                $studentName = $student ? $student->name : "ID: {$studentId}";
                $errors[] = "Failed for {$studentName}: " . $e->getMessage();
            }
        }

        // Show success notification
        $message = "✅ Bill generation completed for " . date('F Y', strtotime($month . '-01'));
        $message .= "\n📊 Created: {$createdCount} new bills";
        $message .= "\n📝 Updated: {$updatedCount} existing bills";
        $message .= "\n👥 Total students: " . ($createdCount + $updatedCount);

        if (!empty($errors)) {
            $message .= "\n⚠️ " . count($errors) . " errors occurred";
        }

        Notification::make()
            ->title('Bill Generation Complete')
            ->body($message)
            ->success()
            ->duration(8000)
            ->send();

        // Show errors if any
        if (!empty($errors)) {
            foreach ($errors as $error) {
                Notification::make()
                    ->title('Error')
                    ->body($error)
                    ->danger()
                    ->send();
            }
        }

        // Refresh the table to show new bills
        $this->resetTable();
    }
}
