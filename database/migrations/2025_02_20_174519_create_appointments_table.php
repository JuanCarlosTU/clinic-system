<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('appointments', function (Blueprint $table) {
        $table->id();
        $table->foreignId('patient_id')->constrained();
        $table->foreignId('doctor_id')->constrained();
        $table->dateTime('appointment_date');
        $table->enum('status', ['scheduled', 'completed', 'canceled'])->default('scheduled');
        $table->timestamps();
    });
}

public function down()
{
    Schema::dropIfExists('appointments');
}

};
