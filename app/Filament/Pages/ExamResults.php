<?php

namespace App\Filament\Pages;

use Barryvdh\DomPDF\Facade\Pdf;

use App\Models\Exam;
use App\Models\Result;
use App\Models\Student;
use App\Models\Subject;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Schemas\Schema;
use Filament\Pages\Page;
use Filament\Support\Icons\Heroicon;
use BackedEnum;
use UnitEnum;
use Illuminate\Support\Collection;

class ExamResults extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChartBar;
    
    protected string $view = 'filament.pages.exam-results';
    
    protected static ?string $navigationLabel = 'Exam Results';
    
    protected static ?string $title = 'View Exam Results';
    
    protected static string|UnitEnum|null $navigationGroup = 'Academic';
    
    protected static ?int $navigationSort = 2;

    public ?array $data = [];
    public $selectedExam = null;
    public $resultsData = [];
    public $subjects = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Select Exam')
                    ->description('Choose an exam to view student results')
                    ->schema([
                        Forms\Components\Select::make('exam_id')
                            ->label('Select Exam')
                            ->options(Exam::where('status', 'completed')
                                ->orderBy('exam_date', 'desc')
                                ->pluck('name', 'id'))
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($state) {
                                $this->selectedExam = $state;
                                $this->loadResults();
                            })
                            ->helperText('Only completed exams with results are shown')
                            ->columnSpan(1),
                    ])
                    ->columns(1)
                    ->collapsible(),
            ])
            ->statePath('data');
    }

    public function loadResults()
    {
        if (!$this->selectedExam) {
            $this->resultsData = [];
            $this->subjects = [];
            return;
        }

        $exam = Exam::find($this->selectedExam);
        if (!$exam) {
            $this->resultsData = [];
            $this->subjects = [];
            return;
        }

        // Get all subjects that have results for this exam
        $this->subjects = Subject::whereHas('results', function ($query) use ($exam) {
            $query->where('exam_id', $exam->id);
        })->orderBy('name')->get();

        // Get all students who have results for this exam
        $students = Student::whereHas('results', function ($query) use ($exam) {
            $query->where('exam_id', $exam->id);
        })->orderBy('name')->get();

        $this->resultsData = $students->map(function ($student) use ($exam) {
            $studentResults = Result::where('student_id', $student->student_id)
                ->where('exam_id', $exam->id)
                ->with('subject')
                ->get()
                ->keyBy('subject_id');

            $subjectMarks = [];
            $totalObtained = 0;
            $totalMaxMarks = 0;

            foreach ($this->subjects as $subject) {
                $result = $studentResults->get($subject->id);
                if ($result) {
                    $subjectMarks[$subject->id] = [
                        'obtained' => $result->obtained_marks,
                        'total' => $result->total_marks,
                        'percentage' => $result->percentage,
                        'status' => $result->status,
                    ];
                    $totalObtained += $result->obtained_marks;
                    $totalMaxMarks += $result->total_marks;
                } else {
                    $subjectMarks[$subject->id] = [
                        'obtained' => 'N/A',
                        'total' => 'N/A',
                        'percentage' => 'N/A',
                        'status' => 'N/A',
                    ];
                }
            }

            $overallPercentage = $totalMaxMarks > 0 ? round(($totalObtained / $totalMaxMarks) * 100, 2) : 0;
            $grade = $this->calculateGrade($overallPercentage);

            return [
                'student_id' => $student->student_id,
                'student_name' => $student->name,
                'class_name' => $student->schoolClass->name ?? 'N/A',
                'subject_marks' => $subjectMarks,
                'total_obtained' => $totalObtained,
                'total_max_marks' => $totalMaxMarks,
                'overall_percentage' => $overallPercentage,
                'grade' => $grade,
            ];
        })->toArray();
    }

    private function calculateGrade($percentage)
    {
        if ($percentage >= 80) return 'A+';
        if ($percentage >= 70) return 'A';
        if ($percentage >= 60) return 'A-';
        if ($percentage >= 50) return 'B';
        if ($percentage >= 40) return 'C';
        if ($percentage >= 35) return 'D';
        return 'F';
    }

    public function getSelectedExamName()
    {
        if (!$this->selectedExam) return null;
        
        $exam = Exam::find($this->selectedExam);
        return $exam ? $exam->name : null;
    }

    public function downloadResultsPdf()
    {
        if (!$this->selectedExam || empty($this->resultsData)) {
            return;
        }

        $exam = Exam::find($this->selectedExam);
        $examName = $exam ? $exam->name : 'Exam Results';

        $pdf = Pdf::loadView('pdf.exam-results-summary', [
            'resultsData' => $this->resultsData,
            'subjects' => $this->subjects,
            'examName' => $examName,
            'exam' => $exam,
        ]);

        $pdf->setPaper('a4', 'landscape');

        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'exam_results_' . str_replace(' ', '_', $examName) . '.pdf');
    }
}