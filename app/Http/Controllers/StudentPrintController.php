<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;

class StudentPrintController extends Controller
{
    public function print(Student $student)
    {
        // Load all relationships
        $student->load(['addresses', 'fees']);
        
        return view('print.student-card-new', compact('student'));
    }
    
    public function printDirect(Student $student)
    {
        // Load all relationships
        $student->load(['addresses', 'fees']);
        
        // Return view with immediate print trigger
        return view('print.student-card-new', compact('student'))
            ->with('autoPrint', true);
    }
    
    public function printData(Student $student)
    {
        // Load all relationships and return JSON for JavaScript printing
        $student->load(['addresses', 'fees']);
        
        $html = view('print.student-card', compact('student'))->render();
        
        return response()->json([
            'html' => $html,
            'student_name' => $student->name,
            'student_id' => $student->student_id
        ]);
    }
}
