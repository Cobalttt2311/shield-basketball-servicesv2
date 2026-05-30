<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password — Shield Basketball</title>
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
            --input-bg: #1F1F1F;
            --error: #FF4D4D;
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

        /* Background glow */
        body::before {
            content: '';
            position: fixed;
            top: -20%;
            left: -10%;
            width: 60vw;
            height: 60vw;
            background: radial-gradient(circle, rgba(245,82,12,0.12) 0%, transparent 65%);
            pointer-events: none;
        }

        .card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 48px 40px;
            width: 100%;
            max-width: 420px;
            position: relative;
            animation: fadeUp 0.5s ease both;
        }

        .card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 3px;
            border-radius: 20px 20px 0 0;
            background: linear-gradient(90deg, var(--orange), var(--orange-dark));
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 36px;
        }

        .brand-icon {
            width: 36px;
            height: 36px;
            background: var(--orange);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .brand-icon svg {
            width: 20px;
            height: 20px;
            fill: white;
        }

        .brand-name {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 22px;
            letter-spacing: 1px;
            color: var(--text);
        }

        .card-title {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 36px;
            letter-spacing: 1.5px;
            color: var(--text);
            line-height: 1;
            margin-bottom: 8px;
        }

        .card-desc {
            font-size: 14px;
            color: var(--muted);
            line-height: 1.6;
            margin-bottom: 32px;
        }

        .error-box {
            background: rgba(255,77,77,0.1);
            border: 1px solid rgba(255,77,77,0.3);
            border-radius: 10px;
            padding: 12px 16px;
            font-size: 13px;
            color: var(--error);
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .error-box svg { flex-shrink: 0; }

        .field {
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 0.8px;
            text-transform: uppercase;
            color: var(--muted);
            margin-bottom: 8px;
        }

        input[type="email"] {
            width: 100%;
            background: var(--input-bg);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 13px 16px;
            font-family: 'DM Sans', sans-serif;
            font-size: 15px;
            color: var(--text);
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        input[type="email"]::placeholder { color: #555; }

        input[type="email"]:focus {
            border-color: var(--orange);
            box-shadow: 0 0 0 3px rgba(245,82,12,0.15);
        }

        .btn {
            width: 100%;
            background: var(--orange);
            color: white;
            border: none;
            border-radius: 10px;
            padding: 14px;
            font-family: 'Bebas Neue', sans-serif;
            font-size: 18px;
            letter-spacing: 1.5px;
            cursor: pointer;
            transition: background 0.2s, transform 0.1s;
            margin-top: 4px;
        }

        .btn:hover { background: var(--orange-dark); }
        .btn:active { transform: scale(0.98); }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            font-size: 13px;
            color: var(--muted);
            text-decoration: none;
            transition: color 0.2s;
        }
        .back-link:hover { color: var(--orange); }
    </style>
</head>
<body>
    <div class="card">
        <div class="brand">
            <div class="brand-icon">
                <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 14H9V8h2v8zm4 0h-2V8h2v8z"/>
                </svg>
            </div>
            <span class="brand-name">Shield Basketball</span>
        </div>

        <h1 class="card-title">Lupa Password?</h1>
        <p class="card-desc">Masukkan email akunmu dan kami akan mengirimkan link untuk mereset password.</p>

        @if(isset($error))
        <div class="error-box">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/>
            </svg>
            {{ $error }}
        </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <div class="field">
                <label for="email">Alamat Email</label>
                <input type="email" name="email" id="email" placeholder="contoh@email.com" required autocomplete="email">
            </div>
            <button type="submit" class="btn">Kirim Link Reset</button>
        </form>

        <a href="{{ url('http://localhost:3000/sign-in') }}" class="back-link">← Kembali ke halaman login</a>
    </div>
</body>
</html>