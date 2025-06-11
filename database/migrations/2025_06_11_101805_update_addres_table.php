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
        Schema::table('addres', function (Blueprint $table) {
            $table->string('city')->nullable();     // new
            $table->string('state')->nullable();    // new
            $table->string('country')->nullable();  // new
            $table->string('mobile_number')->nullable();        // new
            $table->string('alt_mobile_number')->nullable();    // new
            $table->integer('postal_code')->nullable();         // optional new
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('addres', function (Blueprint $table) {
            $table->dropColumn(['city', 'state', 'country', 'mobile_number', 'alt_mobile_number']);
        });
    }
};
