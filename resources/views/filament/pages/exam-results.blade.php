<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Form Section -->
        <form wire:submit.prevent="submit" class="no-print">
            {{ $this->form }}
        </form>

        <!-- Results Table Section -->
        @if(!empty($resultsData))
            <!-- Header Section -->
            <x-filament::section>
                <x-slot name="heading">
                    Results for: {{ $this->getSelectedExamName() }}
                </x-slot>
                <x-slot name="description">
                    {{ count($resultsData) }} students found
                </x-slot>

                <div class="flex justify-end mb-4">
                    <x-filament::button wire:click="downloadResultsPdf" color="success" size="sm">
                        Download PDF Report
                    </x-filament::button>
                </div>

                <!-- Filament Table -->
                <div class="overflow-x-auto">
                    <table class="fi-ta-table w-full table-auto divide-y divide-gray-200 dark:divide-white/10">
                        <!-- Table Header -->
                        <thead class="fi-ta-header bg-gray-50 dark:bg-white/5">
                            <tr class="divide-x divide-gray-200 dark:divide-white/10">
                                <th class="fi-ta-header-cell p-3 text-start min-w-[120px]">
                                    <span class="fi-ta-header-cell-label text-sm font-medium text-gray-950 dark:text-white">
                                        Student ID
                                    </span>
                                </th>
                                <th class="fi-ta-header-cell p-3 text-start min-w-[200px]">
                                    <span class="fi-ta-header-cell-label text-sm font-medium text-gray-950 dark:text-white">
                                        Student Name
                                    </span>
                                </th>
                                <th class="fi-ta-header-cell p-3 text-start min-w-[100px]">
                                    <span class="fi-ta-header-cell-label text-sm font-medium text-gray-950 dark:text-white">
                                        Class
                                    </span>
                                </th>
                                
                                <!-- Subject Columns -->
                                @foreach($subjects as $subject)
                                    <th class="fi-ta-header-cell p-3 text-center min-w-[120px]">
                                        <span class="fi-ta-header-cell-label text-sm font-medium text-gray-950 dark:text-white">
                                            {{ $subject->name }}
                                            <br>
                                            <span class="text-xs font-normal text-gray-500 dark:text-gray-400">
                                                ({{ $subject->total_marks }} marks)
                                            </span>
                                        </span>
                                    </th>
                                @endforeach
                                
                                <th class="fi-ta-header-cell p-3 text-center min-w-[120px]">
                                    <span class="fi-ta-header-cell-label text-sm font-medium text-gray-950 dark:text-white">
                                        Total Marks
                                    </span>
                                </th>
                                <th class="fi-ta-header-cell p-3 text-center min-w-[100px]">
                                    <span class="fi-ta-header-cell-label text-sm font-medium text-gray-950 dark:text-white">
                                        Average %
                                    </span>
                                </th>
                                <th class="fi-ta-header-cell p-3 text-center min-w-[80px]">
                                    <span class="fi-ta-header-cell-label text-sm font-medium text-gray-950 dark:text-white">
                                        Grade
                                    </span>
                                </th>
                            </tr>
                        </thead>

                        <!-- Table Body -->
                        <tbody class="fi-ta-body divide-y divide-gray-200 dark:divide-white/10">
                            @foreach($resultsData as $studentResult)
                                <tr class="fi-ta-row hover:bg-gray-50 dark:hover:bg-white/5 divide-x divide-gray-200 dark:divide-white/10">
                                    <td class="fi-ta-cell p-3 min-w-[120px]">
                                        <span class="fi-ta-text text-sm text-gray-950 dark:text-white">
                                            {{ $studentResult['student_id'] }}
                                        </span>
                                    </td>
                                    <td class="fi-ta-cell p-3 min-w-[200px]">
                                        <span class="fi-ta-text text-sm font-medium text-gray-950 dark:text-white">
                                            {{ $studentResult['student_name'] }}
                                        </span>
                                    </td>
                                    <td class="fi-ta-cell p-3 min-w-[100px]">
                                        <span class="fi-ta-text text-sm text-gray-950 dark:text-white">
                                            {{ $studentResult['class_name'] }}
                                        </span>
                                    </td>
                                    
                                    <!-- Subject Marks Columns -->
                                    @foreach($subjects as $subject)
                                        @php
                                            $subjectMark = $studentResult['subject_marks'][$subject->id] ?? null;
                                        @endphp
                                        <td class="fi-ta-cell p-3 text-center min-w-[120px]">
                                            @if($subjectMark && $subjectMark['obtained'] !== 'N/A')
                                                <div class="space-y-1">
                                                    <div class="text-sm font-medium text-gray-950 dark:text-white ml-2">
                                                        {{ $subjectMark['obtained'] }}/{{ $subjectMark['total'] }}
                                                    </div>
                                                    
                                                    <x-filament::badge 
                                                        :color="$subjectMark['status'] === 'pass' ? 'success' : 'danger'"
                                                        size="xs"
                                                        class="ml-2"
                                                    >
                                                        {{ ucfirst($subjectMark['status']) }}
                                                    </x-filament::badge>
                                                </div>
                                            @else
                                                <span class="text-sm text-gray-400 dark:text-gray-500 ml-2">N/A</span>
                                            @endif
                                        </td>
                                    @endforeach
                                    
                                    <td class="fi-ta-cell p-3 text-center min-w-[120px]">
                                        <span class="text-sm font-medium text-gray-950 dark:text-white">
                                            {{ $studentResult['total_obtained'] }}/{{ $studentResult['total_max_marks'] }}
                                        </span>
                                    </td>
                                    <td class="fi-ta-cell p-3 text-center min-w-[100px]">
                                        <span class="text-sm font-medium text-gray-950 dark:text-white">
                                            {{ $studentResult['overall_percentage'] }}%
                                        </span>
                                    </td>
                                    <td class="fi-ta-cell p-3 text-center min-w-[80px]">
                                        @php
                                            $gradeColor = match($studentResult['grade']) {
                                                'A+' => 'success',
                                                'A' => 'info', 
                                                'A-' => 'warning',
                                                'B' => 'warning',
                                                'C' => 'gray',
                                                'D' => 'gray',
                                                default => 'danger'
                                            };
                                        @endphp
                                        <x-filament::badge :color="$gradeColor">
                                            {{ $studentResult['grade'] }}
                                        </x-filament::badge>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </x-filament::section>          
            </div>
        @elseif($selectedExam)
            <x-filament::section>
                <div class="fi-alert flex gap-3 rounded-lg bg-yellow-50 p-4 ring-1 ring-yellow-200 dark:bg-yellow-950 dark:ring-yellow-700">
                    <div class="flex-shrink-0">
                        <x-heroicon-o-exclamation-triangle class="h-5 w-5 text-yellow-600 dark:text-yellow-400" />
                    </div>
                    <div class="flex-1">
                        <div class="text-sm font-medium text-yellow-800 dark:text-yellow-200">
                            No Results Found
                        </div>
                        <p class="mt-1 text-sm text-yellow-700 dark:text-yellow-300">
                            No student results found for the selected exam. Make sure marks have been entered for this exam using the Bulk Mark Entry page.
                        </p>
                    </div>
                </div>
            </x-filament::section>
        @endif
    </div>
</x-filament-panels::page>