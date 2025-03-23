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
        Schema::table('annual_request_item', function (Blueprint $table) {
            $table->integer ('first_semester_quantity');
            $table->integer('second_semester_quantity');
            $table->integer('third_semester_quantity');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('annual_request_item', function (Blueprint $table) {
            $table->dropColumn('first_semester_quantity');
            $table->dropColumn('second_semester_quantity');
            $table->dropColumn('third_semester_quantity');
        });
    }
};
