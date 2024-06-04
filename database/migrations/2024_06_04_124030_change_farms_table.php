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
        Schema::disableForeignKeyConstraints();
        Schema::table('farms', function (Blueprint $table) {
            $table->dropConstrainedForeignId('category_id');
            $table->foreignId('culture_id')->nullable()->constrained('cultures')->cascadeOnUpdate()->cascadeOnDelete();
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
