<?php

namespace App\Filament\Resources\Students\Pages;

use App\Filament\Resources\Students\StudentResource;
use App\Models\Address;
use Filament\Resources\Pages\CreateRecord;

class CreateStudent extends CreateRecord
{
    protected static string $resource = StudentResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Remove address fields from student data before creating student
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
            
            // Remove from student data
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
            
            // Remove from student data
            unset($data['permanent_village'], $data['permanent_post_office'], 
                  $data['permanent_thana'], $data['permanent_district']);
        }
        
        // Store address data for later use
        $this->addressData = $addressData;
        
        return $data;
    }

    protected function afterCreate(): void
    {
        // Create addresses after student is created
        $student = $this->record;
        
        if (isset($this->addressData)) {
            foreach ($this->addressData as $addressInfo) {
                Address::create([
                    'student_id' => $student->student_id,
                    'address_type' => $addressInfo['address_type'],
                    'village' => $addressInfo['village'],
                    'post_office' => $addressInfo['post_office'],
                    'thana' => $addressInfo['thana'],
                    'district' => $addressInfo['district'],
                ]);
            }
        }
    }

    private $addressData = [];
}
