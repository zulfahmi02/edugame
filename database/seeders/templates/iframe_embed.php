<?php
// Iframe Embed Template - UPDATE: Manual Score Entry
return [
    'name' => 'Sematkan Game (Embed/Wordwall)',
    'slug' => 'iframe-embed',
    'template_type' => 'iframe_embed',
    'icon' => '🔗',
    'description' => 'Sematkan game dari Wordwall/Quizizz dengan fitur input skor manual siswa.',
    'is_active' => true,
    'html_template' => '<div class="iframe-container">
    <div class="iframe-display">
        {{correct_answer}}
    </div>
    
    <div id="manual-score-section" class="score-input-card">
        <h3>🎉 Permainan Selesai?</h3>
        <p>Berapa skor yang kamu dapat? Ketik di bawah ya:<br><small>(Ingat, murid hebat itu murid yang <strong>jujur</strong>! 😊)</small></p>
        <div class="input-group-score">
            <input type="number" id="manual-score-input" class="score-field" placeholder="0" min="0">
            <button type="button" onclick="submitManualScore()" class="btn-score-save" id="save-score-btn">
                Simpan Skor ✅
            </button>
        </div>
        <p class="score-note">Skor ini akan dikirimkan ke gurumu.</p>
    </div>

    <input type="hidden" id="answer-input" value="">
</div>',
    'css_style' => '
.iframe-container { text-align: center; padding: 10px; }
.iframe-display { 
    background: #000; 
    border-radius: 20px; 
    overflow: hidden; 
    box-shadow: 0 10px 40px rgba(0,0,0,0.1); 
    margin-bottom: 25px;
    position: relative;
    padding-bottom: 75%; /* Aspect ratio 4:3 */
    height: 0;
}
.iframe-display iframe { 
    position: absolute;
    top: 0;
    left: 0;
    width: 100% !important; 
    height: 100% !important; 
    border: none;
}
.score-input-card {
    background: white;
    padding: 30px;
    border-radius: 25px;
    box-shadow: 0 15px 35px rgba(0,0,0,0.1);
    border: 3px solid #667eea;
    max-width: 500px;
    margin: 0 auto;
}
.score-input-card h3 { color: #4338ca; margin-bottom: 10px; font-weight: 800; }
.score-input-card p { color: #64748b; margin-bottom: 20px; line-height: 1.5; }
.input-group-score { display: flex; gap: 10px; justify-content: center; }
.score-field {
    width: 100px;
    padding: 15px;
    border: 2px solid #e2e8f0;
    border-radius: 15px;
    font-size: 1.5rem;
    font-weight: 800;
    text-align: center;
}
.btn-score-save {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    border: none;
    padding: 0 25px;
    border-radius: 15px;
    font-weight: 700;
    cursor: pointer;
    transition: 0.2s;
}
.btn-score-save:hover { transform: scale(1.05); }
.score-note { font-size: 0.8rem; margin-top: 15px; font-style: italic; }

@media (min-width: 768px) {
    .iframe-display {
        padding-bottom: 56.25%; /* Aspect ratio 16:9 on desktop */
    }
}',
    'js_code' => '
function submitManualScore() {
    const scoreVal = document.getElementById("manual-score-input").value;
    const ansInput = document.getElementById("answer-input");
    const saveBtn = document.getElementById("save-score-btn");

    if (scoreVal === "" || scoreVal < 0) {
        Swal.fire({
            icon: "warning",
            title: "Opps!",
            text: "Silakan masukkan skor yang valid!",
            confirmButtonColor: "#667eea",
        });
        return;
    }

    Swal.fire({
        title: "🚀 Kirim Skor?",
        html: "Kamu dapat skor <strong>" + scoreVal + "</strong>.<br>Kirim ke guru sekarang?<br><br><small><i>\"Kejujuran adalah ciri anak pintar!\"</i> ✨</small>",
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: "#10b981",
        cancelButtonColor: "#f43f5e",
        confirmButtonText: "✅ Ya, Kirim!",
        cancelButtonText: "Batal",
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            ansInput.value = scoreVal;
            saveBtn.disabled = true;
            saveBtn.textContent = "Mengirim...";
            
            if (typeof submitAnswer === "function") {
                submitAnswer();
            } else {
                Swal.fire("Error", "Sistem jawaban tidak ditemukan!", "error");
                saveBtn.disabled = false;
                saveBtn.textContent = "Simpan Skor ✅";
            }
        }
    });
}

document.addEventListener("DOMContentLoaded", function() {
    const originalSubmit = document.getElementById("submit-btn");
    if (originalSubmit) originalSubmit.style.display = "none";
});'
];
