<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print - {{ $student->name }}</title>
    <style>
        @media print {
            body { margin: 0; }
            .no-print { display: none !important; }
        }
        
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            line-height: 1.4;
        }
        
        .print-card {
            max-width: 100%;
            border: 2px solid #333;
            padding: 20px;
            background: white;
        }
        
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            margin-bottom: 20px;
            padding-bottom: 10px;
        }
        
        .school-name {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }
        
        .info-section {
            border: 1px solid #ddd;
            padding: 15px;
        }
        
        .section-title {
            font-weight: bold;
            font-size: 16px;
            margin-bottom: 10px;
            text-decoration: underline;
        }
        
        .info-row {
            margin-bottom: 8px;
            display: flex;
        }
        
        .label {
            font-weight: bold;
            width: 120px;
            margin-right: 10px;
        }
        
        .value {
            flex: 1;
        }
        
        @media print {
            .print-card {
                border: 2px solid #000;
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body>
    <div class="print-card">
        <div class="header">
            <div class="school-name">{{ config('app.name', 'Madrasa Management System') }}</div>
            <div>Student Information Card</div>
            <div><strong>Student ID:</strong> {{ $student->student_id }}</div>
        </div>

        <div class="info-grid">
            <div class="info-section">
                <div class="section-title">Personal Information</div>
                <div class="info-row">
                    <span class="label">Name:</span>
                    <span class="value">{{ $student->name }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Class:</span>
                    <span class="value">{{ ucfirst($student->class) }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Gender:</span>
                    <span class="value">{{ ucfirst($student->gender) }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Date of Birth:</span>
                    <span class="value">{{ $student->date_of_birth ? $student->date_of_birth->format('d M Y') : 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Phone:</span>
                    <span class="value">{{ $student->phone ?? 'N/A' }}</span>
                </div>
            </div>

            <div class="info-section">
                <div class="section-title">Family & Contact</div>
                <div class="info-row">
                    <span class="label">Father:</span>
                    <span class="value">{{ $student->father_name ?? 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Mother:</span>
                    <span class="value">{{ $student->mother_name ?? 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Emergency:</span>
                    <span class="value">{{ $student->emergency_contact ?? 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Residence:</span>
                    <span class="value">{{ ucfirst(str_replace('_', ' ', $student->residence_status)) }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Type:</span>
                    <span class="value">{{ ucfirst(str_replace('_', ' ', $student->student_type)) }}</span>
                </div>
            </div>
        </div>

        @if($student->addresses->count() > 0)
        <div class="info-section">
            <div class="section-title">Address Information</div>
            @foreach($student->addresses as $address)
                <div class="info-row">
                    <span class="label">Address {{ $loop->iteration }}:</span>
                    <span class="value">{{ $address->full_address }}</span>
                </div>
            @endforeach
        </div>
        @endif

        <div style="margin-top: 30px; text-align: center; font-size: 12px; color: #666;">
            Printed on {{ now()->format('d M Y, h:i A') }}
        </div>
    </div>
    
    <script>
        // Auto-print when page loads
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>