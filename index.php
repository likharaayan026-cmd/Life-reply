<?php
session_start();
// Agar user logged in hai toh check karein
$isLoggedIn = isset($_SESSION['user_logged_in']) && $_SESSION['user_logged_in'] === true;
$phone = isset($_SESSION['phone']) ? $_SESSION['phone'] : '';
?>
<!DOCTYPE html>
<html lang="hi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Life Replay AI | Aayan Industries</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800;900&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Round" rel="stylesheet">
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>

    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #6366F1 0%, #A855F7 50%, #EC4899 100%);
            --accent-glow: #00E5FF;
            --bg-dark: #060713;
            --card-glass: rgba(255, 255, 255, 0.04);
            --border-glass: rgba(255, 255, 255, 0.12);
            --text-main: #FFFFFF;
            --text-muted: #94A3B8;
            --radius-xl: 28px;
            --radius-lg: 20px;
            --radius-md: 14px;
            --font-heading: 'Outfit', sans-serif;
            --font-body: 'Plus Jakarta Sans', sans-serif;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: var(--font-body);
            -webkit-tap-highlight-color: transparent;
        }

        body {
            background-color: var(--bg-dark);
            color: var(--text-main);
            overflow: hidden;
            height: 100vh;
            width: 100vw;
        }

        .bg-orb {
            position: fixed;
            border-radius: 50%;
            filter: blur(110px);
            z-index: 0;
            opacity: 0.4;
            pointer-events: none;
        }
        .orb-1 { width: 320px; height: 320px; background: #6366F1; top: -80px; left: -80px; }
        .orb-2 { width: 380px; height: 380px; background: #EC4899; bottom: -50px; right: -50px; }
        .orb-3 { width: 250px; height: 250px; background: #00E5FF; top: 35%; left: 25%; }

        .gradient-text {
            background: var(--primary-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-family: var(--font-heading);
        }

        .hidden { display: none !important; }

        /* Splash Screen */
        .splash-screen {
            position: fixed;
            inset: 0;
            background: var(--bg-dark);
            z-index: 99999;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: opacity 0.4s ease, visibility 0.4s ease;
        }

        .splash-body {
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .glow-ring {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            background: var(--primary-gradient);
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 0 35px rgba(168, 85, 247, 0.6);
            margin-bottom: 20px;
            animation: pulseGlow 2s infinite ease-in-out;
        }

        @keyframes pulseGlow {
            0%, 100% { transform: scale(1); box-shadow: 0 0 35px rgba(168, 85, 247, 0.6); }
            50% { transform: scale(1.05); box-shadow: 0 0 50px rgba(0, 229, 255, 0.8); }
        }

        .splash-icon { font-size: 48px; color: #FFF; }
        .brand-title { font-family: var(--font-heading); font-size: 30px; font-weight: 900; letter-spacing: 1px; }
        .brand-sub { color: var(--text-muted); font-size: 13px; margin-top: 4px; font-weight: 500; }

        .loader-line {
            width: 130px;
            height: 4px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            margin-top: 30px;
            overflow: hidden;
        }

        .loader-fill {
            width: 50%;
            height: 100%;
            background: var(--primary-gradient);
            animation: moveLoader 1.2s infinite ease-in-out;
        }

        @keyframes moveLoader {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(200%); }
        }

        /* Full Screen Login Screen */
        .full-login-screen {
            position: fixed;
            inset: 0;
            background: #060713;
            z-index: 50000;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 24px;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(30px);
            -webkit-backdrop-filter: blur(30px);
            border: 1px solid var(--border-glass);
            border-radius: var(--radius-xl);
            padding: 32px 24px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.7);
            text-align: center;
        }

        .login-card h2 { font-family: var(--font-heading); font-size: 26px; font-weight: 800; margin-bottom: 8px; }
        .login-card p { color: var(--text-muted); font-size: 14px; margin-bottom: 24px; }

        .input-group {
            display: flex;
            align-items: center;
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid var(--border-glass);
            border-radius: var(--radius-md);
            padding: 4px 16px;
            margin-bottom: 16px;
            transition: border-color 0.3s ease;
        }

        .input-group:focus-within {
            border-color: var(--accent-glow);
            box-shadow: 0 0 15px rgba(0, 229, 255, 0.2);
        }

        .input-group .prefix { font-weight: 700; color: var(--accent-glow); margin-right: 10px; font-size: 16px; }

        .input-group input {
            width: 100%;
            background: none;
            border: none;
            outline: none;
            color: #FFF;
            font-size: 18px;
            padding: 14px 0;
            font-weight: 600;
            letter-spacing: 2px;
        }

        .btn-glow {
            width: 100%;
            padding: 16px;
            border-radius: var(--radius-md);
            border: none;
            background: var(--primary-gradient);
            color: #FFF;
            font-weight: 700;
            font-size: 15px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            box-shadow: 0 8px 25px rgba(168, 85, 247, 0.4);
            transition: transform 0.2s ease;
        }

        .btn-glow:active { transform: scale(0.97); }

        /* App Viewport */
        .app-viewport {
            position: relative;
            z-index: 1;
            height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .content-scroll {
            flex: 1;
            overflow-y: auto;
            padding: 20px;
            padding-bottom: 100px;
        }

        .view-section {
            display: flex;
            flex-direction: column;
            gap: 18px;
            animation: slideUp 0.35s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .glass-card {
            background: var(--card-glass);
            backdrop-filter: blur(25px);
            -webkit-backdrop-filter: blur(25px);
            border: 1px solid var(--border-glass);
            border-radius: var(--radius-lg);
            padding: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
        }

        .top-bar { display: flex; justify-content: space-between; align-items: center; }
        .user-chip { display: flex; align-items: center; gap: 12px; }

        .avatar-box img {
            width: 46px;
            height: 46px;
            border-radius: 50%;
            border: 2px solid var(--accent-glow);
            box-shadow: 0 0 12px rgba(0, 229, 255, 0.4);
        }

        .greeting-sub { font-size: 11px; color: var(--text-muted); text-transform: uppercase; font-weight: 600; }
        .top-bar h3 { font-family: var(--font-heading); font-size: 18px; font-weight: 700; }

        .pro-chip {
            background: rgba(0, 229, 255, 0.1);
            border: 1px solid var(--accent-glow);
            color: var(--accent-glow);
            padding: 8px 16px;
            border-radius: 30px;
            display: flex;
            align-items: center;
            gap: 6px;
            font-weight: 800;
            font-size: 13px;
            cursor: pointer;
            box-shadow: 0 0 15px rgba(0, 229, 255, 0.25);
        }

        .ai-hero-card {
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.15), rgba(236, 72, 153, 0.15));
            border: 1px solid rgba(255, 255, 255, 0.15);
        }

        .card-tag {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 11px;
            font-weight: 800;
            color: var(--accent-glow);
            letter-spacing: 1px;
            margin-bottom: 12px;
        }

        .ai-text-box { 
            font-size: 15px; 
            line-height: 1.5; 
            color: #F1F5F9; 
            margin-bottom: 16px; 
            min-height: 40px; 
            font-weight: 600;
        }

        .btn-sm { padding: 10px 18px; font-size: 13px; width: auto; }

        .stats-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }
        .stat-card { display: flex; align-items: center; gap: 14px; }
        .stat-icon { font-size: 32px; color: #6366F1; }
        .mood-icon { color: #EC4899; }
        .stat-info h2 { font-family: var(--font-heading); font-size: 22px; }
        .stat-info span { font-size: 12px; color: var(--text-muted); }

        textarea, select, input[type="text"], input[type="date"] {
            width: 100%;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--border-glass);
            border-radius: var(--radius-md);
            padding: 14px;
            color: #FFF;
            outline: none;
            margin-bottom: 14px;
            font-size: 14px;
        }

        textarea { height: 100px; resize: none; }

        .mood-options { display: flex; gap: 10px; margin: 10px 0 16px; }

        .mood-btn {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--border-glass);
            border-radius: 12px;
            padding: 10px 14px;
            font-size: 20px;
            cursor: pointer;
        }

        .mood-btn.active { border-color: var(--accent-glow); background: rgba(0, 229, 255, 0.15); }

        .media-upload-box {
            border: 2px dashed var(--border-glass);
            border-radius: var(--radius-md);
            padding: 20px;
            text-align: center;
            cursor: pointer;
            margin-bottom: 16px;
            background: rgba(255, 255, 255, 0.02);
            transition: all 0.3s ease;
        }

        .media-upload-box:hover { border-color: var(--accent-glow); background: rgba(0, 229, 255, 0.05); }

        .preview-container {
            display: flex;
            gap: 10px;
            overflow-x: auto;
            margin-bottom: 14px;
        }

        .media-thumb {
            width: 80px;
            height: 80px;
            border-radius: 12px;
            object-fit: cover;
            border: 1px solid var(--accent-glow);
        }

        .card-actions { display: flex; gap: 10px; }

        .action-btn {
            background: rgba(255, 255, 255, 0.08);
            border: 1px solid var(--border-glass);
            color: #FFF;
            border-radius: 8px;
            padding: 4px 8px;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 4px;
            font-size: 12px;
        }

        .action-btn.delete { color: #FF4D4D; border-color: rgba(255, 77, 77, 0.3); }

        .tool-card {
            display: flex;
            gap: 14px;
            align-items: center;
            cursor: pointer;
            transition: transform 0.2s ease, border-color 0.2s ease;
            margin-bottom: 12px;
        }
        .tool-card:hover { border-color: var(--accent-glow); transform: translateY(-2px); }

        /* Navigation Bar */
        .glass-nav {
            position: fixed;
            bottom: 15px;
            left: 15px;
            right: 15px;
            height: 70px;
            background: rgba(10, 11, 26, 0.85);
            backdrop-filter: blur(30px);
            -webkit-backdrop-filter: blur(30px);
            border: 1px solid var(--border-glass);
            border-radius: 35px;
            display: flex;
            justify-content: space-around;
            align-items: center;
            z-index: 100;
        }

        .nav-tab {
            background: none;
            border: none;
            color: var(--text-muted);
            display: flex;
            flex-direction: column;
            align-items: center;
            font-size: 10px;
            gap: 4px;
            cursor: pointer;
        }

        .nav-tab.active { color: var(--accent-glow); }

        .fab-btn .fab-glow {
            width: 52px;
            height: 52px;
            border-radius: 50%;
            background: var(--primary-gradient);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #FFF;
            transform: translateY(-20px);
            box-shadow: 0 8px 25px rgba(236, 72, 153, 0.5);
        }

        .btn-danger {
            background: rgba(239, 68, 68, 0.15);
            border: 1px solid rgba(239, 68, 68, 0.4);
            color: #EF4444;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(15px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>

    <div class="bg-orb orb-1"></div>
    <div class="bg-orb orb-2"></div>
    <div class="bg-orb orb-3"></div>

    <div id="splash-screen" class="splash-screen">
        <div class="splash-body">
            <div class="glow-ring">
                <span class="material-icons-round splash-icon">auto_awesome</span>
            </div>
            <h1 class="brand-title">LIFE REPLAY <span class="gradient-text">AI</span></h1>
            <p class="brand-sub">Crafted by Aayan Industries</p>
            <div class="loader-line"><div class="loader-fill"></div></div>
        </div>
    </div>

    <?php if (!$isLoggedIn): ?>
    <section id="full-login-wall" class="full-login-screen">
        <div class="login-card">
            <div class="glow-ring" style="width: 70px; height: 70px; margin: 0 auto 16px;">
                <span class="material-icons-round" style="font-size: 36px; color:#FFF;">lock</span>
            </div>
            <h2>Life Replay <span class="gradient-text">AI</span></h2>
            <p>Enter 10-digit mobile number for Renflair SMS OTP login</p>

            <form action="send-otp.php" method="POST">
                <div class="input-group">
                    <span class="prefix">+91</span>
                    <input type="tel" name="phone" id="mobile-number" inputmode="numeric" pattern="[0-9]*" placeholder="Mobile Number" maxlength="10" required>
                </div>
                <button type="submit" class="btn-glow">
                    <span>Send Renflair SMS OTP</span>
                    <span class="material-icons-round">arrow_forward</span>
                </button>
            </form>
        </div>
    </section>

    <?php else: ?>

    <div id="app-viewport" class="app-viewport">

        <main class="content-scroll">

            <section id="home-view" class="view-section">
                <header class="top-bar">
                    <div class="user-chip">
                        <div class="avatar-box">
                            <img id="user-avatar" src="https://api.dicebear.com/7.x/bottts/svg?seed=Aayan" alt="Avatar">
                        </div>
                        <div>
                            <span class="greeting-sub">Replaying Life</span>
                            <h3 id="display-username">+91 <?php echo htmlspecialchars($phone); ?></h3>
                        </div>
                    </div>
                    <button id="btn-pro-upgrade" class="pro-chip">
                        <span class="material-icons-round">stars</span>
                        <span>PRO (₹1)</span>
                    </button>
                </header>

                <div class="glass-card ai-hero-card">
                    <div class="card-tag">
                        <span class="material-icons-round">psychology</span>
                        <span>AI DAILY MOTIVATION</span>
                    </div>
                    <div id="ai-daily-text" class="ai-text-box">
                        ✨ Enjoy every small moment today; great things take time!
                    </div>
                    <button id="btn-generate-daily" class="btn-glow btn-sm">
                        <span class="material-icons-round">auto_awesome</span>
                        <span>Get Today's Vibe</span>
                    </button>
                </div>

                <div class="stats-grid">
                    <div class="glass-card stat-card">
                        <span class="material-icons-round stat-icon">collections</span>
                        <div class="stat-info">
                            <h2 id="total-memories-count">0</h2>
                            <span>Saved Entries</span>
                        </div>
                    </div>
                    <div class="glass-card stat-card">
                        <span class="material-icons-round stat-icon mood-icon">mood</span>
                        <div class="stat-info">
                            <h2 id="current-mood-emoji">😊</h2>
                            <span>Today's Vibe</span>
                        </div>
                    </div>
                </div>
            </section>

            <section id="timeline-view" class="view-section hidden">
                <h2 style="font-family:var(--font-heading);">Memory <span class="gradient-text">Timeline</span></h2>
                <div id="timeline-feed" style="display:flex; flex-direction:column; gap:14px;"></div>
            </section>

            <section id="add-view" class="view-section hidden">
                <div class="glass-card">
                    <h2 id="form-title" style="font-family:var(--font-heading); margin-bottom:14px;">Record <span class="gradient-text">Memory</span></h2>
                    
                    <textarea id="memory-text" placeholder="Write about your day, journal, thoughts..."></textarea>

                    <p style="font-size:12px; color:var(--text-muted); font-weight:600; margin-bottom:6px;">Add Photos or MP4 Videos</p>
                    <div class="media-upload-box" id="media-trigger">
                        <span class="material-icons-round" style="font-size:32px; color:var(--accent-glow);">add_a_photo</span>
                        <p style="font-size:12px; color:var(--text-muted); margin-top:4px;">Upload JPG, PNG, WebP or MP4</p>
                    </div>
                    <input type="file" id="media-file-input" accept="image/jpeg,image/png,image/webp,video/mp4" multiple class="hidden">
                    <div class="preview-container" id="media-preview-box"></div>

                    <div class="mood-picker">
                        <p style="font-size:12px; color:var(--text-muted); font-weight:600;">How was your vibe?</p>
                        <div class="mood-options">
                            <button class="mood-btn active" data-mood="😊">😊</button>
                            <button class="mood-btn" data-mood="🚀">🚀</button>
                            <button class="mood-btn" data-mood="😌">😌</button>
                            <button class="mood-btn" data-mood="🔥">🔥</button>
                            <button class="mood-btn" data-mood="😴">😴</button>
                        </div>
                    </div>

                    <button id="btn-save-memory" class="btn-glow">
                        <span class="material-icons-round">bookmark</span>
                        <span id="save-btn-text">Save Lifetime Memory</span>
                    </button>
                </div>
            </section>

            <section id="ai-view" class="view-section hidden">
                <h2 style="font-family:var(--font-heading);">AI <span class="gradient-text">Studio Suite</span></h2>

                <div class="glass-card tool-card" id="tool-movie-script">
                    <span class="material-icons-round" style="font-size:36px; color:var(--accent-glow);">movie</span>
                    <div>
                        <h3 style="font-size:16px;">Memory Movie Script</h3>
                        <p style="font-size:12px; color:var(--text-muted);">Turn memories into a cinematic film scene</p>
                    </div>
                </div>

                <div class="glass-card tool-card" id="tool-life-coach">
                    <span class="material-icons-round" style="font-size:36px; color:#EC4899;">auto_fix_high</span>
                    <div>
                        <h3 style="font-size:16px;">AI Life Coach & Advice</h3>
                        <p style="font-size:12px; color:var(--text-muted);">Get personal advice based on recent memory moods</p>
                    </div>
                </div>

                <div class="glass-card tool-card" id="tool-storyteller">
                    <span class="material-icons-round" style="font-size:36px; color:#A855F7;">menu_book</span>
                    <div>
                        <h3 style="font-size:16px;">Memory Storyteller</h3>
                        <p style="font-size:12px; color:var(--text-muted);">Transform raw bullet notes into a expressive story</p>
                    </div>
                </div>

                <div id="ai-output-card" class="glass-card hidden">
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:10px;">
                        <h3 style="font-size:16px;" id="ai-output-title" class="gradient-text">AI Output</h3>
                        <button onclick="document.getElementById('ai-output-card').classList.add('hidden')" style="background:none; border:none; color:#FFF; cursor:pointer;">
                            <span class="material-icons-round">close</span>
                        </button>
                    </div>
                    <div id="ai-output-body" style="font-size:14px; line-height:1.6; color:#CBD5E1; white-space: pre-wrap;"></div>
                </div>
            </section>

            <section id="profile-view" class="view-section hidden">
                <div class="glass-card" style="text-align:center;">
                    <img src="https://api.dicebear.com/7.x/bottts/svg?seed=Aayan" alt="Profile" style="width:70px; height:70px; border-radius:50%; margin-bottom:10px;">
                    <h3 id="profile-display-name">+91 <?php echo htmlspecialchars($phone); ?></h3>
                    <span id="account-status-badge" style="display:inline-block; font-size:11px; padding:4px 12px; border-radius:20px; background:rgba(255,255,255,0.1); margin-top:6px; color:var(--accent-glow);">Free Account</span>
                </div>

                <div class="glass-card">
                    <h3 style="font-size:16px; margin-bottom:14px;" class="gradient-text">Developer / Profile Details</h3>
                    
                    <p style="font-size:11px; color:var(--text-muted); margin-bottom:4px;">Full Name</p>
                    <input type="text" id="profile-name-input" placeholder="Your Name">
                    
                    <p style="font-size:11px; color:var(--text-muted); margin-bottom:4px;">Date of Birth</p>
                    <input type="date" id="profile-dob">

                    <p style="font-size:11px; color:var(--text-muted); margin-bottom:4px;">Gender</p>
                    <select id="profile-gender">
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Other">Other</option>
                    </select>

                    <p style="font-size:11px; color:var(--text-muted); margin-bottom:4px;">Preferred Language</p>
                    <select id="profile-lang">
                        <option value="Hindi">Hindi</option>
                        <option value="English">English</option>
                    </select>

                    <button id="btn-save-profile" onclick="saveProfileDetails()" class="btn-glow btn-sm" style="width:100%; margin-top:8px;">
                        Save Profile Information (Permanent)
                    </button>
                </div>

                <div class="glass-card">
                    <button id="btn-logout" class="btn-glow btn-danger" onclick="window.location.href='logout.php';">
                        <span class="material-icons-round">logout</span>
                        <span>Logout Account</span>
                    </button>
                </div>
            </section>

        </main>

        <nav class="glass-nav">
            <button class="nav-tab active" data-target="home-view">
                <span class="material-icons-round">grid_view</span>
                <span>Home</span>
            </button>
            <button class="nav-tab" data-target="timeline-view">
                <span class="material-icons-round">timeline</span>
                <span>Timeline</span>
            </button>
            <button class="nav-tab fab-btn" data-target="add-view">
                <div class="fab-glow">
                    <span class="material-icons-round">add</span>
                </div>
            </button>
            <button class="nav-tab" data-target="ai-view">
                <span class="material-icons-round">psychology</span>
                <span>AI Tools</span>
            </button>
            <button class="nav-tab" data-target="profile-view">
                <span class="material-icons-round">person</span>
                <span>Profile</span>
            </button>
        </nav>

    </div>
    <?php endif; ?>

    <script>
        const CONFIG = {
            OPENROUTER_API_KEY: "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",
            RAZORPAY_KEY_ID: "rzp_live_T5OZeAeTkrB7YG"
        };

        const AppState = {
            currentView: 'home-view',
            selectedMood: '😊',
            uploadedMedia: [],
            editingMemoryId: null
        };

        document.addEventListener('DOMContentLoaded', () => {
            setTimeout(() => {
                const splash = document.getElementById('splash-screen');
                if (splash) {
                    splash.style.opacity = '0';
                    setTimeout(() => splash.classList.add('hidden'), 400);
                }
            }, 800);

            setupNavigation();
            setupEventListeners();
            renderTimeline();
            loadProfileDetails();
            setupBackButton();
        });

        // -----------------------------------------------------------------
        // 1. BACK BUTTON HANDLING (Home Redirect & Exit Logic)
        // -----------------------------------------------------------------
        function setupBackButton() {
            // Initial History state
            history.pushState({ view: 'home-view' }, '', '');

            window.onpopstate = function (event) {
                if (AppState.currentView !== 'home-view') {
                    // Agar kisi aur section m hai toh Home Screen par bhej do
                    switchView('home-view', false);
                    history.pushState({ view: 'home-view' }, '', '');
                } else {
                    // Agar pehle se home screen pe hai toh app close karne ka alert / exit karein
                    if (confirm("Kya aap application close karna chahte hain?")) {
                        window.close();
                    } else {
                        history.pushState({ view: 'home-view' }, '', '');
                    }
                }
            };
        }

        // -----------------------------------------------------------------
        // 2. NAVIGATION & SCREEN SWITCHING
        // -----------------------------------------------------------------
        function setupNavigation() {
            const tabs = document.querySelectorAll('.nav-tab');
            tabs.forEach(tab => {
                tab.addEventListener('click', () => {
                    const target = tab.getAttribute('data-target');
                    if (!target) return;

                    if(target === 'add-view' && !AppState.editingMemoryId) {
                        resetAddForm();
                    }

                    switchView(target, true);
                });
            });
        }

        function switchView(viewId, pushHistory = true) {
            AppState.currentView = viewId;

            // Nav Tabs Highlight Update
            document.querySelectorAll('.nav-tab').forEach(t => {
                if (t.getAttribute('data-target') === viewId) {
                    t.classList.add('active');
                } else {
                    t.classList.remove('active');
                }
            });

            // View Visibility Update
            document.querySelectorAll('.view-section').forEach(v => v.classList.add('hidden'));
            const target = document.getElementById(viewId);
            if (target) target.classList.remove('hidden');

            if (pushHistory) {
                history.pushState({ view: viewId }, '', '');
            }
        }

        // -----------------------------------------------------------------
        // 3. PERMANENT PROFILE / DEVELOPER DETAILS SAVE (localStorage)
        // -----------------------------------------------------------------
        function saveProfileDetails() {
            const name = document.getElementById('profile-name-input').value.trim();
            const dob = document.getElementById('profile-dob').value;
            const gender = document.getElementById('profile-gender').value;
            const lang = document.getElementById('profile-lang').value;

            const profileObj = { name, dob, gender, lang };
            localStorage.setItem('user_profile_data', JSON.stringify(profileObj));

            if (name) {
                const dispName = document.getElementById('profile-display-name');
                if (dispName) dispName.innerText = name;
            }

            alert("✅ Developer / Profile details permanently save ho gayi hain!");
        }

        function loadProfileDetails() {
            const savedData = localStorage.getItem('user_profile_data');
            if (savedData) {
                const p = JSON.parse(savedData);
                if (p.name) {
                    document.getElementById('profile-name-input').value = p.name;
                    const dispName = document.getElementById('profile-display-name');
                    if (dispName) dispName.innerText = p.name;
                }
                if (p.dob) document.getElementById('profile-dob').value = p.dob;
                if (p.gender) document.getElementById('profile-gender').value = p.gender;
                if (p.lang) document.getElementById('profile-lang').value = p.lang;
            }
        }

        // -----------------------------------------------------------------
        // 4. SUBSCRIPTION PRICE = 1 RS (Razorpay Integration)
        // -----------------------------------------------------------------
        function launchRazorpay() {
            const options = {
                "key": CONFIG.RAZORPAY_KEY_ID,
                "amount": "100", // 100 Paise = ₹1 INR
                "currency": "INR",
                "name": "Life Replay AI",
                "description": "PRO Subscription Plan (₹1)",
                "handler": function (response) {
                    alert("Payment Success! Premium Plan Activated for ₹1.");
                    const badge = document.getElementById('account-status-badge');
                    if(badge) badge.innerText = "PRO Member (₹1 Active)";
                },
                "theme": { "color": "#6366F1" }
            };
            new Razorpay(options).open();
        }

        // -----------------------------------------------------------------
        // 5. OTHER APP LOGIC (Media, Memory, AI)
        // -----------------------------------------------------------------
        function setupEventListeners() {
            const mediaTrigger = document.getElementById('media-trigger');
            if(mediaTrigger) {
                mediaTrigger.addEventListener('click', () => {
                    document.getElementById('media-file-input').click();
                });
                document.getElementById('media-file-input').addEventListener('change', handleMediaUpload);
            }

            const saveBtn = document.getElementById('btn-save-memory');
            if(saveBtn) saveBtn.addEventListener('click', saveMemory);

            const dailyBtn = document.getElementById('btn-generate-daily');
            if(dailyBtn) dailyBtn.addEventListener('click', generateDailyAIStory);

            const proBtn = document.getElementById('btn-pro-upgrade');
            if(proBtn) proBtn.addEventListener('click', launchRazorpay);

            document.querySelectorAll('.mood-btn').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    document.querySelectorAll('.mood-btn').forEach(b => b.classList.remove('active'));
                    e.target.classList.add('active');
                    AppState.selectedMood = e.target.getAttribute('data-mood');
                });
            });

            const toolMovie = document.getElementById('tool-movie-script');
            if(toolMovie) toolMovie.addEventListener('click', () => callOpenRouterAI("movie_script"));

            const toolCoach = document.getElementById('tool-life-coach');
            if(toolCoach) toolCoach.addEventListener('click', () => callOpenRouterAI("life_coach"));

            const toolStory = document.getElementById('tool-storyteller');
            if(toolStory) toolStory.addEventListener('click', () => callOpenRouterAI("storyteller"));
        }

        function handleMediaUpload(e) {
            const files = Array.from(e.target.files);
            const previewBox = document.getElementById('media-preview-box');
            
            files.forEach(file => {
                if (file.type === "application/pdf") {
                    alert("PDF files allow nahi hain! JPG, PNG, WebP ya MP4 video hi upload karein.");
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(event) {
                    const isVideo = file.type.startsWith('video');
                    const mediaObj = {
                        type: isVideo ? 'video' : 'image',
                        src: event.target.result
                    };
                    AppState.uploadedMedia.push(mediaObj);

                    if (mediaObj.type === 'image') {
                        previewBox.innerHTML += `<img src="${mediaObj.src}" class="media-thumb">`;
                    } else {
                        previewBox.innerHTML += `<video src="${mediaObj.src}" class="media-thumb" controls></video>`;
                    }
                };
                reader.readAsDataURL(file);
            });
        }

        function resetAddForm() {
            AppState.editingMemoryId = null;
            document.getElementById('form-title').innerHTML = `Record <span class="gradient-text">Memory</span>`;
            document.getElementById('save-btn-text').innerText = 'Save Lifetime Memory';
            document.getElementById('memory-text').value = '';
            document.getElementById('media-preview-box').innerHTML = '';
            AppState.uploadedMedia = [];
        }

        function saveMemory() {
            const text = document.getElementById('memory-text').value.trim();
            if (!text && AppState.uploadedMedia.length === 0) {
                return alert("Kuch text, photo ya video zaroor dalein!");
            }

            let memories = JSON.parse(localStorage.getItem('memories_db') || '[]');

            if (AppState.editingMemoryId) {
                const index = memories.findIndex(m => m.id === AppState.editingMemoryId);
                if (index !== -1) {
                    memories[index].text = text;
                    memories[index].mood = AppState.selectedMood;
                    memories[index].media = AppState.uploadedMedia;
                }
                alert("Memory update ho gayi!");
            } else {
                const memory = {
                    id: "mem_" + Date.now(),
                    text: text,
                    mood: AppState.selectedMood,
                    media: AppState.uploadedMedia,
                    date: new Date().toLocaleDateString('en-US', { day: 'numeric', month: 'short', year: 'numeric' })
                };
                memories.unshift(memory);
                alert("Memory save ho gayi!");
            }

            localStorage.setItem('memories_db', JSON.stringify(memories));
            resetAddForm();
            switchView('timeline-view');
            renderTimeline();
        }

        function deleteMemory(id) {
            if (confirm("Kya aap is memory ko delete karna chahte hain?")) {
                let memories = JSON.parse(localStorage.getItem('memories_db') || '[]');
                memories = memories.filter(m => m.id !== id);
                localStorage.setItem('memories_db', JSON.stringify(memories));
                renderTimeline();
            }
        }

        function editMemory(id) {
            let memories = JSON.parse(localStorage.getItem('memories_db') || '[]');
            const memory = memories.find(m => m.id === id);
            if (!memory) return;

            AppState.editingMemoryId = memory.id;
            document.getElementById('form-title').innerHTML = `Edit <span class="gradient-text">Memory</span>`;
            document.getElementById('save-btn-text').innerText = 'Update Memory';
            document.getElementById('memory-text').value = memory.text || '';
            
            AppState.uploadedMedia = memory.media || [];
            const previewBox = document.getElementById('media-preview-box');
            previewBox.innerHTML = '';
            AppState.uploadedMedia.forEach(m => {
                if (m.type === 'image') {
                    previewBox.innerHTML += `<img src="${m.src}" class="media-thumb">`;
                } else {
                    previewBox.innerHTML += `<video src="${m.src}" class="media-thumb" controls></video>`;
                }
            });

            switchView('add-view');
        }

        function renderTimeline() {
            const container = document.getElementById('timeline-feed');
            if(!container) return;

            const memories = JSON.parse(localStorage.getItem('memories_db') || '[]');
            const countEl = document.getElementById('total-memories-count');
            if(countEl) countEl.innerText = memories.length;

            if (memories.length === 0) {
                container.innerHTML = `<div class="glass-card"><p style="color:var(--text-muted); font-size:14px;">No memories recorded yet.</p></div>`;
                return;
            }

            container.innerHTML = memories.map(m => {
                let mediaHtml = '';
                if (m.media && m.media.length > 0) {
                    mediaHtml = `<div class="preview-container" style="margin-top:10px;">` + 
                        m.media.map(item => item.type === 'image' ? 
                            `<img src="${item.src}" class="media-thumb">` : 
                            `<video src="${item.src}" class="media-thumb" controls></video>`
                        ).join('') + `</div>`;
                }

                return `
                    <div class="glass-card">
                        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:8px;">
                            <span style="font-size:12px; color:var(--text-muted);">${m.date}</span>
                            <div style="display:flex; align-items:center; gap:8px;">
                                <span style="font-size:18px;">${m.mood}</span>
                                <div class="card-actions">
                                    <button class="action-btn" onclick="editMemory('${m.id}')">
                                        <span class="material-icons-round" style="font-size:14px;">edit</span>
                                    </button>
                                    <button class="action-btn delete" onclick="deleteMemory('${m.id}')">
                                        <span class="material-icons-round" style="font-size:14px;">delete</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <p style="font-size:14px; line-height:1.5;">${m.text}</p>
                        ${mediaHtml}
                    </div>
                `;
            }).join('');
        }

        async function generateDailyAIStory() {
            const box = document.getElementById('ai-daily-text');
            box.innerText = "✨ Generating today's vibe...";

            try {
                const res = await fetch("https://openrouter.ai/api/v1/chat/completions", {
                    method: "POST",
                    headers: {
                        "Authorization": `Bearer ${CONFIG.OPENROUTER_API_KEY}`,
                        "Content-Type": "application/json",
                        "HTTP-Referer": "https://aayanindustries.shop",
                        "X-Title": "Life Replay AI"
                    },
                    body: JSON.stringify({
                        "model": "meta-llama/llama-3.2-1b-instruct:free",
                        "messages": [
                            { "role": "system", "content": "Output ONLY ONE motivational sentence in Hinglish or English. Max 12 words." },
                            { "role": "user", "content": "Give me today's motivation." }
                        ]
                    })
                });

                const data = await res.json();
                if (data && data.choices && data.choices[0]) {
                    box.innerText = data.choices[0].message.content.trim();
                }
            } catch (e) {
                box.innerText = "✨ Believe in your journey—today is another chance to shine!";
            }
        }

        async function callOpenRouterAI(toolType) {
            const card = document.getElementById('ai-output-card');
            const body = document.getElementById('ai-output-body');
            const title = document.getElementById('ai-output-title');
            
            card.classList.remove('hidden');
            body.innerText = "⏳ AI Engine Processing...";

            const memories = JSON.parse(localStorage.getItem('memories_db') || '[]');
            const recentText = memories.slice(0, 3).map(m => m.text).join(". ");

            let systemPrompt = "You are a helpful assistant.";
            let userPrompt = "Hello!";

            if (toolType === "movie_script") {
                title.innerText = "🎬 Memory Movie Script";
                systemPrompt = "You are a film screenwriter. Create a short visual scene script based on user memories.";
                userPrompt = `Turn these memory notes into a cinematic screenplay scene:\n"${recentText || 'Working hard on my startup dream.'}"`;
            } else if (toolType === "life_coach") {
                title.innerText = "💡 AI Life Coach & Advice";
                systemPrompt = "You are an empathetic life coach. Give 3 actionable, uplifting tips for personal growth based on recent thoughts.";
                userPrompt = `Analyze these recent thoughts and give personal advice:\n"${recentText || 'Feeling busy and aiming high.'}"`;
            } else if (toolType === "storyteller") {
                title.innerText = "📖 Memory Storyteller";
                systemPrompt = "You are a warm storyteller. Write a short narrative paragraph from raw notes.";
                userPrompt = `Write a short story paragraph from these notes:\n"${recentText || 'Spent a productive day planning.'}"`;
            }

            try {
                const res = await fetch("https://openrouter.ai/api/v1/chat/completions", {
                    method: "POST",
                    headers: {
                        "Authorization": `Bearer ${CONFIG.OPENROUTER_API_KEY}`,
                        "Content-Type": "application/json",
                        "HTTP-Referer": "https://aayanindustries.shop",
                        "X-Title": "Life Replay AI"
                    },
                    body: JSON.stringify({
                        "model": "meta-llama/llama-3.2-1b-instruct:free",
                        "messages": [
                            { "role": "system", "content": systemPrompt },
                            { "role": "user", "content": userPrompt }
                        ]
                    })
                });

                const data = await res.json();
                if (data && data.choices && data.choices[0]) {
                    body.innerText = data.choices[0].message.content.trim();
                } else {
                    body.innerText = userPrompt;
                }
            } catch (e) {
                body.innerText = "SCENE 1: THE REPLAY\nFade in on an ambitious user building something amazing every day.";
            }
        }
    </script>
</body>
</html>