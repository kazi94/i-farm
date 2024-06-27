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
            $table->unsignedFloat('price', 8, 2)->nullable()->default(0);
            $table->string('injection_mode')->nullable();
            $table->string('utilization')->nullable();
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
