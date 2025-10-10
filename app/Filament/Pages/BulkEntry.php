<?php

namespace App\Filament\Pages;

use App\Models\Exam;
use App\Models\Subject;
use App\Models\Student;
use App\Models\Result;
use App\Models\ClassSubject;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Schemas\Schema;
use Filament\Pages\Page;
use Filament\Notifications\Notification;
use Filament\Support\Icons\Heroicon;
use BackedEnum;
use UnitEnum;

class BulkEntry extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPencilSquare;
    
    protected string $view = 'filament.pages.bulk-entry';
    
    protected static ?string $navigationLabel = 'Bulk Mark Entry';
    
    protected static ?string $title = 'Enter Student Marks';
    
    protected static string|UnitEnum|null $navigationGroup = 'Academic';
    
    protected static ?int $navigationSort = 1;

    public ?array $data = [];
    public $selectedExam = null;
    public $selectedSubject = null;
    public $students = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Exam & Subject Selection')
                    ->description('Choose an exam and subject to load students and enter their marks')
                    ->schema([
                        Forms\Components\Select::make('exam_id')
                            ->label('Select Exam')
                            ->options(Exam::all()->pluck('name', 'id'))
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set) {
                                $this->selectedExam = $state;
                                $set('subject_id', null);
                                $this->loadStudents();
                            })
                            ->columnSpan(1),

                        Forms\Components\Select::make('subject_id')
                            ->label('Select Subject')
                            ->options(function (callable $get) {
                                $examId = $get('exam_id');
                                if (!$examId) {
                                    return [];
                                }
                                
                                $exam = Exam::find($examId);
                                if (!$exam) {
                                    return [];
                                }

                                // Get subjects for this class
                                return ClassSubject::where('class_id', $exam->class_id)
                                    ->where('is_active', true)
                                    ->with('subject')
                                    ->get()
                                    ->pluck('subject.name', 'subject.id');
                            })
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($state) {
                                $this->selectedSubject = $state;
                                $this->loadStudents();
                            })
                            ->columnSpan(1),
                    ])
                    ->columns(2)
                    ->collapsible(),

                Section::make('Student Marks Entry')
                    ->description('Enter marks for each student below')
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

                                Forms\Components\TextInput::make('total_marks')
                                    ->label('Total Marks')
                                    ->disabled()
                                    ->columnSpan(1),

                                Forms\Components\TextInput::make('obtained_marks')
                                    ->label('Obtained Marks')
                                    ->numeric()
                                    ->minValue(0)
                                    ->required()
                                    ->columnSpan(1),
                            ])
                            ->columns(5)
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
        if (!$this->selectedExam || !$this->selectedSubject) {
            $this->students = [];
            return;
        }

        $exam = Exam::find($this->selectedExam);
        $subject = Subject::find($this->selectedSubject);

        if (!$exam || !$subject) {
            $this->students = [];
            return;
        }

        // Get all students in the exam's class
        $students = Student::where('class_id', $exam->class_id)
            ->orderBy('name')
            ->get();

        // Get existing results
        $existingResults = Result::where('exam_id', $exam->id)
            ->where('subject_id', $subject->id)
            ->get()
            ->keyBy('student_id');

        $this->students = $students->map(function ($student) use ($existingResults, $subject) {
            $existingResult = $existingResults->get($student->student_id);
            
            return [
                'student_id' => $student->student_id,
                'student_name' => $student->name,
                'obtained_marks' => $existingResult->obtained_marks ?? null,
                'total_marks' => $subject->total_marks,
            ];
        })->toArray();

        // Update the form data
        $this->data['students'] = $this->students;
    }

    public function saveResults()
    {
        if (!$this->selectedExam || !$this->selectedSubject) {
            Notification::make()
                ->danger()
                ->title('Please select both exam and subject')
                ->send();
            return;
        }

        $exam = Exam::find($this->selectedExam);
        $subject = Subject::find($this->selectedSubject);

        // Get form data
        $formData = $this->form->getState();
        
        foreach ($formData['students'] ?? [] as $index => $studentData) {
            // Get the corresponding student_id from the original array
            $studentId = $this->students[$index]['student_id'] ?? null;
            
            if (!$studentId || !isset($studentData['obtained_marks']) || $studentData['obtained_marks'] === null) {
                continue; // Skip students without marks or invalid data
            }

            Result::updateOrCreate(
                [
                    'student_id' => $studentId,
                    'exam_id' => $exam->id,
                    'subject_id' => $subject->id,
                ],
                [
                    'obtained_marks' => $studentData['obtained_marks'],
                    'total_marks' => $subject->total_marks,
                    'percentage' => 0, // Will be calculated
                    'status' => null, // Will be calculated
                ]
            );

            // Update calculated fields
            $result = Result::where('student_id', $studentId)
                ->where('exam_id', $exam->id)
                ->where('subject_id', $subject->id)
                ->first();
            
            if ($result) {
                $result->updateCalculatedFields();
            }
        }

        Notification::make()
            ->success()
            ->title('Marks saved successfully!')
            ->send();

        $this->loadStudents();
    }
}
