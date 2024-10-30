<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medication extends Model
{
    use HasFactory;

    // Specify the table if it's not the plural form of the model name
    protected $table = 'medications';

    // Specify the fillable properties
    protected $fillable = [
        'medication',
        'isRefillable',
    ];
}
