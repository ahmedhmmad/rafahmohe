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
        Schema::table('plan_restrictions', function (Blueprint $table) {
            $table->dropForeign(['override_department_id']);
            $table->dropColumn('override_department_id');
            $table->boolean('can_override_multi_department')->default(false);
            //
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('plan_restriction', function (Blueprint $table) {
            //
        });
    }
};
