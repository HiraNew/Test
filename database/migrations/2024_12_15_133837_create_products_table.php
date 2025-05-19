<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id(); // Primary key for the product
            $table->string('name'); // Name of the product
            $table->string('slug')->unique(); // URL-friendly version of the name
            $table->text('ldescription')->nullable(); // Detailed description of the product
            $table->text('sdescription')->nullable();
            $table->decimal('price', 8, 2); // Price of the product (supports two decimal places)
            $table->integer('quantity')->default(0); // Available quantity
            $table->string('image')->nullable(); // Product image (nullable if not uploaded)
            $table->enum('status', ['active', 'inactive'])->default('active'); // Product status (e.g., active, inactive)
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade'); // Foreign key to the `users` table
            $table->foreignId('category_id')->constrained('categorys')->onDelete('cascade'); // Foreign key to the `categories` table (nullable)
            $table->timestamps(); // created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}

