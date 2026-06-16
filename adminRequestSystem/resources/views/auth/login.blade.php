<x-guest-layout>
    <style>
        body {
            background-color: #f8fafc !important;
            font-family: 'DM Sans', sans-serif !important;
        }

        .login-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 14px;
            padding: 2.5rem 2rem;
            box-shadow: 0 4px 20px rgba(37,99,235,0.02);
            max-width: 420px;
            margin: 4rem auto 0;
        }

        .auth-logo {
            width: 45px;
            height: 45px;
            margin: 0 auto 1.25rem auto;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .auth-logo img {
            height: 100%;
            width: auto;
            object-fit: contain;
        }

        .login-title {
            font-family: 'DM Serif Display', serif;
            font-size: 26px;
            color: #0f172a;
            margin-bottom: 0.25rem;
            text-align: center;
        }

        .login-subtitle {
            font-size: 13px;
            color: #64748b;
            margin-bottom: 2rem;
            text-align: center;
        }

        input[type="email"], input[type="password"] {
            background-color: #f8fafc !important;
            border: 1px solid #e2e8f0 !important;
            color: #0f172a !important;
            border-radius: 8px !important;
            padding: 10px 14px !important;
            font-size: 14px !important;
            transition: all 0.15s ease !important;
        }

        input[type="email"]:focus, input[type="password"]:focus {
            border-color: #93c5fd !important;
            background-color: #ffffff !important;
            box-shadow: 0 0 0 3px rgba(37,99,235,0.1) !important;
            outline: none !important;
        }

        label {
            font-size: 11px !important;
            font-weight: 600 !important;
            color: #64748b !important;
            text-transform: uppercase !important;
            letter-spacing: 0.6px !important;
            margin-bottom: 6px !important;
        }

        input[type="checkbox"] {
            border-color: #e2e8f0 !important;
            color: #2563eb !important;
        }
        input[type="checkbox"]:focus {
            --tw-ring-color: #2563eb !important;
        }

        .btn-royal {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            padding: 11px 24px;
            background: #2563eb !important;
            border: 1px solid #2563eb !important;
            border-radius: 8px !important;
            font-size: 13px !important;
            font-weight: 600 !important;
            color: #ffffff !important;
            text-transform: uppercase !important;
            letter-spacing: 0.5px !important;
            transition: all 0.15s ease !important;
            cursor: pointer;
            margin-top: 1.5rem;
        }

        .btn-royal:hover {
            background: #1d4ed8 !important;
            border-color: #1d4ed8 !important;
            transform: translateY(-1px);
        }

        .forgot-link, .register-link {
            font-size: 13px;
            color: #64748b;
            text-decoration: none;
            transition: color 0.15s;
        }
        .forgot-link:hover, .register-link:hover {
            color: #2563eb;
        }
    </style>

    <div class="login-card">

        <div class="auth-logo">
            <img src="{{ asset('images/img.png') }}" alt="Лого">
        </div>

        <div class="login-title">Најава на Систем</div>
        <div class="login-subtitle">Внесете ги вашите податоци за пристап до порталот</div>

        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div>
                <x-input-label for="email" value="Е-маил адреса" />
                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div class="mt-4">
                <x-input-label for="password" value="Лозинка" />
                <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div class="flex items-center justify-between mt-4">
                <label for="remember_me" class="inline-flex items-center" style="text-transform: none !important; letter-spacing: normal !important; font-weight: 500 !important; font-size: 13px !important; margin-bottom: 0 !important; color: #334155 !important;">
                    <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500" name="remember">
                    <span class="ms-2">Запомни ме</span>
                </label>

                @if (Route::has('password.request'))
                    <a class="forgot-link" href="{{ route('password.request') }}">
                        Ја заборавивте лозинката?
                    </a>
                @endif
            </div>

            <button type="submit" class="btn-royal">
                Најави се
            </button>

            @if (Route::has('register'))
                <div class="text-center mt-4">
                    <a class="register-link" href="{{ route('register') }}">
                        Немате профил? Регистрирајте се тука
                    </a>
                </div>
            @endif
        </form>

    </div>
</x-guest-layout>
