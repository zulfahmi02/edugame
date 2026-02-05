<?php

namespace App\Http\Controllers;

use App\Models\OrangTua;
use App\Models\Student;
use App\Models\GameSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\ParentRegisterRequest;

class ParentController extends Controller
{
    /**
     * Show login form
     */
    public function showLoginForm()
    {
        return view('parent.login');
    }

    /**
     * Process login
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Find parent by email
        $parent = OrangTua::where('email', $request->email)->first();

        // Check if parent exists and password matches
        if ($parent && Hash::check($request->password, $parent->password)) {
            session([
                'parent_id' => $parent->id,
                'parent_name' => $parent->parent_name
            ]);

            return redirect()->route('parent.dashboard');
        }

        return back()->with('error', 'Email atau password salah!');
    }

    /**
     * Show register form
     */
    public function showRegisterForm()
    {
        return view('parent.register');
    }

    /**
     * Process registration
     */
    public function register(ParentRegisterRequest $request)
    {
        // Create new parent account
        $parent = OrangTua::create([
            'parent_name' => $request->parent_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'gender' => $request->gender,
            'child_name' => '', // Will be filled later when adding children
        ]);

        // Auto-login after registration
        session([
            'parent_id' => $parent->id,
            'parent_name' => $parent->parent_name
        ]);

        return redirect()->route('parent.dashboard')->with('success', 'Pendaftaran berhasil! Selamat datang, ' . $parent->parent_name);
    }
    /**
     * Show parent dashboard
     */
    public function dashboard()
    {
        if (!session('parent_id')) {
            return redirect()->route('parent.login');
        }

        $parent = OrangTua::with('students')->findOrFail(session('parent_id'));

        // Get all children of this parent
        $students = $parent->students;

        // Last 7 days range (including today)
        $rangeStart = now()->subDays(6)->startOfDay();
        $rangeEnd = now()->endOfDay();

        $dayKeys = [];
        $dayLabels = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $dayKeys[] = $date->toDateString();
            $dayLabels[] = $date->format('d M');
        }

        // Get game sessions for all children (last 7 days)
        $allSessions = [];
        $allSchedules = [];
        $dailyGameCounts = [];
        $totalGamesPlayed = 0;
        $totalScore = 0;
        $totalQuestions = 0;
        $totalCorrect = 0;

        foreach ($students as $student) {
            $sessions = GameSession::with('game')
                ->where('student_id', $student->id)
                ->whereNotNull('completed_at')
                ->whereBetween('completed_at', [$rangeStart, $rangeEnd])
                ->orderBy('completed_at', 'desc')
                ->get();

            $allSessions[$student->id] = $sessions;

            $counts = array_fill_keys($dayKeys, 0);
            foreach ($sessions as $session) {
                $key = $session->completed_at->toDateString();
                if (isset($counts[$key])) {
                    $counts[$key]++;
                }
            }

            $dailyGameCounts[$student->id] = [
                'labels' => $dayLabels,
                'values' => array_values($counts),
                'max' => max($counts) ?: 1,
            ];

            // Get schedules for this student
            $allSchedules[$student->id] = \App\Models\Schedule::active()
                ->forStudent($student->id)
                ->with('teacher')
                ->orderBy('day_of_week')
                ->orderBy('start_time')
                ->get();

            // Calculate totals
            $totalGamesPlayed += $sessions->count();
            $totalScore += $sessions->sum('total_score');
            $totalQuestions += $sessions->sum('total_questions');
            $totalCorrect += $sessions->sum('correct_answers');
        }

        $overallAccuracy = $totalQuestions > 0 ? round(($totalCorrect / $totalQuestions) * 100, 2) : 0;

        return view('parent.dashboard', compact(
            'parent',
            'students',
            'allSessions',
            'allSchedules',
            'totalGamesPlayed',
            'totalScore',
            'overallAccuracy',
            'dailyGameCounts'
        ));
    }

    /**
     * Show jadwal page
     */
    public function jadwal()
    {
        if (!session('parent_id')) {
            return redirect()->route('parent.login');
        }

        $parent = OrangTua::with('students')->findOrFail(session('parent_id'));
        $students = $parent->students;

        $allSchedules = [];
        $totalSchedules = 0;

        foreach ($students as $student) {
            $schedules = \App\Models\Schedule::active()
                ->forStudent($student->id)
                ->with('teacher')
                ->orderBy('day_of_week')
                ->orderBy('start_time')
                ->get();

            $allSchedules[$student->id] = $schedules;
            $totalSchedules += $schedules->count();
        }

        return view('parent.jadwal', compact(
            'parent',
            'students',
            'allSchedules',
            'totalSchedules'
        ));
    }

    /**
     * Logout
     */
    public function logout()
    {
        session()->forget(['parent_id', 'parent_name']);
        return redirect()->route('parent.login')->with('success', 'Berhasil logout');
    }
}
