<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
    use HasFactory;

    protected $table = 'prescriptions';

    protected $fillable = [
        'record_id',
        'medication',
        'quantity',
        'sig',
        'isRefillable',
        'refill_date',
        'date_started',
        'last_prescribed',
    ];

    /**
     * Get the record associated with the prescription.
     */
    public function record()
    {
        return $this->belongsTo(Record::class);
    }
}
