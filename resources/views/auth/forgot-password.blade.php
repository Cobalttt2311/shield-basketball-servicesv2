<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password — Shield Basketball</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --primary: #2a85ff;
            --primary-deep: #0069f6;
            --dark: #0a0a0a;
            --card: #171717;
            --border: rgba(255,255,255,0.08);
            --text: #fafafa;
            --muted: #737373;
            --input-bg: #262626;
            --error: #ff6a55;
        }

        body {
            background-color: var(--dark);
            color: var(--text);
            font-family: 'Inter', sans-serif;
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
            background: radial-gradient(circle, rgba(42,133,255,0.12) 0%, transparent 65%);
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
            background: linear-gradient(90deg, var(--primary), var(--primary-deep));
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
            background: var(--primary);
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
            font-size: 20px;
            font-weight: 700;
            letter-spacing: 0.5px;
            color: var(--text);
        }

        .card-title {
            font-size: 28px;
            font-weight: 700;
            letter-spacing: -0.5px;
            color: var(--text);
            line-height: 1.2;
            margin-bottom: 8px;
        }

        .card-desc {
            font-size: 14px;
            color: var(--muted);
            line-height: 1.6;
            margin-bottom: 32px;
        }

        .error-box {
            background: rgba(255,106,85,0.1);
            border: 1px solid rgba(255,106,85,0.3);
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
            margin-bottom: 24px;
        }

        label {
            display: block;
            font-size: 11px;
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
            font-family: 'Inter', sans-serif;
            font-size: 15px;
            color: var(--text);
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        input[type="email"]::placeholder { color: #555; }

        input[type="email"]:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(42,133,255,0.15);
        }

        .btn {
            width: 100%;
            background: var(--primary);
            color: white;
            border: none;
            border-radius: 10px;
            padding: 14px;
            font-family: 'Inter', sans-serif;
            font-weight: 600;
            font-size: 15px;
            cursor: pointer;
            transition: background 0.2s, transform 0.1s;
            margin-top: 4px;
        }

        .btn:hover { background: var(--primary-deep); }
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
        .back-link:hover { color: var(--primary); }
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

        <h1 class="card-title">Forgot Password?</h1>
        <p class="card-desc">Enter your email address and we'll send you a link to reset your password.</p>

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
                <label for="email">Email Address</label>
                <input type="email" name="email" id="email" placeholder="example@email.com" required autocomplete="email">
            </div>
            <button type="submit" class="btn">Send Reset Link</button>
        </form>

        <a href="{{ url('http://http://52.64.55.223/sign-in') }}" class="back-link">← Back to login page</a>
    </div>
</body>
</html>