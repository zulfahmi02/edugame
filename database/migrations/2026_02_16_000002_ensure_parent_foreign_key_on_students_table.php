<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('students') || !Schema::hasColumn('students', 'parent_id')) {
            return;
        }

        // Normalize orphan references before adding/validating FK.
        DB::table('students')
            ->whereNotNull('parent_id')
            ->whereNotExists(function ($query): void {
                $query->select(DB::raw(1))
                    ->from('parents')
                    ->whereColumn('parents.id', 'students.parent_id');
            })
            ->update(['parent_id' => null]);

        $hasParentForeignKey = collect(Schema::getForeignKeys('students'))->contains(
            fn(array $foreignKey): bool =>
                in_array('parent_id', $foreignKey['columns'] ?? [], true)
                && (($foreignKey['foreign_table'] ?? null) === 'parents')
        );

        if ($hasParentForeignKey) {
            return;
        }

        Schema::table('students', function (Blueprint $table): void {
            $table->foreign('parent_id', 'students_parent_id_foreign')
                ->references('id')
                ->on('parents')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('students') || !Schema::hasColumn('students', 'parent_id')) {
            return;
        }

        $hasParentForeignKey = collect(Schema::getForeignKeys('students'))->contains(
            fn(array $foreignKey): bool =>
                in_array('parent_id', $foreignKey['columns'] ?? [], true)
                && (($foreignKey['foreign_table'] ?? null) === 'parents')
        );

        if (!$hasParentForeignKey) {
            return;
        }

        Schema::table('students', function (Blueprint $table): void {
            try {
                $table->dropForeign(['parent_id']);
            } catch (\Throwable) {
                // Ignore to keep rollback resilient across drivers with custom FK naming.
            }
        });
    }
};
