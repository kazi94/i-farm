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
        Schema::table('preconisations', function (Blueprint $table) {
            $table->foreignId('culture_id')->nullable()->constrained('cultures')->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('depredateur_id')->nullable()->constrained('depredateurs')->cascadeOnUpdate()->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
