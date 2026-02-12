<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SUJAL - Generate Image Watermarker</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 50%, #1e293b 100%);
            color: white;
            padding: 40px 20px;
            overflow-x: hidden;
        }

        .container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1fr 380px;
            gap: 30px;
        }

        @media (max-width: 968px) {
            .container { grid-template-columns: 1fr; }
        }

        .glass-card {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            padding: 30px;
            height: fit-content;
        }

        .header {
            grid-column: 1 / -1;
            text-align: center;
            margin-bottom: 10px;
        }

        .header h1 {
            font-size: 32px;
            font-weight: 900;
            background: linear-gradient(to right, #60a5fa, #34d399);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 10px;
        }

        /* Canvas Area */
        .preview-area {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .canvas-container {
            width: 100%;
            background: rgba(0, 0, 0, 0.3);
            border-radius: 16px;
            border: 2px dashed rgba(255, 255, 255, 0.2);
            min-height: 480px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
            cursor: crosshair;
        }

        canvas {
            max-width: 100%;
            border-radius: 8px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.5);
            display: none;
        }

        .placeholder-msg {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 15px;
            opacity: 0.5;
            pointer-events: none;
        }

        .placeholder-msg i { font-size: 50px; }

        /* Actions Bar */
        .actions {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        @media (max-width: 600px) {
            .actions { grid-template-columns: 1fr; }
        }

        /* Sidebar Controls */
        .controls {
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .control-group {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        label {
            font-size: 13px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #94a3b8;
        }

        .mode-selector {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 8px;
            background: rgba(255, 255, 255, 0.05);
            padding: 5px;
            border-radius: 12px;
        }

        .mode-btn {
            background: transparent;
            border: none;
            color: white;
            padding: 8px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 700;
            font-size: 11px;
            transition: all 0.2s;
            opacity: 0.6;
        }

        .mode-btn.active {
            background: #3b82f6;
            opacity: 1;
            box-shadow: 0 4px 10px rgba(59, 130, 246, 0.3);
        }

        input[type="text"], input[type="file"] {
            width: 100%;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 12px 16px;
            color: white;
            outline: none;
            transition: border 0.3s;
        }

        input[type="text"]:focus {
            border-color: #3b82f6;
        }

        .icon-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 8px;
            max-height: 150px;
            overflow-y: auto;
            padding: 5px;
            background: rgba(255, 255, 255, 0.02);
            border-radius: 12px;
        }

        .icon-grid::-webkit-scrollbar { width: 4px; }
        .icon-grid::-webkit-scrollbar-thumb { background: rgba(255, 255, 255, 0.2); border-radius: 10px; }

        .icon-btn {
            aspect-ratio: 1;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            transition: all 0.2s;
            color: rgba(255, 255, 255, 0.7);
        }

        .icon-btn:hover, .icon-btn.active {
            background: #3b82f6;
            border-color: #3b82f6;
            color: white;
            transform: scale(1.1);
        }

        input[type="range"] {
            width: 100%;
            accent-color: #3b82f6;
            height: 6px;
            cursor: pointer;
        }

        .btn {
            width: 100%;
            padding: 16px;
            border-radius: 14px;
            border: none;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 13px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(37, 99, 235, 0.3);
        }

        .btn-primary:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(37, 99, 235, 0.4);
        }

        .btn-emerald {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
        }

        .btn-emerald:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(16, 185, 129, 0.4);
        }

        .btn:disabled { opacity: 0.5; cursor: not-allowed; }

        .footer-tag {
            text-align: center;
            opacity: 0.4;
            font-size: 12px;
            margin-top: 40px;
            grid-column: 1 / -1;
        }

        .drag-hint {
            position: absolute;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(0, 0, 0, 0.6);
            padding: 8px 16px;
            border-radius: 50px;
            font-size: 12px;
            backdrop-filter: blur(10px);
            display: none;
            pointer-events: none;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="header">
            <h1><i class="fas fa-shield-halved"></i>  Image Watermarker</h1>
            <p style="opacity: 0.7;">Secure your images with elegant, custom watermarks instantly.</p>
        </div>

        <div class="preview-area">
            <div class="glass-card" style="padding: 15px; flex: 1; min-height: 520px;">
                <div class="canvas-container" id="dropZone">
                    <div class="placeholder-msg" id="placeholder">
                        <i class="fas fa-image"></i>
                        <p>Drag image here or use sidebar to upload</p>
                    </div>
                    <canvas id="canvas"></canvas>
                    <div class="drag-hint" id="dragHint">
                        <i class="fas fa-arrows-up-down-left-right"></i> Drag watermarks to position them
                    </div>
                </div>
            </div>
            <div class="actions">
                <button id="downloadBtn" class="btn btn-primary" disabled>
                    <i class="fas fa-image"></i> Download Original
                </button>
                <button id="downloadWebpBtn" class="btn btn-emerald" disabled>
                    <i class="fas fa-bolt"></i> Download as WebP
                </button>
            </div>
        </div>

        <div class="controls glass-card">
            <div class="control-group">
                <label><i class="fas fa-upload"></i> 1. Upload Photo</label>
                <input type="file" id="fileInput" accept="image/*">
            </div>

            <div class="control-group">
                <label><i class="fas fa-layer-group"></i> 2. Watermark Mode (Count)</label>
                <div class="mode-selector">
                    <button class="mode-btn" data-mode="1">1</button>
                    <button class="mode-btn" data-mode="2">2</button>
                    <button class="mode-btn" data-mode="3">3</button>
                    <button class="mode-btn active" data-mode="tiled">Whole</button>
                </div>
            </div>

            <div class="control-group">
                <label><i class="fas fa-font"></i> 3. Brand Name</label>
                <input type="text" id="brandInput" placeholder="e.g. SUJAL DEVKOTA" value="SUJAL DEVKOTA">
            </div>

            <div class="control-group">
                <label><i class="fas fa-icons"></i> 4. Choose Icon</label>
                <input type="text" id="iconSearch" placeholder="Search icons..." style="margin-bottom: 8px; font-size: 12px;">
                <div class="icon-grid" id="iconGrid"></div>
            </div>

            <div class="control-group">
                <div style="display: flex; gap: 10px; align-items: center;">
                    <label><i class="fas fa-image"></i> 4. Upload Logo</label>
                    <button id="removeLogoBtn" class="btn btn-danger" style="padding: 4px 8px; font-size: 10px; width: auto; display: none;">Remove</button>
                </div>
                <input type="file" id="logoInput" accept="image/*">
            </div>

            <div class="control-group" id="logoConfig" style="display: none;">
                <label>Logo Shape</label>
                <div class="mode-selector" id="logoShapeSelector">
                    <button class="mode-btn active" data-shape="none">Original</button>
                    <button class="mode-btn" data-shape="circle">Circle</button>
                    <button class="mode-btn" data-shape="star">Star</button>
                    <button class="mode-btn" data-shape="hexagon">Hex</button>
                </div>
            </div>

            <div class="control-group">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <label><i class="fas fa-sliders"></i> 5. Compression Quality</label>
                    <span id="qualityVal" style="font-size: 12px; font-weight: 800; color: #10b981;">85%</span>
                </div>
                <input type="range" id="qualityRange" min="1" max="100" value="85">
            </div>

            <div class="control-group">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <label>6. Text Visibility</label>
                    <span id="opacityVal" style="font-size: 12px; font-weight: 800; color: #60a5fa;">30%</span>
                </div>
                <input type="range" id="opacityRange" min="1" max="100" value="30">
            </div>

            <div class="control-group">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <label>7. Logo Visibility</label>
                    <span id="logoOpacityVal" style="font-size: 12px; font-weight: 800; color: #60a5fa;">80%</span>
                </div>
                <input type="range" id="logoOpacityRange" min="1" max="100" value="80">
            </div>

            <div class="control-group">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <label>8. Text Scale</label>
                    <span id="sizeVal" style="font-size: 12px; font-weight: 800; color: #60a5fa;">24px</span>
                </div>
                <input type="range" id="sizeRange" min="10" max="200" value="24">
            </div>

            <div class="control-group">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <label>9. Logo Scale</label>
                    <span id="logoSizeVal" style="font-size: 12px; font-weight: 800; color: #60a5fa;">100px</span>
                </div>
                <input type="range" id="logoSizeRange" min="20" max="500" value="100">
            </div>
        </div>

        <div class="footer-tag">
            &copy; 2026 SUJAL - Premium Digital Tools
        </div>
    </div>

    <script>
        const canvas = document.getElementById('canvas');
        const ctx = canvas.getContext('2d');
        const fileInput = document.getElementById('fileInput');
        const logoInput = document.getElementById('logoInput');
        const brandInput = document.getElementById('brandInput');
        const iconGrid = document.getElementById('iconGrid');
        const iconSearch = document.getElementById('iconSearch');
        const opacityRange = document.getElementById('opacityRange');
        const opacityVal = document.getElementById('opacityVal');
        const logoOpacityRange = document.getElementById('logoOpacityRange');
        const logoOpacityVal = document.getElementById('logoOpacityVal');
        const sizeRange = document.getElementById('sizeRange');
        const sizeVal = document.getElementById('sizeVal');
        const logoSizeRange = document.getElementById('logoSizeRange');
        const logoSizeVal = document.getElementById('logoSizeVal');
        const qualityRange = document.getElementById('qualityRange');
        const qualityVal = document.getElementById('qualityVal');
        const downloadBtn = document.getElementById('downloadBtn');
        const downloadWebpBtn = document.getElementById('downloadWebpBtn');
        const placeholder = document.getElementById('placeholder');
        const dragHint = document.getElementById('dragHint');
        const modeBtns = document.querySelectorAll('.mode-btn:not([data-shape])');
        const shapeBtns = document.querySelectorAll('[data-shape]');
        const logoConfig = document.getElementById('logoConfig');
        const removeLogoBtn = document.getElementById('removeLogoBtn');

        let originalImage = null;
        let logoImage = null;
        let selectedIcon = "fa-shield-halved";
        let currentMode = "tiled"; // 1, 2, 3, tiled
        let currentShape = "none";
        let watermarks = []; // [{x, y}] for manual modes
        let logoPos = { x: 50, y: 50 }; // Normalized position (0-100)

        const popularIcons = [
            "fa-shield-halved", "fa-star", "fa-camera", "fa-copyright", "fa-lock",
            "fa-heart", "fa-bolt", "fa-image", "fa-circle-check", "fa-crown",
            "fa-signature", "fa-water", "fa-fingerprint", "fa-ghost", "fa-user",
            "fa-code", "fa-globe", "fa-lightbulb", "fa-gem", "fa-anchor"
        ];

        // Init/Render icons
        function renderIcons(filter = "") {
            iconGrid.innerHTML = "";
            
            // Add "None" option
            const noneBtn = document.createElement('button');
            noneBtn.className = `icon-btn ${selectedIcon === 'none' ? 'active' : ''}`;
            noneBtn.innerHTML = `<i class="fas fa-ban"></i>`;
            noneBtn.title = "No Icon";
            noneBtn.onclick = () => {
                selectedIcon = 'none';
                document.querySelectorAll('.icon-btn').forEach(b => b.classList.remove('active'));
                noneBtn.classList.add('active');
                updateWatermark();
            };
            iconGrid.appendChild(noneBtn);

            // Add "Custom Logo" option if uploaded
            if (logoImage) {
                const logoBtn = document.createElement('button');
                logoBtn.className = `icon-btn ${selectedIcon === 'custom-logo' ? 'active' : ''}`;
                logoBtn.innerHTML = `<i class="fas fa-image"></i>`;
                logoBtn.title = "Use Uploaded Logo";
                logoBtn.onclick = () => {
                    selectedIcon = 'custom-logo';
                    document.querySelectorAll('.icon-btn').forEach(b => b.classList.remove('active'));
                    logoBtn.classList.add('active');
                    updateWatermark();
                };
                iconGrid.appendChild(logoBtn);
            }

            const list = filter ? popularIcons.filter(icon => icon.includes(filter)) : popularIcons;
            list.forEach(icon => {
                const btn = document.createElement('button');
                btn.className = `icon-btn ${selectedIcon === icon ? 'active' : ''}`;
                btn.innerHTML = `<i class="fas ${icon}"></i>`;
                btn.onclick = () => {
                    selectedIcon = icon;
                    document.querySelectorAll('.icon-btn').forEach(b => b.classList.remove('active'));
                    btn.classList.add('active');
                    updateWatermark();
                };
                iconGrid.appendChild(btn);
            });
        }
        renderIcons();
        iconSearch.oninput = (e) => renderIcons(e.target.value);

        // Mode switching
        modeBtns.forEach(btn => {
            btn.onclick = () => {
                currentMode = btn.dataset.mode;
                modeBtns.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                
                if (currentMode !== 'tiled') {
                    dragHint.style.display = "block";
                    initManualWatermarks(parseInt(currentMode));
                } else if (!logoImage) {
                    dragHint.style.display = "none";
                }
                updateWatermark();
            };
        });

        // Shape switching
        shapeBtns.forEach(btn => {
            btn.onclick = () => {
                currentShape = btn.dataset.shape;
                shapeBtns.forEach(b => b.classList.remove('active'));
                btn.classList.add('active');
                updateWatermark();
            };
        });

        function initManualWatermarks(count) {
            watermarks = [];
            if (!originalImage) return;
            for (let i = 0; i < count; i++) {
                watermarks.push({
                    x: originalImage.width / 2,
                    y: (originalImage.height / (count + 1)) * (i + 1)
                });
            }
        }

        // Dragging Logic
        let isDragging = false;
        let dragIdx = -1; // -1 for logo, >=0 for text watermarks
        let isLogoDragging = false;

        canvas.onmousedown = (e) => {
            if (!originalImage) return;
            const rect = canvas.getBoundingClientRect();
            const scaleX = canvas.width / rect.width;
            const scaleY = canvas.height / rect.height;
            const mx = (e.clientX - rect.left) * scaleX;
            const my = (e.clientY - rect.top) * scaleY;

            // Check Logo first
            if (logoImage) {
                const lSize = parseInt(logoSizeRange.value);
                const lx = (logoPos.x / 100) * canvas.width;
                const ly = (logoPos.y / 100) * canvas.height;
                if (mx > lx - lSize/2 && mx < lx + lSize/2 && my > ly - lSize/2 && my < ly + lSize/2) {
                    isLogoDragging = true;
                    isDragging = true;
                    return;
                }
            }

            // Check watermarks
            if (currentMode !== 'tiled') {
                const hitZone = parseInt(sizeRange.value) * 3;
                dragIdx = watermarks.findIndex(w => {
                    return Math.abs(w.x - mx) < hitZone && Math.abs(w.y - my) < hitZone;
                });
                if (dragIdx !== -1) isDragging = true;
            }
        };

        window.onmousemove = (e) => {
            if (!isDragging) return;
            const rect = canvas.getBoundingClientRect();
            const scaleX = canvas.width / rect.width;
            const scaleY = canvas.height / rect.height;
            const mx = (e.clientX - rect.left) * scaleX;
            const my = (e.clientY - rect.top) * scaleY;

            if (isLogoDragging) {
                logoPos.x = (mx / canvas.width) * 100;
                logoPos.y = (my / canvas.height) * 100;
            } else if (dragIdx !== -1) {
                watermarks[dragIdx].x = mx;
                watermarks[dragIdx].y = my;
            }
            updateWatermark();
        };

        window.onmouseup = () => {
            isDragging = false;
            isLogoDragging = false;
            dragIdx = -1;
        };

        // File Handling
        fileInput.onchange = (e) => handleImageUpload(e, 'original');
        logoInput.onchange = (e) => handleImageUpload(e, 'logo');

        function handleImageUpload(e, type) {
            const file = e.target.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = (event) => {
                const img = new Image();
                img.onload = () => {
                    if (type === 'original') {
                        originalImage = img;
                        canvas.width = img.width;
                        canvas.height = img.height;
                        canvas.style.display = "block";
                        placeholder.style.display = "none";
                        downloadBtn.disabled = false;
                        downloadWebpBtn.disabled = false;
                        if (currentMode !== 'tiled') initManualWatermarks(parseInt(currentMode));
                    } else {
                        logoImage = img;
                        logoConfig.style.display = "block";
                        removeLogoBtn.style.display = "block";
                        dragHint.style.display = "block";
                        renderIcons(iconSearch.value); // Re-render to show "Logo" option
                    }
                    updateWatermark();
                };
                img.src = event.target.result;
            };
            reader.readAsDataURL(file);
        }

        removeLogoBtn.onclick = () => {
            logoImage = null;
            logoInput.value = "";
            logoConfig.style.display = "none";
            removeLogoBtn.style.display = "none";
            if (selectedIcon === 'custom-logo') selectedIcon = 'fa-shield-halved';
            if (currentMode === 'tiled') dragHint.style.display = "none";
            renderIcons(iconSearch.value);
            updateWatermark();
        };

        // Control Inputs
        brandInput.oninput = updateWatermark;
        opacityRange.oninput = (e) => {
            opacityVal.textContent = e.target.value + "%";
            updateWatermark();
        };
        logoOpacityRange.oninput = (e) => {
            logoOpacityVal.textContent = e.target.value + "%";
            updateWatermark();
        };
        sizeRange.oninput = (e) => {
            sizeVal.textContent = e.target.value + "px";
            updateWatermark();
        };
        logoSizeRange.oninput = (e) => {
            logoSizeVal.textContent = e.target.value + "px";
            updateWatermark();
        };
        qualityRange.oninput = (e) => {
            qualityVal.textContent = e.target.value + "%";
        };

        function updateWatermark() {
            if (!originalImage) return;
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            ctx.drawImage(originalImage, 0, 0);

            // Draw Logo first if exists
            if (logoImage) {
                drawLogo();
            }

            // Draw Text watermarks
            const opacity = opacityRange.value / 100;
            const size = parseInt(sizeRange.value);
            const brand = brandInput.value;

            ctx.save();
            ctx.globalAlpha = opacity;
            ctx.fillStyle = "white";
            ctx.textAlign = "center";
            ctx.textBaseline = "middle";

            if (currentMode === 'tiled') {
                drawTiledPattern(brand, selectedIcon, size);
            } else {
                watermarks.forEach(w => drawSingleWatermark(w.x, w.y, brand, selectedIcon, size));
            }
            ctx.restore();
        }

        function drawLogo() {
            const size = parseInt(logoSizeRange.value);
            const opacity = parseInt(logoOpacityRange.value) / 100;
            const lx = (logoPos.x / 100) * canvas.width;
            const ly = (logoPos.y / 100) * canvas.height;

            ctx.save();
            ctx.globalAlpha = opacity;
            
            // Create Clipping Path for Shape
            ctx.beginPath();
            if (currentShape === 'circle') {
                ctx.arc(lx, ly, size / 2, 0, Math.PI * 2);
                ctx.clip();
            } else if (currentShape === 'star') {
                drawStarPath(lx, ly, 5, size / 2, size / 4);
                ctx.clip();
            } else if (currentShape === 'hexagon') {
                drawHexPath(lx, ly, size / 2);
                ctx.clip();
            }

            // Maintain Aspect Ratio while fitting into 'size'
            const ratio = logoImage.width / logoImage.height;
            let w = size, h = size;
            if (ratio > 1) h = size / ratio;
            else w = size * ratio;

            ctx.drawImage(logoImage, lx - w / 2, ly - h / 2, w, h);
            ctx.restore();
        }

        function drawStarPath(cx, cy, spikes, outerRadius, innerRadius) {
            let rot = Math.PI / 2 * 3;
            let x = cx;
            let y = cy;
            let step = Math.PI / spikes;

            ctx.moveTo(cx, cy - outerRadius);
            for (let i = 0; i < spikes; i++) {
                x = cx + Math.cos(rot) * outerRadius;
                y = cy + Math.sin(rot) * outerRadius;
                ctx.lineTo(x, y);
                rot += step;

                x = cx + Math.cos(rot) * innerRadius;
                y = cy + Math.sin(rot) * innerRadius;
                ctx.lineTo(x, y);
                rot += step;
            }
            ctx.lineTo(cx, cy - outerRadius);
            ctx.closePath();
        }

        function drawHexPath(x, y, r) {
            ctx.moveTo(x + r * Math.cos(0), y + r * Math.sin(0));
            for (let i = 1; i <= 6; i++) {
                ctx.lineTo(x + r * Math.cos(i * 2 * Math.PI / 6), y + r * Math.sin(i * 2 * Math.PI / 6));
            }
            ctx.closePath();
        }

        function drawSingleWatermark(x, y, text, iconChoice, size) {
            ctx.save();
            ctx.translate(x, y);
            
            // Draw Icon/Logo if selected
            if (iconChoice === 'custom-logo' && logoImage) {
                // Use Logo as Icon
                const ratio = logoImage.width / logoImage.height;
                let w = size * 1.5, h = size * 1.5;
                if (ratio > 1) h = w / ratio; else w = h * ratio;
                ctx.drawImage(logoImage, -w/2, -size/2 - h/2, w, h);
            } else if (iconChoice !== 'none') {
                // Use FontAwesome Icon
                ctx.font = `900 ${size * 1.2}px "Font Awesome 6 Free"`;
                ctx.fillText(getIconUnicode(iconChoice), 0, -size/2);
            }
            
            // Draw Text
            ctx.font = `bold ${size}px Inter, sans-serif`;
            ctx.fillText(text, 0, size);
            ctx.restore();
        }

        function drawTiledPattern(text, iconChoice, size) {
            ctx.font = `bold ${size}px Inter`;
            const textWidth = ctx.measureText(text).width;
            const spacingX = textWidth + size * 4;
            const spacingY = size * 6;
            const diag = Math.sqrt(canvas.width**2 + canvas.height**2);
            const angle = -30 * Math.PI / 180;

            for (let y = -diag; y < diag * 1.5; y += spacingY) {
                for (let x = -diag; x < diag * 1.5; x += spacingX) {
                    ctx.save();
                    ctx.translate(x, y);
                    ctx.rotate(angle);
                    
                    // Draw Icon/Logo
                    if (iconChoice === 'custom-logo' && logoImage) {
                        const ratio = logoImage.width / logoImage.height;
                        let w = size * 1.5, h = size * 1.5;
                        if (ratio > 1) h = w / ratio; else w = h * ratio;
                        ctx.drawImage(logoImage, -w/2, -h/2, w, h);
                    } else if (iconChoice !== 'none') {
                        ctx.font = `900 ${size * 1.2}px "Font Awesome 6 Free"`;
                        ctx.fillText(getIconUnicode(iconChoice), 0, 0);
                    }
                    
                    ctx.font = `bold ${size}px Inter`;
                    ctx.fillText(text, 0, size * 1.5);
                    ctx.restore();
                }
            }
        }

        function getIconUnicode(iconClass) {
            const map = {
                "fa-shield-halved": "\uf3ed", "fa-star": "\uf005", "fa-camera": "\uf030",
                "fa-copyright": "\uf1f9", "fa-lock": "\uf023", "fa-heart": "\uf004",
                "fa-bolt": "\uf0e7", "fa-image": "\uf03e", "fa-circle-check": "\uf058",
                "fa-crown": "\uf521", "fa-signature": "\uf5b7", "fa-water": "\uf773",
                "fa-fingerprint": "\uf577", "fa-ghost": "\uf6e2", "fa-user": "\uf007",
                "fa-code": "\uf121", "fa-globe": "\uf0ac", "fa-lightbulb": "\uf0eb",
                "fa-gem": "\uf3a5", "fa-anchor": "\uf13d"
            };
            return map[iconClass] || "\uf005";
        }

        function downloadImage(type, ext) {
            const quality = parseInt(qualityRange.value) / 100;
            const fileName = 'sujal-watermarked-' + Date.now() + '.' + ext;
            
            canvas.toBlob((blob) => {
                const url = URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = fileName;
                a.click();
                URL.revokeObjectURL(url);
            }, type, quality);
        }

        downloadBtn.onclick = () => downloadImage('image/png', 'png');
        downloadWebpBtn.onclick = () => downloadImage('image/webp', 'webp');

        // Drag & Drop File
        const dropZone = document.getElementById('dropZone');
        dropZone.ondragover = (e) => { e.preventDefault(); dropZone.style.background = "rgba(59, 130, 246, 0.1)"; };
        dropZone.ondragleave = () => { dropZone.style.background = "rgba(0, 0, 0, 0.3)"; };
        dropZone.ondrop = (e) => {
            e.preventDefault();
            dropZone.style.background = "rgba(0, 0, 0, 0.3)";
            if (e.dataTransfer.files.length) {
                fileInput.files = e.dataTransfer.files;
                fileInput.onchange({ target: fileInput });
            }
        };
    </script>
</body>

</html>
