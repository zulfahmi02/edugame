<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $session->game->title }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
        }

        .game-container {
            background: white;
            padding: 40px;
            border-radius: 20px;
            max-width: 800px;
            margin: 0 auto;
            box-shadow: 0 10px 40px rgba(0,0,0,0.3);
        }

        /* Custom CSS from database will be injected here */
        {!! $session->game->css_style !!}

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
    </style>
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
    </div>

    <div class="game-container">
        @if($session->game->html_template)
            <!-- Custom template from database -->
            <div id="custom-game-template">
                {!! str_replace(
                    ['{{question}}', '{{QUESTION}}'],
                    [$question->question_text, $question->question_text],
                    $session->game->html_template
                ) !!}
            </div>
        @else
            <!-- Default template -->
            <div class="default-question">
                {{ $question->question_text }}
            </div>

            <input type="text" id="answer-input" class="default-answer-input" placeholder="Ketik jawaban Anda di sini..." autofocus>

            <button onclick="submitAnswer()" class="btn-submit" id="submit-btn">
                Kirim Jawaban
            </button>
        @endif

        <div id="feedback" class="feedback"></div>
        <button onclick="nextQuestion()" class="btn-next" id="next-btn">Soal Berikutnya →</button>
    </div>

    <script>
        const sessionId = {{ $session->id }};
        const questionId = {{ $question->id }};
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        // Custom JS from database
        {!! $session->game->js_code !!}

        // Default submit answer function
        function submitAnswer() {
            const answerInput = document.getElementById('answer-input') || document.querySelector('input[type="text"]');
            const answer = answerInput ? answerInput.value.trim() : '';

            if (!answer) {
                alert('Silakan isi jawaban terlebih dahulu!');
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
            .then(response => response.json())
            .then(data => {
                const feedback = document.getElementById('feedback');
                const nextBtn = document.getElementById('next-btn');

                if (data.correct) {
                    feedback.className = 'feedback correct';
                    feedback.innerHTML = `✅ Benar! +${data.points} poin`;
                } else {
                    feedback.className = 'feedback incorrect';
                    feedback.innerHTML = `❌ Salah! Jawaban yang benar: <strong>${data.correct_answer}</strong>`;
                }

                if (nextBtn) nextBtn.style.display = 'block';
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan. Silakan coba lagi.');
                if (submitBtn) submitBtn.disabled = false;
            });
        }

        function nextQuestion() {
            window.location.href = `/session/${sessionId}/question`;
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
