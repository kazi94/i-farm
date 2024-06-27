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

        Schema::table('culture_intrant', function (Blueprint $table) {
            $table->foreignId('culture_setting_id')->nullable()->constrained('culture_settings')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('culture_variante_id')->nullable()->constrained('culture_variantes')->cascadeOnUpdate()->cascadeOnDelete();
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
