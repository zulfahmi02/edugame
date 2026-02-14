<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk Orang Tua - Taman Belajar Sedjati</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Fredoka:wght@400;600;700&family=Plus+Jakarta+Sans:wght@400;600;700&display=swap');

        :root {
            --ink: #1f2937;
            --muted: #64748b;
            --border: #e2e8f0;
            --card: #ffffff;
            --shadow: 0 24px 60px rgba(15, 23, 42, 0.15);
        }

        * , *::before, *::after {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: var(--ink);
            background: radial-gradient(circle at 20% 20%, #fbcfe8 0%, transparent 45%),
                radial-gradient(circle at 80% 30%, #e9d5ff 0%, transparent 45%),
                radial-gradient(circle at 70% 80%, #c7d2fe 0%, transparent 40%),
                #f8fafc;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 32px 20px 64px;
            overflow-x: hidden;
        }

        body.theme-parent {
            --accent: #ec4899;
            --accent-deep: #db2777;
            --soft: #ffe4f1;
            --visual-bg: linear-gradient(135deg, #fbd38d, #fbcfe8 55%, #c7d2fe 100%);
        }

        body.dark-mode {
            --ink: #e2e8f0;
            --muted: #94a3b8;
            --border: #334155;
            --card: #0f172a;
            --shadow: 0 24px 60px rgba(0, 0, 0, 0.45);
            background: radial-gradient(circle at 20% 25%, rgba(147, 51, 234, 0.35) 0%, transparent 45%),
                radial-gradient(circle at 85% 25%, rgba(190, 18, 60, 0.35) 0%, transparent 45%),
                radial-gradient(circle at 70% 80%, rgba(79, 70, 229, 0.35) 0%, transparent 45%),
                #0b1120;
        }

        body.dark-mode .auth-card {
            border: 1px solid rgba(148, 163, 184, 0.2);
        }

        body.dark-mode input {
            background: #0b1220;
            color: var(--ink);
            border-color: var(--border);
        }

        body.dark-mode input:focus {
            background: #0f172a;
        }

        body.dark-mode .visual-chip {
            background: rgba(15, 23, 42, 0.9);
            color: #e2e8f0;
        }

        body.dark-mode .link-row a,
        body.dark-mode .helper,
        body.dark-mode .lead {
            color: var(--muted);
        }

        body.dark-mode .moon-btn {
            background: #0f172a;
            color: #e2e8f0;
            border: 1px solid rgba(148, 163, 184, 0.3);
        }

        .bg-shapes {
            position: fixed;
            inset: 0;
            pointer-events: none;
            z-index: 0;
        }

        .shape {
            position: absolute;
            opacity: 0.2;
            filter: blur(0.4px);
        }

        .shape.star {
            width: 140px;
            height: 140px;
            border-radius: 42% 58% 52% 48% / 52% 46% 54% 48%;
            background: radial-gradient(circle at 30% 30%, rgba(255, 255, 255, 0.85), rgba(255, 255, 255, 0) 60%),
                linear-gradient(135deg, rgba(244, 114, 182, 0.35), rgba(251, 207, 232, 0.05));
            top: 14%;
            right: 14%;
        }

        .shape.pentagon {
            width: 120px;
            height: 120px;
            border-radius: 28px;
            transform: rotate(12deg);
            background: linear-gradient(135deg, rgba(251, 191, 36, 0.35), rgba(253, 230, 138, 0.05));
            top: 18%;
            left: 10%;
        }

        .shape.dot {
            width: 110px;
            height: 110px;
            border-radius: 50%;
            background: radial-gradient(circle at 35% 35%, rgba(255, 255, 255, 0.8), rgba(251, 207, 232, 0.2), rgba(255, 255, 255, 0));
            bottom: 12%;
            left: 8%;
        }

        body.dark-mode .shape {
            opacity: 0.12;
            filter: blur(0.6px);
        }

        .auth-shell {
            width: min(1080px, 100%);
            display: grid;
            grid-template-columns: minmax(320px, 420px) minmax(320px, 1fr);
            gap: 32px;
            align-items: center;
            position: relative;
            z-index: 1;
        }

        .auth-card {
            background: var(--card);
            border-radius: 28px;
            padding: 36px;
            box-shadow: var(--shadow);
        }

        .pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 14px;
            border-radius: 999px;
            font-size: 12px;
            letter-spacing: 0.08em;
            font-weight: 700;
            color: var(--accent-deep);
            background: var(--soft);
        }

        .auth-card h1 {
            font-family: 'Fredoka', sans-serif;
            font-size: 34px;
            margin: 18px 0 10px;
        }

        .lead {
            color: var(--muted);
            font-size: 15px;
            line-height: 1.6;
            margin-bottom: 22px;
        }

        .alert {
            padding: 12px 14px;
            border-radius: 12px;
            margin-bottom: 16px;
            font-size: 14px;
        }

        .alert-error {
            background: #fee2e2;
            color: #b91c1c;
            border: 1px solid #fecaca;
        }

        .alert-success {
            background: #dcfce7;
            color: #15803d;
            border: 1px solid #bbf7d0;
        }

        .form-group {
            margin-bottom: 18px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            font-size: 14px;
        }

        .input-wrap {
            position: relative;
        }

        .input-icon {
            position: absolute;
            top: 50%;
            left: 16px;
            transform: translateY(-50%);
            width: 18px;
            height: 18px;
            color: #94a3b8;
        }

        input {
            width: 100%;
            padding: 14px 16px 14px 46px;
            border-radius: 14px;
            border: 1.5px solid var(--border);
            font-size: 14px;
            background: #f8fafc;
            outline: none;
            transition: border 0.2s ease, box-shadow 0.2s ease;
        }

        input:focus {
            border-color: var(--accent);
            box-shadow: 0 0 0 4px rgba(236, 72, 153, 0.2);
            background: #fff;
        }

        /* Password toggle button */
        .password-toggle {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            padding: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #94a3b8;
            font-size: 18px;
            transition: color 0.2s ease;
            z-index: 10;
        }

        .password-toggle:hover {
            color: var(--accent);
        }

        .password-toggle:focus {
            outline: 2px solid var(--accent);
            outline-offset: 2px;
            border-radius: 4px;
        }

        input[type="password"],
        input[type="text"].password-input {
            padding-right: 46px;
        }

        .btn-primary {
            width: 100%;
            margin-top: 6px;
            padding: 14px 18px;
            border: none;
            border-radius: 14px;
            background: linear-gradient(135deg, var(--accent), var(--accent-deep));
            color: #fff;
            font-weight: 700;
            font-size: 16px;
            cursor: pointer;
            box-shadow: 0 12px 24px rgba(236, 72, 153, 0.35);
            display: inline-flex;
            justify-content: center;
            gap: 10px;
            align-items: center;
        }

        .helper {
            text-align: center;
            margin-top: 18px;
            font-size: 13px;
            color: var(--muted);
        }

        .helper a {
            color: var(--accent-deep);
            font-weight: 700;
            text-decoration: none;
        }

        .link-row {
            margin-top: 16px;
            display: flex;
            justify-content: center;
            gap: 16px;
            font-size: 13px;
        }

        .link-row a {
            text-decoration: none;
            color: var(--muted);
            font-weight: 600;
        }

        .auth-visual {
            position: relative;
            border-radius: 28px;
            min-height: 460px;
            box-shadow: var(--shadow);
            overflow: hidden;
            background: transparent;
        }

        .visual-art {
            width: 100%;
            height: 100%;
            position: absolute;
            inset: 0;
            z-index: 0;
        }

        .visual-image {
            width: 100%;
            height: 100%;
            display: block;
            object-fit: cover;
        }

        .visual-chip {
            position: absolute;
            background: #fff;
            border-radius: 16px;
            padding: 12px 14px;
            box-shadow: 0 12px 26px rgba(15, 23, 42, 0.18);
            font-size: 13px;
            color: #334155;
            width: 180px;
            z-index: 1;
        }

        .visual-chip span {
            display: block;
            font-weight: 700;
            margin-top: 4px;
        }

        .chip-top {
            top: 18px;
            right: 18px;
        }

        .chip-bottom {
            bottom: 18px;
            left: 18px;
        }

        .moon-btn {
            position: fixed;
            right: 24px;
            bottom: 24px;
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: #fff;
            box-shadow: 0 12px 24px rgba(15, 23, 42, 0.2);
            display: inline-grid;
            place-items: center;
            color: #1f2937;
            font-size: 20px;
            border: none;
            cursor: pointer;
        }

        @media (max-width: 960px) {
            .auth-shell {
                grid-template-columns: 1fr;
            }

            .auth-visual {
                min-height: 360px;
            }

            .chip-top {
                right: 6%;
            }

            .chip-bottom {
                left: 6%;
            }
        }

        @media (max-width: 560px) {
            .auth-card {
                padding: 28px 24px;
            }

            .auth-card h1 {
                font-size: 28px;
            }

            .visual-chip {
                width: 150px;
                font-size: 12px;
            }
        }
    </style>
</head>

<body class="theme-parent">
    <div class="bg-shapes">
        <div class="shape star"></div>
        <div class="shape pentagon"></div>
        <div class="shape dot"></div>
    </div>

    <main class="auth-shell">
        <section class="auth-card">
            <span class="pill">PORTAL ORANG TUA</span>
            <h1>Selamat Datang!</h1>
            <p class="lead">Mulai petualangan belajar keluarga. Masuk untuk memantau progres dan jadwal anak.</p>

            @if(session('error'))
                <div class="alert alert-error">
                    {{ session('error') }}
                </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('parent.login.post') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="email">Email Pengguna</label>
                    <div class="input-wrap">
                        <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M4 4h16v16H4z" opacity="0.2" fill="currentColor"></path>
                            <path d="m4 6 8 6 8-6" />
                        </svg>
                        <input type="email" id="email" name="email" required autofocus placeholder="ortu@demo.test">
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Kata Sandi</label>
                    <div class="input-wrap">
                        <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="4" y="11" width="16" height="9" rx="2" />
                            <path d="M8 11V7a4 4 0 0 1 8 0v4" />
                        </svg>
                        <input type="password" id="password" name="password" class="password-input" required placeholder="Masukkan kata sandi">
                        <button type="button" class="password-toggle" aria-label="Tampilkan password" onclick="togglePassword()">
                            <span id="toggleIcon">👁️</span>
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn-primary">
                    Masuk Sekarang
                    <span>🚀</span>
                </button>
            </form>

            <div class="helper">Butuh bantuan login? <a href="{{ route('home') }}">Tanya Admin</a></div>

            <div class="link-row">
                <a href="{{ route('home') }}">Kembali ke Beranda</a>
                <a href="{{ route('parent.register') }}">Daftar Orang Tua</a>
            </div>
        </section>

        <section class="auth-visual">
            <div class="visual-art">
                <img class="visual-image" src="{{ asset('images/login_ortu.png') }}" alt="Ilustrasi login orang tua">
            </div>            
        </section>
    </main>

    <button class="moon-btn" type="button" aria-label="Ganti tema">🌙</button>

    <script>
        // Password toggle functionality
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');
            const toggleButton = document.querySelector('.password-toggle');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.textContent = '🙈';
                toggleButton.setAttribute('aria-label', 'Sembunyikan password');
            } else {
                passwordInput.type = 'password';
                toggleIcon.textContent = '👁️';
                toggleButton.setAttribute('aria-label', 'Tampilkan password');
            }
        }

        // Theme toggle functionality
        const themeToggle = document.querySelector('.moon-btn');
        const body = document.body;

        const setTheme = (mode) => {
            body.classList.toggle('dark-mode', mode === 'dark');
            themeToggle.textContent = mode === 'dark' ? '☀️' : '🌙';
            themeToggle.setAttribute('aria-label', mode === 'dark' ? 'Tema terang' : 'Tema gelap');
        };

        const savedTheme = localStorage.getItem('login-theme');
        if (savedTheme === 'dark' || savedTheme === 'light') {
            setTheme(savedTheme);
        }

        themeToggle.addEventListener('click', () => {
            const nextTheme = body.classList.contains('dark-mode') ? 'light' : 'dark';
            localStorage.setItem('login-theme', nextTheme);
            setTheme(nextTheme);
        });
    </script>
</body>

</html>
