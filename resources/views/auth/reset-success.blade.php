<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Changed Successfully — Shield Basketball</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --primary: #2a85ff;
            --primary-deep: #0069f6;
            --success: #10b981;
            --dark: #0a0a0a;
            --card: #171717;
            --border: rgba(255,255,255,0.08);
            --text: #fafafa;
            --muted: #737373;
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
            right: -10%;
            width: 60vw;
            height: 60vw;
            background: radial-gradient(circle, rgba(16,185,129,0.08) 0%, transparent 65%);
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
            background: linear-gradient(90deg, var(--success), #059669);
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes pop {
            0% { transform: scale(0) rotate(-10deg); opacity: 0; }
            70% { transform: scale(1.15) rotate(3deg); }
            100% { transform: scale(1) rotate(0); opacity: 1; }
        }

        @keyframes ripple {
            0% { transform: scale(1); opacity: 0.6; }
            100% { transform: scale(2.2); opacity: 0; }
        }

        .icon-wrap {
            width: 80px;
            height: 80px;
            position: relative;
            margin: 0 auto 28px;
        }

        .icon-circle {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: rgba(16,185,129,0.12);
            border: 2px solid rgba(16,185,129,0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            animation: pop 0.6s cubic-bezier(0.34,1.56,0.64,1) 0.2s both;
            position: relative;
            z-index: 1;
        }

        .icon-ripple {
            position: absolute;
            inset: 0;
            border-radius: 50%;
            border: 2px solid rgba(16,185,129,0.4);
            animation: ripple 1.5s ease-out 0.8s infinite;
        }

        .icon-circle svg { width: 36px; height: 36px; color: var(--success); }

        .card-title {
            font-size: 28px;
            font-weight: 700;
            letter-spacing: -0.5px;
            color: var(--text);
            line-height: 1.2;
            margin-bottom: 12px;
        }

        .card-desc {
            font-size: 14px;
            color: var(--muted);
            line-height: 1.7;
            margin-bottom: 36px;
            max-width: 280px;
            margin-left: auto;
            margin-right: auto;
        }

        .btn {
            display: block;
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
            text-decoration: none;
            transition: background 0.2s, transform 0.1s;
        }

        .btn:hover { background: var(--primary-deep); }
        .btn:active { transform: scale(0.98); }

        .countdown {
            font-size: 12px;
            color: var(--muted);
            margin-top: 14px;
        }

        .countdown span { color: var(--primary); font-weight: 600; }
    </style>
</head>
<body>
    <div class="card">
        <div class="icon-wrap">
            <div class="icon-ripple"></div>
            <div class="icon-circle">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="20 6 9 17 4 12"/>
                </svg>
            </div>
        </div>

        <h1 class="card-title">Password Updated!</h1>
        <p class="card-desc">Your password has been changed successfully. Please log in using your new password.</p>

        <a href="{{ url('http://localhost:3000/sign-in') }}" class="btn" id="login-btn">Log In Now</a>

        <p class="countdown">Redirecting automatically in <span id="counter">5</span> seconds...</p>
    </div>

    <script>
        let sec = 5;
        const counter = document.getElementById('counter');
        const timer = setInterval(() => {
            sec--;
            counter.textContent = sec;
            if (sec <= 0) {
                clearInterval(timer);
                window.location.href = "{{ url('http://localhost:3000/sign-in') }}";
            }
        }, 1000);
    </script>
</body>
</html>