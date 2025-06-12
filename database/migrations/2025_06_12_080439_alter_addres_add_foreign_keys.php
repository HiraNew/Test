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
            // Ensure columns are of correct type
            $table->unsignedBigInteger('country')->change();
            $table->unsignedBigInteger('state')->change();
            $table->unsignedBigInteger('city')->change();
            $table->unsignedBigInteger('village')->nullable()->change();

            // Add foreign key constraints with custom names
            $table->foreign('country', 'fk_addres_country')->references('id')->on('countries')->onDelete('restrict');
            $table->foreign('state', 'fk_addres_state')->references('id')->on('states')->onDelete('restrict');
            $table->foreign('city', 'fk_addres_city')->references('id')->on('cities')->onDelete('restrict');
            $table->foreign('village', 'fk_addres_village')->references('id')->on('villages')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('addres', function (Blueprint $table) {
            $table->dropForeign('fk_addres_country');
            $table->dropForeign('fk_addres_state');
            $table->dropForeign('fk_addres_city');
            $table->dropForeign('fk_addres_village');
        });
    }
};
