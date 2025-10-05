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

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Ensure addresses are loaded for the view
        $this->record->load(['addresses']);
        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make()
                ->label('Edit Student')           // Custom label
                ->icon('heroicon-o-pencil-square') // Custom icon
                ->color('gray')                 // Custom color
                ->requiresConfirmation()           // Ask for confirmation
                ->modalHeading('Edit Student Information')
                ->modalDescription('Are you sure you want to edit this student?'),
            DeleteAction::make(),
            Action::make('print_route')
                ->label('Print Student Card ')
                ->icon('heroicon-o-printer')
                ->color('gray')
                ->url(fn (): string => route('student.print', ['student' => $this->record]))
                ->openUrlInNewTab(),
        ];
    }


    protected function printCustomCard()
    {
        $student = $this->record;
        $student->load(['addresses']);

        // Get the print URL for this student
        $printUrl = route('student.print', ['student' => $student]);
        
        // Open the dedicated print page in a new window
        return $this->js("
            const printWindow = window.open('{$printUrl}', '_blank', 'width=900,height=700');
            printWindow.focus();
            
            // Wait for the page to load, then print
            printWindow.onload = function() {
                setTimeout(() => {
                    printWindow.print();
                }, 1000);
            };
        ");
    }
}
