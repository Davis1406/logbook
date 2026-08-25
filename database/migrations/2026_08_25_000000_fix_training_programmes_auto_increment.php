<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * The training_programmes.id column was missing AUTO_INCREMENT (an artifact
 * of how the production database was provisioned), which caused inserts
 * (both from the app's "Add Programme" form and from imports) to fail with
 * "Field 'id' doesn't have a default value". This restores it and makes
 * sure the counter starts above the current max id.
 */
return new class extends Migration
{
    public function up()
    {
        // The 'id' column is referenced by rotations.programme_id, so MySQL
        // won't allow modifying it in place; drop and recreate the FK.
        if ($this->hasForeignKey('rotations', 'rotations_programme_id_foreign')) {
            DB::statement('ALTER TABLE rotations DROP FOREIGN KEY rotations_programme_id_foreign');
        }

        DB::statement('ALTER TABLE training_programmes MODIFY id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT');

        $maxId = DB::table('training_programmes')->max('id') ?? 0;
        DB::statement('ALTER TABLE training_programmes AUTO_INCREMENT = '.((int) $maxId + 1));

        DB::statement('ALTER TABLE rotations ADD CONSTRAINT rotations_programme_id_foreign FOREIGN KEY (programme_id) REFERENCES training_programmes (id) ON DELETE CASCADE');
    }

    public function down()
    {
        if ($this->hasForeignKey('rotations', 'rotations_programme_id_foreign')) {
            DB::statement('ALTER TABLE rotations DROP FOREIGN KEY rotations_programme_id_foreign');
        }

        DB::statement('ALTER TABLE training_programmes MODIFY id BIGINT UNSIGNED NOT NULL');

        DB::statement('ALTER TABLE rotations ADD CONSTRAINT rotations_programme_id_foreign FOREIGN KEY (programme_id) REFERENCES training_programmes (id) ON DELETE CASCADE');
    }

    private function hasForeignKey(string $table, string $constraintName): bool
    {
        $result = DB::selectOne(
            'SELECT COUNT(*) AS cnt FROM information_schema.TABLE_CONSTRAINTS
             WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND CONSTRAINT_NAME = ? AND CONSTRAINT_TYPE = "FOREIGN KEY"',
            [$table, $constraintName]
        );

        return $result && $result->cnt > 0;
    }
};
