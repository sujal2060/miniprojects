<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SUJAL - Pro Image Filtration</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
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
            .container { 
                grid-template-columns: 1fr;
                gap: 20px;
                padding: 10px;
            }
            .preview-area {
                order: 1;
            }
            .controls {
                order: 2;
            }
            .canvas-container {
                min-height: 350px;
            }
            .header h1 {
                font-size: 24px;
            }
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
            background: linear-gradient(to right, #60a5fa, #a78bfa);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 10px;
        }

        /* Preview Area */
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
            min-height: 500px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            position: relative;
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
        }

        .placeholder-msg i { font-size: 60px; }

        /* Controls */
        .controls {
            display: flex;
            flex-direction: column;
            gap: 24px;
        }

        .control-group {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        label {
            font-size: 13px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #94a3b8;
            display: flex;
            justify-content: space-between;
        }

        label span {
            color: #60a5fa;
            font-weight: 800;
        }

        input[type="range"] {
            width: 100%;
            accent-color: #60a5fa;
            height: 6px;
            cursor: pointer;
        }

        input[type="file"] {
            width: 100%;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 12px;
            color: white;
            outline: none;
        }

        .actions {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
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

        .btn-emerald {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
        }

        .btn-reset {
            background: rgba(239, 68, 68, 0.1);
            color: #ef4444;
            border: 1px solid rgba(239, 68, 68, 0.2);
            padding: 10px;
            font-size: 11px;
        }

        .btn:hover:not(:disabled) {
            transform: translateY(-2px);
            filter: brightness(1.1);
        }

        .btn:disabled { opacity: 0.5; cursor: not-allowed; }

        .footer-tag {
            text-align: center;
            opacity: 0.4;
            font-size: 12px;
            margin-top: 40px;
            grid-column: 1 / -1;
        }

        .filters-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 20px;
            max-height: 400px;
            overflow-y: auto;
            padding-right: 10px;
        }

        .filters-grid::-webkit-scrollbar { width: 4px; }
        .filters-grid::-webkit-scrollbar-thumb { background: rgba(255, 255, 255, 0.2); border-radius: 10px; }
    </style>
</head>

<body>

    <div class="container">
        <div class="header">
            <h1><i class="fas fa-sliders"></i> Pro Image Filtration</h1>
            <p style="opacity: 0.7;">Enhance and transform your photos with high-quality visual filters.</p>
        </div>

        <div class="preview-area">
            <div class="glass-card" style="padding: 15px; min-height: 520px; display: flex; flex-direction: column;">
                <div class="canvas-container" id="dropZone">
                    <div class="placeholder-msg" id="placeholder">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <p>Drag image here or use sidebar to upload</p>
                    </div>
                    <canvas id="canvas"></canvas>
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
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <label><i class="fas fa-magic"></i> 2. Apply Filters</label>
                    <button class="btn btn-reset" id="resetFilters"><i class="fas fa-undo"></i> Reset</button>
                </div>
                
                <div class="filters-grid">
                    <div class="control-group">
                        <label>Brightness <span id="brightnessVal">100%</span></label>
                        <input type="range" id="brightness" min="0" max="200" value="100">
                    </div>
                    <div class="control-group">
                        <label>Contrast <span id="contrastVal">100%</span></label>
                        <input type="range" id="contrast" min="0" max="200" value="100">
                    </div>
                    <div class="control-group">
                        <label>Saturation <span id="saturationVal">100%</span></label>
                        <input type="range" id="saturation" min="0" max="200" value="100">
                    </div>
                    <div class="control-group">
                        <label>Grayscale <span id="grayscaleVal">0%</span></label>
                        <input type="range" id="grayscale" min="0" max="100" value="0">
                    </div>
                    <div class="control-group">
                        <label>Sepia <span id="sepiaVal">0%</span></label>
                        <input type="range" id="sepia" min="0" max="100" value="0">
                    </div>
                    <div class="control-group">
                        <label>Hue-Rotate <span id="hueRotateVal">0deg</span></label>
                        <input type="range" id="hueRotate" min="0" max="360" value="0">
                    </div>
                    <div class="control-group">
                        <label>Blur <span id="blurVal">0px</span></label>
                        <input type="range" id="blur" min="0" max="20" value="0">
                    </div>
                    <div class="control-group">
                        <label>Invert <span id="invertVal">0%</span></label>
                        <input type="range" id="invert" min="0" max="100" value="0">
                    </div>
                </div>
            </div>

            <div class="control-group">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <label><i class="fas fa-tachometer-alt"></i> 3. Export Quality</label>
                    <span id="qualityVal" style="font-size: 12px; font-weight: 800; color: #10b981;">85%</span>
                </div>
                <input type="range" id="qualityRange" min="1" max="100" value="85">
            </div>
        </div>

        <div class="footer-tag">
            &copy; 2026 SUJAL - Premium Digital Tools
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/heic2any@0.0.4/dist/heic2any.min.js"></script>
    <script>
        const canvas = document.getElementById('canvas');
        const ctx = canvas.getContext('2d');
        const fileInput = document.getElementById('fileInput');
        const downloadBtn = document.getElementById('downloadBtn');
        const downloadWebpBtn = document.getElementById('downloadWebpBtn');
        const placeholder = document.getElementById('placeholder');
        const resetBtn = document.getElementById('resetFilters');
        const qualityRange = document.getElementById('qualityRange');
        const qualityVal = document.getElementById('qualityVal');

        const filterIds = ['brightness', 'contrast', 'saturation', 'grayscale', 'sepia', 'hueRotate', 'blur', 'invert'];
        let originalImage = null;

        const ALLOWED_TYPES = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp', 'image/heic', 'image/heif', 'image/svg+xml', 'image/gif'];

        // Handle File Upload
        fileInput.onchange = async (e) => {
            let file = e.target.files[0];
            if (!file) return;

            // Simple extension check as fallback
            const ext = file.name.split('.').pop().toLowerCase();
            const isHeic = ext === 'heic' || ext === 'heif';
            
            if (!ALLOWED_TYPES.includes(file.type) && !isHeic) {
                alert("Invalid file type! Supported: JPG, PNG, WebP, HEIC, SVG, GIF");
                fileInput.value = "";
                return;
            }

            placeholder.innerHTML = `<i class="fas fa-spinner fa-spin"></i><p>Processing Image...</p>`;

            if (isHeic) {
                try {
                    const blob = await heic2any({ blob: file, toType: "image/jpeg", quality: 0.8 });
                    const convertedFile = new File([blob], file.name.replace(/\.[^/.]+$/, ".jpg"), { type: "image/jpeg" });
                    processImageFile(convertedFile);
                } catch (err) {
                    alert("Failed to process HEIC file. Try another format.");
                    console.error(err);
                    resetPlaceholder();
                }
            } else {
                processImageFile(file);
            }
        };

        function processImageFile(file) {
            const reader = new FileReader();
            reader.onload = (event) => {
                const img = new Image();
                img.onload = () => {
                    originalImage = img;
                    canvas.width = img.width;
                    canvas.height = img.height;
                    canvas.style.display = "block";
                    placeholder.style.display = "none";
                    downloadBtn.disabled = false;
                    downloadWebpBtn.disabled = false;
                    applyFilters();
                };
                img.src = event.target.result;
            };
            reader.readAsDataURL(file);
        }

        function resetPlaceholder() {
            placeholder.style.display = "flex";
            placeholder.innerHTML = `<i class="fas fa-cloud-upload-alt"></i><p>Drag image here or use sidebar to upload</p>`;
        }

        // Filter Controls Logic
        filterIds.forEach(id => {
            const el = document.getElementById(id);
            const valEl = document.getElementById(id + 'Val');
            
            el.oninput = () => {
                let suffix = id === 'hueRotate' ? 'deg' : (id === 'blur' ? 'px' : '%');
                valEl.textContent = el.value + suffix;
                applyFilters();
            };
        });

        function applyFilters() {
            if (!originalImage) return;

            const b = document.getElementById('brightness').value;
            const c = document.getElementById('contrast').value;
            const s = document.getElementById('saturation').value;
            const g = document.getElementById('grayscale').value;
            const sep = document.getElementById('sepia').value;
            const h = document.getElementById('hueRotate').value;
            const bl = document.getElementById('blur').value;
            const inv = document.getElementById('invert').value;

            ctx.clearRect(0, 0, canvas.width, canvas.height);
            ctx.filter = `
                brightness(${b}%) 
                contrast(${c}%) 
                saturate(${s}%) 
                grayscale(${g}%) 
                sepia(${sep}%) 
                hue-rotate(${h}deg) 
                blur(${bl}px) 
                invert(${inv}%)
            `;
            ctx.drawImage(originalImage, 0, 0);
        }

        resetBtn.onclick = () => {
            filterIds.forEach(id => {
                const el = document.getElementById(id);
                const valEl = document.getElementById(id + 'Val');
                el.value = (id === 'brightness' || id === 'contrast' || id === 'saturation') ? 100 : 0;
                let suffix = id === 'hueRotate' ? 'deg' : (id === 'blur' ? 'px' : '%');
                valEl.textContent = el.value + suffix;
            });
            applyFilters();
        };

        qualityRange.oninput = (e) => {
            qualityVal.textContent = e.target.value + "%";
        };

        // Download Logic
        function downloadImage(type, ext) {
            const quality = parseInt(qualityRange.value) / 100;
            const fileName = 'sujal-filtered-' + Date.now() + '.' + ext;
            
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

        // Drag & Drop
        const dropZone = document.getElementById('dropZone');
        dropZone.ondragover = (e) => { e.preventDefault(); dropZone.style.background = "rgba(96, 165, 250, 0.1)"; };
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
