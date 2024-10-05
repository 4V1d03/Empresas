<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('timesheets', function (Blueprint $table) { //tabla ojas de horarios
            $table->id();
            $table->foreignId('calendar_id');
            $table->foreignId('user_id');
            $table->enum('type',['work','pause' /*tipos permitidos*/])->default('work'); //para registrar jornadas de trabajo y las pausas
            $table->timestamp('day_in');//en estas dos clumnas se guarda cuanda empiza a trabajar o descanzar y cuando para de trabajar o descanzar
            $table->timestamp('day_out');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('timesheets');
    }
};
