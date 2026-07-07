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
            background: #2a85ff;
            color: white !important;
            text-decoration: none;
            border-radius: 8px;
            margin-top: 20px;
            font-weight: bold;
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
        We received a request to reset your account password.
    </p>

    <p>
        Click the button below to create a new password.
    </p>

    <a href="{{ $frontendUrl }}" class="button">
        Reset Password
    </a>

    <p>
        This link is valid for 60 minutes.
    </p>

    <p>
        If you did not request a password reset, please ignore this email.
    </p>
</div>

</body>
</html>