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
        if (!Schema::hasTable('scores')) {
            return;
        }

        // Remove duplicate score rows first, keeping the earliest record per session/question.
        $duplicates = DB::table('scores')
            ->select('game_session_id', 'question_id', DB::raw('MIN(id) as keep_id'))
            ->groupBy('game_session_id', 'question_id')
            ->havingRaw('COUNT(*) > 1')
            ->get();

        foreach ($duplicates as $duplicate) {
            DB::table('scores')
                ->where('game_session_id', $duplicate->game_session_id)
                ->where('question_id', $duplicate->question_id)
                ->where('id', '!=', $duplicate->keep_id)
                ->delete();
        }

        $hasUniqueConstraint = collect(Schema::getIndexes('scores'))->contains(function (array $index): bool {
            $columns = $index['columns'] ?? [];
            sort($columns);

            return ($index['unique'] ?? false)
                && $columns === ['game_session_id', 'question_id'];
        });

        if ($hasUniqueConstraint) {
            return;
        }

        Schema::table('scores', function (Blueprint $table): void {
            $table->unique(['game_session_id', 'question_id'], 'scores_session_question_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('scores')) {
            return;
        }

        $indexNames = collect(Schema::getIndexes('scores'))
            ->pluck('name')
            ->filter()
            ->all();

        Schema::table('scores', function (Blueprint $table) use ($indexNames): void {
            if (in_array('scores_session_question_unique', $indexNames, true)) {
                $table->dropUnique('scores_session_question_unique');
            } elseif (in_array('scores_game_session_id_question_id_unique', $indexNames, true)) {
                $table->dropUnique('scores_game_session_id_question_id_unique');
            }
        });
    }
};
