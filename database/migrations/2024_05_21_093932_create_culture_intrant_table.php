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
            $table->id();
            $table->foreignId('intrant_id')->nullable()->constrained('intrants')->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('culture_id')->nullable()->constrained('cultures')->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('depredateur_id')->nullable()->constrained('depredateurs')->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('unit_id')->nullable()->constrained('units')->cascadeOnUpdate()->nullOnDelete();
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
