<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrescriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prescriptions', function (Blueprint $table) {
            $table->id(); // Auto-incrementing ID
            $table->foreignId('record_id')->constrained()->onDelete('cascade'); // Foreign key for record_id
            $table->string('medication'); 
            $table->integer('quantity'); // Quantity of medication
            $table->string('sig'); // Sig (instructions for use)
            $table->boolean('isRefillable'); // Indicates if the prescription is refillable
            $table->date('refill_date')->nullable(); // Refill date (can be null)
            $table->date('date_started');
            $table->date('last_prescribed');
            $table->timestamps(); // Created at and updated at timestamps
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('prescriptions');
    }
}
