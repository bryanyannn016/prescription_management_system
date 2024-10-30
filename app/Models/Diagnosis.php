<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Diagnosis extends Model
{
    use HasFactory;

    protected $table = 'diagnoses';
    protected $fillable = [
        'diagnosis',
        'isOngoing',
        'record_id',
    ];

    /**
     * Get the record associated with the diagnosis.
     */
    public function record()
    {
        return $this->belongsTo(Record::class);
    }
}
