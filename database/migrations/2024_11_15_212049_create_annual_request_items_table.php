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
        Schema::create('annual_request_item', function (Blueprint $table) {
            $table->id();
            $table->foreignId('annual_request_id')->constrained()->onDelete('cascade');
            $table->foreignId('item_id');
            $table->integer('quantity');
            $table->boolean('frozen')->default(0);
            $table->string('freeze_reason')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('annual_request_items');
    }
};
