@php
    $template = $session->game->template;
    $templateType = $template->template_type ?? 'quiz';
    $activeHtmlTemplate = $session->game->html_template ?: ($template->html_template ?? null);
    $activeCssStyle = $session->game->css_style ?: ($template->css_style ?? '');
    $activeJsCode = $session->game->js_code ?: ($template->js_code ?? '');

    $displayQuestionText = $question->question_text;
    if ($templateType === 'hangman') {
        if (!trim((string) $displayQuestionText)) {
            $wordLength = strlen((string) $question->correct_answer);
            $displayQuestionText = "Tebak kata ini ({$wordLength} huruf)";
        }
    } elseif (in_array($templateType, ['spell_word', 'word_search'], true) && !trim((string) $displayQuestionText)) {
        $wordLength = strlen((string) $question->correct_answer);
        $displayQuestionText = $templateType === 'spell_word'
            ? "Eja kata ini ({$wordLength} huruf)"
            : "Cari kata dengan {$wordLength} huruf";
    }

    if (in_array($templateType, ['hangman', 'crossword', 'word_search'], true)) {
        $activeJsCode = str_replace('"JAWABAN"', json_encode((string) $question->correct_answer), $activeJsCode);
    }

    $normalizeAssetPath = function (?string $path): string {
        if (!$path) {
            return '';
        }
        if (preg_match('~^https?://~i', $path)) {
            return $path;
        }
        $path = ltrim($path, '/');
        if (str_starts_with($path, 'storage/')) {
            return asset($path);
        }
        return asset('storage/' . $path);
    };

    $questionImageUrl = $normalizeAssetPath($question->image ?? null);

    $gameImages = is_array($session->game->game_images ?? null) ? $session->game->game_images : [];
    $gameImageUrl = $normalizeAssetPath($gameImages[0] ?? null);

    $safeQuestionText = e((string) $displayQuestionText);
    $safeQuestionImageUrl = e((string) ($questionImageUrl ?: $gameImageUrl));
    $safeGameImageUrl = e((string) $gameImageUrl);
    $safeCorrectAnswer = e((string) $question->correct_answer);

    $templateReplacements = [
        '{{question}}' => $safeQuestionText,
        '{{QUESTION}}' => $safeQuestionText,
        '{{question_image_url}}' => $safeQuestionImageUrl,
        '{{game_image_url}}' => $safeGameImageUrl,
        '{{correct_answer}}' => $safeCorrectAnswer,
        '{{question_number}}' => $session->total_questions + 1,
        '{{is_first_question}}' => $session->total_questions == 0 ? 'true' : 'false',
        '{{session_id}}' => $session->id,
    ];
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $session->game->title }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }

        .game-header {
            background: white;
            padding: 15px 30px;
            border-radius: 15px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
        }

        .game-info h2 {
            color: #333;
            margin-bottom: 5px;
        }

        .game-stats {
            display: flex;
            gap: 20px;
            font-size: 14px;
            color: #666;
            flex-wrap: wrap;
        }

        .game-actions {
            display: flex;
            align-items: center;
            justify-content: flex-end;
        }

        .btn-exit {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #ef4444;
            color: #fff;
            text-decoration: none;
            padding: 10px 16px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 700;
            box-shadow: 0 6px 14px rgba(239, 68, 68, 0.25);
            transition: all 0.2s ease;
        }

        .btn-exit:hover {
            background: #dc2626;
            transform: translateY(-1px);
        }

        .game-container {
            margin: 0 auto;
            width: 100%;
            max-width: 1200px; /* Lebar maksimal yang lebih lega */
            transition: all 0.3s ease;
        }

        /* Jika menggunakan template kustom, hilangkan padding dan background bawaan */
        .game-container.has-custom-template {
            background: transparent;
            padding: 0;
            box-shadow: none;
            max-width: 100%;
        }

        /* Style default tetap ada jika bukan kustom */
        .game-container:not(.has-custom-template) {
            background: white;
            padding: 40px;
            border-radius: 20px;
            max-width: 800px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.3);
        }

	        /* Custom CSS from database will be injected here */
	        {!! $activeCssStyle !!}

        .default-question {
            font-size: 24px;
            color: #333;
            margin-bottom: 30px;
            text-align: center;
        }

        .default-answer-input {
            width: 100%;
            padding: 15px;
            font-size: 18px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .default-answer-input:focus {
            outline: none;
            border-color: #667eea;
        }

        .btn-submit {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
        }

        .btn-submit:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .feedback {
            padding: 15px;
            border-radius: 10px;
            margin-top: 20px;
            display: none;
            text-align: center;
            font-weight: 600;
        }

        .feedback.correct {
            background: #d1fae5;
            color: #065f46;
            display: block;
        }

        .feedback.incorrect {
            background: #fee2e2;
            color: #991b1b;
            display: block;
        }

        .btn-next {
            width: 100%;
            padding: 15px;
            background: #10b981;
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 15px;
            display: none;
        }

        .btn-next:hover {
            background: #059669;
        }

        .action-bar {
            margin-top: 15px;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        /* Multiple choice options styles */
        .options-container {
            display: flex;
            flex-direction: column;
            gap: 12px;
            margin-bottom: 20px;
        }

        .option-item {
            display: flex;
            align-items: center;
            padding: 15px 20px;
            background: #f8fafc;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .option-item:hover {
            background: #eef2ff;
            border-color: #667eea;
            transform: translateX(5px);
        }

        .option-item.selected {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-color: transparent;
            color: white;
        }

        .option-item.selected .option-key {
            background: white;
            color: #667eea;
        }

        .option-key {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            background: #667eea;
            color: white;
            border-radius: 50%;
            font-weight: 700;
            margin-right: 15px;
            flex-shrink: 0;
        }

        .option-text {
            font-size: 16px;
            font-weight: 500;
        }

        @media (max-width: 768px) {
            body {
                padding: 12px 12px 80px;
            }

            .game-header {
                padding: 12px;
                border-radius: 12px;
                flex-direction: column;
                align-items: stretch;
            }

            .game-info h2 {
                font-size: 20px;
            }

            .game-stats {
                font-size: 12px;
                gap: 10px;
            }

            .btn-exit {
                width: 100%;
                padding: 12px 14px;
                font-size: 14px;
            }

            .game-container:not(.has-custom-template) {
                padding: 18px 14px;
                border-radius: 14px;
            }

            .default-question {
                font-size: 20px;
            }

            .option-item {
                padding: 12px;
            }

            .option-key {
                width: 30px;
                height: 30px;
                margin-right: 10px;
                font-size: 13px;
            }

            .option-text {
                font-size: 14px;
            }

            .btn-submit,
            .btn-next {
                font-size: 15px;
                padding: 12px;
            }

            .action-bar {
                position: sticky;
                bottom: 8px;
                background: rgba(255, 255, 255, 0.95);
                padding: 10px;
                border-radius: 12px;
                box-shadow: 0 8px 20px rgba(15, 23, 42, 0.15);
                backdrop-filter: blur(6px);
                z-index: 50;
            }
        }
    </style>
    <link rel="stylesheet" href="{{ asset('css/mobile-responsive-fix.css') }}">
</head>
<body>
    <div class="game-header">
        <div class="game-info">
            <h2>{{ $session->game->title }}</h2>
            <div class="game-stats">
                <span>👤 {{ session('student_name') }}</span>
                <span>📊 Soal: {{ $session->total_questions }}</span>
                <span>✅ Benar: {{ $session->correct_answers }}</span>
                <span>⭐ Poin: {{ $session->total_score }}</span>
            </div>
        </div>
        <div class="game-actions">
            <a class="btn-exit" href="{{ route('games.index') }}">✖ Keluar Game</a>
        </div>
    </div>

		    <div class="game-container {{ $activeHtmlTemplate ? 'has-custom-template' : '' }}">
	        @if($activeHtmlTemplate)
	            <!-- Custom template (game override or selected template) -->
	            <div class="custom-template-container" id="custom-game-template">
	                {!! str_replace(array_keys($templateReplacements), array_values($templateReplacements), $activeHtmlTemplate) !!}
	            </div>

                @if($question->options && count($question->options) > 0)
                    <!-- Provide option items for template JS (hidden) -->
                    <div id="option-items-source" style="display:none">
                        @foreach($question->options as $key => $value)
                            <div class="option-item" data-value="{{ $key }}">
                                @if($templateType == 'true_false')
                                    <span class="option-key">{{ $key == 'true' ? '✅' : '❌' }}</span>
                                @else
                                    <span class="option-key">{{ $key }}</span>
                                @endif
                                <span class="option-text">{{ $value }}</span>
                            </div>
                        @endforeach
                    </div>
                @endif
	        @else
	            <!-- Default template -->
	            <div class="default-question">
	                {{ $question->question_text }}
	            </div>

	            @if($question->options && count($question->options) > 0)
	                <!-- Multiple choice / True-False options -->
	                <div class="options-container" id="options-container">
	                    @foreach($question->options as $key => $value)
	                        <div class="option-item" data-value="{{ $key }}" onclick="selectOption(this, '{{ $key }}')">
                            @if($templateType == 'true_false')
                                <span class="option-key">{{ $key == 'true' ? '✅' : '❌' }}</span>
                            @else
                                <span class="option-key">{{ $key }}</span>
                            @endif
                            <span class="option-text">{{ $value }}</span>
                        </div>
                    @endforeach
                </div>
                <input type="hidden" id="answer-input" value="">
		            @else
		                <!-- Text input for non-multiple choice -->
		                <input type="text" id="answer-input" class="default-answer-input" placeholder="Ketik jawaban Anda di sini..." autofocus>
		            @endif
		        @endif

		        <div id="feedback" class="feedback"></div>
                <div class="action-bar">
	                <button onclick="submitAnswer()" class="btn-submit" id="submit-btn">
	                    Kirim Jawaban
	                </button>
		            <button onclick="nextQuestion()" class="btn-next" id="next-btn">Soal Berikutnya →</button>
                </div>
		    </div>

    <script>
        const sessionId = {{ $session->id }};
        const questionId = {{ $question->id }};
        const templateType = '{{ $templateType }}';
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        // Custom JS from database
        {!! str_replace(array_keys($templateReplacements), array_values($templateReplacements), $activeJsCode) !!}

            // Ensure answer input exists for template-based games
            document.addEventListener("DOMContentLoaded", function () {
                if (!document.getElementById("answer-input")) {
                    const input = document.createElement("input");
                    input.type = "hidden";
                    input.id = "answer-input";
                    input.value = "";
                    document.body.appendChild(input);
                }
            });

        function setupWordSearchResponsiveLayout() {
            if (templateType !== 'word_search') {
                return;
            }

            const grid = document.getElementById('word-grid');
            if (!grid) {
                return;
            }

            const fitGrid = () => {
                const cells = Array.from(grid.querySelectorAll('.grid-cell'));
                if (cells.length === 0) {
                    return;
                }

                const columnCount = Math.max(1, Math.round(Math.sqrt(cells.length)));
                const gameBox = grid.closest('.wordsearch-game') || grid.parentElement;
                const isMobile = window.matchMedia('(max-width: 768px)').matches;

                grid.style.setProperty('--ws-cols', String(columnCount));

                if (!isMobile) {
                    return;
                }

                const boxWidth = Math.max(240, gameBox?.clientWidth || window.innerWidth || 320);
                const gap = 3;
                const horizontalPadding = 12; // 6px left + 6px right
                const rawCellSize = (boxWidth - horizontalPadding - (gap * (columnCount - 1))) / columnCount;
                const cellSize = Math.max(22, Math.floor(rawCellSize));
                const fontSize = Math.max(12, Math.min(18, Math.floor(cellSize * 0.46)));

                grid.style.setProperty('display', 'grid', 'important');
                grid.style.setProperty('grid-template-columns', `repeat(${columnCount}, minmax(0, 1fr))`, 'important');
                grid.style.setProperty('width', '100%', 'important');
                grid.style.setProperty('max-width', '100%', 'important');
                grid.style.setProperty('padding', '6px', 'important');
                grid.style.setProperty('gap', `${gap}px`, 'important');
                grid.style.setProperty('box-sizing', 'border-box', 'important');

                if (gameBox) {
                    gameBox.style.setProperty('overflow-x', 'hidden', 'important');
                }

                cells.forEach((cell) => {
                    cell.style.setProperty('width', 'auto', 'important');
                    cell.style.setProperty('height', 'auto', 'important');
                    cell.style.setProperty('aspect-ratio', '1 / 1', 'important');
                    cell.style.setProperty('font-size', `${fontSize}px`, 'important');
                });
            };

            fitGrid();
            window.addEventListener('resize', fitGrid);
            window.addEventListener('orientationchange', () => setTimeout(fitGrid, 120));
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', setupWordSearchResponsiveLayout);
        } else {
            setupWordSearchResponsiveLayout();
        }

        // Function to select option (for multiple choice)
        function selectOption(element, value) {
            // Remove selected class from all options
            document.querySelectorAll('.option-item').forEach(item => {
                item.classList.remove('selected');
            });
            
            // Add selected class to clicked option
            element.classList.add('selected');
            
            // Set the answer value
            document.getElementById('answer-input').value = value;
        }

        // Default submit answer function
        function submitAnswer() {
            const answerInput = document.getElementById('answer-input') || document.querySelector('input[type="text"]');
            const answer = answerInput ? answerInput.value.trim() : '';

            if (!answer) {
                const isMultipleChoice = document.getElementById('options-container') !== null;
                alert(isMultipleChoice ? 'Silakan pilih salah satu jawaban!' : 'Silakan isi jawaban terlebih dahulu!');
                return;
            }

            const submitBtn = document.getElementById('submit-btn');
            if (submitBtn) submitBtn.disabled = true;

            fetch(`/session/${sessionId}/answer`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    question_id: questionId,
                    answer: answer
                })
            })
            .then(async (response) => {
                const data = await response.json();
                if (!response.ok) {
                    throw new Error(data.error || 'Terjadi kesalahan. Silakan coba lagi.');
                }
                return data;
            })
            .then(data => {
                const feedback = document.getElementById('feedback');
                const nextBtn = document.getElementById('next-btn');

                if (data.correct) {
                    if (templateType === 'iframe_embed') {
                        Swal.fire({
                            title: '🎉 Berhasil Dikirim!',
                            text: 'Skor kamu sudah dicatat oleh Pak/Bu Guru. Mantap!',
                            icon: 'success',
                            confirmButtonColor: '#667eea'
                        });
                        
                        // Hide scoring card after success
                        const scoreSection = document.getElementById('manual-score-section');
                        if (scoreSection) {
                            scoreSection.innerHTML = '<div style="background:#f0fdf4; color:#166534; padding:20px; border-radius:15px; border:2px solid #86efac; font-weight:700;">✅ Skor Berhasil Disimpan!</div>';
                        }
                    }

                    feedback.className = 'feedback correct';
                    feedback.innerHTML = `✅ Berhasil! +${data.points} poin`;
                    
                    // Specific tweak: hide default feedback for embed because we have the "Skor Berhasil Disimpan" box
                    if (templateType === 'iframe_embed') {
                        feedback.style.display = 'none';
                    }
                } else {
                    feedback.className = 'feedback incorrect';
                    feedback.innerHTML = '❌ Salah! Jawaban yang benar: <strong></strong>';
                    const answerStrong = feedback.querySelector('strong');
                    if (answerStrong) {
                        answerStrong.textContent = data.correct_answer || '-';
                    }
                }

                if (data.is_last) {
                    nextBtn.innerHTML = 'Selesaikan & Lihat Hasil ✅';
                    nextBtn.style.background = '#059669';
                    nextBtn.setAttribute('data-is-last', 'true');
                    
                    if (templateType === 'iframe_embed') {
                        nextBtn.style.padding = '20px';
                        nextBtn.style.fontSize = '1.2rem';
                        nextBtn.style.boxShadow = '0 10px 25px rgba(16, 185, 129, 0.4)';
                    }
                }

                if (nextBtn) nextBtn.style.display = 'block';
            })
            .catch(error => {
                console.error('Error:', error);
                alert(error.message || 'Terjadi kesalahan. Silakan coba lagi.');
                if (submitBtn) submitBtn.disabled = false;
            });
        }

        function nextQuestion() {
            const nextBtn = document.getElementById('next-btn');
            if (nextBtn && nextBtn.getAttribute('data-is-last') === 'true') {
                window.location.href = `/session/${sessionId}/finish`;
            } else {
                window.location.href = `/session/${sessionId}/question`;
            }
        }

        // Allow Enter key to submit
        document.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                const submitBtn = document.getElementById('submit-btn');
                if (submitBtn && !submitBtn.disabled) {
                    submitAnswer();
                }
            }
        });
    </script>
</body>
</html>
