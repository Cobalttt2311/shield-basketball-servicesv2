<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password — Shield Basketball</title>
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

        .brand-icon svg { width: 20px; height: 20px; fill: white; }

        .brand-name {
            font-size: 20px;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        .card-title {
            font-size: 28px;
            font-weight: 700;
            letter-spacing: -0.5px;
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
        }

        .error-box p + p { margin-top: 4px; }

        .field {
            margin-bottom: 20px;
            position: relative;
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

        .pw-input {
            width: 100%;
            background: var(--input-bg);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 13px 48px 13px 16px;
            font-family: 'Inter', sans-serif;
            font-size: 15px;
            color: var(--text);
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .pw-input::placeholder { color: #555; }

        .pw-input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(42,133,255,0.15);
        }

        .toggle-pw {
            position: absolute;
            right: 14px;
            bottom: 13px;
            background: none;
            border: none;
            cursor: pointer;
            color: var(--muted);
            padding: 0;
            display: flex;
            transition: color 0.2s;
        }
        .toggle-pw:hover { color: var(--text); }

        .strength-bar {
            display: flex;
            gap: 4px;
            margin-top: 8px;
        }

        .strength-bar span {
            flex: 1;
            height: 3px;
            border-radius: 2px;
            background: var(--border);
            transition: background 0.3s;
        }

        .strength-label {
            font-size: 11px;
            color: var(--muted);
            margin-top: 5px;
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
            margin-top: 8px;
        }

        .btn:hover { background: var(--primary-deep); }
        .btn:active { transform: scale(0.98); }
    </style>
</head>
<body>
    <div class="card">
        <div class="brand">
            <div class="brand-icon">
                <svg viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 14H9V8h2v8zm4 0h-2V8h2v8z"/></svg>
            </div>
            <span class="brand-name">Shield Basketball</span>
        </div>

        <h1 class="card-title">Reset Password</h1>
        <p class="card-desc">Create a strong new password for your account.</p>

        @if($errors->any())
        <div class="error-box">
            @foreach($errors->all() as $err)
                <p>{{ $err }}</p>
            @endforeach
        </div>
        @endif

        @if(isset($error))
        <div class="error-box"><p>{{ $error }}</p></div>
        @endif

        <form method="POST" action="{{ route('password.reset.submit') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <input type="hidden" name="email" value="{{ $email }}">

            <div class="field">
                <label for="password">New Password</label>
                <input type="password" name="password" id="password" class="pw-input" placeholder="At least 8 characters" required autocomplete="new-password" oninput="checkStrength(this.value)">
                <button type="button" class="toggle-pw" onclick="togglePw('password', this)" title="Show password">
                    <!-- eye-open -->
                    <svg class="icon-eye-open" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                    </svg>
                    <!-- eye-off -->
                    <svg class="icon-eye-off" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:none">
                        <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/><line x1="1" y1="1" x2="23" y2="23"/>
                    </svg>
                </button>
                <div class="strength-bar">
                    <span id="s1"></span><span id="s2"></span><span id="s3"></span><span id="s4"></span>
                </div>
                <div class="strength-label" id="strength-text"></div>
            </div>

            <div class="field">
                <label for="password_confirmation">Confirm Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="pw-input" placeholder="Repeat new password" required autocomplete="new-password">
                <button type="button" class="toggle-pw" onclick="togglePw('password_confirmation', this)" title="Show password">
                    <!-- eye-open -->
                    <svg class="icon-eye-open" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                    </svg>
                    <!-- eye-off -->
                    <svg class="icon-eye-off" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display:none">
                        <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94"/><path d="M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19"/><line x1="1" y1="1" x2="23" y2="23"/>
                    </svg>
                </button>
            </div>

            <button type="submit" class="btn">Save New Password</button>
        </form>
    </div>

    <script>
        function togglePw(id, btn) {
            const input = document.getElementById(id);
            const isPassword = input.type === 'password';
            input.type = isPassword ? 'text' : 'password';

            // Swap ikon
            btn.querySelector('.icon-eye-open').style.display = isPassword ? 'none' : '';
            btn.querySelector('.icon-eye-off').style.display  = isPassword ? ''     : 'none';
        }

        function checkStrength(val) {
            const bars = [document.getElementById('s1'), document.getElementById('s2'), document.getElementById('s3'), document.getElementById('s4')];
            const label = document.getElementById('strength-text');
            let score = 0;
            if (val.length >= 8) score++;
            if (/[A-Z]/.test(val)) score++;
            if (/[0-9]/.test(val)) score++;
            if (/[^A-Za-z0-9]/.test(val)) score++;

            const colors = ['#ff6a55', '#f59e0b', '#ffd400', '#10b981'];
            const labels = ['', 'Weak', 'Medium', 'Strong', 'Very Strong'];

            bars.forEach((b, i) => {
                b.style.background = i < score ? colors[score - 1] : 'rgba(255,255,255,0.08)';
            });
            label.textContent = val.length ? labels[score] : '';
            label.style.color = score > 0 ? colors[score - 1] : '#737373';
        }
    </script>
</body>
</html>