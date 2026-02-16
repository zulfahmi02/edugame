<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Question;
use App\Models\GameSession;
use App\Models\Score;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class GameController extends Controller
{
    private function getLoggedInStudent(): ?\App\Models\Student
    {
        $studentId = session('student_id');

        if (!$studentId) {
            return null;
        }

        return \App\Models\Student::find($studentId);
    }

    private function canStudentAccessGame(\App\Models\Student $student, Game $game): bool
    {
        if (!filled($game->class)) {
            return true;
        }

        return (string) $game->class === (string) $student->kelas;
    }

    /**
     * Show games index (dashboard for students) - ONLY TEACHER GAMES
     */
    public function index(Request $request)
    {
        if (!session('student_id')) {
            return redirect()->route('home')->with('error', 'Silakan login terlebih dahulu');
        }

        $student = $this->getLoggedInStudent();
        if (!$student) {
            session()->forget(['student_id', 'student_name', 'student_class', 'is_student_logged_in']);
            return redirect()->route('home')->with('show_login', true)->with('error', 'Sesi login tidak valid. Silakan login kembali.');
        }

        $selectedCategory = $request->get('category');
        
        // Ambil semua kategori unik yang tersedia untuk kelas siswa ini (untuk tombol filter)
        $availableCategories = Game::where('is_active', true)
            ->whereNotNull('teacher_id')
            ->where(function ($q) use ($student) {
                $q->where('class', $student->kelas)
                    ->orWhereNull('class');
            })
            ->whereNotNull('category')
            ->distinct()
            ->pluck('category');

        // Tampilkan game yang sesuai kelas siswa ini
        $query = Game::where('is_active', true)
            ->whereNotNull('teacher_id')
            ->where(function ($q) use ($student) {
                $q->where('class', $student->kelas)
                    ->orWhereNull('class');
            });

        if ($selectedCategory) {
            $query->where('category', $selectedCategory);
        }

        $games = $query->orderBy('order', 'asc')->get();

        return view('game.index', compact('games', 'student', 'availableCategories', 'selectedCategory'));
    }

    /**
     * Show all games - ONLY SPECIAL/ADMIN GAMES
     */
    public function all(Request $request)
    {
        if (!session('student_id')) {
            return redirect()->route('home')->with('error', 'Silakan login terlebih dahulu');
        }

        $student = $this->getLoggedInStudent();
        if (!$student) {
            session()->forget(['student_id', 'student_name', 'student_class', 'is_student_logged_in']);
            return redirect()->route('home')->with('show_login', true)->with('error', 'Sesi login tidak valid. Silakan login kembali.');
        }

        $selectedCategory = $request->get('category');

        // Filter: hanya game admin (teacher_id NULL) dan sesuaikan dengan kelas siswa
        $query = Game::where('is_active', true)
            ->whereNull('teacher_id')
            ->where(function($q) use ($student) {
                $q->where('class', $student->kelas)
                  ->orWhereNull('class'); // Game untuk semua kelas
            });

        // Ambil kategori tersedia untuk filter
        $availableCategories = (clone $query)->whereNotNull('category')->distinct()->pluck('category');

        if ($selectedCategory) {
            $query->where('category', $selectedCategory);
        }

        $games = $query->orderBy('order', 'asc')->get();

        return view('game.all', compact('games', 'student', 'availableCategories', 'selectedCategory'));
    }

    /**
     * Show history page
     */
    public function history()
    {
        if (!session('student_id')) {
            return redirect()->route('home')->with('error', 'Silakan login terlebih dahulu');
        }

        $history_sessions = GameSession::where('student_id', session('student_id'))
            ->with(['game'])
            ->whereNotNull('completed_at')
            ->latest()
            ->get();

        // Calculate Stats
        $total_games = $history_sessions->count();
        $total_score = $history_sessions->sum('total_score');
        $highest_score = $history_sessions->max('total_score') ?? 0;
        
        // Split Scores: Weekly Games (Bahasa Indonesia) vs Teacher Games
        $weekly_game_score = $history_sessions->filter(function ($session) {
            return $session->game && $session->game->category === 'Bahasa Indonesia';
        })->sum('total_score');

        $teacher_game_score = $history_sessions->filter(function ($session) {
            return $session->game && $session->game->category !== 'Bahasa Indonesia';
        })->sum('total_score');

        // Split Sessions for Display
        $weekly_sessions = $history_sessions->filter(function ($session) {
            return $session->game && $session->game->category === 'Bahasa Indonesia';
        });

        $teacher_sessions = $history_sessions->filter(function ($session) {
            return $session->game && $session->game->category !== 'Bahasa Indonesia';
        });

        // Average Score
        $average_score = $total_games > 0 ? round($history_sessions->avg('total_score')) : 0;

        return view('game.history', compact(
            'history_sessions', 
            'total_games', 
            'total_score', 
            'highest_score', 
            'average_score', 
            'weekly_game_score', 
            'teacher_game_score',
            'weekly_sessions',
            'teacher_sessions'
        ));
    }

    /**
     * Show specific game detail
     */
    public function show($slug)
    {
        if (!session('student_id')) {
            return redirect()->route('home')->with('error', 'Silakan login terlebih dahulu');
        }

        $student = $this->getLoggedInStudent();
        if (!$student) {
            session()->forget(['student_id', 'student_name', 'student_class', 'is_student_logged_in']);
            return redirect()->route('home')->with('show_login', true)->with('error', 'Sesi login tidak valid. Silakan login kembali.');
        }

        $game = Game::where('slug', $slug)->where('is_active', true)->firstOrFail();
        if (!$this->canStudentAccessGame($student, $game)) {
            return redirect()->route('games.index')->with('error', 'Game ini tidak tersedia untuk kelas kamu.');
        }

        $questionsCount = $game->activeQuestions()->count();

        return view('game.detail', compact('game', 'questionsCount'));
    }

    /**
     * Start a new game session
     */
    public function start($slug)
    {
        if (!session('student_id')) {
            // Simpan slug game yang dituju untuk redirect setelah login
            session(['intended_game_slug' => $slug]);
            return redirect()->route('home')->with('show_login', true)->with('error', 'Silakan login terlebih dahulu untuk bermain!');
        }

        $student = $this->getLoggedInStudent();
        if (!$student) {
            session()->forget(['student_id', 'student_name', 'student_class', 'is_student_logged_in']);
            return redirect()->route('home')->with('show_login', true)->with('error', 'Sesi login tidak valid. Silakan login kembali.');
        }

        $game = Game::where('slug', $slug)->where('is_active', true)->firstOrFail();
        if (!$this->canStudentAccessGame($student, $game)) {
            return redirect()->route('games.index')->with('error', 'Game ini tidak tersedia untuk kelas kamu.');
        }

        // Create new game session
        $session = GameSession::create([
            'student_id' => session('student_id'),
            'game_id' => $game->id,
            'started_at' => now(),
            'total_questions' => 0,
            'correct_answers' => 0,
            'total_score' => 0
        ]);

        return redirect()->route('games.question', $session->id);
    }

    /**
     * Get and display question for current session
     */
    public function getQuestion($sessionId)
    {
        if (!session('student_id')) {
            return redirect()->route('home')->with('error', 'Silakan login terlebih dahulu');
        }

        $session = GameSession::with(['game.template'])->findOrFail($sessionId);

        // Check if session belongs to current student
        if ($session->student_id != session('student_id')) {
            abort(403, 'Unauthorized');
        }

        // Legacy full-page template (stored as raw HTML)
        if (
            $session->game->custom_template_enabled &&
            filled($session->game->custom_template) &&
            is_null($session->game->teacher_id)
        ) {
            return view('game.custom', compact('session'));
        }

        // Get random question that hasn't been answered in this session
        $answeredQuestionIds = Score::where('game_session_id', $sessionId)
            ->pluck('question_id')
            ->toArray();

        $question = Question::where('game_id', $session->game_id)
            ->where('is_active', true)
            ->whereNotIn('id', $answeredQuestionIds)
            ->inRandomOrder()
            ->first();

        // If no more questions, finish the game
        if (!$question) {
            return redirect()->route('games.finish', $sessionId);
        }

        return view('game.play', compact('session', 'question'));
    }

    /**
     * Submit answer for a question
     */
    public function submitAnswer(Request $request, $sessionId)
    {
        if (!session('student_id')) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $validator = Validator::make($request->all(), [
            'question_id' => ['required', 'integer'],
            'answer' => ['nullable'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->first(),
            ], 422);
        }

        $questionId = (int) $request->input('question_id');
        $submittedAnswer = $request->input('answer');
        if (is_array($submittedAnswer)) {
            $submittedAnswer = json_encode($submittedAnswer);
        }
        $answer = is_null($submittedAnswer) ? '' : (string) $submittedAnswer;

        $result = DB::transaction(function () use ($sessionId, $questionId, $answer) {
            $session = GameSession::whereKey($sessionId)
                ->lockForUpdate()
                ->firstOrFail();

            if ($session->student_id != session('student_id')) {
                return ['error' => 'Unauthorized', 'status' => 403];
            }

            if ($session->completed_at) {
                return ['error' => 'Sesi game sudah selesai.', 'status' => 409];
            }

            $question = Question::with('game.template')
                ->where('id', $questionId)
                ->where('game_id', $session->game_id)
                ->where('is_active', true)
                ->first();

            if (!$question) {
                return ['error' => 'Soal tidak valid untuk sesi ini.', 'status' => 422];
            }

            $alreadyAnswered = Score::where('game_session_id', $sessionId)
                ->where('question_id', $question->id)
                ->exists();

            if ($alreadyAnswered) {
                return ['error' => 'Soal ini sudah dijawab.', 'status' => 409];
            }

            $templateType = $question->game?->template?->template_type;

            if ($templateType === 'iframe_embed') {
                $rawPoints = is_numeric($answer) ? (int) $answer : 0;
                $maxPoints = max((int) $question->points, 0);
                $pointsEarned = min(max($rawPoints, 0), $maxPoints);
                $isCorrect = $pointsEarned > 0;
            } else {
                $isCorrect = $question->checkAnswer($answer);
                $pointsEarned = $isCorrect ? (int) $question->points : 0;
            }

            Score::create([
                'student_id' => session('student_id'),
                'game_session_id' => $sessionId,
                'question_id' => $question->id,
                'answer' => $answer,
                'is_correct' => $isCorrect,
                'points_earned' => $pointsEarned,
            ]);

            $session->total_questions += 1;
            if ($isCorrect) {
                $session->correct_answers += 1;
            }
            $session->total_score += $pointsEarned;
            $session->save();

            $correctAnswerForUser = $question->correct_answer;
            if (is_string($correctAnswerForUser)) {
                $decodedCorrect = json_decode($correctAnswerForUser, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($decodedCorrect)) {
                    if (is_array($question->options)) {
                        $correctAnswerForUser = collect($decodedCorrect)
                            ->map(fn($key) => $question->options[$key] ?? $key)
                            ->implode(' → ');
                    } else {
                        $correctAnswerForUser = collect($decodedCorrect)->implode(' → ');
                    }
                } elseif (is_array($question->options) && array_key_exists($correctAnswerForUser, $question->options)) {
                    $correctAnswerForUser = $question->options[$correctAnswerForUser];
                } elseif ($templateType === 'labeled_diagram' && trim($correctAnswerForUser) !== '') {
                    $correctAnswerForUser = 'Titik ' . $correctAnswerForUser;
                }
            }

            return [
                'status' => 200,
                'correct' => $isCorrect,
                'points' => $pointsEarned,
                'correct_answer' => strip_tags((string) $correctAnswerForUser),
                'game_id' => $session->game_id,
            ];
        });

        if (($result['status'] ?? 200) !== 200) {
            return response()->json(['error' => $result['error'] ?? 'Terjadi kesalahan.'], $result['status']);
        }

        $answeredQuestionIds = Score::where('game_session_id', $sessionId)
            ->pluck('question_id')
            ->toArray();

        $nextQuestion = Question::where('game_id', $result['game_id'])
            ->where('is_active', true)
            ->whereNotIn('id', $answeredQuestionIds)
            ->first();

        return response()->json([
            'correct' => $result['correct'],
            'points' => $result['points'],
            'correct_answer' => $result['correct_answer'],
            'is_last' => !$nextQuestion,
        ]);
    }

    /**
     * Finish game session and show results
     */
    public function finish($sessionId)
    {
        if (!session('student_id')) {
            return redirect()->route('home')->with('error', 'Silakan login terlebih dahulu');
        }

        $session = GameSession::with('game')->findOrFail($sessionId);

        if ($session->student_id != session('student_id')) {
            abort(403, 'Unauthorized');
        }

        // Mark session as completed
        if (!$session->completed_at) {
            $session->update(['completed_at' => now()]);
        }

        return view('game.result', compact('session'));
    }

    /**
     * Retry the same game
     */
    public function retry($sessionId)
    {
        if (!session('student_id')) {
            return redirect()->route('home')->with('error', 'Silakan login terlebih dahulu');
        }

        $session = GameSession::findOrFail($sessionId);

        if ($session->student_id != session('student_id')) {
            abort(403, 'Unauthorized');
        }

        // Start new session for the same game
        return redirect()->route('games.start', $session->game->slug);
    }
}
