<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            margin: 0;
            padding: 30px;
        }

        .container {
            max-width: 600px;
            margin: auto;
            background: white;
            padding: 40px;
            border-radius: 12px;
            text-align: center;
        }

        .button {
            display: inline-block;
            padding: 14px 28px;
            background: #2563eb;
            color: white !important;
            text-decoration: none;
            border-radius: 8px;
            margin-top: 20px;
        }

        p {
            color: #555;
            line-height: 1.6;
        }
    </style>
</head>

<body>

<div class="container">
    <h1>Reset Password</h1>

    <p>
        Kami menerima permintaan untuk reset password akun Anda.
    </p>

    <p>
        Klik tombol di bawah untuk membuat password baru.
    </p>

    <a href="{{ $frontendUrl }}" class="button">
        Reset Password
    </a>

    <p>
        Link ini berlaku selama 60 menit.
    </p>

    <p>
        Jika Anda tidak meminta reset password, abaikan email ini.
    </p>
</div>

</body>
</html>