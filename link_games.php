<?php
use Illuminate\Support\Facades\DB;
use App\Models\Game;
use App\Models\GameTemplate;

$games = Game::all();
foreach ($games as $game) {
    if (!$game->template_id) {
        $defaultTemplate = GameTemplate::where('slug', 'kuis-gameshow')->first();
        if ($defaultTemplate) {
            echo "🔗 Linking game '{$game->title}' to template 'Kuis Gameshow'\n";
            $game->update(['template_id' => $defaultTemplate->id]);
        }
    } else {
        $template = GameTemplate::find($game->template_id);
        echo "✅ Game '{$game->title}' is already linked to template: " . ($template ? $template->name : 'N/A') . "\n";
    }
}
