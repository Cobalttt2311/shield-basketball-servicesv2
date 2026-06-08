<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password — Shield Basketball</title>
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

        .brand-icon svg { width: 20px; height: 20px; fill: white; }

        .brand-name {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 22px;
            letter-spacing: 1px;
        }

        .card-title {
            font-family: 'Bebas Neue', sans-serif;
            font-size: 36px;
            letter-spacing: 1.5px;
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
        }

        .error-box p + p { margin-top: 4px; }

        .field {
            margin-bottom: 20px;
            position: relative;
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

        /* Pakai class .pw-input supaya styling tidak hilang saat type berubah ke "text" */
        .pw-input {
            width: 100%;
            background: var(--input-bg);
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 13px 48px 13px 16px;
            font-family: 'DM Sans', sans-serif;
            font-size: 15px;
            color: var(--text);
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .pw-input::placeholder { color: #555; }

        .pw-input:focus {
            border-color: var(--orange);
            box-shadow: 0 0 0 3px rgba(245,82,12,0.15);
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

        /* Password strength */
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
            margin-top: 8px;
        }

        .btn:hover { background: var(--orange-dark); }
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
        <p class="card-desc">Buat password baru yang kuat untuk akunmu.</p>

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
                <label for="password">Password Baru</label>
                <input type="password" name="password" id="password" class="pw-input" placeholder="Minimal 8 karakter" required autocomplete="new-password" oninput="checkStrength(this.value)">
                <button type="button" class="toggle-pw" onclick="togglePw('password', this)" title="Tampilkan password">
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
                <label for="password_confirmation">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="pw-input" placeholder="Ulangi password baru" required autocomplete="new-password">
                <button type="button" class="toggle-pw" onclick="togglePw('password_confirmation', this)" title="Tampilkan password">
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

            <button type="submit" class="btn">Simpan Password Baru</button>
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

            const colors = ['#FF4D4D', '#F5A623', '#F5D020', '#22C55E'];
            const labels = ['', 'Lemah', 'Cukup', 'Kuat', 'Sangat Kuat'];

            bars.forEach((b, i) => {
                b.style.background = i < score ? colors[score - 1] : 'rgba(255,255,255,0.08)';
            });
            label.textContent = val.length ? labels[score] : '';
            label.style.color = score > 0 ? colors[score - 1] : '#888';
        }
    </script>
</body>
</html>