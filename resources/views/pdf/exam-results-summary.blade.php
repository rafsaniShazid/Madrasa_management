<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exam Results - {{ $examName }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            margin: 20px;
            font-size: 12px;
            line-height: 1.4;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .school-name {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .exam-title {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .exam-details {
            font-size: 12px;
            color: #666;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            font-size: 10px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 6px;
            text-align: left;
            vertical-align: top;
        }

        th {
            background-color: #f5f5f5;
            font-weight: bold;
            text-align: center;
        }

        .student-info th {
            text-align: left;
        }

        .subject-header {
            text-align: center;
        }

        .subject-header .subject-name {
            font-weight: bold;
            display: block;
        }

        .subject-header .marks-info {
            font-size: 9px;
            color: #666;
            font-weight: normal;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .badge-success {
            background-color: #d4edda;
            color: #155724;
        }

        .badge-danger {
            background-color: #f8d7da;
            color: #721c24;
        }

        .summary-section {
            margin-top: 30px;
            page-break-before: always;
        }

        .summary-table {
            width: 60%;
            margin: 0 auto;
        }

        .summary-table th, .summary-table td {
            padding: 8px;
            text-align: center;
        }

        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 20px;
        }

        @media print {
            body { margin: 10px; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="school-name">{{ config('app.name', 'Madrasa Management System') }}</div>
        <div class="exam-title">Exam Results Report</div>
        <div class="exam-details">
            <strong>{{ $examName }}</strong>
            @if($exam)
                <br>Date: {{ $exam->exam_date ? \Carbon\Carbon::parse($exam->exam_date)->format('d/m/Y') : 'N/A' }}
            @endif
        </div>
    </div>

    <table class="student-info">
        <thead>
            <tr>
                <th style="width: 60px;">ID</th>
                <th style="width: 150px;">Student Name</th>
                <th style="width: 80px;">Class</th>

                <!-- Subject Headers -->
                @foreach($subjects as $subject)
                    <th class="subject-header" style="width: 100px;">
                        <span class="subject-name">{{ $subject->name }}</span>
                        <span class="marks-info">({{ $subject->total_marks }})</span>
                    </th>
                @endforeach

                <th class="text-center" style="width: 80px;">Total</th>
                <th class="text-center" style="width: 60px;">%</th>
                <th class="text-center" style="width: 50px;">Grade</th>
            </tr>
        </thead>
        <tbody>
            @foreach($resultsData as $studentResult)
                <tr>
                    <td>{{ $studentResult['student_id'] }}</td>
                    <td>{{ $studentResult['student_name'] }}</td>
                    <td>{{ $studentResult['class_name'] }}</td>

                    <!-- Subject Marks -->
                    @foreach($subjects as $subject)
                        @php
                            $subjectMark = $studentResult['subject_marks'][$subject->id] ?? null;
                        @endphp
                        <td class="text-center">
                            @if($subjectMark && $subjectMark['obtained'] !== 'N/A')
                                <div>{{ $subjectMark['obtained'] }}/{{ $subjectMark['total'] }}</div>
                                <span class="badge {{ $subjectMark['status'] === 'pass' ? 'badge-success' : 'badge-danger' }}">
                                    {{ ucfirst($subjectMark['status']) }}
                                </span>
                            @else
                                <span>N/A</span>
                            @endif
                        </td>
                    @endforeach

                    <td class="text-center">{{ $studentResult['total_obtained'] }}/{{ $studentResult['total_max_marks'] }}</td>
                    <td class="text-center">{{ $studentResult['overall_percentage'] }}%</td>
                    <td class="text-center">
                        <strong>{{ $studentResult['grade'] }}</strong>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Summary Statistics -->
    <div class="summary-section">
        <h3 style="text-align: center; margin-bottom: 20px;">Summary Statistics</h3>

        @php
            $totalStudents = count($resultsData);
            $passedStudents = collect($resultsData)->where('overall_percentage', '>=', 40)->count();
            $failedStudents = $totalStudents - $passedStudents;
            $averagePercentage = collect($resultsData)->avg('overall_percentage');
            $highestScore = collect($resultsData)->max('overall_percentage');
            $lowestScore = collect($resultsData)->min('overall_percentage');
        @endphp

        <table class="summary-table">
            <thead>
                <tr>
                    <th>Total Students</th>
                    <th>Passed</th>
                    <th>Failed</th>
                    <th>Class Average</th>
                    <th>Highest Score</th>
                    <th>Lowest Score</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $totalStudents }}</td>
                    <td>{{ $passedStudents }}</td>
                    <td>{{ $failedStudents }}</td>
                    <td>{{ number_format($averagePercentage, 2) }}%</td>
                    <td>{{ number_format($highestScore, 2) }}%</td>
                    <td>{{ number_format($lowestScore, 2) }}%</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="footer">
        <p>Report generated on {{ date('d/m/Y H:i') }} | {{ config('app.name', 'Madrasa Management System') }}</p>
    </div>
</body>
</html>