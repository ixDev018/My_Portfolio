<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>CMS Login | Admin Portal</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Outfit:wght@600;700;800&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', sans-serif;
            background: #EDEAE0;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            position: relative;
            overflow: hidden;
        }

        /* Decorative warm blobs */
        .blob {
            position: fixed;
            border-radius: 50%;
            filter: blur(90px);
            pointer-events: none;
            z-index: 0;
        }
        .blob-1 {
            width: 480px; height: 480px;
            background: rgba(104, 41, 170, 0.08);
            top: -120px; right: -100px;
        }
        .blob-2 {
            width: 360px; height: 360px;
            background: rgba(255, 107, 0, 0.06);
            bottom: -80px; left: -80px;
        }
        .blob-3 {
            width: 240px; height: 240px;
            background: rgba(77, 217, 240, 0.05);
            top: 40%; left: 35%;
        }

        /* Decorative grid lines */
        .grid-bg {
            position: fixed; inset: 0; z-index: 0; pointer-events: none;
            background-image:
                linear-gradient(rgba(104,41,170,0.04) 1px, transparent 1px),
                linear-gradient(90deg, rgba(104,41,170,0.04) 1px, transparent 1px);
            background-size: 48px 48px;
        }

        /* Card */
        .login-card {
            position: relative; z-index: 1;
            width: 100%; max-width: 420px;
            background: #ffffff;
            border: 1px solid #D8D4C8;
            border-radius: 1.5rem;
            padding: 2.5rem 2.25rem;
            box-shadow:
                0 1px 2px rgba(0,0,0,0.04),
                0 8px 32px rgba(0,0,0,0.08),
                0 24px 64px rgba(104,41,170,0.06);
        }

        /* Logo mark */
        .logo-mark {
            width: 52px; height: 52px;
            border-radius: 0.875rem;
            background: linear-gradient(135deg, #6829AA, #4dd9f0);
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 1.5rem;
            box-shadow: 0 6px 20px rgba(104,41,170,0.3);
        }

        /* Header text */
        .login-title {
            font-family: 'Outfit', sans-serif;
            font-size: 1.6rem; font-weight: 800;
            color: #1a1207; letter-spacing: -0.02em;
            text-align: center; margin-bottom: 0.35rem;
        }
        .login-subtitle {
            font-family: 'Space Mono', monospace;
            font-size: 0.62rem; text-transform: uppercase;
            letter-spacing: 0.12em; color: #9B9589;
            text-align: center; margin-bottom: 2rem;
        }

        /* Error box */
        .auth-error {
            padding: 0.85rem 1rem;
            background: #FFF1F1; border: 1px solid #FECACA;
            border-radius: 0.65rem; color: #dc2626;
            font-size: 0.8rem; font-weight: 600;
            margin-bottom: 1.25rem;
        }

        /* Form fields */
        .field { margin-bottom: 1.1rem; }
        .field label {
            display: block;
            font-family: 'Space Mono', monospace;
            font-size: 0.58rem; text-transform: uppercase;
            letter-spacing: 0.1em; color: #9B9589;
            margin-bottom: 0.4rem;
        }
        .field input {
            width: 100%;
            background: #FAFAF8;
            border: 1px solid #D8D4C8;
            border-radius: 0.6rem;
            padding: 0.7rem 1rem;
            color: #1a1207; font-size: 0.875rem;
            font-family: 'Inter', sans-serif;
            outline: none;
            transition: border-color 0.18s, box-shadow 0.18s;
        }
        .field input:focus {
            border-color: #6829AA;
            box-shadow: 0 0 0 3px rgba(104,41,170,0.12);
            background: #fff;
        }
        .field input::placeholder { color: #B0A99F; }
        .field-err {
            font-size: 0.72rem; color: #dc2626;
            font-weight: 600; margin-top: 0.3rem;
        }

        /* Submit button */
        .btn-signin {
            width: 100%;
            padding: 0.85rem;
            background: linear-gradient(135deg, #6829AA, #4dd9f0);
            color: #fff; border: none; border-radius: 0.75rem;
            font-size: 0.9rem; font-weight: 700;
            font-family: 'Outfit', sans-serif;
            cursor: pointer;
            box-shadow: 0 4px 20px rgba(104,41,170,0.3);
            transition: all 0.18s;
            letter-spacing: 0.02em;
        }
        .btn-signin:hover {
            background: linear-gradient(135deg, #5720A0, #3dc8de);
            box-shadow: 0 6px 24px rgba(104,41,170,0.38);
            transform: translateY(-1px);
        }
        .btn-signin:active { transform: scale(0.99); }

        /* Divider */
        .divider {
            height: 1px; background: #E2DDD3;
            margin: 1.5rem 0;
        }

        /* Back link */
        .back-link {
            display: block; text-align: center;
            font-family: 'Space Mono', monospace;
            font-size: 0.62rem; text-transform: uppercase;
            letter-spacing: 0.08em; color: #B0A99F;
            text-decoration: none;
            transition: color 0.15s;
        }
        .back-link:hover { color: #6829AA; }

        /* Watermark label */
        .card-watermark {
            position: absolute;
            top: 1.1rem; right: 1.25rem;
            font-family: 'Space Mono', monospace;
            font-size: 0.5rem; text-transform: uppercase;
            letter-spacing: 0.14em; color: #D8D4C8;
            user-select: none;
        }
    </style>
</head>
<body>

    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>
    <div class="blob blob-3"></div>
    <div class="grid-bg"></div>

    <div class="login-card">
        <span class="card-watermark">CMS v1</span>

        <!-- Logo mark -->
        <div class="logo-mark">
            <svg style="width:24px;height:24px;color:#fff;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
            </svg>
        </div>

        <!-- Header -->
        <h1 class="login-title">Admin Login</h1>
        <p class="login-subtitle">Authenticate to manage your portfolio</p>

        <!-- Form -->
        <form action="{{ route('admin.login.submit') }}" method="POST">
            @csrf

            @error('auth')
                <div class="auth-error">{{ $message }}</div>
            @enderror

            <div class="field">
                <label for="username">Username</label>
                <input type="text" name="username" id="username"
                       required autofocus autocomplete="username"
                       placeholder="Enter your username">
                @error('username')
                    <p class="field-err">{{ $message }}</p>
                @enderror
            </div>

            <div class="field" style="margin-bottom:1.5rem;">
                <label for="password">Password</label>
                <input type="password" name="password" id="password"
                       required autocomplete="current-password"
                       placeholder="••••••••">
                @error('password')
                    <p class="field-err">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="btn-signin">Sign In</button>
        </form>

        <div class="divider"></div>

        <a href="{{ route('portfolio.index') }}" class="back-link">
            ← Back to visitor website
        </a>
    </div>

</body>
</html>
