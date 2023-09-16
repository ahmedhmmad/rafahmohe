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
        Schema::create('delegated_assignments', function (Blueprint $table) {

                        $table->id();
                        $table->unsignedBigInteger('ticket_assignment_id');
                        $table->unsignedBigInteger('assigned_by');
                        $table->unsignedBigInteger('assigned_to');
                        $table->text('comments')->nullable();
                        $table->string('attachment')->nullable();
                        $table->timestamps();

                        // Define foreign key constraints
                        $table->foreign('ticket_assignment_id')->references('id')->on('ticket_assignments')->onDelete('cascade');
                        $table->foreign('assigned_by')->references('id')->on('users')->onDelete('cascade');
                        $table->foreign('assigned_to')->references('id')->on('temp_employees')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delegated_assignments');
    }
};
