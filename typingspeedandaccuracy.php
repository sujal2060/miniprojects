<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sujal - Ninja Typing Master</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700;900&family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #3b82f6;
            --secondary: #10b981;
            --accent: #f59e0b;
            --danger: #ef4444;
            --bg: #0f172a;
            --glass: rgba(255, 255, 255, 0.05);
            --border: rgba(255, 255, 255, 0.1);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background: var(--bg);
            color: white;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            overflow-x: hidden;
            background-image: 
                radial-gradient(circle at 10% 20%, rgba(59, 130, 246, 0.1) 0%, transparent 40%),
                radial-gradient(circle at 90% 80%, rgba(16, 185, 129, 0.1) 0%, transparent 40%);
        }

        .container {
            max-width: 900px;
            margin: 40px auto;
            padding: 0 20px;
            width: 100%;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header h1 {
            font-size: 42px;
            font-weight: 900;
            background: linear-gradient(to right, #60a5fa, #34d399);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 5px;
        }

        .level-badge {
            display: inline-block;
            background: rgba(59, 130, 246, 0.2);
            border: 1px solid var(--primary);
            color: var(--primary);
            padding: 4px 12px;
            border-radius: 20px;
            font-weight: 800;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Stats Bar */
        .stats-bar {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            margin-bottom: 25px;
        }

        .stat-card {
            background: var(--glass);
            backdrop-filter: blur(10px);
            border: 1px solid var(--border);
            padding: 15px;
            border-radius: 18px;
            text-align: center;
            transition: all 0.3s;
        }

        .stat-card.active { border-color: var(--primary); box-shadow: 0 0 15px rgba(59, 130, 246, 0.2); }

        .stat-label { font-size: 11px; opacity: 0.6; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 4px; }
        .stat-value { font-size: 24px; font-weight: 800; font-family: 'JetBrains Mono', monospace; }

        /* Game Board */
        .game-board {
            background: var(--glass);
            backdrop-filter: blur(20px);
            border: 1px solid var(--border);
            border-radius: 30px;
            padding: 40px;
            position: relative;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }

        .text-display {
            font-family: 'JetBrains Mono', monospace;
            font-size: 22px;
            line-height: 1.6;
            margin-bottom: 30px;
            position: relative;
            color: rgba(255, 255, 255, 0.3);
            user-select: none;
            word-wrap: break-word;
        }

        .text-display span {
            position: relative;
        }

        .char-correct { color: #fff; }
        .char-incorrect { color: var(--danger); background: rgba(239, 68, 68, 0.2); border-radius: 2px; }
        .char-current { 
            background: rgba(59, 130, 246, 0.3);
            border-bottom: 3px solid var(--primary);
            animation: pulse 1s infinite;
        }

        @keyframes pulse {
            0%, 100% { border-color: var(--primary); }
            50% { border-color: transparent; }
        }

        .typing-input {
            width: 100%;
            height: 0;
            opacity: 0;
            position: absolute;
        }

        .typing-area-trigger {
            width: 100%;
            padding: 20px;
            background: rgba(255, 255, 255, 0.03);
            border-radius: 15px;
            text-align: center;
            border: 2px dashed rgba(255, 255, 255, 0.1);
            cursor: pointer;
            transition: all 0.3s;
        }

        .typing-area-trigger:hover { background: rgba(255, 255, 255, 0.06); }

        /* Progress Line */
        .progress-line {
            width: 100%;
            height: 4px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 2px;
            margin-top: 30px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(to right, var(--primary), var(--secondary));
            width: 0%;
            transition: width 0.3s ease;
        }

        /* Report Modal */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.85);
            backdrop-filter: blur(10px);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 1000;
        }

        .modal {
            background: #1e293b;
            width: 90%;
            max-width: 500px;
            border-radius: 30px;
            padding: 40px;
            border: 1px solid var(--border);
            text-align: center;
            animation: slideUp 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        @keyframes slideUp {
            from { transform: translateY(50px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        .modal-title { font-size: 28px; font-weight: 900; margin-bottom: 25px; }
        .modal-stats {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
        }

        .modal-stat-box {
            background: rgba(255, 255, 255, 0.03);
            padding: 15px;
            border-radius: 15px;
        }

        .btn {
            padding: 14px 30px;
            border-radius: 15px;
            border: none;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: 5px;
        }

        .btn-primary { background: var(--primary); color: white; }
        .btn-secondary { background: var(--secondary); color: white; }
        
        .mistake-tag {
            display: inline-block;
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger);
            padding: 4px 10px;
            border-radius: 8px;
            margin: 3px;
            font-size: 13px;
        }

        .footer {
            margin-top: auto;
            text-align: center;
            padding: 30px;
            opacity: 0.5;
            font-size: 12px;
        }

        /* Traffic Light Countdown */
        .countdown-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(15, 23, 42, 0.9);
            display: none;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            z-index: 50;
            border-radius: 30px;
        }

        .light-container {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }

        .light {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            border: 2px solid rgba(255, 255, 255, 0.05);
            transition: all 0.3s;
        }

        .light.red { background: #ef4444; box-shadow: 0 0 20px #ef4444; }
        .light.yellow { background: #f59e0b; box-shadow: 0 0 20px #f59e0b; }
        .light.green { background: #10b981; box-shadow: 0 0 20px #10b981; }

        .countdown-text {
            font-size: 80px;
            font-weight: 900;
            font-family: 'JetBrains Mono', monospace;
        }

        /* Levels Mobile */
        @media (max-width: 640px) {
            .stats-bar { grid-template-columns: 1fr 1fr; }
            .header h1 { font-size: 32px; }
            .modal-stats { grid-template-columns: 1fr; }
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="header">
            <span class="level-badge" id="currentLevelBadge">Level 1</span>
            <h1>Ninja Typing Master</h1>
            <p style="opacity: 0.6;">Test your speed, master your accuracy.</p>
        </div>

        <div class="stats-bar">
            <div class="stat-card">
                <div class="stat-label">Time</div>
                <div class="stat-value" id="timer">00:00</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">WPM</div>
                <div class="stat-value" id="wpm">0</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Accuracy</div>
                <div class="stat-value" id="accuracy">100%</div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Mistakes</div>
                <div class="stat-value" id="mistakeCount">0</div>
            </div>
        </div>

        <div class="game-board" id="gameBoard">
            <div class="countdown-overlay" id="countdownOverlay">
                <div class="light-container">
                    <div class="light" id="redLight"></div>
                    <div class="light" id="yellowLight"></div>
                    <div class="light" id="greenLight"></div>
                </div>
                <div class="countdown-text" id="countdownText">3</div>
            </div>

            <div class="text-display" id="textDisplay">
                <!-- Text will be injected here -->
            </div>

            <input type="text" class="typing-input" id="typingInput" spellcheck="false" autocomplete="off">
            <div class="typing-area-trigger" id="focusTrigger">
                <i class="fas fa-keyboard mr-2"></i> Click or tap here to start typing...
            </div>

            <div class="progress-line">
                <div class="progress-fill" id="progressFill"></div>
            </div>
        </div>

        <div class="footer">
            &copy; 2026 SUJAL - Premium Digital Arena
        </div>
    </div>

    <!-- Result Modal -->
    <div class="modal-overlay" id="resultModal">
        <div class="modal">
            <h2 class="modal-title" id="modalTitle">Level Complete!</h2>
            <div class="modal-stats">
                <div class="modal-stat-box">
                    <div class="stat-label text-blue-400">Speed</div>
                    <div class="stat-value" id="modalWpm">0 WPM</div>
                </div>
                <div class="modal-stat-box">
                    <div class="stat-label text-emerald-400">Accuracy</div>
                    <div class="stat-value" id="modalAccuracy">0%</div>
                </div>
                <div class="modal-stat-box">
                    <div class="stat-label text-purple-400">Level</div>
                    <div class="stat-value" id="modalLevel">1</div>
                </div>
                <div class="modal-stat-box">
                    <div class="stat-label text-orange-400">Total Time</div>
                    <div class="stat-value" id="modalTime">0s</div>
                </div>
            </div>
            
            <div id="mistakeWrapper" style="margin-bottom: 25px; text-align: left; max-height: 100px; overflow-y: auto;">
                <p class="stat-label" style="margin-bottom: 8px;">Misspelled Words:</p>
                <div id="mistakeTags"></div>
            </div>

            <div class="actions">
                <button class="btn btn-secondary" onclick="restartLevel()">Try Again</button>
                <button class="btn btn-primary" id="nextLevelBtn" onclick="nextLevel()">Next Level</button>
            </div>
        </div>
    </div>

    <script>
        const levelsContent = [
            // Level 1-5 (Simple)
            "The quick brown fox jumps over the lazy dog.",
            "A small cat sits on the warm brick wall.",
            "Birds sing sweet songs in the deep green woods.",
            "Water falls from the sky like soft white pearls.",
            "Always be kind to every living soul you meet.",
            
            // Level 6-10 (Medium)
            "The ancient mountains stand tall against the vast blue horizon of the world.",
            "Successful people are not gifted; they just work hard, then succeed on purpose.",
            "Technology is best when it brings people together and solves real problems effectively.",
            "Opportunities don't happen, you create them by being persistent and remarkably brave.",
            "Sustainability means meeting our own needs without compromising future generations' ability.",
            
            // Level 11-15 (Difficult)
            "Quantum mechanics is a fundamental theory in physics that provides a description of the physical properties of nature.",
            "Artificial intelligence is intelligence demonstrated by machines, as opposed to the natural intelligence displayed by animals.",
            "The industrial revolution was a period of global economic transition towards more efficient and stable manufacturing processes.",
            "Metacognition is an awareness and understanding of one's own thought processes and the patterns governing human behavior.",
            "Encryption is the process of encoding information so that only authorized parties can access it securely and privately.",
            
            // Level 16-20 (Master)
            "In thermodynamics, entropy is a thermodynamic property that increases with time in an isolated system, representing randomness.",
            "Hyperparameter optimization in machine learning is the problem of choosing a set of optimal hyperparameters for a learning algorithm.",
            "The phenomenon of bioluminescence occurs through a chemical reaction that produces light energy within an organism's unique physiology.",
            "Constitutionalism is the idea, often associated with political theories, that government's authority is determined by a body of fundamental law.",
            "The juxtaposition of complex architectural methodologies creates a multifaceted aesthetic that challenges conventional visual perceptions."
        ];

        let currentLevel = 1;
        let timer = null;
        let startTime = null;
        let timeLimit = 0;
        let timeRemaining = 0;
        let isPlaying = false;
        
        // Advanced Stats
        let totalKeys = 0;
        let correctKeys = 0;
        let backspaces = 0;
        let mistakesSet = new Set();
        let keyTimes = [];
        let lastKeyTime = null;

        const textDisplay = document.getElementById('textDisplay');
        const typingInput = document.getElementById('typingInput');
        const focusTrigger = document.getElementById('focusTrigger');
        const timerEl = document.getElementById('timer');
        const wpmEl = document.getElementById('wpm');
        const accuracyEl = document.getElementById('accuracy');
        const mistakeEl = document.getElementById('mistakeCount');
        const currentLevelBadge = document.getElementById('currentLevelBadge');
        const progressFill = document.getElementById('progressFill');

        function initLevel() {
            const text = levelsContent[currentLevel - 1];
            textDisplay.innerHTML = text.split('').map(char => `<span>${char}</span>`).join('');
            typingInput.value = "";
            currentLevelBadge.textContent = `Level ${currentLevel}`;
            
            // Reset stats
            totalKeys = 0;
            correctKeys = 0;
            backspaces = 0;
            mistakesSet = new Set();
            keyTimes = [];
            lastKeyTime = null;

            wpmEl.textContent = "0";
            accuracyEl.textContent = "100%";
            mistakeEl.textContent = "0";
            progressFill.style.width = "0%";
            
            // Dynamic Time Balancing:
            // Target WPM increases with level: 15 (L1) to 70 (L20)
            const wordsCount = text.split(' ').length;
            const targetWpmForLevel = 15 + (currentLevel * 3);
            const rawTimeLimit = Math.round((wordsCount / targetWpmForLevel) * 60) + 5; // +5s buffer
            timeLimit = Math.max(10, rawTimeLimit); // Minimum 10s
            
            timeRemaining = timeLimit;
            updateTimerDisplay();
            
            isPlaying = false;
            if(timer) clearInterval(timer);
        }

        function updateTimerDisplay() {
            const mins = Math.floor(timeRemaining / 60);
            const secs = timeRemaining % 60;
            timerEl.textContent = `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
        }

        function startTimer() {
            startTime = Date.now();
            timer = setInterval(() => {
                timeRemaining--;
                updateTimerDisplay();
                if (timeRemaining <= 0) {
                    endGame(false);
                }
            }, 1000);
        }

        let isCountingDown = false;

        const countdownOverlay = document.getElementById('countdownOverlay');
        const countdownText = document.getElementById('countdownText');
        const redLight = document.getElementById('redLight');
        const yellowLight = document.getElementById('yellowLight');
        const greenLight = document.getElementById('greenLight');

        function startCountdown() {
            if (isCountingDown || isPlaying) return;
            isCountingDown = true;
            countdownOverlay.style.display = "flex";
            
            let count = 1;
            const sequence = () => {
                redLight.className = "light";
                yellowLight.className = "light";
                greenLight.className = "light";

                if (count === 1) {
                    redLight.classList.add('red');
                    countdownText.textContent = "1";
                    countdownText.style.color = "#ef4444";
                } else if (count === 2) {
                    yellowLight.classList.add('yellow');
                    countdownText.textContent = "2";
                    countdownText.style.color = "#f59e0b";
                } else if (count === 3) {
                    greenLight.classList.add('green');
                    countdownText.textContent = "3";
                    countdownText.style.color = "#10b981";
                }

                if (count < 4) {
                    count++;
                    setTimeout(sequence, 1000);
                } else {
                    countdownOverlay.style.display = "none";
                    isCountingDown = false;
                    typingInput.focus();
                }
            };
            sequence();
        }

        focusTrigger.onclick = () => {
            if (!isPlaying && !isCountingDown) {
                startCountdown();
            } else {
                typingInput.focus();
            }
        };
        
        typingInput.onkeydown = (e) => {
            if (isCountingDown) {
                e.preventDefault();
                return;
            }
            if (e.key === 'Backspace') backspaces++;
            
            const now = Date.now();
            if (lastKeyTime) {
                keyTimes.push(now - lastKeyTime);
            }
            lastKeyTime = now;
        };

        typingInput.oninput = (e) => {
            if (!isPlaying && typingInput.value.length > 0) {
                isPlaying = true;
                startTimer();
            }

            const textChars = textDisplay.querySelectorAll('span');
            const inputVal = typingInput.value;
            const targetText = levelsContent[currentLevel - 1];

            // Real-time Accuracy tracking: Key-by-key
            if (e.inputType !== 'deleteContentBackward') {
                totalKeys++;
                const actualChar = targetText[inputVal.length - 1];
                const inputChar = inputVal[inputVal.length - 1];
                if (actualChar === inputChar) {
                    correctKeys++;
                }
            }

            let errors = 0;
            let currentMistakes = new Set();

            textChars.forEach((span, idx) => {
                const char = inputVal[idx];
                span.classList.remove('char-current', 'char-correct', 'char-incorrect');

                if (char == null) {
                    if (idx === inputVal.length) span.classList.add('char-current');
                } else if (char === span.innerText) {
                    span.classList.add('char-correct');
                } else {
                    span.classList.add('char-incorrect');
                    errors++;
                    
                    // Track misspelled word
                    const wordsArr = targetText.split(' ');
                    let c = 0;
                    for(let i=0; i<wordsArr.length; i++){
                        c += wordsArr[i].length + (i > 0 ? 1 : 0);
                        if(idx < c) {
                            currentMistakes.add(wordsArr[i]);
                            break;
                        }
                    }
                }
            });
            
            currentMistakes.forEach(m => mistakesSet.add(m));

            // Update stats
            const timePassed = (Date.now() - startTime) / 60000;
            const currentWpm = timePassed > 0 ? Math.round((inputVal.length / 5) / timePassed) : 0;
            
            // Refined Accuracy: (Correct Key Hits / Total Key Hits)
            const rawAccuracy = totalKeys > 0 ? Math.round((correctKeys / totalKeys) * 100) : 100;
            
            wpmEl.textContent = Math.max(0, currentWpm);
            accuracyEl.textContent = `${Math.max(0, rawAccuracy)}%`;
            mistakeEl.textContent = errors;
            progressFill.style.width = `${(inputVal.length / targetText.length) * 100}%`;

            if (inputVal.length >= targetText.length && errors === 0) {
                endGame(true);
            }
        };

        function endGame(success) {
            clearInterval(timer);
            isPlaying = false;
            
            const timeSpent = timeLimit - timeRemaining;
            const avgLatency = keyTimes.length > 0 ? Math.round(keyTimes.reduce((a,b)=>a+b, 0) / keyTimes.length) : 0;

            document.getElementById('modalTitle').textContent = success ? "Ninja Level Clear!" : "Test Failed!";
            document.getElementById('modalTitle').style.color = success ? "var(--secondary)" : "var(--danger)";
            
            document.getElementById('modalLevel').textContent = currentLevel;
            document.getElementById('modalWpm').textContent = wpmEl.textContent + " WPM";
            document.getElementById('modalAccuracy').textContent = accuracyEl.textContent;
            document.getElementById('modalTime').textContent = timeSpent + "s";
            
            const tags = document.getElementById('mistakeTags');
            tags.innerHTML = "";
            mistakesSet.forEach(m => {
                const span = document.createElement('span');
                span.className = 'mistake-tag';
                span.textContent = m;
                tags.appendChild(span);
            });
            
            // Add Advanced Stats in tags
            const backspaceTag = document.createElement('span');
            backspaceTag.className = 'mistake-tag';
            backspaceTag.style.background = 'rgba(59, 130, 246, 0.1)';
            backspaceTag.style.color = 'var(--primary)';
            backspaceTag.textContent = `Backspaces: ${backspaces}`;
            tags.appendChild(backspaceTag);

            const latencyTag = document.createElement('span');
            latencyTag.className = 'mistake-tag';
            latencyTag.style.background = 'rgba(16, 185, 129, 0.1)';
            latencyTag.style.color = 'var(--secondary)';
            latencyTag.textContent = `Avg Latency: ${avgLatency}ms`;
            tags.appendChild(latencyTag);

            document.getElementById('mistakeWrapper').style.display = "block";

            document.getElementById('nextLevelBtn').style.display = success ? "inline-block" : "none";
            document.getElementById('resultModal').style.display = "flex";
        }

        function nextLevel() {
            if (currentLevel < 20) {
                currentLevel++;
                document.getElementById('resultModal').style.display = "none";
                initLevel();
            } else {
                alert("Ultimate Typing Ninja Acquired! Level 20 Mastered.");
            }
        }

        function restartLevel() {
            document.getElementById('resultModal').style.display = "none";
            initLevel();
        }

        initLevel();
    </script>
</body>
</html>
