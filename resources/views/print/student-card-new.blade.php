<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print - {{ $student->name ?? 'Student' }}</title>
    <style>
        @media print {
            body { margin: 0; }
            .no-print { display: none !important; }
            .print-card {
                border: 2px solid #000;
                page-break-inside: avoid;
                box-shadow: none;
            }
            .header {
                border-bottom: 2px solid #000;
            }
            .info-section {
                border: 1px solid #000;
            }
        }
        
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            line-height: 1.4;
            color: #333;
        }
        
        .print-controls {
            position: fixed;
            top: 10px;
            right: 10px;
            z-index: 1000;
            background: white;
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .print-btn {
            background: #1e40af;
            color: white;
            padding: 8px 15px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            margin-right: 5px;
            font-size: 14px;
        }
        
        .print-btn:hover {
            background: #1d4ed8;
        }
        
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            line-height: 1.4;
            color: #333;
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
            color: #1e40af;
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
            border-radius: 5px;
        }
        
        .section-title {
            font-weight: bold;
            font-size: 16px;
            margin-bottom: 10px;
            text-decoration: underline;
            color: #1e40af;
        }
        
        .info-row {
            margin-bottom: 8px;
            display: flex;
        }
        
        .label {
            font-weight: bold;
            width: 130px;
            margin-right: 10px;
            color: #555;
        }
        
        .value {
            flex: 1;
            color: #333;
        }
        
        .full-width {
            grid-column: span 2;
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
    <!-- Print Controls (hidden when printing) -->
    <div class="print-controls no-print">
        <button class="print-btn" onclick="window.print()">🖨️ Print</button>
        <button class="print-btn" onclick="window.close()" style="background: #dc2626;">❌ Close</button>
    </div>

    <div class="print-card">
        <div class="header">
            <div class="school-name">{{ config('app.name', 'Madrasa Management System') }}</div>
            <div>Student Information Card</div>
            <div><strong>Student ID:</strong> {{ $student->student_id ?? 'N/A' }}</div>
        </div>

        <div class="info-grid">
            <div class="info-section">
                <div class="section-title">Personal Information</div>
                <div class="info-row">
                    <span class="label">Name:</span>
                    <span class="value">{{ $student->name ?? 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Class:</span>
                    <span class="value">{{ $student->class ? ucfirst($student->class) : 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Session:</span>
                    <span class="value">{{ $student->session ?? 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Gender:</span>
                    <span class="value">{{ $student->gender ? ucfirst($student->gender) : 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Date of Birth:</span>
                    <span class="value">{{ $student->date_of_birth ? $student->date_of_birth->format('d M Y') : 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Blood Group:</span>
                    <span class="value">{{ $student->blood_group ?? 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Nationality:</span>
                    <span class="value">{{ $student->nationality ?? 'N/A' }}</span>
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
                    <span class="label">Guardian Phone:</span>
                    <span class="value">{{ $student->guardian_phone ?? 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="label">SMS Number:</span>
                    <span class="value">{{ $student->sms_number ?? 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Residence:</span>
                    <span class="value">{{ $student->residence_status ? ucfirst(str_replace('_', ' ', $student->residence_status)) : 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Student Type:</span>
                    <span class="value">{{ $student->student_type ? ucfirst(str_replace('_', ' ', $student->student_type)) : 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="label">NID/Birth No:</span>
                    <span class="value">{{ $student->nid_birth_no ?? 'N/A' }}</span>
                </div>
            </div>
        </div>

        @if($student->addresses && $student->addresses->count() > 0)
        <div class="info-section full-width">
            <div class="section-title">Address Information</div>
            
            @php
                $presentAddress = $student->addresses->where('address_type', 'present')->first();
                $permanentAddress = $student->addresses->where('address_type', 'permanent')->first();
            @endphp
            
            @if($presentAddress)
                <div class="info-row" style="border-bottom: 1px solid #eee; padding-bottom: 8px; margin-bottom: 12px;">
                    <span class="label" style="color: #059669; font-weight: bold;">Present Address:</span>
                    <span class="value" style="font-weight: 500;">{{ $presentAddress->full_address }}</span>
                </div>
                <div style="margin-bottom: 8px; font-size: 12px; color: #666;">
                    <strong>Details:</strong> 
                    Village: {{ $presentAddress->village }}, 
                    Post Office: {{ $presentAddress->post_office }}, 
                    Thana: {{ $presentAddress->thana }}, 
                    District: {{ $presentAddress->district }}
                </div>
            @endif
            
            @if($permanentAddress)
                <div class="info-row" style="border-bottom: 1px solid #eee; padding-bottom: 8px; margin-bottom: 12px;">
                    <span class="label" style="color: #dc2626; font-weight: bold;">Permanent Address:</span>
                    <span class="value" style="font-weight: 500;">{{ $permanentAddress->full_address }}</span>
                </div>
                <div style="margin-bottom: 8px; font-size: 12px; color: #666;">
                    <strong>Details:</strong> 
                    Village: {{ $permanentAddress->village }}, 
                    Post Office: {{ $permanentAddress->post_office }}, 
                    Thana: {{ $permanentAddress->thana }}, 
                    District: {{ $permanentAddress->district }}
                </div>
            @endif
            
            @if(!$presentAddress && !$permanentAddress)
                @foreach($student->addresses as $address)
                    <div class="info-row">
                        <span class="label">{{ ucfirst($address->address_type ?? 'Address') }} {{ $loop->iteration }}:</span>
                        <span class="value">{{ $address->full_address ?? 'N/A' }}</span>
                    </div>
                @endforeach
            @endif
            
            @if(!$presentAddress)
                <div style="color: #dc2626; font-style: italic; font-size: 12px;">
                    * Present address not provided
                </div>
            @endif
            
            @if(!$permanentAddress)
                <div style="color: #dc2626; font-style: italic; font-size: 12px;">
                    * Permanent address not provided
                </div>
            @endif
        </div>
        @endif

        @if($student->fees)
        <div class="fee-section">
            <div class="section-title">Fee Information</div>
            @php $fee = $student->fees; @endphp
            
            @if($fee->admission_fee > 0)
            <div class="info-item">
                <span class="info-label">Admission Fee:</span>
                <span class="info-value">৳{{ number_format($fee->admission_fee, 2) }}</span>
            </div>
            @endif
            
            @if($fee->admit_form_fee > 0)
            <div class="info-item">
                <span class="info-label">Admit Form Fee:</span>
                <span class="info-value">৳{{ number_format($fee->admit_form_fee, 2) }}</span>
            </div>
            @endif
            
            @if($fee->id_card > 0)
            <div class="info-item">
                <span class="info-label">ID Card Fee:</span>
                <span class="info-value">৳{{ number_format($fee->id_card, 2) }}</span>
            </div>
            @endif
            
            <div class="info-item" style="border-top: 1px solid #e5e7eb; padding-top: 10px; margin-top: 10px; font-weight: bold;">
                <span class="info-label">Total Fees:</span>
                <span class="info-value">৳{{ number_format($fee->total_fees, 2) }}</span>
            </div>
        </div>
        @endif

        <div style="margin-top: 30px; text-align: center; font-size: 12px; color: #666;">
            <p>Generated on {{ now()->format('d M Y, h:i A') }}</p>
            <p>This is an official document generated by {{ config('app.name', 'Madrasa Management System') }}</p>
        </div>
    </div>
    
    <script>
        // Check if auto-print is enabled
        const autoPrint = {{ isset($autoPrint) && $autoPrint ? 'true' : 'false' }};
        
        window.onload = function() {
            if (autoPrint) {
                // Auto-print when page loads (for direct print route)
                setTimeout(() => {
                    window.print();
                }, 1000);
            }
        }
        
        // Handle print dialog events
        window.onbeforeprint = function() {
            console.log('Preparing to print...');
        };
        
        window.onafterprint = function() {
            console.log('Print dialog closed');
            // Optionally close the window after printing
            // if (autoPrint) {
            //     window.close();
            // }
        };
        
        // Keyboard shortcut for printing
        document.addEventListener('keydown', function(e) {
            if (e.ctrlKey && e.key === 'p') {
                e.preventDefault();
                window.print();
            }
        });
    </script>
</body>
</html>