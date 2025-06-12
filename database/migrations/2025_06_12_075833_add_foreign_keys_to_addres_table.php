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
            $table->unsignedBigInteger('country')->change();
            $table->unsignedBigInteger('state')->change();
            $table->unsignedBigInteger('city')->change();
            $table->unsignedBigInteger('village')->nullable()->change(); // optional field

            // Add foreign key constraints
            $table->foreign('country')->references('id')->on('countries')->onDelete('restrict');
            $table->foreign('state')->references('id')->on('states')->onDelete('restrict');
            $table->foreign('city')->references('id')->on('cities')->onDelete('restrict');
            $table->foreign('village')->references('id')->on('villages')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('addres', function (Blueprint $table) {
            $table->dropForeign(['country']);
            $table->dropForeign(['state']);
            $table->dropForeign(['city']);
            $table->dropForeign(['village']);
        });
    }
};
