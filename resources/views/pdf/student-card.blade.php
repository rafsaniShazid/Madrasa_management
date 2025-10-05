<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Card - {{ $student->name }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f8f9fa;
        }
        
        .student-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 30px;
            margin: 0 auto;
            max-width: 800px;
            border: 2px solid #2563eb;
        }
        
        .header {
            text-align: center;
            border-bottom: 3px solid #2563eb;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        
        .school-name {
            font-size: 24px;
            font-weight: bold;
            color: #2563eb;
            margin-bottom: 5px;
        }
        
        .subtitle {
            font-size: 16px;
            color: #6b7280;
            margin-bottom: 10px;
        }
        
        .student-info {
            display: table;
            width: 100%;
            margin-bottom: 30px;
        }
        
        .info-section {
            display: table-cell;
            vertical-align: top;
            width: 50%;
            padding: 10px;
        }
        
        .section-title {
            font-size: 18px;
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 15px;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 5px;
        }
        
        .info-item {
            margin-bottom: 12px;
            display: flex;
            align-items: center;
        }
        
        .info-label {
            font-weight: bold;
            color: #374151;
            width: 140px;
            margin-right: 10px;
        }
        
        .info-value {
            color: #1f2937;
            flex: 1;
        }
        
        .badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .badge-primary {
            background-color: #dbeafe;
            color: #1e40af;
        }
        
        .badge-success {
            background-color: #dcfce7;
            color: #166534;
        }
        
        .badge-warning {
            background-color: #fef3c7;
            color: #92400e;
        }
        
        .address-section, .fee-section {
            margin-top: 30px;
            padding: 20px;
            background-color: #f9fafb;
            border-radius: 8px;
            border-left: 4px solid #2563eb;
        }
        
        .footer {
            margin-top: 40px;
            text-align: center;
            padding-top: 20px;
            border-top: 2px solid #e5e7eb;
            color: #6b7280;
            font-size: 12px;
        }
        
        .qr-placeholder {
            width: 80px;
            height: 80px;
            background-color: #e5e7eb;
            border: 2px dashed #9ca3af;
            display: inline-block;
            text-align: center;
            line-height: 76px;
            color: #6b7280;
            font-size: 10px;
            margin-left: 20px;
        }
    </style>
</head>
<body>
    <div class="student-card">
        <!-- Header -->
        <div class="header">
            <div class="school-name">{{ config('app.name', 'Madrasa Management System') }}</div>
            <div class="subtitle">Student Information Card</div>
            <div style="margin-top: 10px;">
                <span class="badge badge-primary">Student ID: {{ $student->student_id }}</span>
            </div>
        </div>

        <!-- Student Information -->
        <div class="student-info">
            <div class="info-section">
                <div class="section-title">Personal Information</div>
                
                <div class="info-item">
                    <span class="info-label">Full Name:</span>
                    <span class="info-value">{{ $student->name }}</span>
                </div>
                
                <div class="info-item">
                    <span class="info-label">Class:</span>
                    <span class="info-value">
                        <span class="badge badge-success">{{ ucfirst($student->class) }}</span>
                    </span>
                </div>
                
                <div class="info-item">
                    <span class="info-label">Gender:</span>
                    <span class="info-value">{{ ucfirst($student->gender) }}</span>
                </div>
                
                <div class="info-item">
                    <span class="info-label">Date of Birth:</span>
                    <span class="info-value">{{ $student->date_of_birth ? $student->date_of_birth->format('d M Y') : 'N/A' }}</span>
                </div>
                
                <div class="info-item">
                    <span class="info-label">Phone:</span>
                    <span class="info-value">{{ $student->phone ?? 'N/A' }}</span>
                </div>
            </div>
            
            <div class="info-section">
                <div class="section-title">Family Information</div>
                
                <div class="info-item">
                    <span class="info-label">Father's Name:</span>
                    <span class="info-value">{{ $student->father_name ?? 'N/A' }}</span>
                </div>
                
                <div class="info-item">
                    <span class="info-label">Mother's Name:</span>
                    <span class="info-value">{{ $student->mother_name ?? 'N/A' }}</span>
                </div>
                
                <div class="info-item">
                    <span class="info-label">Emergency Contact:</span>
                    <span class="info-value">{{ $student->emergency_contact ?? 'N/A' }}</span>
                </div>
                
                <div class="info-item">
                    <span class="info-label">Residence:</span>
                    <span class="info-value">
                        <span class="badge {{ $student->residence_status === 'residential' ? 'badge-success' : 'badge-warning' }}">
                            {{ ucfirst(str_replace('_', ' ', $student->residence_status)) }}
                        </span>
                    </span>
                </div>
                
                <div class="info-item">
                    <span class="info-label">Student Type:</span>
                    <span class="info-value">
                        <span class="badge badge-primary">{{ ucfirst(str_replace('_', ' ', $student->student_type)) }}</span>
                    </span>
                </div>
                
                <div class="qr-placeholder">
                    QR Code
                </div>
            </div>
        </div>

        <!-- Address Information -->
        @if($student->addresses->count() > 0)
        <div class="address-section">
            <div class="section-title">Address Information</div>
            
            @php
                $presentAddress = $student->addresses->where('address_type', 'present')->first();
                $permanentAddress = $student->addresses->where('address_type', 'permanent')->first();
            @endphp
            
            @if($presentAddress)
                <div class="info-item">
                    <span class="info-label" style="color: #059669;">Present Address:</span>
                    <span class="info-value">{{ $presentAddress->full_address }}</span>
                </div>
                <div style="margin-left: 150px; font-size: 11px; color: #666; margin-bottom: 10px;">
                    Village: {{ $presentAddress->village }}, P.O: {{ $presentAddress->post_office }}, 
                    Thana: {{ $presentAddress->thana }}, District: {{ $presentAddress->district }}
                </div>
            @endif
            
            @if($permanentAddress)
                <div class="info-item">
                    <span class="info-label" style="color: #dc2626;">Permanent Address:</span>
                    <span class="info-value">{{ $permanentAddress->full_address }}</span>
                </div>
                <div style="margin-left: 150px; font-size: 11px; color: #666; margin-bottom: 10px;">
                    Village: {{ $permanentAddress->village }}, P.O: {{ $permanentAddress->post_office }}, 
                    Thana: {{ $permanentAddress->thana }}, District: {{ $permanentAddress->district }}
                </div>
            @endif
            
            @if(!$presentAddress && !$permanentAddress)
                @foreach($student->addresses as $address)
                    <div class="info-item">
                        <span class="info-label">{{ ucfirst($address->address_type) }} Address:</span>
                        <span class="info-value">{{ $address->full_address }}</span>
                    </div>
                @endforeach
            @endif
        </div>
        @endif
        

        <!-- Footer -->
        <div class="footer">
            <p>Generated on {{ now()->format('d M Y, h:i A') }}</p>
            <p>This is an official document generated by {{ config('app.name', 'Madrasa Management System') }}</p>
        </div>
    </div>
</body>
</html>