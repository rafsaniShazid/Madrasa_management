<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Student extends Model
{
    protected $primaryKey = 'student_id';
    
    protected $fillable = [
        'session',
        'class_id',
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
        'is_active',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'student_type' => 'string',
        'gender' => 'string',
        'residence_status' => 'string',
        'is_active' => 'boolean',
    ];

    // Get formatted class name (now using relationship)
    public function getFormattedClassAttribute(): string
    {
        return $this->schoolClass?->name ?? 'N/A';
    }

    // Relationships
    public function schoolClass(): BelongsTo
    {
        return $this->belongsTo(SchoolClass::class, 'class_id');
    }

    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class, 'student_id', 'student_id');
    }

    public function fees(): HasOne
    {
        return $this->hasOne(Fee::class, 'student_id', 'student_id');
    }

    public function results(): HasMany
    {
        return $this->hasMany(Result::class, 'student_id', 'student_id');
    }

    public function meals(): HasMany
    {
        return $this->hasMany(Meal::class, 'student_id', 'student_id');
    }

    public function mealBills(): HasMany
    {
        return $this->hasMany(MealBill::class, 'student_id', 'student_id');
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
