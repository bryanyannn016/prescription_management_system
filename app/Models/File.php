<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;

    protected $table = 'files';

    // Define the fillable fields
    protected $fillable = ['record_id', 'file_path'];

    public function record()
    {
        return $this->belongsTo(Record::class);
    }
}
