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
        Schema::table('users', function (Blueprint $table) { //se va amodificar la tabla no la va a crear
            //
            $table->foreignId('country_id') //agrega esta llave foranea
                ->nullable() //para que pueda aceptar nulos    
                ->constrained() //cuando se elimine o actualice el country_id tambien pasara en empleado-employees (users)
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->foreignId('state_id')->nullable()->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('city_id')->nullable()->constrained()->onUpdate('cascade')->onDelete('cascade');
            //no hay que porner la referencia a la tabla por que laravel ya sabe que cada id pertenese a una tabla llamada con ese nombre
            $table->string('address')->nullable(); 
            $table->string('postal_code')->nullable();
            //al finalizar los cambios de los nuvos campos de la tabla se ejecuta una nueva migracion para efectuar los nuevos campos
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
