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

        Schema::table('intrants', function (Blueprint $table) {
            $table->unsignedSmallInteger('score')->nullable()->default(0)->comment('0-10');
            $table->boolean('is_approved')->default(true)->comment('0 - not approved, 1 - approved');
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
