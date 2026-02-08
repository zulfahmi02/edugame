<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    /**
     * Dashboard admin
     */
    public function index()
    {
        $totalGames = Game::count();
        $totalQuestions = Question::count();
        $activeGames = Game::where('is_active', true)->count();
        
        return view('admin.dashboard', compact('totalGames', 'totalQuestions', 'activeGames'));
    }

    /**
     * Show login form
     */
    public function showLogin()
    {
        return view('admin.login');
    }

    /**
     * Process login
     */
    public function login(Request $request)
    {
        // Simple hardcoded admin login (bisa diganti dengan database nanti)
        if ($request->username == 'admin' && $request->password == 'admin123') {
            session(['admin_logged_in' => true, 'admin_name' => 'Administrator']);
            return redirect()->route('admin.dashboard');
        }
        
        return back()->with('error', 'Username atau password salah!');
    }

    /**
     * Logout admin
     */
    public function logout()
    {
        session()->forget(['admin_logged_in', 'admin_name']);
        return redirect()->route('admin.login')->with('success', 'Berhasil logout');
    }

    // ==================== GAMES MANAGEMENT ====================

    /**
     * List all games
     */
    public function games()
    {
        $games = Game::orderBy('order', 'asc')->get();
        return view('admin.games.index', compact('games'));
    }

    /**
     * Show create game form
     */
    public function createGame()
    {
        return view('admin.games.create');
    }

    /**
     * Store new game
     */
    public function storeGame(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'nullable|string',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'order' => 'nullable|integer',
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->title);
        
        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
            $file = $request->file('thumbnail');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images/games'), $filename);
            $data['thumbnail'] = 'images/games/' . $filename;
        }

        // Create game first to get ID
        $game = Game::create($data);

        // Handle multiple game images upload
        if ($request->hasFile('game_images')) {
            $uploadedImages = [];
            $gameFolder = public_path('images/game_assets/' . $game->id);
            
            // Create folder if not exists
            if (!file_exists($gameFolder)) {
                mkdir($gameFolder, 0777, true);
            }

            foreach ($request->file('game_images') as $image) {
                $filename = time() . '_' . uniqid() . '_' . $image->getClientOriginalName();
                $image->move($gameFolder, $filename);
                $uploadedImages[] = $filename;
            }

            // Save image filenames as JSON
            $game->game_images = json_encode($uploadedImages);
            $game->save();
        }

        return redirect()->route('admin.games')->with('success', 'Game berhasil ditambahkan!');
    }

    /**
     * Show edit game form
     */
    public function editGame($id)
    {
        $game = Game::findOrFail($id);
        return view('admin.games.edit', compact('game'));
    }

    /**
     * Update game
     */
    public function updateGame(Request $request, $id)
    {
        $game = Game::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'nullable|string',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'order' => 'nullable|integer',
        ]);

        $data = $request->all();
        $data['slug'] = Str::slug($request->title);

        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
            // Delete old thumbnail
            if ($game->thumbnail && file_exists(public_path($game->thumbnail))) {
                unlink(public_path($game->thumbnail));
            }
            
            $file = $request->file('thumbnail');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images/games'), $filename);
            $data['thumbnail'] = 'images/games/' . $filename;
        }

        $game->update($data);

        // Handle multiple game images upload
        if ($request->hasFile('game_images')) {
            $gameFolder = public_path('images/game_assets/' . $game->id);
            
            // Create folder if not exists
            if (!file_exists($gameFolder)) {
                mkdir($gameFolder, 0777, true);
            }

            // Get existing images
            $existingImages = $game->game_images ? json_decode($game->game_images, true) : [];
            $uploadedImages = [];

            foreach ($request->file('game_images') as $image) {
                $filename = time() . '_' . uniqid() . '_' . $image->getClientOriginalName();
                $image->move($gameFolder, $filename);
                $uploadedImages[] = $filename;
            }

            // Merge with existing images
            $allImages = array_merge($existingImages, $uploadedImages);
            $game->game_images = json_encode($allImages);
            $game->save();
        }

        return redirect()->route('admin.games')->with('success', 'Game berhasil diupdate!');
    }

    /**
     * Delete game
     */
    public function deleteGame($id)
    {
        $game = Game::findOrFail($id);
        
        // Delete thumbnail
        if ($game->thumbnail && file_exists(public_path($game->thumbnail))) {
            unlink(public_path($game->thumbnail));
        }
        
        $game->delete();

        return redirect()->route('admin.games')->with('success', 'Game berhasil dihapus!');
    }

    // ==================== QUESTIONS MANAGEMENT ====================

    /**
     * List questions for a specific game
     */
    public function questions($gameId)
    {
        $game = Game::findOrFail($gameId);
        $questions = Question::where('game_id', $gameId)->get();
        
        return view('admin.questions.index', compact('game', 'questions'));
    }

    /**
     * Show create question form
     */
    public function createQuestion($gameId)
    {
        $game = Game::findOrFail($gameId);
        return view('admin.questions.create', compact('game'));
    }

    /**
     * Store new question
     */
    public function storeQuestion(Request $request)
    {
        $request->validate([
            'game_id' => 'required|exists:games,id',
            'question_text' => 'required|string',
            'correct_answer' => 'required|string',
            'points' => 'nullable|integer|min:1',
            'difficulty' => 'nullable|in:easy,medium,hard',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();
        
        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('images/questions'), $imageName);
            $data['image'] = 'images/questions/' . $imageName;
        }
        
        // Handle question_data (untuk data tambahan seperti gambar, grid TTS, dll)
        if ($request->has('question_data')) {
            $data['question_data'] = $request->question_data;
        }

        // Handle options (untuk multiple choice)
        if ($request->has('options')) {
            $data['options'] = $request->options;
        }

        Question::create($data);

        return redirect()->route('admin.questions', $request->game_id)
            ->with('success', 'Soal berhasil ditambahkan!');
    }

    /**
     * Show edit question form
     */
    public function editQuestion($id)
    {
        $question = Question::with('game')->findOrFail($id);
        return view('admin.questions.edit', compact('question'));
    }

    /**
     * Update question
     */
    public function updateQuestion(Request $request, $id)
    {
        $question = Question::findOrFail($id);

        $request->validate([
            'question_text' => 'required|string',
            'correct_answer' => 'required|string',
            'points' => 'nullable|integer|min:1',
            'difficulty' => 'nullable|in:easy,medium,hard',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();
        
        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($question->image && file_exists(public_path($question->image))) {
                unlink(public_path($question->image));
            }
            
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('images/questions'), $imageName);
            $data['image'] = 'images/questions/' . $imageName;
        }
        
        if ($request->has('question_data')) {
            $data['question_data'] = $request->question_data;
        }

        if ($request->has('options')) {
            $data['options'] = $request->options;
        }

        $question->update($data);

        return redirect()->route('admin.questions', $question->game_id)
            ->with('success', 'Soal berhasil diupdate!');
    }

    /**
     * Delete question
     */
    public function deleteQuestion($id)
    {
        $question = Question::findOrFail($id);
        $gameId = $question->game_id;
        
        // Delete image if exists
        if ($question->image && file_exists(public_path($question->image))) {
            unlink(public_path($question->image));
        }
        
        $question->delete();

        return redirect()->route('admin.questions', $gameId)
            ->with('success', 'Soal berhasil dihapus!');
    }

    // ==================== PARENTS MANAGEMENT ====================

    /**
     * List all parents
     */
    public function parents()
    {
        $parents = \App\Models\OrangTua::withCount('students')->get();
        return view('admin.parents.index', compact('parents'));
    }

    /**
     * Show create parent form
     */
    public function createParent()
    {
        return view('admin.parents.create');
    }

    /**
     * Store new parent
     */
    public function storeParent(Request $request)
    {
        $request->validate([
            'parent_name' => 'required|string|max:255',
            'gender' => 'required|in:L,P',
            'email' => 'required|email|unique:parents,email',
            'password' => 'required|string|min:6',
        ]);

        \App\Models\OrangTua::create([
            'parent_name' => $request->parent_name,
            'gender' => $request->gender,
            'child_name' => $request->child_name ?? '',
            'email' => $request->email,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
        ]);

        return redirect()->route('admin.parents')->with('success', 'Orang tua berhasil ditambahkan!');
    }

    /**
     * Show edit parent form
     */
    public function editParent($id)
    {
        $parent = \App\Models\OrangTua::findOrFail($id);
        return view('admin.parents.edit', compact('parent'));
    }

    /**
     * Update parent
     */
    public function updateParent(Request $request, $id)
    {
        $parent = \App\Models\OrangTua::findOrFail($id);

        $request->validate([
            'parent_name' => 'required|string|max:255',
            'email' => 'required|email|unique:parents,email,' . $id,
            'password' => 'nullable|string|min:6',
        ]);

        $data = [
            'parent_name' => $request->parent_name,
            'child_name' => $request->child_name ?? '',
            'email' => $request->email,
        ];

        // Only update password if provided
        if ($request->filled('password')) {
            $data['password'] = \Illuminate\Support\Facades\Hash::make($request->password);
        }

        $parent->update($data);

        return redirect()->route('admin.parents')->with('success', 'Orang tua berhasil diupdate!');
    }

    /**
     * Delete parent
     */
    public function deleteParent($id)
    {
        $parent = \App\Models\OrangTua::findOrFail($id);
        $parent->delete();

        return redirect()->route('admin.parents')->with('success', 'Orang tua berhasil dihapus!');
    }

    // ==================== STUDENTS MANAGEMENT ====================

    /**
     * List all students
     */
    public function students()
    {
        $students = \App\Models\Student::with('orangtua')->get();
        return view('admin.students.index', compact('students'));
    }

    /**
     * Show create student form
     */
    public function createStudent()
    {
        $parents = \App\Models\OrangTua::all();
        return view('admin.students.create', compact('parents'));
    }

    /**
     * Store new student
     */
    public function storeStudent(Request $request)
    {
        $request->validate([
            'nama_anak' => 'required|string|max:255',
            'kelas' => 'required|integer|min:1|max:6',
            'parent_id' => 'required|exists:parents,id',
        ]);

        \App\Models\Student::create($request->all());

        return redirect()->route('admin.students')->with('success', 'Anak berhasil ditambahkan!');
    }

    /**
     * Show edit student form
     */
    public function editStudent($id)
    {
        $student = \App\Models\Student::findOrFail($id);
        $parents = \App\Models\OrangTua::all();
        return view('admin.students.edit', compact('student', 'parents'));
    }

    /**
     * Update student
     */
    public function updateStudent(Request $request, $id)
    {
        $student = \App\Models\Student::findOrFail($id);

        $request->validate([
            'nama_anak' => 'required|string|max:255',
            'kelas' => 'required|integer|min:1|max:6',
            'parent_id' => 'required|exists:parents,id',
        ]);

        $student->update($request->all());

        return redirect()->route('admin.students')->with('success', 'Data anak berhasil diupdate!');
    }

    /**
     * Delete student
     */
    public function deleteStudent($id)
    {
        $student = \App\Models\Student::findOrFail($id);
        $student->delete();

    }

    // ==================== POSTERS MANAGEMENT ====================

    /**
     * Display all posters
     */
    public function posters()
    {
        $posters = \App\Models\Poster::orderBy('order', 'asc')->orderBy('created_at', 'desc')->get();
        return view('admin.posters.index', compact('posters'));
    }

    /**
     * Show create poster form
     */
    public function createPoster()
    {
        return view('admin.posters.create');
    }

    /**
     * Store new poster
     */
    public function storePoster(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120',
            'category' => 'nullable|string|max:100',
            'order' => 'nullable|integer',
        ]);

        $data = $request->except('image');

        // Handle image upload
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('images/posters'), $imageName);
            $data['image'] = 'images/posters/' . $imageName;
        }

        \App\Models\Poster::create($data);

        return redirect()->route('admin.posters')->with('success', 'Poster berhasil ditambahkan!');
    }

    /**
     * Show edit poster form
     */
    public function editPoster($id)
    {
        $poster = \App\Models\Poster::findOrFail($id);
        return view('admin.posters.edit', compact('poster'));
    }

    /**
     * Update poster
     */
    public function updatePoster(Request $request, $id)
    {
        $poster = \App\Models\Poster::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'category' => 'nullable|string|max:100',
            'order' => 'nullable|integer',
        ]);

        $data = $request->except('image');

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($poster->image && file_exists(public_path($poster->image))) {
                unlink(public_path($poster->image));
            }

            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('images/posters'), $imageName);
            $data['image'] = 'images/posters/' . $imageName;
        }

        $poster->update($data);

        return redirect()->route('admin.posters')->with('success', 'Poster berhasil diupdate!');
    }

    /**
     * Delete poster
     */
    public function deletePoster($id)
    {
        $poster = \App\Models\Poster::findOrFail($id);

        // Delete image file
        if ($poster->image && file_exists(public_path($poster->image))) {
            unlink(public_path($poster->image));
        }

        $poster->delete();

        return redirect()->route('admin.posters')->with('success', 'Poster berhasil dihapus!');
    }
}
