<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="icon" type="image/png" href="{{ asset('images/img.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600&family=DM+Serif+Display&display=swap"
          rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --ink: #0f172a;
            --ink2: #334155;
            --ink3: #64748b;
            --surface: #ffffff;
            --bg: #f8fafc;
            --line: #e2e8f0;
            --accent: #2563eb;
            --accent-light: #eff6ff;
            --red: #e11d48;
        }

        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--bg);
            color: var(--ink);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
            -webkit-font-smoothing: antialiased;
        }

        .guest-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            margin-bottom: 1.5rem;
        }

        .brand-logo-container {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .brand-logo-container img {
            height: 100%;
            width: auto;
            object-fit: contain;
        }

        .brand-sub {
            font-size: 12px;
            color: var(--ink3);
            font-weight: 500;
        }

        .guest-card {
            width: 100%;
            max-width: 440px;
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: 16px;
            padding: 2.5rem 2rem;
            box-shadow: 0 4px 24px rgba(37, 99, 235, 0.02);
        }
    </style>
</head>
<body>

<a href="/" class="guest-brand">
    <div class="brand-logo-container">
        <img src="{{ asset('images/img.png') }}" alt="Лого на Универзитетот">
    </div>
    <div>
        <div class="brand-sub">Студентски Портал за Документи</div>
    </div>
</a>

<div class="guest-card">
    {{ $slot }}
</div>

</body>
</html>
