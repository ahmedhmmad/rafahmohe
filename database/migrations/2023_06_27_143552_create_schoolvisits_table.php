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
        Schema::create('schoolvisits', function (Blueprint $table) {
            $table->id();
            $table->date('visit_date');
            $table->time('coming_time');
            $table->time('leaving_time');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('school_id');
            $table->string('job_title');

            $table->string('purpose');
            $table->text('activities');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('school_id')->references('id')->on('schools');
            $table->unique(['user_id', 'visit_date', 'coming_time']);




        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schoolvisits');
    }
};
