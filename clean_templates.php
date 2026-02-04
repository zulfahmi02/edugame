<?php
use Illuminate\Support\Facades\DB;

$gamesWithOverrides = DB::table('games')
    ->whereNotNull('html_template')
    ->where('html_template', '!=', '')
    ->get();

if ($gamesWithOverrides->isEmpty()) {
    echo "No games have custom template overrides.\n";
} else {
    foreach ($gamesWithOverrides as $game) {
        if (str_contains($game->html_template, '<!DOCTYPE html>') || str_contains($game->html_template, '<body>')) {
            echo "🧹 Cleaning broken Wordwall code from game: {$game->title}\n";
            DB::table('games')->where('id', $game->id)->update([
                'html_template' => null,
                'css_style' => null,
                'js_code' => null,
                'custom_template_enabled' => false
            ]);
        } else {
            echo "✅ Game '{$game->title}' has irrelevant custom template, skipping.\n";
        }
    }
}
