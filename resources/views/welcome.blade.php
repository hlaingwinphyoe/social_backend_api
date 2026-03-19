<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Social API</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --bg: #090b10;
            --card-glass: rgba(255, 255, 255, 0.03);
            --primary: #10b981; /* Emerald/Cactus green */
            --primary-glow: rgba(16, 185, 129, 0.15);
            --text: #f8fafc;
            --text-dim: #94a3b8;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: 'Outfit', sans-serif;
            background-color: var(--bg);
            color: var(--text);
            overflow-x: hidden;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            position: relative;
        }

        main {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            position: relative;
            z-index: 10;
        }

        .card {
            background: var(--card-glass);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.06);
            border-radius: 2rem;
            padding: 4rem 3rem;
            max-width: 640px;
            width: 100%;
            text-align: center;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            animation: fadeIn 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        h1 {
            font-size: 3.5rem;
            font-weight: 800;
            margin: 0 0 1rem 0;
            letter-spacing: -0.03em;
            line-height: 1.1;
        }

        h1 span {
            background: linear-gradient(135deg, #10b981, #059669);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .btn-primary {
            display: inline-flex;
            align-items: center;
            gap: 0.625rem;
            background: #10b981;
            color: #062e1e;
            text-decoration: none;
            padding: 1rem 2.25rem;
            border-radius: 1rem;
            font-weight: 800;
            font-size: 1.125rem;
            transition: all 0.25s ease;
            box-shadow: 0 4px 20px rgba(16, 185, 129, 0.25);
        }

        .btn-primary:hover {
            background: #34d399;
            transform: translateY(-2px);
            box-shadow: 0 6px 25px rgba(16, 185, 129, 0.4);
        }

        .btn-primary svg {
            width: 1.25rem;
            height: 1.25rem;
            transition: transform 0.2s ease;
        }

        .btn-primary:hover svg {
            transform: translateX(3px);
        }
    </style>
</head>
<body>
    <main>
        <div class="card">
            <h1>Social <span>API</span></h1>
            <br>

            <a href="{{ url('/docs/api') }}" class="btn-primary">
                Explore Documentation
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M3 10a.75.75 0 01.75-.75h10.638L10.23 5.29a.75.75 0 111.04-1.08l5.5 5.25a.75.75 0 010 1.08l-5.5 5.25a.75.75 0 11-1.04-1.08l4.158-3.96H3.75A.75.75 0 013 10z" clip-rule="evenodd" />
                </svg>
            </a>
        </div>
    </main>
</body>
</html>
