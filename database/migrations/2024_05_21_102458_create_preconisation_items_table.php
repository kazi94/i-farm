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

        Schema::create('preconisation_items', function (Blueprint $table) {
            $table->id();
            $table->integer('quantity')->nullable();
            $table->float('price')->nullable();
            $table->string('note', 100)->nullable();
            $table->foreignId('unit_id')->nullable()->constrained('units')->nullOnDelete()->cascadeOnUpdate();
            $table->foreignId('preconisation_id')->constrained('preconisations')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('intrant_id')->constrained('intrants')->cascadeOnDelete()->cascadeOnUpdate();
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('preconisation_items');
    }
};
