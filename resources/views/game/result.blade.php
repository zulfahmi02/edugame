<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil - {{ $session->game->title }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px; }
        .result-container { background: white; padding: 50px; border-radius: 25px; max-width: 600px; width: 100%; box-shadow: 0 20px 60px rgba(0,0,0,0.3); text-align: center; }
        .trophy { font-size: 100px; margin-bottom: 20px; animation: bounce 1s ease-in-out; }
        @keyframes bounce { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-20px); } }
        h1 { color: #333; margin-bottom: 10px; }
        .subtitle { color: #666; margin-bottom: 30px; }
        .stats-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px; margin-bottom: 30px; }
        .stat-card { background: #f9fafb; padding: 20px; border-radius: 15px; }
        .stat-label { color: #666; font-size: 14px; margin-bottom: 5px; }
        .stat-value { font-size: 32px; font-weight: bold; color: #667eea; }
        .accuracy { font-size: 48px; font-weight: bold; margin: 20px 0; }
        .accuracy.excellent { color: #10b981; }
        .accuracy.good { color: #f59e0b; }
        .accuracy.poor { color: #ef4444; }
        .actions { display: flex; gap: 10px; flex-direction: column; }
        .btn { padding: 15px 30px; border-radius: 10px; text-decoration: none; font-size: 16px; font-weight: 600; border: none; cursor: pointer; transition: all 0.3s; }
        .btn-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; }
        .btn-secondary { background: #10b981; color: white; }
        .btn-outline { background: white; color: #667eea; border: 2px solid #667eea; }
        .btn:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(0,0,0,0.2); }
    </style>
    <link rel="stylesheet" href="{{ asset('css/mobile-responsive-fix.css') }}">
</head>
<body>
    <div class="result-container">
        <div class="trophy">
            @if($session->accuracy >= 80)
                🏆
            @elseif($session->accuracy >= 60)
                🥈
            @else
                🎯
            @endif
        </div>

        <h1>Selamat, {{ session('student_name') }}!</h1>
        <p class="subtitle">Kamu telah menyelesaikan {{ $session->game->title }}</p>

        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-label">Total Soal</div>
                <div class="stat-value">{{ $session->total_questions }}</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Jawaban Benar</div>
                <div class="stat-value">{{ $session->correct_answers }}</div>
            </div>
        </div>

        <div class="accuracy {{ $session->accuracy >= 80 ? 'excellent' : ($session->accuracy >= 60 ? 'good' : 'poor') }}">
            {{ $session->accuracy }}% Akurasi
        </div>

        <div class="stat-card" style="margin-bottom: 30px;">
            <div class="stat-label">Total Poin</div>
            <div class="stat-value" style="font-size: 48px;">{{ $session->total_score }}</div>
        </div>

        <div class="actions">
            <form action="{{ route('games.start', $session->game->slug) }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-secondary" style="width: 100%;">🔄 Main Lagi</button>
            </form>
            <a href="{{ route('games.index') }}" class="btn btn-primary">🎮 Pilih Game Lain</a>
            <form action="{{ route('student.logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-outline" style="width: 100%;">🚪 Keluar</button>
            </form>
        </div>
    </div>

    <script>
        // Synthetic Win Sound
        function playWinSound() {
            const ctx = new (window.AudioContext || window.webkitAudioContext)();
            const freqs = [523.25, 659.25, 783.99, 1046.50]; // C5, E5, G5, C6
            
            freqs.forEach((f, i) => {
                const osc = ctx.createOscillator();
                const gain = ctx.createGain();
                osc.type = 'triangle';
                osc.frequency.setValueAtTime(f, ctx.currentTime + (i * 0.15));
                gain.gain.setValueAtTime(0.1, ctx.currentTime + (i * 0.15));
                gain.gain.exponentialRampToValueAtTime(0.01, ctx.currentTime + (i * 0.15) + 0.4);
                osc.connect(gain);
                gain.connect(ctx.destination);
                osc.start(ctx.currentTime + (i * 0.15));
                osc.stop(ctx.currentTime + (i * 0.15) + 0.4);
            });
        }

        // Play on load
        window.addEventListener('load', () => {
            // Suara terkadang diblokir oleh browser jika tidak ada interaksi sebelumnya.
            // Namun karena ini adalah halaman redirect setelah menjawab soal (interaksi terakhir),
            // biasanya suara akan tetap berbunyi.
            playWinSound();
        });
    </script>
</body>
</html>
