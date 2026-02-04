<?php
// Arrow Matching Template
return [
    'name' => 'Mencocokkan dengan Panah',
    'slug' => 'mencocokkan-panah',
    'template_type' => 'arrow_matching',
    'icon' => '🎯',
    'description' => 'Cocokkan item dengan menarik panah dari kiri ke kanan',
    'is_active' => true,
    'html_template' => '<div class="arrow-matching-game">
    <div class="question-text">{{question}}</div>
    <div class="matching-container">
        <div class="left-column" id="left-column"></div>
        <svg class="arrow-canvas" id="arrow-canvas"></svg>
        <div class="right-column" id="right-column"></div>
    </div>
    <input type="hidden" id="answer-input" value="">
    <div class="instructions">💡 Klik item di kiri, lalu klik pasangannya di kanan untuk membuat panah</div>
</div>',
    'css_style' => '
.arrow-matching-game { padding: 20px; max-width: 900px; margin: 0 auto; }
.question-text { font-size: 22px; font-weight: 600; color: #333; margin-bottom: 30px; text-align: center; }
.matching-container { position: relative; display: grid; grid-template-columns: 1fr 200px 1fr; gap: 20px; min-height: 400px; }
.left-column, .right-column { display: flex; flex-direction: column; gap: 20px; }
.match-item { background: white; border: 3px solid #e0e0e0; border-radius: 15px; padding: 20px; cursor: pointer; transition: all 0.3s; text-align: center; font-weight: 600; font-size: 16px; position: relative; min-height: 80px; display: flex; align-items: center; justify-content: center; }
.match-item:hover { border-color: #667eea; transform: scale(1.02); box-shadow: 0 4px 15px rgba(102,126,234,0.3); }
.match-item.selected { border-color: #27ae60; background: linear-gradient(135deg, rgba(39,174,96,0.1), white); }
.match-item.matched { border-color: #3498db; background: linear-gradient(135deg, rgba(52,152,219,0.1), white); }
.match-item img { max-width: 100%; max-height: 60px; object-fit: contain; }
.arrow-canvas { position: absolute; top: 0; left: 0; width: 100%; height: 100%; pointer-events: none; z-index: 1; }
.arrow-line { stroke: #3498db; stroke-width: 3; fill: none; cursor: pointer; pointer-events: all; }
.arrow-line:hover { stroke: #e74c3c; stroke-width: 4; }
.instructions { text-align: center; margin-top: 20px; color: #7f8c8d; font-size: 14px; }
.btn-submit { margin-top: 25px; }',
    'js_code' => '
document.addEventListener("DOMContentLoaded", function() {
    const leftColumn = document.getElementById("left-column");
    const rightColumn = document.getElementById("right-column");
    const canvas = document.getElementById("arrow-canvas");
    const answerInput = document.getElementById("answer-input");
    
    let selectedLeft = null;
    let matches = {};
    let leftItems = [];
    let rightItems = [];
    
    // Parse options from hidden option-item elements
    const options = document.querySelectorAll(".option-item");
    options.forEach(opt => {
        const value = opt.getAttribute("data-value");
        const text = opt.querySelector(".option-text").textContent;
        const imgEl = opt.querySelector(".option-image");
        const img = imgEl ? imgEl.getAttribute("src") : null;
        
        if (value.startsWith("L")) {
            leftItems.push({id: value, text: text, img: img});
        } else if (value.startsWith("R")) {
            rightItems.push({id: value, text: text, img: img});
        }
    });
    
    // Render items
    leftItems.forEach(item => {
        const div = document.createElement("div");
        div.className = "match-item";
        div.setAttribute("data-id", item.id);
        if (item.img) {
            div.innerHTML = `<img src="${item.img}" alt="${item.text}">`;
        } else {
            div.textContent = item.text;
        }
        div.onclick = () => selectLeft(item.id);
        leftColumn.appendChild(div);
    });
    
    rightItems.forEach(item => {
        const div = document.createElement("div");
        div.className = "match-item";
        div.setAttribute("data-id", item.id);
        if (item.img) {
            div.innerHTML = `<img src="${item.img}" alt="${item.text}">`;
        } else {
            div.textContent = item.text;
        }
        div.onclick = () => selectRight(item.id);
        rightColumn.appendChild(div);
    });
    
    function selectLeft(id) {
        document.querySelectorAll(".left-column .match-item").forEach(el => el.classList.remove("selected"));
        document.querySelector(`.left-column [data-id="${id}"]`).classList.add("selected");
        selectedLeft = id;
    }
    
    function selectRight(id) {
        if (!selectedLeft) {
            alert("Pilih item di kolom kiri terlebih dahulu!");
            return;
        }
        
        // Remove existing match for this left item
        if (matches[selectedLeft]) {
            delete matches[selectedLeft];
        }
        
        // Add new match
        matches[selectedLeft] = id;
        updateMatches();
        selectedLeft = null;
        document.querySelectorAll(".match-item").forEach(el => el.classList.remove("selected"));
    }
    
    function updateMatches() {
        // Clear canvas
        canvas.innerHTML = "";
        
        // Update matched status
        document.querySelectorAll(".match-item").forEach(el => el.classList.remove("matched"));
        
        // Draw arrows
        Object.keys(matches).forEach(leftId => {
            const rightId = matches[leftId];
            const leftEl = document.querySelector(`.left-column [data-id="${leftId}"]`);
            const rightEl = document.querySelector(`.right-column [data-id="${rightId}"]`);
            
            if (leftEl && rightEl) {
                leftEl.classList.add("matched");
                rightEl.classList.add("matched");
                drawArrow(leftEl, rightEl, leftId);
            }
        });
        
        // Update answer input
        answerInput.value = JSON.stringify(matches);
    }
    
    function drawArrow(fromEl, toEl, leftId) {
        const containerRect = canvas.getBoundingClientRect();
        const fromRect = fromEl.getBoundingClientRect();
        const toRect = toEl.getBoundingClientRect();
        
        const x1 = fromRect.right - containerRect.left;
        const y1 = fromRect.top + fromRect.height / 2 - containerRect.top;
        const x2 = toRect.left - containerRect.left;
        const y2 = toRect.top + toRect.height / 2 - containerRect.top;
        
        const line = document.createElementNS("http://www.w3.org/2000/svg", "line");
        line.setAttribute("x1", x1);
        line.setAttribute("y1", y1);
        line.setAttribute("x2", x2);
        line.setAttribute("y2", y2);
        line.setAttribute("class", "arrow-line");
        line.setAttribute("marker-end", "url(#arrowhead)");
        line.onclick = () => {
            delete matches[leftId];
            updateMatches();
        };
        
        canvas.appendChild(line);
    }
    
    // Add arrowhead marker
    const defs = document.createElementNS("http://www.w3.org/2000/svg", "defs");
    const marker = document.createElementNS("http://www.w3.org/2000/svg", "marker");
    marker.setAttribute("id", "arrowhead");
    marker.setAttribute("markerWidth", "10");
    marker.setAttribute("markerHeight", "10");
    marker.setAttribute("refX", "9");
    marker.setAttribute("refY", "3");
    marker.setAttribute("orient", "auto");
    const polygon = document.createElementNS("http://www.w3.org/2000/svg", "polygon");
    polygon.setAttribute("points", "0 0, 10 3, 0 6");
    polygon.setAttribute("fill", "#3498db");
    marker.appendChild(polygon);
    defs.appendChild(marker);
    canvas.appendChild(defs);
});'
];
