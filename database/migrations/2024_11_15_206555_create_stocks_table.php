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
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('annual_request_id')->nullable()->constrained();
            $table->integer('in_quantity')->default(0)->nullable();
            $table->integer('out_quantity')->default(0)->nullable();
            $table->string('details', '300');
            $table->boolean('approved');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stocks');
    }
};
