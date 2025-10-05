<?php

namespace App\Filament\Resources\Students\Pages;

use App\Filament\Resources\Students\StudentResource;
use App\Models\Address;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditStudent extends EditRecord
{
    protected static string $resource = StudentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Load existing addresses into form fields
        $student = $this->record;
        $addresses = $student->addresses;
        
        $presentAddress = $addresses->where('address_type', 'present')->first();
        $permanentAddress = $addresses->where('address_type', 'permanent')->first();
        
        if ($presentAddress) {
            $data['present_village'] = $presentAddress->village;
            $data['present_post_office'] = $presentAddress->post_office;
            $data['present_thana'] = $presentAddress->thana;
            $data['present_district'] = $presentAddress->district;
        }
        
        if ($permanentAddress) {
            $data['permanent_village'] = $permanentAddress->village;
            $data['permanent_post_office'] = $permanentAddress->post_office;
            $data['permanent_thana'] = $permanentAddress->thana;
            $data['permanent_district'] = $permanentAddress->district;
        }
        
        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Remove address fields from student data before saving
        $addressData = [];
        
        // Extract present address data
        if (isset($data['present_village'])) {
            $addressData['present'] = [
                'address_type' => 'present',
                'village' => $data['present_village'],
                'post_office' => $data['present_post_office'],
                'thana' => $data['present_thana'],
                'district' => $data['present_district'],
            ];
            
            unset($data['present_village'], $data['present_post_office'], 
                  $data['present_thana'], $data['present_district']);
        }
        
        // Extract permanent address data
        if (isset($data['permanent_village'])) {
            $addressData['permanent'] = [
                'address_type' => 'permanent',
                'village' => $data['permanent_village'],
                'post_office' => $data['permanent_post_office'],
                'thana' => $data['permanent_thana'],
                'district' => $data['permanent_district'],
            ];
            
            unset($data['permanent_village'], $data['permanent_post_office'], 
                  $data['permanent_thana'], $data['permanent_district']);
        }
        
        $this->addressData = $addressData;
        
        return $data;
    }

    protected function afterSave(): void
    {
        // Update addresses after student is saved
        $student = $this->record;
        
        if (isset($this->addressData)) {
            foreach ($this->addressData as $type => $addressInfo) {
                // Update or create address
                Address::updateOrCreate(
                    [
                        'student_id' => $student->student_id,
                        'address_type' => $type
                    ],
                    $addressInfo
                );
            }
        }
    }

    private $addressData = [];
}
