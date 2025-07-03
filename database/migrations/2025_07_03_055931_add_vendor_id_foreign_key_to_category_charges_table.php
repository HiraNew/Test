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
        Schema::table('category_charges', function (Blueprint $table) {
             if (!Schema::hasColumn('category_charges', 'vendor_id')) {
                $table->unsignedBigInteger('vendor_id')->nullable()->after('id');
            }

            // Add foreign key constraint
            $table->foreign('vendor_id')
                  ->references('id')
                  ->on('vendors')  // assuming the table name is 'vendors'
                  ->onDelete('cascade'); // optional: choose your desired behavior
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('category_charges', function (Blueprint $table) {
             $table->dropForeign(['vendor_id']);
            // Optional: if you want to remove the column as well
            $table->dropColumn('vendor_id');
        });
    }
};
