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
        Schema::table('ticket_assignments', function (Blueprint $table) {
            $table->unsignedBigInteger('delegated_assignment_id')->nullable();

            // Define foreign key constraint
            $table->foreign('delegated_assignment_id')->references('id')->on('delegated_assignments')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ticket_assignments', function (Blueprint $table) {
            $table->dropForeign(['delegated_to']);
            $table->dropColumn('delegated_to');
        });
    }
};
