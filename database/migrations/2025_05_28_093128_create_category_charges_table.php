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
        Schema::create('category_charges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->string('charge_type'); // e.g., gst, platform_fee, delivery_charge, cod_charge
            $table->decimal('amount', 10, 2)->default(0.00); // Charge value
            $table->boolean('is_active')->default(true); // Can be disabled anytime
            $table->timestamps();

            $table->unique(['category_id', 'charge_type']); // prevent duplicate entries
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category_charges');
    }
};
