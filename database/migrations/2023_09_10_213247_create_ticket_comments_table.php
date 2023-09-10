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
        Schema::create('ticket_comments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ticket_assignment_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('role_id'); // or use an enum if you have a fixed number of roles.
            $table->text('comment');
            $table->timestamps();

            $table->foreign('ticket_assignment_id')->references('id')->on('ticket_assignments')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_comments');
    }
};
