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
        Schema::create('plan_restrictions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->boolean('can_override_last_week')->default(false);
            $table->boolean('can_override_department')->default(false);
            $table->date('override_start_date')->nullable();
            $table->date('override_end_date')->nullable();
            $table->unsignedInteger('override_week_number')->nullable();
            $table->unsignedBigInteger('override_department_id')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('override_department_id')->references('id')->on('departments')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plan_restrictions');
    }
};
