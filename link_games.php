<?php
use Illuminate\Support\Facades\DB;
use App\Models\Game;
use App\Models\GameTemplate;

$games = Game::all();
foreach ($games as $game) {
    if (!$game->game_template_id) {
        $defaultTemplate = GameTemplate::where('slug', 'kuis-gameshow')->first();
        if ($defaultTemplate) {
            echo "🔗 Linking game '{$game->title}' to template 'Kuis Gameshow'\n";
            $game->update(['game_template_id' => $defaultTemplate->id]);
        }
    } else {
        $template = GameTemplate::find($game->game_template_id);
        echo "✅ Game '{$game->title}' is already linked to template: " . ($template ? $template->name : 'N/A') . "\n";
    }
}
