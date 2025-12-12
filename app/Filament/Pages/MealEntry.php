<?php

namespace App\Filament\Pages;

use App\Models\Meal;
use App\Models\SchoolClass;
use App\Models\Student;
use Filament\Forms;
use Filament\Schemas\Components\Section;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Schemas\Schema;
use Filament\Pages\Page;
use Filament\Notifications\Notification;
use Filament\Actions\Action;
use Filament\Support\Icons\Heroicon;
use BackedEnum;
use UnitEnum;

class MealEntry extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    protected string $view = 'filament.pages.meal-entry';

    protected static ?string $navigationLabel = 'Meal Entry';

    protected static ?string $title = 'Daily Meal Entry';

    protected static string|UnitEnum|null $navigationGroup = 'Meal Management';

    protected static ?int $navigationSort = 1;

    public ?array $data = [];
    public $selectedClass = null;
    public $selectedDate = null;
    public $students = [];
    public bool $autoBreakfast = true;
    public bool $autoLunch = true;
    public bool $autoDinner = true;

    public function mount(): void
    {
        $this->selectedDate = now()->toDateString();
        $this->form->fill([
            'meal_date' => now()->toDateString(),
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Date & Class Selection')
                    ->description('Select the date and class to enter meal data')
                    ->schema([
                        Forms\Components\DatePicker::make('meal_date')
                            ->label('Meal Date')
                            ->default(now()->toDateString())
                            ->required()
                            ->live()
                            ->afterStateUpdated(function ($state) {
                                $this->selectedDate = $state;
                                $this->loadStudents();
                                // Force refresh of cooking count fields
                                $this->form->fill($this->form->getState());
                            })
                            ->columnSpan(1),

                        Forms\Components\Select::make('class_id')
                            ->label('Select Class')
                            ->options(SchoolClass::all()->pluck('name', 'id'))
                            ->required()
                            ->live()
                            ->afterStateUpdated(function ($state, callable $set) {
                                $this->selectedClass = $state;
                                $this->loadStudents();
                                // Force refresh of cooking count fields
                                $this->form->fill($this->form->getState());
                            })
                            ->columnSpan(1),
                    ])
                    ->columns(2)
                    ->collapsible(),

                Section::make('🚀 Auto Meal Settings')
                    ->description('Configure default meals for auto-creation of all students')
                    ->schema([
                        Forms\Components\Toggle::make('autoBreakfast')
                            ->label('Include Breakfast')
                            ->default(true)
                            ->inline(false)
                            ->columnSpan(1),
                            
                        Forms\Components\Toggle::make('autoLunch')
                            ->label('Include Lunch')
                            ->default(true)
                            ->inline(false)
                            ->columnSpan(1),
                            
                        Forms\Components\Toggle::make('autoDinner')
                            ->label('Include Dinner')
                            ->default(true)
                            ->inline(false)
                            ->columnSpan(1),
                    ])
                    ->columns(3)
                    ->collapsible()
                    ->collapsed(),

                Section::make('🍽️ Cooking Information')
                    ->description('Number of students to cook for today')
                    ->schema([
                        Forms\Components\TextInput::make('breakfast_count')
                            ->label('🌅 Breakfast')
                            ->formatStateUsing(function () {
                                $count = $this->getBreakfastCount();
                                $context = $this->selectedClass ? 'in this class' : 'total';
                                return "Cook for {$count} students ({$context})";
                            })
                            ->disabled()
                            ->dehydrated(false)
                            ->live()
                            ->columnSpan(1),
                            
                        Forms\Components\TextInput::make('lunch_count')
                            ->label('🌞 Lunch')
                            ->formatStateUsing(function () {
                                $count = $this->getLunchCount();
                                $context = $this->selectedClass ? 'in this class' : 'total';
                                return "Cook for {$count} students ({$context})";
                            })
                            ->disabled()
                            ->dehydrated(false)
                            ->live()
                            ->columnSpan(1),
                            
                        Forms\Components\TextInput::make('dinner_count')
                            ->label('🌙 Dinner')
                            ->formatStateUsing(function () {
                                $count = $this->getDinnerCount();
                                $context = $this->selectedClass ? 'in this class' : 'total';
                                return "Cook for {$count} students ({$context})";
                            })
                            ->disabled()
                            ->dehydrated(false)
                            ->live()
                            ->columnSpan(1),
                    ])
                    ->columns(3)
                    ->collapsible()
                    ->hidden(fn () => !$this->selectedDate),

                Section::make('Student Meal Selection')
                    ->description('Select meals for each student')
                    ->schema([
                        Forms\Components\Repeater::make('students')
                            ->schema([
                                Forms\Components\TextInput::make('student_name')
                                    ->label('Student Name')
                                    ->disabled()
                                    ->columnSpan(2),

                                Forms\Components\TextInput::make('student_id')
                                    ->label('Student ID')
                                    ->disabled()
                                    ->columnSpan(1),

                                Forms\Components\Checkbox::make('breakfast')
                                    ->label('Breakfast')
                                    ->default(true)
                                    ->columnSpan(1),

                                Forms\Components\Checkbox::make('lunch')
                                    ->label('Lunch')
                                    ->default(true)
                                    ->columnSpan(1),

                                Forms\Components\Checkbox::make('dinner')
                                    ->label('Dinner')
                                    ->default(true)
                                    ->columnSpan(1),
                            ])
                            ->columns(6)
                            ->addable(false)
                            ->deletable(false)
                            ->reorderable(false)
                            ->defaultItems(0)
                            ->hidden(fn () => empty($this->students)),
                    ])
                    ->collapsible()
                    ->hidden(fn () => empty($this->students)),
            ])
            ->statePath('data');
    }

    public function loadStudents()
    {
        if (!$this->selectedClass || !$this->selectedDate) {
            $this->students = [];
            return;
        }

        $class = SchoolClass::find($this->selectedClass);

        if (!$class) {
            $this->students = [];
            return;
        }

        // Get all active students in the selected class
        $students = Student::where('class_id', $class->id)
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        // Get existing meals for this date
        $existingMeals = Meal::where('meal_date', $this->selectedDate)
            ->where('class_id', $class->id)
            ->get()
            ->keyBy('student_id');

        // Map students data with existing meal selections
        $this->students = $students->map(function ($student) use ($existingMeals) {
            $existingMeal = $existingMeals->get($student->student_id);

            return [
                'student_id' => $student->student_id,
                'student_name' => $student->name,
                'breakfast' => $existingMeal->breakfast ?? true,
                'lunch' => $existingMeal->lunch ?? true,
                'dinner' => $existingMeal->dinner ?? true,
            ];
        })->toArray();

        // Update the form data
        $this->data['students'] = $this->students;
    }

    public function saveMeals()
    {
        if (!$this->selectedClass || !$this->selectedDate) {
            Notification::make()
                ->danger()
                ->title('Please select both date and class')
                ->send();
            return;
        }

        $class = SchoolClass::find($this->selectedClass);

        // Get form data
        $formData = $this->form->getState();

        $savedCount = 0;
        foreach ($formData['students'] ?? [] as $index => $studentData) {
            // Get the corresponding student_id from the original array
            $studentId = $this->students[$index]['student_id'] ?? null;

            if (!$studentId) {
                continue;
            }

            // Create or update meal record
            Meal::updateOrCreate(
                [
                    'student_id' => $studentId,
                    'meal_date' => $this->selectedDate,
                ],
                [
                    'class_id' => $class->id,
                    'breakfast' => $studentData['breakfast'] ?? true,
                    'lunch' => $studentData['lunch'] ?? true,
                    'dinner' => $studentData['dinner'] ?? true,
                ]
            );

            $savedCount++;
        }

        Notification::make()
            ->success()
            ->title("Meal data saved successfully for {$savedCount} students!")
            ->send();

        $this->loadStudents();
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('createAutoMeals')
                ->label('🚀 Auto Create All Students')
                ->color('success')
                ->icon('heroicon-o-plus-circle')
                ->requiresConfirmation()
                ->modalHeading('Auto Create Meal Entries')
                ->modalDescription('This will automatically create meal records for ALL active students for the selected date with your meal preferences.')
                ->modalSubmitActionLabel('Create Meal Entries')
                ->action(function () {
                    $this->createAutoMeals();
                }),
        ];
    }

    public function createAutoMeals(): void
    {
        if (!$this->selectedDate) {
            Notification::make()
                ->title('Error')
                ->body('Please select a date first.')
                ->danger()
                ->send();
            return;
        }

        // Get all active students
        $students = Student::where('is_active', true)->get();

        if ($students->isEmpty()) {
            Notification::make()
                ->title('No Students Found')
                ->body('No active students found to create meal entries.')
                ->warning()
                ->send();
            return;
        }

        $createdCount = 0;
        $updatedCount = 0;
        $errors = [];

        foreach ($students as $student) {
            try {
                // Check if meal record already exists
                $existingMeal = Meal::where('student_id', $student->student_id)
                    ->where('meal_date', $this->selectedDate)
                    ->first();

                if ($existingMeal) {
                    // Update existing meal
                    $existingMeal->update([
                        'breakfast' => $this->autoBreakfast,
                        'lunch' => $this->autoLunch,
                        'dinner' => $this->autoDinner,
                    ]);
                    $updatedCount++;
                } else {
                    // Create new meal record
                    Meal::create([
                        'student_id' => $student->student_id,
                        'class_id' => $student->class_id,
                        'meal_date' => $this->selectedDate,
                        'breakfast' => $this->autoBreakfast,
                        'lunch' => $this->autoLunch,
                        'dinner' => $this->autoDinner,
                    ]);
                    $createdCount++;
                }
            } catch (\Exception $e) {
                $errors[] = "Failed for {$student->name}: " . $e->getMessage();
            }
        }

        // Show success notification
        $mealTypes = [];
        if ($this->autoBreakfast) $mealTypes[] = 'Breakfast';
        if ($this->autoLunch) $mealTypes[] = 'Lunch';
        if ($this->autoDinner) $mealTypes[] = 'Dinner';
        $mealTypeText = implode(', ', $mealTypes);

        $message = "✅ Auto meal entry completed for " . date('d M Y', strtotime($this->selectedDate));
        $message .= "\n📊 Created: {$createdCount} new records";
        $message .= "\n📝 Updated: {$updatedCount} existing records";
        $message .= "\n🍽️ Meals: {$mealTypeText}";

        if (!empty($errors)) {
            $message .= "\n⚠️ " . count($errors) . " errors occurred";
        }

        Notification::make()
            ->title('Auto Meal Entry Complete')
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

        // Reload students to show updated data
        $this->loadStudents();
    }

    private function getBreakfastCount(): int
    {
        if (!$this->selectedDate) {
            return 0;
        }

        $query = Meal::where('meal_date', $this->selectedDate)
            ->where('breakfast', true);
            
        if ($this->selectedClass) {
            $query->where('class_id', $this->selectedClass);
        }
        
        return $query->count();
    }

    private function getLunchCount(): int
    {
        if (!$this->selectedDate) {
            return 0;
        }

        $query = Meal::where('meal_date', $this->selectedDate)
            ->where('lunch', true);
            
        if ($this->selectedClass) {
            $query->where('class_id', $this->selectedClass);
        }
        
        return $query->count();
    }

    private function getDinnerCount(): int
    {
        if (!$this->selectedDate) {
            return 0;
        }

        $query = Meal::where('meal_date', $this->selectedDate)
            ->where('dinner', true);
            
        if ($this->selectedClass) {
            $query->where('class_id', $this->selectedClass);
        }
        
        return $query->count();
    }
}
