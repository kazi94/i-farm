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
        Schema::create('intrant_principe_actif', function (Blueprint $table) {
            $table->id();
            $table->foreignId('intrant_id')->constrained('intrants')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('principe_actif_id')->nullable()->constrained('principe_actifs')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('unit_id')->nullable();
            $table->unsignedDecimal('concentration', 8, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('intrant_principe_actif');
    }
};
