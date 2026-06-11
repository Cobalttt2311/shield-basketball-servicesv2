<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Terkirim — Shield Basketball</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --orange: #F5520C;
            --orange-dark: #C23D00;
            --dark: #0D0D0D;
            --card: #161616;
            --border: rgba(255,255,255,0.08);
            --text: #E8E8E8;
            --muted: #888;
            --success: #22C55E;
        }

        body {
            background-color: var(--dark);
            color: var(--text);
            font-family: 'DM Sans', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            overflow: hidden;
        }

        body::before {
            content: '';
            position: fixed;
            top: -20%;
            left: -10%;
            width: 60vw;
            height: 60vw;
            background: radial-gradient(circle, rgba(34,197,94,0.08) 0%, transparent 65%);
            pointer-events: none;
        }

        .card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 48px 40px;
            width: 100%;
            max-width: 420px;
            text-align: center;
            position: relative;
            animation: fadeUp 0.5s ease both;
        }

        .card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 3px;
            border-radius: 20px 20px 0 0;
            background: linear-gradient(90deg, var(--success), #16a34a);
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes pop {
            0% { transform: scale(0); opacity: 0; }
            70% { transform: scale(1.15); }
            100% { transform: scale(1); opacity: 1; }
        }

        .icon-wrap {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            background: rgba(34,197,94,0.12);
            border: 2px solid rgba(34,197,94,0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 28px;
            animation: pop 0.6s cubic-bezier(0.34,1.56,0.64,1) 0.2s both;
        }

        .icon-wrap svg {
            width: 32px;
            height: 32px;
            color: var(--success);
        }

        .card-title {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 36px;
            letter-spacing: 1.5px;
            color: var(--text);
            line-height: 1;
            margin-bottom: 12px;
        }

        .card-desc {
            font-size: 14px;
            color: var(--muted);
            line-height: 1.7;
            margin-bottom: 32px;
            max-width: 300px;
            margin-left: auto;
            margin-right: auto;
        }

        .hint-box {
            background: rgba(255,255,255,0.04);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 14px 16px;
            font-size: 12.5px;
            color: var(--muted);
            line-height: 1.6;
            margin-bottom: 28px;
            text-align: left;
        }

        .hint-box strong { color: var(--text); }

        .btn-outline {
            display: inline-block;
            width: 100%;
            background: transparent;
            color: var(--text);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 13px;
            font-family: 'DM Sans', sans-serif;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            transition: border-color 0.2s, background 0.2s;
        }
        .btn-outline:hover {
            border-color: rgba(255,255,255,0.2);
            background: rgba(255,255,255,0.04);
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="icon-wrap">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                <polyline points="22,6 12,13 2,6"/>
            </svg>
        </div>

        <h1 class="card-title">Email Terkirim!</h1>
        <p class="card-desc">Link reset password telah dikirim ke email kamu. Silakan cek inbox atau folder spam.</p>

        <div class="hint-box">
            <strong>Tidak menerima email?</strong><br>
            Pastikan email yang kamu masukkan benar, tunggu beberapa menit, dan cek folder <em>Spam</em> atau <em>Junk</em>.
        </div>

        <a href="{{ url('http://localhost:3000/sign-in') }}" class="btn-outline">← Kembali ke Login</a>
    </div>
</body>
</html>