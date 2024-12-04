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
        Schema::create('slots', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('availability_id'); // Foreign key for the availability
            $table->time('slot_start_time'); // Slot's start time
            $table->time('slot_end_time');   // Slot's end time
            $table->tinyInteger('slot_status')->default(1); // 1 for active, 0 for inactive
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('availability_id')->references('id')->on('availabilities')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('slots');
    }
};
