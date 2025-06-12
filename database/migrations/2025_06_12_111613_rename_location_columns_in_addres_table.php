<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Drop existing foreign key constraints
        DB::statement('ALTER TABLE addres DROP FOREIGN KEY addres_country_foreign');
        DB::statement('ALTER TABLE addres DROP FOREIGN KEY addres_state_foreign');
        DB::statement('ALTER TABLE addres DROP FOREIGN KEY addres_city_foreign');
        DB::statement('ALTER TABLE addres DROP FOREIGN KEY addres_village_foreign');

        // Rename columns using CHANGE (type must match original column)
        DB::statement('ALTER TABLE addres CHANGE country country_id BIGINT UNSIGNED NULL');
        DB::statement('ALTER TABLE addres CHANGE state state_id BIGINT UNSIGNED NULL');
        DB::statement('ALTER TABLE addres CHANGE city city_id BIGINT UNSIGNED NULL');
        DB::statement('ALTER TABLE addres CHANGE village village_id BIGINT UNSIGNED NULL');

        // Add updated foreign keys
        DB::statement('ALTER TABLE addres ADD CONSTRAINT addres_country_id_foreign FOREIGN KEY (country_id) REFERENCES countries(id) ON DELETE SET NULL');
        DB::statement('ALTER TABLE addres ADD CONSTRAINT addres_state_id_foreign FOREIGN KEY (state_id) REFERENCES states(id) ON DELETE SET NULL');
        DB::statement('ALTER TABLE addres ADD CONSTRAINT addres_city_id_foreign FOREIGN KEY (city_id) REFERENCES cities(id) ON DELETE SET NULL');
        DB::statement('ALTER TABLE addres ADD CONSTRAINT addres_village_id_foreign FOREIGN KEY (village_id) REFERENCES villages(id) ON DELETE SET NULL');
    }

    public function down(): void
    {
        // Drop new foreign keys
        DB::statement('ALTER TABLE addres DROP FOREIGN KEY addres_country_id_foreign');
        DB::statement('ALTER TABLE addres DROP FOREIGN KEY addres_state_id_foreign');
        DB::statement('ALTER TABLE addres DROP FOREIGN KEY addres_city_id_foreign');
        DB::statement('ALTER TABLE addres DROP FOREIGN KEY addres_village_id_foreign');

        // Revert column names
        DB::statement('ALTER TABLE addres CHANGE country_id country BIGINT UNSIGNED NULL');
        DB::statement('ALTER TABLE addres CHANGE state_id state BIGINT UNSIGNED NULL');
        DB::statement('ALTER TABLE addres CHANGE city_id city BIGINT UNSIGNED NULL');
        DB::statement('ALTER TABLE addres CHANGE village_id village BIGINT UNSIGNED NULL');

        // Re-add original foreign keys
        DB::statement('ALTER TABLE addres ADD CONSTRAINT addres_country_foreign FOREIGN KEY (country) REFERENCES countries(id) ON DELETE SET NULL');
        DB::statement('ALTER TABLE addres ADD CONSTRAINT addres_state_foreign FOREIGN KEY (state) REFERENCES states(id) ON DELETE SET NULL');
        DB::statement('ALTER TABLE addres ADD CONSTRAINT addres_city_foreign FOREIGN KEY (city) REFERENCES cities(id) ON DELETE SET NULL');
        DB::statement('ALTER TABLE addres ADD CONSTRAINT addres_village_foreign FOREIGN KEY (village) REFERENCES villages(id) ON DELETE SET NULL');
    }
};
