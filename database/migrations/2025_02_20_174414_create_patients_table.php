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
    Schema::create('patients', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->string('dni')->unique();
        $table->date('birth_date');
        $table->string('phone')->nullable();
        $table->text('address')->nullable();
        $table->timestamps();
    });
}

public function down()
{
    Schema::dropIfExists('patients');
}

};
