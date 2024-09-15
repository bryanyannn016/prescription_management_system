<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePatientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('patient_number', 7)->unique();
            $table->string('first_name');
            $table->string('middle_name')->nullable(); // Not required
            $table->string('last_name');
            $table->date('birthday');
            $table->integer('age');
            $table->enum('sex', ['Male', 'Female']); // Assuming these are the only options
            $table->string('mhp_no');
            $table->date('mhp_exp');
            $table->string('address');
            $table->string('barangay')->nullable(); // Not required
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('patients');
    }
}
