<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sujal - Advanced Image to PDF Converter</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- jsPDF Library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <!-- SortableJS Library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.0/Sortable.min.js"></script>
    <style>
        :root {
            --primary: #6366f1;
            --primary-light: #818cf8;
            --secondary: #10b981;
            --accent: #f59e0b;
            --danger: #ef4444;
            --bg: #0f172a;
            --card-bg: rgba(30, 41, 59, 0.7);
            --border: rgba(255, 255, 255, 0.1);
            --text: #f8fafc;
            --text-dim: #94a3b8;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            user-select: none;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            overflow-x: hidden;
            background-image: 
                radial-gradient(circle at 0% 0%, rgba(99, 102, 241, 0.15) 0%, transparent 50%),
                radial-gradient(circle at 100% 100%, rgba(16, 185, 129, 0.1) 0%, transparent 50%);
        }

        .container {
            max-width: 1000px;
            margin: 40px auto;
            padding: 0 20px;
            width: 100%;
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
        }

        .header h1 {
            font-size: 48px;
            font-weight: 800;
            background: linear-gradient(135deg, #818cf8, #34d399);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 10px;
        }

        .header p {
            color: var(--text-dim);
            font-size: 18px;
        }

        .main-card {
            background: var(--card-bg);
            backdrop-filter: blur(20px);
            border: 1px solid var(--border);
            border-radius: 32px;
            padding: 40px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }

        /* Upload Area */
        .upload-section {
            border: 2px dashed rgba(99, 102, 241, 0.3);
            background: rgba(99, 102, 241, 0.05);
            border-radius: 24px;
            padding: 60px 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
        }

        .upload-section:hover {
            border-color: var(--primary);
            background: rgba(99, 102, 241, 0.1);
            transform: translateY(-2px);
        }

        .upload-section i {
            font-size: 48px;
            color: var(--primary);
            margin-bottom: 20px;
        }

        .upload-section h3 {
            font-size: 20px;
            margin-bottom: 8px;
        }

        .upload-section p {
            color: var(--text-dim);
            font-size: 14px;
        }

        #fileInput {
            display: none;
        }

        /* Preview Grid */
        .preview-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            gap: 20px;
            margin-top: 40px;
            max-height: 500px;
            overflow-y: auto;
            padding: 10px;
            background: rgba(255, 255, 255, 0.02);
            border-radius: 20px;
            border: 1px solid var(--border);
        }

        .preview-grid::-webkit-scrollbar { width: 6px; }
        .preview-grid::-webkit-scrollbar-thumb { background: var(--border); border-radius: 10px; }

        .preview-item {
            position: relative;
            aspect-ratio: 3/4;
            background: rgba(255, 255, 255, 0.03);
            border-radius: 16px;
            border: 1px solid var(--border);
            overflow: hidden;
            animation: fadeIn 0.3s ease;
            cursor: grab;
        }

        .preview-item:active { cursor: grabbing; }

        .sortable-ghost {
            opacity: 0.4;
            transform: scale(0.95);
            border: 2px dashed var(--primary);
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.9); }
            to { opacity: 1; transform: scale(1); }
        }

        .preview-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            pointer-events: none;
        }

        .remove-btn {
            position: absolute;
            top: 8px;
            right: 8px;
            width: 28px;
            height: 28px;
            background: var(--danger);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            z-index: 10;
            font-size: 14px;
            transition: all 0.2s;
            box-shadow: 0 4px-12px rgba(239, 68, 68, 0.4);
        }

        .remove-btn:hover {
            transform: scale(1.1);
            background: #f43f5e;
        }

        .page-badge {
            position: absolute;
            bottom: 8px;
            left: 8px;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(4px);
            padding: 2px 8px;
            border-radius: 6px;
            font-size: 10px;
            font-weight: 700;
            color: white;
            z-index: 10;
        }

        /* Controls */
        .controls {
            margin-top: 40px;
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            align-items: center;
            justify-content: space-between;
            padding-top: 30px;
            border-top: 1px solid var(--border);
        }

        .settings {
            display: flex;
            gap: 15px;
        }

        .settings-item {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .settings-item label {
            font-size: 12px;
            color: var(--text-dim);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .select-input {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--border);
            color: white;
            padding: 10px 15px;
            border-radius: 12px;
            font-family: inherit;
            cursor: pointer;
            outline: none;
            transition: all 0.2s;
        }

        .select-input:focus { border-color: var(--primary); }

        .download-btn {
            background: linear-gradient(135deg, var(--primary), var(--primary-light));
            color: white;
            padding: 16px 40px;
            border-radius: 16px;
            font-weight: 700;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 12px;
            transition: all 0.3s;
            box-shadow: 0 10px 20px -5px rgba(99, 102, 241, 0.4);
            font-size: 16px;
        }

        .download-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            filter: grayscale(1);
        }

        .download-btn:not(:disabled):hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 30px -5px rgba(99, 102, 241, 0.5);
        }

        .download-btn:active { transform: scale(0.98); }

        /* Loader */
        .loader {
            display: none;
            margin-left: 10px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        footer {
            margin-top: auto;
            text-align: center;
            padding: 40px;
            color: var(--text-dim);
            font-size: 14px;
        }

        @media (max-width: 640px) {
            .header h1 { font-size: 32px; }
            .controls { flex-direction: column; align-items: stretch; }
            .download-btn { justify-content: center; }
            .main-card { padding: 25px; }
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="header">
            <h1>Image to PDF</h1>
            <p>Convert your photos into a professional PDF in seconds.</p>
        </div>

        <div class="main-card">
            <div class="upload-section" id="dropZone" onclick="document.getElementById('fileInput').click()">
                <i class="fas fa-images"></i>
                <h3>Drop your images here</h3>
                <p>or click to browse from your device</p>
                <input type="file" id="fileInput" multiple accept="image/*">
            </div>

            <div class="preview-grid" id="previewGrid">
                <!-- Preview items will appear here -->
            </div>

            <div class="controls">
                <div class="settings">
                    <div class="settings-item">
                        <label>Page Orientation</label>
                        <select class="select-input" id="orientation">
                            <option value="p">Portrait</option>
                            <option value="l">Landscape</option>
                        </select>
                    </div>
                </div>

                <button class="download-btn" id="generateBtn" disabled>
                    <i class="fas fa-file-pdf"></i>
                    <span>Generate PDF</span>
                    <i class="fas fa-spinner loader" id="loader"></i>
                </button>
            </div>
        </div>

        <footer>
            &copy; 2026 SUJAL - Premium Conversion Studio
        </footer>
    </div>

    <script>
        const { jsPDF } = window.jspdf;
        const fileInput = document.getElementById('fileInput');
        const previewGrid = document.getElementById('previewGrid');
        const generateBtn = document.getElementById('generateBtn');
        const loader = document.getElementById('loader');
        const orientationSelect = document.getElementById('orientation');
        const dropZone = document.getElementById('dropZone');

        let imageFiles = [];

        // Initialize Sortable
        new Sortable(previewGrid, {
            animation: 150,
            ghostClass: 'sortable-ghost',
            onEnd: () => {
                // If we were using an array index for something, we'd update it here.
                // But we'll pull fresh order from DOM during generation.
                updatePageBadges();
            }
        });

        // Drag and Drop Logic
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            dropZone.addEventListener(eventName, () => dropZone.style.borderColor = 'var(--primary)', false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, () => dropZone.style.borderColor = 'rgba(99, 102, 241, 0.3)', false);
        });

        dropZone.addEventListener('drop', handleDrop, false);

        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = Array.from(dt.files).filter(file => file.type.startsWith('image/'));
            addFiles(files);
        }

        fileInput.onchange = (e) => {
            const files = Array.from(e.target.files);
            addFiles(files);
            fileInput.value = ""; // Clear for next selection
        };

        function addFiles(files) {
            files.forEach(file => {
                const reader = new FileReader();
                reader.onload = (e) => {
                    const id = "img_" + Date.now() + Math.random();
                    imageFiles.push({ id, data: e.target.result, name: file.name, type: file.type });
                    renderPreviews();
                };
                reader.readAsDataURL(file);
            });
        }

        function renderPreviews() {
            previewGrid.innerHTML = "";
            imageFiles.forEach((img, index) => {
                const div = document.createElement('div');
                div.className = "preview-item";
                div.setAttribute('data-id', img.id);
                div.innerHTML = `
                    <img src="${img.data}" alt="${img.name}">
                    <div class="page-badge">PAGE ${index + 1}</div>
                    <div class="remove-btn" onclick="removeImage('${img.id}')">
                        <i class="fas fa-times"></i>
                    </div>
                `;
                previewGrid.appendChild(div);
            });

            generateBtn.disabled = imageFiles.length === 0;
            const btnText = generateBtn.querySelector('span');
            btnText.textContent = imageFiles.length > 0 ? `Generate PDF (${imageFiles.length} Images)` : "Generate PDF";
            updatePageBadges();
        }

        function updatePageBadges() {
            const items = previewGrid.querySelectorAll('.preview-item');
            items.forEach((item, idx) => {
                item.querySelector('.page-badge').textContent = `PAGE ${idx + 1}`;
            });
        }

        window.removeImage = (id) => {
            imageFiles = imageFiles.filter(img => img.id !== id);
            renderPreviews();
        };

        generateBtn.onclick = async () => {
            if (imageFiles.length === 0) return;
            
            generateBtn.disabled = true;
            loader.style.display = "inline-block";
            
            try {
                const orientation = orientationSelect.value;
                const pdf = new jsPDF({
                    orientation: orientation,
                    unit: 'px'
                });

                // Get visual order from DOM
                const sortedItems = Array.from(previewGrid.querySelectorAll('.preview-item'));
                const sortedData = sortedItems.map(item => {
                    const id = item.getAttribute('data-id');
                    return imageFiles.find(img => img.id === id);
                });

                for (let i = 0; i < sortedData.length; i++) {
                    const imgData = sortedData[i].data;
                    
                    const img = new Image();
                    img.src = imgData;
                    
                    await new Promise(resolve => {
                        img.onload = () => {
                            const pageWidth = pdf.internal.pageSize.getWidth();
                            const pageHeight = pdf.internal.pageSize.getHeight();
                            
                            const ratio = Math.min(pageWidth / img.width, pageHeight / img.height);
                            const finalWidth = img.width * ratio;
                            const finalHeight = img.height * ratio;
                            
                            const x = (pageWidth - finalWidth) / 2;
                            const y = (pageHeight - finalHeight) / 2;

                            if (i > 0) pdf.addPage();
                            
                            const format = sortedData[i].type.split('/')[1].toUpperCase();
                            pdf.addImage(imgData, format, x, y, finalWidth, finalHeight);
                            resolve();
                        };
                    });
                }

                pdf.save(`sujal-pdf-${Date.now()}.pdf`);
            } catch (err) {
                console.error(err);
                alert("Generation failed. Check console.");
            } finally {
                generateBtn.disabled = false;
                loader.style.display = "none";
            }
        };
    </script>
</body>

</html>
