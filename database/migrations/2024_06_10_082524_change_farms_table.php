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
        Schema::table('farms', function (Blueprint $table) {
            $table->mediumInteger('density', false, true)->nullable();
            $table->mediumInteger('age', false, true)->nullable();
            $table->decimal('distance_tree', 4, 2, true)->nullable();
            $table->decimal('distance_line', 4, 2, true)->nullable();
            $table->decimal('n', 8, 2, true)->nullable();
            $table->decimal('p', 8, 2, true)->nullable();
            $table->decimal('k', 8, 2, true)->nullable();
            $table->decimal('ca', 8, 2, true)->nullable();
            $table->decimal('s', 8, 2, true)->nullable();
            $table->decimal('so3', 8, 2, true)->nullable();
            $table->decimal('mgo', 8, 2, true)->nullable();
            $table->decimal('b', 8, 2, true)->nullable();
            $table->decimal('cu', 8, 2, true)->nullable();
            $table->decimal('fe', 8, 2, true)->nullable();
            $table->decimal('mn', 8, 2, true)->nullable();

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
