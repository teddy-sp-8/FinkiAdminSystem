<!DOCTYPE html>
<html lang="mk" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }}</title>

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
            --accent-mid: #93c5fd;

            --red: #e11d48;
        }

        body {
            font-family: 'DM Sans', system-ui, sans-serif;
            background: var(--bg);
            color: var(--ink);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            -webkit-font-smoothing: antialiased;
        }

        .nav {
            background: var(--surface);
            border-bottom: 1px solid var(--line);
            height: 68px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 2rem;
            position: sticky;
            top: 0;
            z-index: 50;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.03);
        }

        .nav-brand {
            display: flex;
            align-items: center;
            gap: 14px;
            text-decoration: none;
        }

        .brand-logo-container img {
            height: 42px;
            width: auto;
            object-fit: contain;
        }

        .brand-text {
            display: flex;
            flex-direction: column;
            line-height: 1.1;
        }

        .brand-name {
            font-size: 16px;
            font-weight: 700;
            color: var(--ink);
            letter-spacing: -0.3px;
        }

        .brand-sub {
            font-size: 11px;
            font-weight: 500;
            color: var(--ink3);
        }

        .nav-links {
            display: flex;
            gap: 6px;
            background: #f1f5f9;
            padding: 5px;
            border-radius: 12px;
        }

        .nav-link {
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 13.5px;
            font-weight: 600;
            color: var(--ink2);
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .nav-link:hover {
            background: #e0e7ff;
            color: #1e40af;
        }

        .nav-link.active {
            background: #2563eb;
            color: white;
            box-shadow: 0 2px 4px rgba(37, 99, 235, 0.2);
        }

        .nav-right {
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .user-pill {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 6px 12px;
            border-radius: 10px;
            transition: background 0.2s;
        }

        .user-pill:hover {
            background: #f8fafc;
        }

        .user-avatar {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            background: var(--accent-light);
            border: 2px solid var(--accent-mid);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 700;
            color: var(--accent);
            flex-shrink: 0;
        }

        .user-info {
            display: flex;
            flex-direction: column;
            line-height: 1.1;
        }

        .user-name {
            font-size: 13.5px;
            font-weight: 600;
            color: var(--ink);
        }

        .user-role {
            font-size: 11px;
            font-weight: 500;
            color: var(--ink3);
        }

        .logout-btn {
            padding: 8px 16px;
            border-radius: 8px;
            border: 1px solid #fecdd3;
            background: #fff1f2;
            font-size: 13px;
            font-weight: 600;
            color: var(--red);
            cursor: pointer;
            transition: all 0.2s;
        }

        .logout-btn:hover {
            background: #fee2e2;
            border-color: #f43f5e;
            color: #be123c;
        }

        .main-content {
            max-width: 1280px;
            width: 100%;
            margin: 0 auto;
            padding: 2.5rem 2rem;
            flex: 1;
        }
    </style>
</head>
<body>

<nav class="nav">
    <a href="@auth{{ auth()->user()->is_admin ? route('admin.dashboard_stats') : url('/') }}@else{{ url('/') }}@endauth"
       class="nav-brand">

        <div class="brand-logo-container">
            <img src="{{ asset('images/img.png') }}" alt="FINKI Logo">
        </div>

        <div class="brand-text">
            <div class="brand-name">
                @auth
                    {{ auth()->user()->is_admin ? 'Административен портал за документи' : config('app.name') }}
                @else
                    {{ config('app.name') }}
                @endauth
            </div>
            <div class="brand-sub">
                @auth
                    {{ auth()->user()->is_admin ? 'Администраторски портал' : 'Студентски Портал' }}
                @else
                    Студентски Портал
                @endauth
            </div>
        </div>
    </a>

    @auth
        <div class="nav-links">
            @if(auth()->user()->is_admin ?? false)
                <a href="{{ route('admin.dashboard_stats') }}"
                   class="nav-link {{ request()->routeIs('admin.dashboard_stats') ? 'active' : '' }}">
                    📊 Аналитика
                </a>
                <a href="{{ route('admin.requests.index') }}"
                   class="nav-link {{ request()->routeIs('admin.requests.index') ? 'active' : '' }}">
                    Сите Барања
                </a>
                <a href="{{ route('admin.students.index') }}"
                   class="nav-link {{ request()->routeIs('admin.students.*') ? 'active' : '' }}">
                    Студенти
                </a>
                <a href="{{ route('admin.service_config.index') }}"
                   class="nav-link {{ request()->routeIs('admin.service_config.*') ? 'active' : '' }}">
                    ⚙️ Услуги
                </a>
            @else
                <a href="{{ route('student.requests.index') }}"
                   class="nav-link {{ request()->routeIs('student.requests.*') ? 'active' : '' }}">
                    Мои Барања
                </a>
            @endif

            <a href="{{ route('profile.edit') }}"
               class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                Мој Профил
            </a>
        </div>
    @endauth

    @auth
        <div class="nav-right">
            <div class="user-pill">
                <div class="user-avatar">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}{{ strtoupper(substr(strstr(auth()->user()->name, ' ') ?: '', 1, 1)) }}
                </div>
                <div class="user-info">
                    <div class="user-name">{{ auth()->user()->name }}</div>
                    <div class="user-role">
                        {{ (auth()->user()->is_admin ?? false) ? 'Администратор' : 'Студент' }}
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-btn">Одјави се</button>
            </form>
        </div>
    @endauth
</nav>

<main class="main-content">
    @yield('content')
</main>

</body>
</html>
