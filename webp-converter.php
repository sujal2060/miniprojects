<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SUJAL - Powerful WebP Converter</title>
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #4facfe 100%);
            background-size: 400% 400%;
            animation: gradientShift 15s ease infinite;
            display: flex;
            flex-direction: column;
            align-items: center;
            color: white;
            padding: 40px 20px;
            overflow-x: hidden;
        }

        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .container {
            width: 100%;
            max-width: 900px;
            z-index: 10;
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(20px);
            padding: 30px;
            border-radius: 30px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
        }

        .header h1 {
            font-size: 36px;
            font-weight: 900;
            letter-spacing: -1px;
            margin-bottom: 10px;
            text-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        .header p {
            font-size: 16px;
            opacity: 0.9;
            font-weight: 500;
        }

        /* Uploader Box */
        .upload-card {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(20px);
            border-radius: 30px;
            padding: 40px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.15);
            margin-bottom: 30px;
            transition: transform 0.3s ease;
        }

        .drop-zone {
            border: 3px dashed rgba(255, 255, 255, 0.4);
            border-radius: 20px;
            padding: 50px 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
        }

        .drop-zone:hover, .drop-zone.drag-over {
            border-color: white;
            background: rgba(255, 255, 255, 0.1);
        }

        .drop-zone i {
            font-size: 60px;
            margin-bottom: 20px;
            opacity: 0.8;
        }

        .drop-zone span {
            display: block;
            font-size: 18px;
            font-weight: 600;
        }

        #fileInput {
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            opacity: 0;
            cursor: pointer;
        }

        /* Controls */
        .controls {
            display: flex;
            flex-direction: column;
            gap: 20px;
            margin-top: 30px;
            padding-top: 30px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }

        .quality-wrapper {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            flex-wrap: wrap;
        }

        .quality-label {
            font-weight: 700;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 1px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        input[type="range"] {
            flex: 1;
            accent-color: #4facfe;
            cursor: pointer;
            height: 6px;
            border-radius: 5px;
        }

        .quality-val {
            background: #4facfe;
            padding: 4px 12px;
            border-radius: 8px;
            font-weight: 800;
            min-width: 45px;
            text-align: center;
        }

        /* Actions */
        .actions {
            display: flex;
            gap: 15px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn {
            padding: 15px 30px;
            border-radius: 15px;
            border: none;
            color: white;
            font-weight: 700;
            font-size: 14px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .btn:hover:not(:disabled) {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
        }

        .btn:active:not(:disabled) {
            transform: translateY(0);
        }

        .btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .btn-primary { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }
        .btn-success { background: linear-gradient(135deg, #10b981 0%, #34d399 100%); }
        .btn-danger { background: linear-gradient(135deg, #ef4444 0%, #f87171 100%); }

        /* Preview List */
        .preview-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .preview-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 15px;
            color: #2d3748;
            display: flex;
            flex-direction: column;
            gap: 12px;
            animation: fadeIn 0.4s ease forwards;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .preview-img-box {
            width: 100%;
            height: 160px;
            border-radius: 12px;
            overflow: hidden;
            background: #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .preview-img-box img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        .file-info {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .file-name {
            font-weight: 700;
            font-size: 14px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .file-stats {
            display: flex;
            justify-content: space-between;
            font-size: 12px;
            color: #64748b;
            font-weight: 500;
        }

        .reduction-tag {
            background: #dcfce7;
            color: #166534;
            padding: 2px 8px;
            border-radius: 5px;
            font-weight: 700;
        }

        .progress-bar {
            height: 6px;
            background: #e2e8f0;
            border-radius: 3px;
            overflow: hidden;
            position: relative;
        }

        .progress-fill {
            height: 100%;
            background: #4facfe;
            width: 0%;
            transition: width 0.3s ease;
        }

        /* Toast notifications */
        .toast {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: rgba(255, 255, 255, 0.9);
            color: #1e293b;
            padding: 15px 25px;
            border-radius: 15px;
            backdrop-filter: blur(10px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            transform: translateX(150%);
            transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            z-index: 100;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .toast.show { transform: translateX(0); }
        .toast.error { border-left: 5px solid #ef4444; }
        .toast.success { border-left: 5px solid #10b981; }

        @media (max-width: 600px) {
            .header h1 { font-size: 28px; }
            .upload-card { padding: 25px; }
            .btn { width: 100%; justify-content: center; }
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="header">
            <h1><i class="fas fa-bolt"></i> Powerful WebP Converter</h1>
            <p>Batch convert images to high-performance WebP format instantly.</p>
        </div>

        <div class="upload-card">
            <div class="drop-zone" id="dropZone">
                <i class="fas fa-cloud-arrow-up"></i>
                <span>Drag & drop images here or Click to Browse</span>
                <p style="font-size: 13px; opacity: 0.7; margin-top: 10px;">Supports JPG, PNG, WEBP, GIF</p>
                <input type="file" id="fileInput" multiple accept="image/*">
            </div>

            <div class="controls">
                <div class="quality-wrapper">
                    <div class="quality-label">
                        <i class="fas fa-sliders"></i>
                        Compression Quality
                    </div>
                    <input type="range" id="qualityRange" min="1" max="100" value="80">
                    <span class="quality-val" id="qualityValue">80%</span>
                </div>

                <div class="actions">
                    <button id="convertBtn" class="btn btn-primary" disabled>
                        <i class="fas fa-magic"></i> Convert All
                    </button>
                    <button id="downloadAllBtn" class="btn btn-success" style="display: none;">
                        <i class="fas fa-file-zipper"></i> Download All (ZIP)
                    </button>
                    <button id="clearBtn" class="btn btn-danger">
                        <i class="fas fa-trash"></i> Clear All
                    </button>
                </div>
            </div>
        </div>

        <div class="preview-list" id="previewList">
            <!-- Images will appear here -->
        </div>
    </div>

    <!-- Feedback Toast -->
    <div id="toast" class="toast"></div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script>
        const dropZone = document.getElementById('dropZone');
        const fileInput = document.getElementById('fileInput');
        const previewList = document.getElementById('previewList');
        const convertBtn = document.getElementById('convertBtn');
        const downloadAllBtn = document.getElementById('downloadAllBtn');
        const clearBtn = document.getElementById('clearBtn');
        const qualityRange = document.getElementById('qualityRange');
        const qualityValue = document.getElementById('qualityValue');
        const toast = document.getElementById('toast');

        let filesToProcess = [];
        let convertedFiles = [];

        // Quality update
        qualityRange.addEventListener('input', (e) => {
            qualityValue.textContent = e.target.value + '%';
        });

        // File Selection
        const handleFiles = (files) => {
            const validFiles = Array.from(files).filter(file => file.type.startsWith('image/'));
            
            if (validFiles.length === 0) {
                showToast("Please drop valid image files!", "error");
                return;
            }

            validFiles.forEach(file => {
                const id = Math.random().toString(36).substr(2, 9);
                filesToProcess.push({ id, file, converted: false });
                addPreviewCard(id, file);
            });

            convertBtn.disabled = false;
        };

        fileInput.addEventListener('change', (e) => handleFiles(e.target.files));

        dropZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropZone.classList.add('drag-over');
        });

        dropZone.addEventListener('dragleave', () => dropZone.classList.remove('drag-over'));

        dropZone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropZone.classList.remove('drag-over');
            handleFiles(e.dataTransfer.files);
        });

        function addPreviewCard(id, file) {
            const card = document.createElement('div');
            card.className = 'preview-card';
            card.id = `card-${id}`;
            
            const reader = new FileReader();
            reader.onload = (e) => {
                card.innerHTML = `
                    <div class="preview-img-box">
                        <img src="${e.target.result}" alt="Preview">
                    </div>
                    <div class="file-info">
                        <div class="file-name">${file.name}</div>
                        <div class="file-stats">
                            <span>${(file.size / 1024).toFixed(1)} KB</span>
                            <span class="status-msg">Waiting...</span>
                        </div>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill" id="progress-${id}"></div>
                    </div>
                    <button class="btn btn-success" style="padding: 8px; font-size: 11px; display: none;" id="dl-${id}">
                        <i class="fas fa-download"></i> Download
                    </button>
                `;
            };
            reader.readAsDataURL(file);
            previewList.appendChild(card);
        }

        // Conversion Logic
        convertBtn.addEventListener('click', async () => {
            convertBtn.disabled = true;
            const quality = parseInt(qualityRange.value) / 100;

            for (let item of filesToProcess) {
                if (item.converted) continue;

                const card = document.getElementById(`card-${item.id}`);
                const progressFill = document.getElementById(`progress-${item.id}`);
                const statusMsg = card.querySelector('.status-msg');

                statusMsg.textContent = "Processing...";
                progressFill.style.width = "40%";

                try {
                    const webpData = await convertToWebP(item.file, quality);
                    item.converted = true;
                    item.webpBlob = webpData.blob;
                    item.newName = item.file.name.replace(/\.[^/.]+$/, "") + ".webp";
                    
                    const oldSize = item.file.size;
                    const newSize = item.webpBlob.size;
                    const reduction = (((oldSize - newSize) / oldSize) * 100).toFixed(0);

                    progressFill.style.width = "100%";
                    statusMsg.innerHTML = `<span class="reduction-tag">${reduction}% smaller</span> ${(newSize / 1024).toFixed(1)} KB`;
                    
                    const dlBtn = document.getElementById(`dl-${item.id}`);
                    dlBtn.style.display = "flex";
                    dlBtn.onclick = () => downloadBlob(item.webpBlob, item.newName);

                    convertedFiles.push(item);
                } catch (err) {
                    console.error(err);
                    statusMsg.textContent = "Error!";
                    progressFill.style.backgroundColor = "#ef4444";
                }
            }

            if (convertedFiles.length > 1) {
                downloadAllBtn.style.display = "flex";
            }
            showToast(`Converted ${convertedFiles.length} images!`, "success");
        });

        function convertToWebP(file, quality) {
            return new Promise((resolve, reject) => {
                const img = new Image();
                img.src = URL.createObjectURL(file);
                img.onload = () => {
                    const canvas = document.createElement('canvas');
                    canvas.width = img.width;
                    canvas.height = img.height;
                    const ctx = canvas.getContext('2d');
                    ctx.drawImage(img, 0, 0);
                    
                    canvas.toBlob((blob) => {
                        resolve({ blob });
                    }, 'image/webp', quality);
                };
                img.onerror = reject;
            });
        }

        function downloadBlob(blob, name) {
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = name;
            a.click();
            URL.revokeObjectURL(url);
        }

        // JSZip Download
        downloadAllBtn.addEventListener('click', async () => {
            const zip = new JSZip();
            convertedFiles.forEach(item => {
                zip.file(item.newName, item.webpBlob);
            });

            const content = await zip.generateAsync({ type: "blob" });
            downloadBlob(content, "converted_images.zip");
        });

        clearBtn.addEventListener('click', () => {
            filesToProcess = [];
            convertedFiles = [];
            previewList.innerHTML = "";
            convertBtn.disabled = true;
            downloadAllBtn.style.display = "none";
        });

        function showToast(msg, type) {
            toast.textContent = msg;
            toast.className = `toast show ${type}`;
            setTimeout(() => toast.classList.remove('show'), 3000);
        }
    </script>
</body>

</html>
