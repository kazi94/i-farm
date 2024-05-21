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
        Schema::create('intrants', function (Blueprint $table) {
            $table->id();
            $table->string('name_fr', 100);
            $table->string('name_ar', 100)->nullable();
            $table->string('formulation', 50)->nullable();
            $table->string('homologation_number', 30)->nullable();
            $table->foreignId('firm_id')->nullable()->constrained('firms')->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('intrant_sous_category_id')->nullable()->constrained('intrant_sous_categories')->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('distributor_id')->nullable()->constrained('distributors')->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('intrant_category_id')->nullable()->constrained('intrant_categories')->cascadeOnUpdate()->nullOnDelete();
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('intrants');
    }
};
