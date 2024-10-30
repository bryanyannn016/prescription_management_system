<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $fillable = [
        'first_name', 
        'middle_name', 
        'last_name', 
        'birthday', 
        'age', 
        'sex', 
        'mhp_no', 
        'mhp_exp', 
        'address', 
        'barangay',
        'patient_number'
    ];

    public static function boot()
    {
        parent::boot();

        // Before creating a patient, generate the patient number
        static::creating(function ($patient) {
            $patient->patient_number = Patient::generatePatientNumber();
        });
    }

    // Method to generate a unique 7-digit patient number
    public static function generatePatientNumber()
    {
        do {
            $number = str_pad(mt_rand(1, 9999999), 7, '0', STR_PAD_LEFT); // Generate a 7-digit number
        } while (self::where('patient_number', $number)->exists());

        return $number;
    }

    public function records()
{
    return $this->hasMany(Record::class, 'patient_id');
}

}
