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
        Schema::table('payments', function (Blueprint $table) {
            $table->string('size')->nullable();
            $table->string('weight')->nullable();
            $table->string('color')->nullable();
            $table->string('return_time')->nullable();
            $table->text('return_policy')->nullable();

            // Additional fields
            $table->string('feild1')->nullable();
            $table->string('feild2')->nullable();
            $table->string('feild3')->nullable();
            $table->string('feild4')->nullable();
            $table->string('feild5')->nullable();
            $table->string('feild6')->nullable();
            $table->string('feild7')->nullable();
            $table->string('feild8')->nullable();
            $table->string('feild9')->nullable();
            $table->string('feild10')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
             $table->dropColumn([
                'size', 'weight', 'color',
                'return_time', 'return_policy',
                'feild1', 'feild2', 'feild3', 'feild4', 'feild5',
                'feild6', 'feild7', 'feild8', 'feild9', 'feild10'
            ]);
        });
    }
};
