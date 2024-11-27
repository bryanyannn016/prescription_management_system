<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Record extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'user_id',
        'service',
        'status',
        'date',
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

public function patient()
{
    return $this->belongsTo(Patient::class);
}

public function user()
{
    return $this->belongsTo(User::class);
}




    
}

