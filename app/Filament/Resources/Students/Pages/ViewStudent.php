<?php

namespace App\Filament\Resources\Students\Pages;

use App\Filament\Resources\Students\StudentResource;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\Action;
use Filament\Resources\Pages\ViewRecord;
use Barryvdh\DomPDF\Facade\Pdf;

class ViewStudent extends ViewRecord
{
    protected static string $resource = StudentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
            DeleteAction::make(),
            Action::make('print')
                ->label('Print Student Card')
                ->icon('heroicon-o-printer')
                ->color('success')
                ->action(function () {
                    $this->js('window.print()');
                }),
            Action::make('export_pdf')
                ->label('Export PDF')
                ->icon('heroicon-o-document-arrow-down')
                ->color('info')
                ->action(function () {
                    return $this->exportToPdf();
                }),
        ];
    }

    protected function exportToPdf()
    {
        $student = $this->record;
        $student->load(['addresses', 'fees']);

        $pdf = Pdf::loadView('pdf.student-card', compact('student'));
        
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, 'student-' . $student->student_id . '.pdf');
    }
}
