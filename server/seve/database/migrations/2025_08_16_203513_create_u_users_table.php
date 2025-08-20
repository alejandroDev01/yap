<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('u_users', function (Blueprint $table) {
            $table->id();
            $table->string('nro_documento')->nullable();
            $table->string('genero')->nullable();
            $table->string('nombre');
            $table->string('foto')->nullable();
            $table->string('apellidos');
            $table->date('fecha_nacimiento')->nullable();
            $table->string('direccion')->nullable();
            $table->string('celular')->nullable();
            $table->string('email')->unique();
            $table->string('password');
            $table->string('estado')->default(\App\Enums\UserStatus::ACTIVE->value);
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('u_users');
    }
};
