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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            // $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->integer('qty');
            $table->string('amount');
            $table->string('payment_mode')->default('Cash');
            $table->integer('pincode');
            $table->string('order_date')->default('today');
            $table->string('delevery_date')->default('today');
            $table->boolean('Return_period')->default('0');
            $table->string('Remarks_by_user')->default('No');
            $table->boolean('is_canceled')->default('0');
            $table->boolean('status')->default('0');
            $table->string('orderid');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
