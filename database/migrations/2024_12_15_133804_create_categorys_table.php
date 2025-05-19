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
        Schema::create('categorys', function (Blueprint $table) {
            $table->id(); // auto-incrementing primary key
            $table->string('name'); // category name
            $table->string('slug')->unique(); // URL-friendly version of the category name
            $table->text('description')->nullable(); // category description (nullable)
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade'); // foreign key to the users table
            $table->boolean('status')->default(0);
            $table->timestamps(); // created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categorys');
    }
};
