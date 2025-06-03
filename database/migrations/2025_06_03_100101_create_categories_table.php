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
        Schema::create('categories', function (Blueprint $table) {
            $table->id(); // Primary key

            $table->string('name'); // Category name
            $table->string('slug')->unique(); // Slug for URLs
            $table->text('description')->nullable(); // Optional description

            $table->string('icon', 50)->nullable(); // Icon name/path

            $table->unsignedBigInteger('parent_id')->nullable(); // Self-reference for subcategories
            $table->foreign('parent_id')->references('id')->on('categories')->onDelete('cascade');

            $table->foreignId('created_by')->constrained('users')->onDelete('cascade'); // Who created it

            $table->boolean('status')->default(0); // 0 = Active, 1 = Inactive or vice-versa

            $table->timestamps(); // created_at & updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
