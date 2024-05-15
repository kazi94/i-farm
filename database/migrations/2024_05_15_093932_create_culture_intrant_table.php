<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('culture_intrant', function (Blueprint $table) {
            $table->foreignId('intrant_id');
            $table->foreignId('culture_id')->nullable();
            $table->foreignId('depredateur_id')->nullable();
            $table->foreignId('unit_id')->nullable();
            $table->unsignedFloat('dose_min', 8, 2)->nullable();
            $table->unsignedFloat('dose_max', 8, 2)->nullable();
            $table->unsignedMediumInteger('dar_min')->nullable();
            $table->unsignedMediumInteger('dar_max')->nullable();
            $table->string('observation')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('culture_intrant');
    }
};
