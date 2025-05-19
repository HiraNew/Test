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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->foreignId('total_item')->constrained('carts')->onDelete('cascade');
            $table->foreignId('address_id')->constrained('addres')->onDelete('cascade');
            $table->string('order_date')->default('today');
            $table->string('delevery_date')->default('Unknown');
            $table->boolean('Return_period')->default('0');
            $table->string('Remarks_by_user')->default('No');
            $table->boolean('status')->default('0');
            $table->boolean('is_canceled')->default('0');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
