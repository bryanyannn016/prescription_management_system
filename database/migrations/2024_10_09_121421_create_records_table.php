<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecordsTable extends Migration
{
    public function up()
    {
        Schema::create('records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained()->onDelete('cascade'); // Assuming patient_id references the patients table
            $table->string('service');
            $table->string('status')->default('Pending'); // Default status to Pending
            $table->date('date')->default(now()); // Current date
            $table->string('prescription_id')->nullable(); // Prescription ID can be nullable
            $table->json('final_diagnosis')->nullable(); // Store multiple diagnoses as JSON
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('records');
    }
}

