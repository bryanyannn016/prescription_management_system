<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Record extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'service',
        'status',
        'date',
        'prescription_id',
        'final_diagnosis',
    ];

    public function files()
    {
        return $this->hasMany(File::class);
    }

    public function diagnoses()
{
    return $this->hasMany(Diagnosis::class);
}

/**
 * Get the prescriptions for the record.
 */
public function prescriptions()
{
    return $this->hasMany(Prescription::class);
}

    
}

