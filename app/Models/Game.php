<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'thumbnail',
        'html_template',
        'css_style',
        'js_code',
        'custom_template',
        'game_images',
        'category',
        'is_active',
        'order'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Relasi ke questions
     */
    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    /**
     * Relasi ke game sessions
     */
    public function sessions()
    {
        return $this->hasMany(GameSession::class);
    }

    /**
     * Get active questions only
     */
    public function activeQuestions()
    {
        return $this->hasMany(Question::class)->where('is_active', true);
    }
}
