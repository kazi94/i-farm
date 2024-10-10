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

        Schema::create('farmers', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50);
            $table->string('fullname', 200)->index();
            $table->string('address', 100)->nullable();
            $table->string('phone1', 100)->nullable();
            $table->string('phone2', 100)->nullable();
            $table->string('website', 100)->nullable();
            $table->string('facebook_url', 100)->nullable();
            $table->string('twitter_url', 100)->nullable();
            $table->string('instagram_url', 100)->nullable();
            $table->string('linkedin_url', 100)->nullable();
            $table->string('image_url', 100)->nullable();
            $table->string('email', 100)->nullable();
            $table->string('note', 500)->nullable();
            $table->float('latitude')->nullable();
            $table->float('longitude')->nullable();
            $table->enum('activity', ["culture", "culture_livestock"])->default('culture');
            $table->enum('status', ["silver", "bronze", "gold"])->default('silver');
            $table->foreignId('commune_id')->constrained();
            $table->foreignId('daira_id')->constrained();
            $table->foreignId('wilaya_id')->constrained();
            $table->foreignId('created_by')->nullable()->constrained('users')->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->cascadeOnUpdate()->nullOnDelete();
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();
        });

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('farmers');
    }
};
