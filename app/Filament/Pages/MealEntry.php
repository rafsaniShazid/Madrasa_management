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
                            ->reactive()
                            ->afterStateUpdated(function ($state) {
                                $this->selectedDate = $state;
                                $this->loadStudents();
                            })
                            ->columnSpan(1),

                        Forms\Components\Select::make('class_id')
                            ->label('Select Class')
                            ->options(SchoolClass::all()->pluck('name', 'id'))
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set) {
                                $this->selectedClass = $state;
                                $this->loadStudents();
                            })
                            ->columnSpan(1),
                    ])
                    ->columns(2)
                    ->collapsible(),

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
}
