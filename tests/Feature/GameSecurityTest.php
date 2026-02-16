<?php

namespace Tests\Feature;

use App\Models\Game;
use App\Models\GameSession;
use App\Models\GameTemplate;
use App\Models\Question;
use App\Models\Score;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GameSecurityTest extends TestCase
{
    use RefreshDatabase;

    private function studentSession(Student $student): array
    {
        return [
            'student_id' => $student->id,
            'student_name' => $student->nama_anak,
            'student_class' => $student->kelas,
            'is_student_logged_in' => true,
        ];
    }

    public function test_submit_answer_rejects_question_from_other_game(): void
    {
        $student = Student::create(['nama_anak' => 'Siswa Test', 'kelas' => '3']);

        $gameA = Game::create([
            'title' => 'Game A',
            'slug' => 'game-a',
            'is_active' => true,
            'class' => '3',
        ]);

        $gameB = Game::create([
            'title' => 'Game B',
            'slug' => 'game-b',
            'is_active' => true,
            'class' => '3',
        ]);

        $questionA = Question::create([
            'game_id' => $gameA->id,
            'question_text' => '2+2?',
            'correct_answer' => '4',
            'points' => 10,
            'is_active' => true,
        ]);

        $questionB = Question::create([
            'game_id' => $gameB->id,
            'question_text' => '3+3?',
            'correct_answer' => '6',
            'points' => 10,
            'is_active' => true,
        ]);

        $session = GameSession::create([
            'student_id' => $student->id,
            'game_id' => $gameA->id,
            'started_at' => now(),
        ]);

        $response = $this->withSession($this->studentSession($student))
            ->postJson(route('games.answer', $session->id), [
                'question_id' => $questionB->id,
                'answer' => '6',
            ]);

        $response->assertStatus(422);
        $this->assertDatabaseCount('scores', 0);
        $this->assertDatabaseHas('questions', ['id' => $questionA->id]);
    }

    public function test_submit_answer_blocks_duplicate_submission_for_same_question(): void
    {
        $student = Student::create(['nama_anak' => 'Siswa Test', 'kelas' => '3']);

        $game = Game::create([
            'title' => 'Game Dup',
            'slug' => 'game-dup',
            'is_active' => true,
            'class' => '3',
        ]);

        $question = Question::create([
            'game_id' => $game->id,
            'question_text' => 'Pilih A',
            'correct_answer' => 'A',
            'options' => ['A' => 'A', 'B' => 'B'],
            'points' => 10,
            'is_active' => true,
        ]);

        $session = GameSession::create([
            'student_id' => $student->id,
            'game_id' => $game->id,
            'started_at' => now(),
        ]);

        $first = $this->withSession($this->studentSession($student))
            ->postJson(route('games.answer', $session->id), [
                'question_id' => $question->id,
                'answer' => 'A',
            ]);
        $first->assertOk();

        $second = $this->withSession($this->studentSession($student))
            ->postJson(route('games.answer', $session->id), [
                'question_id' => $question->id,
                'answer' => 'A',
            ]);
        $second->assertStatus(409);

        $this->assertDatabaseCount('scores', 1);
        $session->refresh();
        $this->assertSame(1, $session->total_questions);
        $this->assertSame(10, $session->total_score);
    }

    public function test_iframe_embed_points_are_capped_by_question_points(): void
    {
        $student = Student::create(['nama_anak' => 'Siswa Embed', 'kelas' => '3']);

        $template = GameTemplate::create([
            'name' => 'Iframe Embed',
            'slug' => 'iframe-embed-test',
            'template_type' => 'iframe_embed',
            'is_active' => true,
        ]);

        $game = Game::create([
            'title' => 'Game Embed',
            'slug' => 'game-embed',
            'template_id' => $template->id,
            'is_active' => true,
            'class' => '3',
        ]);

        $question = Question::create([
            'game_id' => $game->id,
            'question_text' => 'Input skor',
            'correct_answer' => '<iframe></iframe>',
            'points' => 10,
            'is_active' => true,
        ]);

        $session = GameSession::create([
            'student_id' => $student->id,
            'game_id' => $game->id,
            'started_at' => now(),
        ]);

        $response = $this->withSession($this->studentSession($student))
            ->postJson(route('games.answer', $session->id), [
                'question_id' => $question->id,
                'answer' => 999,
            ]);

        $response->assertOk()->assertJson([
            'points' => 10,
            'correct' => true,
        ]);

        $score = Score::firstOrFail();
        $this->assertSame(10, $score->points_earned);
    }

    public function test_student_cannot_access_game_for_different_class(): void
    {
        $student = Student::create(['nama_anak' => 'Siswa Kelas 3', 'kelas' => '3']);

        $game = Game::create([
            'title' => 'Game Kelas 4',
            'slug' => 'game-kelas-4',
            'is_active' => true,
            'class' => '4',
        ]);

        $showResponse = $this->withSession($this->studentSession($student))
            ->get(route('games.show', $game->slug));
        $showResponse->assertRedirect(route('games.index'));

        $startResponse = $this->withSession($this->studentSession($student))
            ->post(route('games.start', $game->slug));
        $startResponse->assertRedirect(route('games.index'));
    }

    public function test_student_logout_route_requires_post(): void
    {
        $student = Student::create(['nama_anak' => 'Logout Test', 'kelas' => '2']);

        $this->withSession($this->studentSession($student))
            ->post(route('student.logout'))
            ->assertRedirect(route('home'));

        $this->get('/student/logout')->assertStatus(405);
    }
}
