<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Outfit', sans-serif;
            height: 100vh;
            background: linear-gradient(135deg, #0b1628 0%, #1a2a4a 30%, #162033 60%, #0b1628 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
            overflow: hidden;
            position: relative;
        }

        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background:
                radial-gradient(2px 2px at 20% 30%, rgba(6, 182, 212, 0.3), transparent),
                radial-gradient(2px 2px at 40% 70%, rgba(245, 158, 11, 0.2), transparent),
                radial-gradient(2px 2px at 60% 20%, rgba(239, 68, 68, 0.2), transparent),
                radial-gradient(2px 2px at 80% 80%, rgba(6, 182, 212, 0.2), transparent),
                radial-gradient(1px 1px at 10% 90%, rgba(255, 255, 255, 0.3), transparent),
                radial-gradient(1px 1px at 90% 10%, rgba(255, 255, 255, 0.3), transparent),
                radial-gradient(1px 1px at 50% 50%, rgba(255, 255, 255, 0.2), transparent);
            pointer-events: none;
            z-index: 0;
        }

        .card {
            background: #ffffff;
            border-radius: 32px;
            padding: 44px 48px 40px;
            max-width: 480px;
            width: 100%;
            text-align: center;
            box-shadow: 0 25px 60px -16px rgba(0, 0, 0, 0.5);
            animation: fadeUp 0.6s ease-out;
            position: relative;
            z-index: 10;
        }

        .card::before {
            content: '';
            position: absolute;
            top: -1px;
            left: 50%;
            transform: translateX(-50%);
            width: 60%;
            height: 2px;
            background: linear-gradient(90deg, transparent, #06b6d4, transparent);
            border-radius: 2px;
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(24px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .scene {
            width: 200px;
            height: 160px;
            margin: 0 auto 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .scene img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        .code {
            font-size: 112px;
            font-weight: 800;
            background: linear-gradient(135deg, #1e3a5f, #06b6d4);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            line-height: 1;
            letter-spacing: -4px;
        }

        .title {
            font-size: 22px;
            font-weight: 700;
            color: #0f172a;
            margin-top: 8px;
        }

        .message {
            font-size: 15px;
            color: #64748b;
            margin-top: 10px;
            line-height: 1.6;
            max-width: 340px;
            margin-left: auto;
            margin-right: auto;
        }

        .actions {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            margin-top: 28px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 28px;
            background: #0f172a;
            color: #fff;
            font-size: 14px;
            font-weight: 600;
            border-radius: 14px;
            text-decoration: none;
            transition: all 0.25s ease;
            border: none;
            cursor: pointer;
        }

        .btn:hover {
            background: #1e3a5f;
            transform: translateY(-2px);
            box-shadow: 0 8px 25px -8px rgba(15, 23, 42, 0.3);
        }

        .btn svg { width: 16px; height: 16px; transition: transform 0.25s ease; }
        .btn:hover svg { transform: translateX(-3px); }

        .bg-emoji {
            position: fixed;
            inset: 0;
            pointer-events: none;
            z-index: 0;
            overflow: hidden;
        }

        .bg-emoji .e {
            position: absolute;
            animation-timing-function: ease-in-out;
            animation-iteration-count: infinite;
        }

        @keyframes driftA {
            0%, 100% { transform: translateY(0) rotate(0deg) scale(1); }
            25% { transform: translateY(-18px) rotate(8deg) scale(1.05); }
            75% { transform: translateY(10px) rotate(-5deg) scale(0.95); }
        }
        @keyframes driftB {
            0%, 100% { transform: translateY(0) translateX(0) scale(1); }
            50% { transform: translateY(-15px) translateX(8px) scale(1.08); }
        }
        @keyframes driftC {
            0%, 100% { transform: translate(0, 0) rotate(0deg); }
            33% { transform: translate(12px, -14px) rotate(6deg); }
            66% { transform: translate(-8px, 8px) rotate(-4deg); }
        }

        .brand {
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 12px;
            color: #64748b;
            letter-spacing: 0.5px;
            z-index: 10;
            opacity: 0.6;
        }
    </style>
    @stack('styles')
</head>
<body>
    @hasSection('animation')
        <div class="bg-emoji">
            @yield('animation')
        </div>
    @else
        <div class="bg-emoji">
            <span class="e" style="font-size:28px; opacity:0.12; top:4%; left:3%; animation: driftA 8s infinite;">✦</span>
            <span class="e" style="font-size:20px; opacity:0.1; top:8%; right:6%; animation: driftB 10s infinite;">⬡</span>
            <span class="e" style="font-size:32px; opacity:0.08; top:25%; left:1%; animation: driftC 7s infinite;">✦</span>
            <span class="e" style="font-size:18px; opacity:0.12; top:40%; right:2%; animation: driftA 9s infinite;">⟡</span>
            <span class="e" style="font-size:24px; opacity:0.1; bottom:30%; left:2%; animation: driftB 11s infinite;">⬡</span>
            <span class="e" style="font-size:36px; opacity:0.07; bottom:12%; right:4%; animation: driftC 8s infinite;">✦</span>
            <span class="e" style="font-size:22px; opacity:0.12; bottom:4%; left:8%; animation: driftA 10s infinite;">⟡</span>
            <span class="e" style="font-size:16px; opacity:0.1; top:60%; left:5%; animation: driftB 7.5s infinite;">⬡</span>
            <span class="e" style="font-size:30px; opacity:0.08; top:15%; left:50%; animation: driftC 12s infinite;">✦</span>
            <span class="e" style="font-size:20px; opacity:0.12; bottom:40%; right:1%; animation: driftA 8.5s infinite;">⟡</span>
            <span class="e" style="font-size:26px; opacity:0.07; top:55%; right:8%; animation: driftB 9.5s infinite;">⬡</span>
            <span class="e" style="font-size:34px; opacity:0.08; bottom:20%; left:12%; animation: driftC 7s infinite;">✦</span>
            <span class="e" style="font-size:18px; opacity:0.1; top:70%; left:50%; animation: driftA 11s infinite;">⟡</span>
            <span class="e" style="font-size:24px; opacity:0.12; top:2%; left:25%; animation: driftB 6s infinite;">⬡</span>
            <span class="e" style="font-size:28px; opacity:0.08; bottom:3%; left:50%; animation: driftC 9s infinite;">✦</span>
        </div>
    @endif

    @stack('bg-styles')

    <div class="card">
        <div class="scene">
            <img src="@yield('image')" alt="">
        </div>

        <div class="code">@yield('code')</div>
        <div class="title">@yield('title')</div>
        <div class="message">@yield('message')</div>

        <div class="actions">
            <a href="{{ route('login') }}" class="btn">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="15 18 9 12 15 6"></polyline>
                </svg>
                Back to Home
            </a>
        </div>
    </div>

    <p class="brand">E-Commerce Management System</p>
</body>
</html>
