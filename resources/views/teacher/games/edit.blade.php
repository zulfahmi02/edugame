<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Game - {{ $game->title }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', 'Segoe UI', sans-serif;
            background: #f5f7fa;
            min-height: 100vh;
            display: flex;
        }

        /* Sidebar */
        .sidebar {
            width: 260px;
            background: white;
            min-height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            box-shadow: 2px 0 10px rgba(0,0,0,0.05);
            display: flex;
            flex-direction: column;
            z-index: 100;
        }

        .sidebar-brand {
            padding: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            border-bottom: 1px solid #f0f0f0;
        }

        .brand-icon {
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, #4B8BF4 0%, #3B7DE0 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
        }

        .brand-text {
            display: flex;
            flex-direction: column;
        }

        .brand-name {
            font-weight: 700;
            font-size: 1.25rem;
            color: #1e293b;
        }

        .brand-subtitle {
            font-size: 0.7rem;
            color: #4B8BF4;
            font-weight: 600;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .sidebar-nav {
            padding: 1rem 0;
            flex: 1;
        }

        .nav-item {
            padding: 0.75rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            color: #64748b;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.2s ease;
            border-left: 3px solid transparent;
        }

        .nav-item:hover {
            background: #f8fafc;
            color: #1e293b;
        }

        .nav-item.active {
            background: #EBF3FF;
            color: #4B8BF4;
            border-left-color: #4B8BF4;
        }

        .sidebar-footer {
            padding: 1rem 1.5rem;
            border-top: 1px solid #f0f0f0;
        }



        .user-profile {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            min-width: 40px;
            min-height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            flex-shrink: 0;
            aspect-ratio: 1;
        }

        .user-info {
            flex: 1;
        }

        .user-name {
            font-weight: 600;
            font-size: 0.9rem;
            color: #1e293b;
        }

        .user-email {
            font-size: 0.75rem;
            color: #64748b;
        }

        /* Main Content */
        .main-content {
            margin-left: 260px;
            flex: 1;
            padding: 1.5rem 2rem;
            max-width: 1000px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .header-btn {
            width: 40px;
            height: 40px;
            border: none;
            background: white;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #64748b;
            cursor: pointer;
        }

        .btn-back {
            color: #64748b;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-weight: 600;
            padding: 0.5rem 1rem;
            background: white;
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .btn-back:hover {
            color: #1e293b;
            background: #f1f5f9;
        }

        .section-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .section-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .form-label {
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.5rem;
        }

        .form-control, .form-select {
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            padding: 0.75rem 1rem;
            font-size: 1rem;
            transition: all 0.2s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: #4B8BF4;
            box-shadow: 0 0 0 3px rgba(75, 139, 244, 0.1);
        }

        .btn-update {
            background: linear-gradient(135deg, #4B8BF4 0%, #3B7DE0 100%);
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 10px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-update:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(75, 139, 244, 0.3);
        }

        .template-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background: #EBF3FF;
            color: #4B8BF4;
            border-radius: 20px;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .stats-row {
            display: flex;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .stat-mini {
            background: #f8fafc;
            padding: 0.75rem 1rem;
            border-radius: 10px;
            flex: 1;
        }

        .stat-mini-label {
            font-size: 0.8rem;
            color: #64748b;
        }

        .stat-mini-value {
            font-weight: 700;
            color: #1e293b;
            font-size: 1.1rem;
        }

        .question-list {
            margin-top: 1rem;
        }

        .question-item {
            background: #f8fafc;
            border-radius: 12px;
            padding: 1.25rem;
            margin-bottom: 1rem;
            border-left: 4px solid #4B8BF4;
        }

        .question-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 0.75rem;
        }

        .question-number {
            font-weight: 700;
            color: #4B8BF4;
            font-size: 0.85rem;
        }

        .question-text {
            font-weight: 600;
            color: #1e293b;
            font-size: 1rem;
            margin-bottom: 0.75rem;
        }

        .options-list {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 0.5rem;
        }

        .option-item {
            padding: 0.5rem 0.75rem;
            background: white;
            border-radius: 8px;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .option-item.correct {
            background: #d1fae5;
            color: #065f46;
            font-weight: 600;
        }

        .question-actions {
            display: flex;
            gap: 0.5rem;
        }

        .btn-sm {
            padding: 0.4rem 0.75rem;
            font-size: 0.85rem;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-delete-q {
            background: #fee2e2;
            color: #991b1b;
        }

        .btn-delete-q:hover {
            background: #fecaca;
        }

        .add-question-form {
            background: #f0fdf4;
            border-radius: 12px;
            padding: 1.5rem;
            border: 2px dashed #86efac;
        }

        .add-question-title {
            font-weight: 700;
            color: #166534;
            margin-bottom: 1rem;
        }

        .btn-add {
            background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 10px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-add:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(34, 197, 94, 0.3);
        }

        .form-check-input:checked {
            background-color: #22c55e;
            border-color: #22c55e;
        }

        .alert {
            border-radius: 10px;
            margin-bottom: 1.5rem;
            border: none;
        }

        .empty-questions {
            text-align: center;
            padding: 2rem;
            color: #64748b;
            background: #f8fafc;
            border-radius: 12px;
        }

        @media (max-width: 1024px) {
            .sidebar { width: 80px; }
            .sidebar-brand .brand-text, .nav-item span, .sidebar-footer .pro-card, .sidebar-footer .user-info { display: none; }
            .sidebar-brand { justify-content: center; padding: 1rem; }
            .nav-item { justify-content: center; padding: 1rem; }
            .user-profile { justify-content: center; }
            .main-content { margin-left: 80px; }
        }

        @media (max-width: 768px) {
            .sidebar { width: 100%; height: auto; position: relative; flex-direction: row; padding: 0.5rem 1rem; }
            .sidebar-nav { display: flex; padding: 0; }
            .nav-item { padding: 0.5rem 1rem; }
            .sidebar-footer { display: none; }
            .main-content { margin-left: 0; padding: 1rem; }
            body { flex-direction: column; }
            .options-list { grid-template-columns: 1fr; }
            .stats-row { flex-direction: column; }
        }
    </style>
</head>

<body>
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-brand">
            <div class="brand-icon">📚</div>
            <div class="brand-text">
                <div class="brand-name">EduPlay</div>
                <div class="brand-subtitle">Teacher Portal</div>
            </div>
        </div>

        <nav class="sidebar-nav">
            <a href="{{ route('teacher.dashboard') }}" class="nav-item">
                <span class="nav-icon">📊</span>
                <span>Dashboard</span>
            </a>
            <a href="#" class="nav-item">
                <span class="nav-icon">👥</span>
                <span>Classes</span>
            </a>
            <a href="{{ route('teacher.games') }}" class="nav-item active">
                <span class="nav-icon">🎮</span>
                <span>Games</span>
            </a>
            <a href="{{ route('teacher.schedules') }}" class="nav-item">
                <span class="nav-icon">📈</span>
                <span>Results</span>
            </a>
        </nav>

        <div class="sidebar-footer">
            <div class="user-profile">
                <div class="user-avatar">{{ substr(session('teacher_name', 'T'), 0, 1) }}</div>
                <div class="user-info">
                    <div class="user-name">{{ session('teacher_name') }}</div>
                    <div class="user-email">teacher@school.edu</div>
                </div>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <div class="header">
            <a href="{{ route('teacher.games') }}" class="btn-back">
                ← Back to Games
            </a>
            <div class="header-actions">
                <button class="header-btn">🔔</button>
                <button class="header-btn">⚙️</button>
            </div>
        </div>

        <!-- Alerts -->
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Game Info Section -->
        <div class="section-card">
            <h2 class="section-title">🎯 Edit Game Information</h2>

            @if($game->template)
                <div class="template-badge">
                    {{ $game->template->icon ?? '🎮' }} Template: {{ $game->template->name }}
                </div>
            @endif

            <div class="stats-row">
                <div class="stat-mini">
                    <div class="stat-mini-label">Total Questions</div>
                    <div class="stat-mini-value">{{ $game->questions->count() }}</div>
                </div>
                <div class="stat-mini">
                    <div class="stat-mini-label">Status</div>
                    <div class="stat-mini-value">{{ $game->is_active ? '✅ Active' : '❌ Inactive' }}</div>
                </div>
                <div class="stat-mini">
                    <div class="stat-mini-label">Created</div>
                    <div class="stat-mini-value">{{ $game->created_at->format('d M Y') }}</div>
                </div>
            </div>

            <form action="{{ route('teacher.games.update', $game->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-5 mb-3">
                        <label for="title" class="form-label">Game Title *</label>
                        <input type="text" class="form-control" id="title" name="title"
                            value="{{ old('title', $game->title) }}" required>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="category" class="form-label">Category *</label>
                        <input type="text" class="form-control" id="category" name="category"
                            value="{{ old('category', $game->category) }}" placeholder="Example: Math, Science" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-5 mb-3">
                        <label for="class" class="form-label">Grade</label>
                        <select class="form-select" id="class" name="class">
                            <option value="">All Grades</option>
                            @for($i = 1; $i <= 6; $i++)
                                <option value="{{ $i }}" {{ old('class', $game->class) == $i ? 'selected' : '' }}>Grade {{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label">Status</label>
                        <div class="form-check mt-2">
                            <input type="checkbox" class="form-check-input" id="is_active" name="is_active" {{ $game->is_active ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">Game Active</label>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description"
                        rows="3">{{ old('description', $game->description) }}</textarea>
                </div>

                <!-- Game Images Section -->
                <div class="mb-3">
                    <label class="form-label">📷 Game Images</label>
                    
                    @if($game->game_images)
                        @php
                            $existingImages = is_string($game->game_images) ? json_decode($game->game_images, true) : $game->game_images;
                        @endphp
                        @if(is_array($existingImages) && count($existingImages) > 0)
                            <div class="mb-3">
                                <small class="text-muted d-block mb-2">Existing images:</small>
                                <div style="display: flex; gap: 10px; flex-wrap: wrap;">
                                    @foreach($existingImages as $image)
                                        <div style="position: relative;">
                                            <img src="{{ asset('storage/' . $image) }}" alt="Game Image" 
                                                style="width: 100px; height: 100px; object-fit: cover; border-radius: 8px; border: 2px solid #e5e7eb;">
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    @endif

                    <input class="form-control" type="file" id="game_images" name="game_images[]" 
                        accept="image/png,image/jpeg,image/jpg,image/webp" multiple>
                    <small class="text-muted">Upload new images (PNG, JPG, WEBP). Max 5 images, max 2MB per file.</small>
                    
                    <!-- Preview Area -->
                    <div id="imagePreview" style="display: flex; gap: 10px; margin-top: 10px; flex-wrap: wrap;"></div>
                </div>

                <button type="submit" class="btn-update">
                    💾 Save Changes
                </button>
            </form>
        </div>

        <!-- Questions Section -->
        <div class="section-card">
            <h2 class="section-title">📝 Manage Questions</h2>

            <!-- Add Question Form -->
            <div class="add-question-form mb-4">
                <h4 class="add-question-title">➕ Add New Question</h4>
                
                @php
                    $templateType = $game->template->template_type ?? 'quiz';
                @endphp

                <form action="{{ route('teacher.games.questions.store', $game->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="template_type" value="{{ $templateType }}">

                    @if($templateType !== 'iframe_embed')
                        <div class="mb-3">
                            <label for="question_text" class="form-label">
                                @if(in_array($templateType, ['hangman', 'spell_word', 'word_search']))
                                    Clue/Hint *
                                @elseif($templateType == 'math_generator')
                                    Math Problem *
                                @elseif($templateType == 'true_false')
                                    True/False Statement *
                                @elseif($templateType == 'crossword')
                                    Clue *
                                @elseif($templateType == 'labeled_diagram')
                                    Label Hint *
                                @else
                                    Question *
                                @endif
                            </label>
                            <textarea class="form-control" id="question_text" name="question_text" rows="2"
                                placeholder="@if(in_array($templateType, ['hangman']))Example: A four-legged animal that eats grass...@elseif(in_array($templateType, ['spell_word']))Example: Spell the word for 'house'...@elseif(in_array($templateType, ['word_search']))Example: Find the word meaning 'school'...@elseif($templateType == 'math_generator')Example: 5 + 3 = ?@elseif($templateType == 'true_false')Enter a true or false statement...@else Enter your question...@endif" required></textarea>
                        </div>
                    @else
                        <input type="hidden" name="question_text" value="Play the game below">
                    @endif

                    @php
                        $textAnswerTypes = ['hangman', 'word_search', 'spell_word'];
                        $customTextTypes = ['math_generator'];
                        $requiresImageTypes = ['image_quiz', 'labeled_diagram'];
                        $requiresFourOptionsTypes = ['quiz_gameshow', 'random_card', 'balloon_pop', 'whack_a_mole', 'flip_tiles', 'win_or_lose', 'watch_memorize', 'flying_fruit', 'airplane', 'ranking_order', 'quick_sort', 'word_magnet', 'pairs_or_no_pairs'];
                        $needsFourOptions = in_array($templateType, $requiresFourOptionsTypes);
                    @endphp

                    @if(in_array($templateType, $requiresImageTypes))
                        <div class="mb-3">
                            <label for="image" class="form-label">
                                @if($templateType === 'labeled_diagram')
                                    Diagram Image *
                                @else
                                    Question Image *
                                @endif
                            </label>
                            <input class="form-control" type="file" id="image" name="image" accept="image/*" required>
                            <small class="text-muted">Image format (JPG/PNG/WebP), max 2MB.</small>
                        </div>
                    @endif

                    @if($templateType == 'true_false')
                        <div class="mb-3">
                            <label class="form-label">Correct Answer *</label>
                            <div class="d-flex gap-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="correct_answer" id="answer_true" value="true" required>
                                    <label class="form-check-label" for="answer_true">✅ True</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="correct_answer" id="answer_false" value="false" required>
                                    <label class="form-check-label" for="answer_false">❌ False</label>
                                </div>
                            </div>
                        </div>

                    @elseif($templateType === 'crossword')
                        <div class="mb-3">
                            <label for="correct_answer" class="form-label">Answer (Word) *</label>
                            <input type="text" class="form-control" id="correct_answer" name="correct_answer"
                                placeholder="Example: SCHOOL" required>
                            <small class="text-muted">Enter single word answer for crossword.</small>
                        </div>

                    @elseif($templateType === 'labeled_diagram')
                        <div class="mb-3">
                            <label for="correct_answer" class="form-label">Correct Point *</label>
                            <select class="form-select" id="correct_answer" name="correct_answer" required>
                                <option value="">Select point on diagram</option>
                                <option value="1">Point 1</option>
                                <option value="2">Point 2</option>
                                <option value="3">Point 3</option>
                            </select>
                            <small class="text-muted">Student will click on point 1/2/3 in the image.</small>
                        </div>

                    @elseif(in_array($templateType, $textAnswerTypes))
                        <div class="mb-3">
                            <label for="correct_answer" class="form-label">Answer Word *</label>
                            <input type="text" class="form-control" id="correct_answer" name="correct_answer"
                                placeholder="Example: SCHOOL" autocomplete="off" autocapitalize="characters" spellcheck="false" required>
                            <small class="text-muted">Answer is typed (no options needed), stored in uppercase.</small>
                        </div>

                    @elseif(in_array($templateType, ['ranking_order', 'word_magnet']))
                        <div class="alert alert-info">
                            💡 Template <strong>{{ $game->template->name ?? $templateType }}</strong> requires <strong>4 items</strong>. Define the <strong>correct order</strong>.
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="option_a" class="form-label">Item A *</label>
                                <input type="text" class="form-control" id="option_a" name="option_a"
                                    placeholder="{{ $templateType === 'word_magnet' ? 'Word A' : 'Item A' }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="option_b" class="form-label">Item B *</label>
                                <input type="text" class="form-control" id="option_b" name="option_b"
                                    placeholder="{{ $templateType === 'word_magnet' ? 'Word B' : 'Item B' }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="option_c" class="form-label">Item C *</label>
                                <input type="text" class="form-control" id="option_c" name="option_c"
                                    placeholder="{{ $templateType === 'word_magnet' ? 'Word C' : 'Item C' }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="option_d" class="form-label">Item D *</label>
                                <input type="text" class="form-control" id="option_d" name="option_d"
                                    placeholder="{{ $templateType === 'word_magnet' ? 'Word D' : 'Item D' }}" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Correct Order *</label>
                            <div class="row g-2">
                                @for($i = 1; $i <= 4; $i++)
                                    <div class="col-6 col-md-3">
                                        <select class="form-select" name="correct_order[]" required>
                                            <option value="">Position {{ $i }}</option>
                                            <option value="A">A</option>
                                            <option value="B">B</option>
                                            <option value="C">C</option>
                                            <option value="D">D</option>
                                        </select>
                                    </div>
                                @endfor
                            </div>
                            <small class="text-muted">Example order: A → B → C → D (no duplicates).</small>
                        </div>

                    @elseif(in_array($templateType, $customTextTypes))
                        <div class="mb-3">
                            <label for="correct_answer" class="form-label">Correct Answer *</label>
                            <input type="text" class="form-control" id="correct_answer" name="correct_answer"
                                placeholder="Enter correct answer (e.g.: 8)" inputmode="decimal" pattern="[0-9]*" autocomplete="off" required>
                            <small class="text-muted">Enter the calculation result. Student answers via number keypad.</small>
                        </div>

                    @elseif($templateType === 'iframe_embed')
                        <div class="mb-3">
                            <label for="correct_answer" class="form-label">HTML Iframe (Embed) Code *</label>
                            <textarea class="form-control" id="correct_answer" name="correct_answer" rows="4" 
                                placeholder="Paste iframe code here..." required></textarea>
                            <div class="alert alert-info mt-2" style="font-size: 0.9rem;">
                                💡 <strong>Wordwall Tips:</strong> Select "Share" -> "Embed" -> Copy the <code>&lt;iframe...&gt;</code> code and paste above.
                            </div>
                        </div>

                    @else
                        @if($needsFourOptions)
                            <div class="alert alert-warning">
                                💡 Template <strong>{{ $game->template->name ?? $templateType }}</strong> requires <strong>4 options</strong> (A, B, C, D).
                            </div>
                        @endif
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="option_a" class="form-label">Option A *</label>
                                <input type="text" class="form-control" id="option_a" name="option_a"
                                    placeholder="Answer A" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="option_b" class="form-label">Option B *</label>
                                <input type="text" class="form-control" id="option_b" name="option_b"
                                    placeholder="Answer B" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="option_c" class="form-label">Option C{{ $needsFourOptions ? ' *' : '' }}</label>
                                <input type="text" class="form-control" id="option_c" name="option_c"
                                    placeholder="{{ $needsFourOptions ? 'Answer C' : 'Answer C (optional)' }}" {{ $needsFourOptions ? 'required' : '' }}>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="option_d" class="form-label">Option D{{ $needsFourOptions ? ' *' : '' }}</label>
                                <input type="text" class="form-control" id="option_d" name="option_d"
                                    placeholder="{{ $needsFourOptions ? 'Answer D' : 'Answer D (optional)' }}" {{ $needsFourOptions ? 'required' : '' }}>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="correct_answer" class="form-label">Correct Answer *</label>
                            <select class="form-select" id="correct_answer" name="correct_answer" required>
                                <option value="">Select correct answer</option>
                                <option value="A">A</option>
                                <option value="B">B</option>
                                <option value="C">C</option>
                                <option value="D">D</option>
                            </select>
                        </div>
                    @endif

                    <button type="submit" class="btn-add">
                        ➕ Add Question
                    </button>
                </form>
            </div>

            <!-- Questions List -->
            <h4 class="mb-3">📋 Question List ({{ $game->questions->count() }})</h4>

            @if($game->questions->count() > 0)
                <div class="question-list">
                    @php
                        $gameTemplateType = $game->template->template_type ?? 'quiz';
                    @endphp
                    @foreach($game->questions as $index => $question)
                        <div class="question-item">
                            <div class="question-header">
                                <span class="question-number">Question #{{ $index + 1 }}</span>
                                <div class="question-actions">
                                    <form action="{{ route('teacher.games.questions.delete', [$game->id, $question->id]) }}"
                                        method="POST" style="display: inline;"
                                        class="delete-question-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-sm btn-delete-q">🗑️ Delete</button>
                                    </form>
                                </div>
                            </div>
                            <p class="question-text">{{ $question->question_text }}</p>
                            <div class="options-list">
                                @if($question->image)
                                    <div class="option-item">
                                        🖼️ Has image
                                    </div>
                                @endif

                                @if($question->options)
                                    @foreach($question->options as $key => $value)
                                        <div class="option-item {{ $question->correct_answer == $key ? 'correct' : '' }}">
                                            <strong>{{ $key }}.</strong> {{ $value }}
                                            @if($question->correct_answer == $key) ✓ @endif
                                        </div>
                                    @endforeach
                                    @if(in_array($gameTemplateType, ['ranking_order', 'word_magnet'], true))
                                        @php
                                            $decodedOrder = is_string($question->correct_answer)
                                                ? json_decode($question->correct_answer, true)
                                                : null;
                                        @endphp
                                        @if(is_array($decodedOrder) && count($decodedOrder) > 0)
                                            <div class="option-item correct">
                                                <strong>Order:</strong>
                                                {{ collect($decodedOrder)->map(fn ($k) => ($question->options[$k] ?? $k))->implode(' → ') }}
                                            </div>
                                        @endif
                                    @endif
                                @else
                                    <div class="option-item correct">
                                        @if($gameTemplateType === 'labeled_diagram')
                                            <strong>Point:</strong> {{ $question->correct_answer }}
                                        @elseif($gameTemplateType === 'crossword')
                                            <strong>Answer:</strong> {{ $question->correct_answer }}
                                        @else
                                            <strong>Answer:</strong> {{ $question->correct_answer }}
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty-questions">
                    <p>📝 No questions yet for this game.<br>Add your first question using the form above!</p>
                </div>
            @endif
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // SweetAlert2 for delete question confirmation
        document.querySelectorAll('.delete-question-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: '🗑️ Delete Question?',
                    html: '<p style="font-size: 1.1rem; color: #64748b;">This question will be permanently deleted!</p>',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#94a3b8',
                    confirmButtonText: '✓ Yes, Delete!',
                    cancelButtonText: '✗ Cancel',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });

        // Show success message if exists
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: '✅ Success!',
                text: '{{ session('success') }}',
                confirmButtonColor: '#22c55e',
                confirmButtonText: 'OK',
                timer: 3000,
                timerProgressBar: true
            });
        @endif

        // Show error message if exists
        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: '❌ Error!',
                text: '{{ session('error') }}',
                confirmButtonColor: '#ef4444',
                confirmButtonText: 'OK'
            });
        @endif

        // Image Preview Functionality
        document.getElementById('game_images').addEventListener('change', function(e) {
            const preview = document.getElementById('imagePreview');
            preview.innerHTML = '';
            const files = Array.from(e.target.files);
            
            if (files.length > 5) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Too Many Images!',
                    text: 'Maximum 5 images can be uploaded',
                    confirmButtonColor: '#f59e0b'
                });
                e.target.value = '';
                return;
            }
            
            const maxSize = 2 * 1024 * 1024;
            for (let file of files) {
                if (file.size > maxSize) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'File Too Large!',
                        text: `File "${file.name}" exceeds 2MB`,
                        confirmButtonColor: '#f59e0b'
                    });
                    e.target.value = '';
                    preview.innerHTML = '';
                    return;
                }
            }
            
            files.forEach(file => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.style.width = '100px';
                    img.style.height = '100px';
                    img.style.objectFit = 'cover';
                    img.style.borderRadius = '8px';
                    img.style.border = '2px solid #22c55e';
                    preview.appendChild(img);
                };
                reader.readAsDataURL(file);
            });
        });
    </script>
</body>

</html>
