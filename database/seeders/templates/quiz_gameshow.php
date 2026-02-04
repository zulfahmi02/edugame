<?php
// Kuis Gameshow Template - VERSI PREMIUM PURPLE (Bulletproof)
return [
    'name' => 'Kuis Gameshow',
    'slug' => 'kuis-gameshow',
    'template_type' => 'quiz_gameshow',
    'icon' => '🎭',
    'description' => 'Kuis premium bergaya Televisi dengan visual Ungu-Emas, lampu sorot, dan fitur anti-macet.',
    'is_active' => true,
    'html_template' => <<<'HTML'
<!-- 1. CSS STYLE: Premium Purple Theme -->
<style>
@import url('https://fonts.googleapis.com/css2?family=Bungee&family=Plus+Jakarta+Sans:wght@400;700;800&display=swap');

.ww-premium-root {
    --bg-grad: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    --gold: #FFD700;
    --gold-grad: linear-gradient(135deg, #FFD700, #FFA500);
    
    width: 100%;
    min-height: 600px;
    background: var(--bg-grad);
    border-radius: 25px;
    font-family: 'Plus Jakarta Sans', sans-serif;
    color: white;
    position: relative;
    overflow: visible;
    z-index: 10;
    box-shadow: 0 20px 50px rgba(0,0,0,0.3);
}

.ww-p-screen {
    width: 100%; min-height: 600px;
    display: none; flex-direction: column; align-items: center; justify-content: center;
    padding: 30px; text-align: center;
    position: relative;
}
.ww-p-screen.active { display: flex; }

/* SPONTANEOUS LIGHTS */
.ww-p-lights { position: absolute; inset: 0; z-index: 1; pointer-events: none; overflow: hidden; opacity: 0.6; }
.p-light { position: absolute; width: 250px; height: 250px; border-radius: 50%; filter: blur(60px); animation: p-spot 3s infinite alternate; }
@keyframes p-spot { from { opacity: 0.2; transform: scale(1); } to { opacity: 0.8; transform: scale(1.3); } }

/* BUTTONS */
.ww-p-btn {
    background: var(--gold-grad); color: #000; border: none;
    padding: 25px 60px; font-size: 2.5rem; font-weight: 900; border-radius: 60px;
    cursor: pointer !important; text-transform: uppercase;
    box-shadow: 0 10px 30px rgba(255,215,0,0.5);
    position: relative; z-index: 9999; transition: 0.2s;
    font-family: 'Bungee', cursive;
}
.ww-p-btn:hover { transform: scale(1.05); box-shadow: 0 15px 40px rgba(255,215,0,0.8); }

/* QUESTION PANEL */
.ww-p-qpanel {
    background: rgba(255, 255, 255, 0.95); color: #333;
    padding: 40px; border-radius: 35px; width: 100%; max-width: 800px;
    margin-bottom: 30px; box-shadow: 0 15px 35px rgba(0,0,0,0.4);
    position: relative; z-index: 50;
}
.ww-p-qtext { font-size: 2.2rem; font-weight: 800; line-height: 1.2; }

/* ANSWERS */
.ww-p-ans-grid { display: grid; gap: 15px; width: 100%; max-width: 800px; position: relative; z-index: 50; }
.ww-p-opt {
    background: #f8fafc; border: 4px solid #667eea; padding: 22px;
    border-radius: 20px; font-size: 1.6rem; font-weight: 800; color: #4338ca;
    cursor: pointer !important; transition: 0.2s;
}
.ww-p-opt:hover { background: #eef2ff; transform: translateX(5px); }
.ww-p-opt.correct { background: #10b981 !important; color: white; border-color: #065f46; }
.ww-p-opt.wrong { background: #ef4444 !important; color: white; border-color: #991b1b; }

#p-debug-log { position: absolute; bottom: 10px; right: 20px; font-size: 12px; color: rgba(255,255,255,0.5); pointer-events: none; }
</style>

<!-- 2. HTML STRUCTURE -->
<div class="ww-premium-root">
    <div class="ww-p-lights">
        <div class="p-light" style="top:5%; left:5%; background:rgba(255,215,0,0.4);"></div>
        <div class="p-light" style="bottom:5%; right:5%; background:rgba(0,255,255,0.3);"></div>
    </div>

    <!-- Start Screen -->
    <div id="p-scr-start" class="ww-p-screen active">
        <div style="z-index: 100;">
            <h1 style="font-family:'Bungee'; font-size: 5rem; color:var(--gold); text-shadow:4px 4px #000; margin-bottom:10px;">🎮 QUIZ SHOW 🎮</h1>
            <p style="font-size:1.5rem; margin-bottom:40px; font-weight:700;">Ultimate Knowledge Challenge</p>
            <button class="ww-p-btn" onmousedown="pStart()">START GAME</button>
        </div>
    </div>

    <div id="p-scr-loading" class="ww-p-screen" style="background:#1e3c72;">
        <h2 style="font-family:'Bungee'; font-size:4rem;">READY?</h2>
    </div>

    <div id="p-scr-game" class="ww-p-screen">
        <div style="width:100%; max-width:800px; display:flex; justify-content:space-between; margin-bottom:20px;">
            <div style="background:rgba(255,255,255,0.9); color:#333; padding:10px 25px; border-radius:15px; font-weight:800;">SOAL {{question_number}}</div>
            <div style="background:rgba(255,215,0,0.9); color:#000; padding:10px 25px; border-radius:15px; font-weight:800;">TIME: <span id="p-timer">10</span>s</div>
        </div>
        <div class="ww-p-qpanel"><h2 class="ww-p-qtext">{{question}}</h2></div>
        <div class="ww-p-ans-grid" id="p-ans-grid"></div>
    </div>
    
    <input type="hidden" id="answer-input" value="">
    <div id="p-debug-log">Premium System Ready.</div>
</div>

<!-- 3. JAVASCRIPT LOGIC -->
<script>
function pLog(m) { 
    console.log("P-Log: "+m); 
    var l = document.getElementById('p-debug-log'); if(l) l.textContent = m; 
}

var pState = {
    isFirst: {{is_first_question}},
    sid: {{session_id}},
    answered: false,
    timeLeft: 10,
    timer: null,
    audio: null
};

function pSfx(f, d) {
    try {
        if(!pState.audio) pState.audio = new (window.AudioContext || window.webkitAudioContext)();
        var o = pState.audio.createOscillator(); var g = pState.audio.createGain();
        o.frequency.value = f; g.gain.exponentialRampToValueAtTime(0.01, pState.audio.currentTime + d);
        o.connect(g); g.connect(pState.audio.destination);
        o.start(); o.stop(pState.audio.currentTime + d);
    } catch(e) {}
}

function pStart() {
    pLog("Starting Game...");
    pSfx(523, 0.5);
    pShow('p-scr-loading');
    setTimeout(pInit, 1200);
}

function pShow(id) {
    document.querySelectorAll('.ww-p-screen').forEach(s => s.classList.remove('active'));
    var t = document.getElementById(id); if(t) t.classList.add('active');
}

function pInit() {
    pShow('p-scr-game');
    pState.answered = false;
    pRender();
    pStartTimer();
}

function pRender() {
    var g = document.getElementById('p-ans-grid');
    var s = document.getElementById('option-items-source');
    if(!g || !s) return; g.innerHTML = '';
    s.querySelectorAll('.option-item').forEach(function(itm, i) {
        var v = itm.getAttribute('data-value');
        var t = itm.querySelector('.option-text').textContent;
        var b = document.createElement('div');
        b.className = 'ww-p-opt';
        b.textContent = t;
        b.onmousedown = function() { pSelect(b, v); };
        g.appendChild(b);
    });
}

function pStartTimer() {
    pState.timeLeft = 10;
    if(pState.timer) clearInterval(pState.timer);
    pState.timer = setInterval(function() {
        if(pState.answered) return;
        pState.timeLeft--;
        var tEl = document.getElementById('p-timer'); if(tEl) tEl.textContent = pState.timeLeft;
        if(pState.timeLeft <= 0) { clearInterval(pState.timer); pSelect(null, 'timeout'); }
    }, 1000);
}

function pSelect(el, val) {
    if(pState.answered) return;
    pState.answered = true;
    clearInterval(pState.timer);
    pLog("Selected: " + val);

    var inp = document.getElementById('answer-input'); if(inp) inp.value = val;
    if(typeof submitAnswer === 'function') submitAnswer();

    var c = setInterval(function() {
        var fb = document.getElementById('feedback');
        if(fb && fb.style.display !== 'none' && fb.innerHTML !== '') {
            clearInterval(c);
            var ok = fb.classList.contains('correct');
            if(el) el.className = 'ww-p-opt ' + (ok ? 'correct' : 'wrong');
            pSfx(ok ? 880 : 220, 0.5);
            setTimeout(function() { window.location.href = '/session/'+pState.sid+'/question'; }, 2000);
        }
    }, 100);
}

if(!pState.isFirst) {
    pLog("Auto-loading Next Level...");
    setTimeout(function() { pShow('p-scr-loading'); setTimeout(pInit, 800); }, 50);
}
</script>
HTML
,
    'css_style' => '',
    'js_code' => '',
];
