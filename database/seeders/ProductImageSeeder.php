<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('extras')->insert([
            ['key' => 'cod_charge', 'value' => '30', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'delivery_charge', 'value' => '50', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'cod_excluded_categories', 'value' => json_encode([2, 5, 9]), 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
