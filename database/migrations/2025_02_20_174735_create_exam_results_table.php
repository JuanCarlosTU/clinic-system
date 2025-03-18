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
    Schema::create('exam_results', function (Blueprint $table) {
        $table->id();
        $table->foreignId('medical_record_id')->constrained()->onDelete('cascade');
        $table->foreignId('exam_id')->constrained();
        $table->string('result');
        $table->text('notes')->nullable();
        $table->json('attachments')->nullable(); // Para subir archivos de resultados
        $table->timestamps();
    });
}

public function down()
{
    Schema::dropIfExists('exam_results');
}

};
