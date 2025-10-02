<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Student extends Model
{
    protected $primaryKey = 'student_id';
    
    protected $fillable = [
        'session',
        'class',
        'student_type',
        'gender',
        'residence_status',
        'name',
        'father_name',
        'mother_name',
        'date_of_birth',
        'nid_birth_no',
        'nationality',
        'blood_group',
        'guardian_phone',
        'sms_number',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'student_type' => 'string',
        'gender' => 'string',
        'residence_status' => 'string',
        'class' => 'string',
    ];

    // Define available class options
    public static function getClassOptions(): array
    {
        return [
            'play' => 'Play',
            'nursery' => 'Nursery',
            'first' => 'First',
            'second' => 'Second',
            'third' => 'Third',
            'fourth' => 'Fourth',
            'nazira' => 'Nazira',
            'hifzul_quran' => 'Hifzul Quran',
        ];
    }

    // Get formatted class name
    public function getFormattedClassAttribute(): string
    {
        return self::getClassOptions()[$this->class] ?? $this->class;
    }

    // Relationships
    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class, 'student_id', 'student_id');
    }

    public function fees(): HasOne
    {
        return $this->hasOne(Fee::class, 'student_id', 'student_id');
    }

    // Accessor methods
    public function getPermanentAddressAttribute()
    {
        return $this->addresses()->where('address_type', 'permanent')->first();
    }

    public function getPresentAddressAttribute()
    {
        return $this->addresses()->where('address_type', 'present')->first();
    }
}
