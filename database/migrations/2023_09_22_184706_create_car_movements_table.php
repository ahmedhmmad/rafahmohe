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
        Schema::create('car_movements', function (Blueprint $table) {
            $table->id();
            $table->enum('direction', ['east', 'west', 'home', 'far', 'special', 'free']);
            $table->date('date'); // Date of movement

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('car_movements');
    }
};
