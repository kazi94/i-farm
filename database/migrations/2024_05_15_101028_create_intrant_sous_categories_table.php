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
        Schema::create('intrant_sous_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->string('description', 100)->nullable();
            $table->foreignId('intrant_category_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('intrant_sous_categories');
    }
};
