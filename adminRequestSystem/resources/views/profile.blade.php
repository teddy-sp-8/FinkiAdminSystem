@extends('layouts.app')

@section('content')

    <style>
        .page-title { font-family: 'DM Serif Display', serif; font-size: 28px; font-weight: 400; color: var(--ink); line-height: 1.2; }
        .page-sub   { font-size: 14px; color: var(--ink3); margin-top: 6px; }

        .form-card {
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: 14px;
            padding: 2rem;
            margin-top: 1.5rem;
        }

        .field { display: flex; flex-direction: column; gap: 7px; margin-bottom: 1.25rem; }
        .field label {
            font-size: 11px; font-weight: 600;
            color: var(--ink3); text-transform: uppercase; letter-spacing: .7px;
        }

        .field input[type="text"],
        .field input[type="email"],
        .field-static {
            width: 100%;
            background: var(--bg);
            border: 1px solid var(--line);
            border-radius: 8px;
            padding: 10px 14px;
            font-size: 14px;
            color: var(--ink);
            font-family: 'DM Sans', sans-serif;
            transition: border-color .15s, box-shadow .15s;
            outline: none;
        }

        .field input:focus {
            border-color: #93c5fd;
            box-shadow: 0 0 0 3px rgba(37,99,235,.1);
            background: var(--surface);
        }

        .field-static {
            background: var(--surface);
            color: var(--ink2);
            font-weight: 500;
            cursor: not-allowed;
        }

        .btn-submit {
            display: inline-flex; align-items: center; justify-content: center; gap: 8px;
            padding: 11px 24px;
            background: #2563eb;
            border: 1px solid #2563eb;
            border-radius: 8px;
            font-size: 13px; font-weight: 600;
            color: #fff;
            cursor: pointer; font-family: 'DM Sans', sans-serif;
            transition: all .15s;
            text-transform: uppercase; letter-spacing: .4px;
        }
        .btn-submit:hover {
            background: #1d4ed8;
            border-color: #1d4ed8;
            transform: translateY(-1px);
        }

        .alert-success {
            background: #eff6ff;
            border: 1px solid #93c5fd;
            color: #1e40af;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            font-size: 13px;
            font-weight: 500;
        }

        .error-text {
            color: #e11d48;
            font-size: 12px;
            margin-top: 2px;
        }
    </style>

    <div style="max-width: 640px; margin: 0 auto;">

        <div class="page-title"> Профил</div>
        <div class="page-sub">Погледнете ги деталите или ажурирајте ги вашите лични податоци</div>

        <div class="form-card">
            @if(session('status'))
                <div class="alert-success">
                    ✅ {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('profile.update') }}">
                @csrf
                @method('PATCH')

                <div class="field">
                    <label for="name">Име и Презиме</label>
                    <input type="text" id="name" name="name" value="{{ old('name', auth()->user()->name) }}" required>
                    @error('name')
                    <span class="error-text">⚠️ {{ $message }}</span>
                    @enderror
                </div>

                <div class="field" style="margin-top: 1.25rem;">
                    <label for="email">Email адреса</label>
                    <input type="email" id="email" name="email" value="{{ old('email', auth()->user()->email) }}" required>
                    @error('email')
                    <span class="error-text">⚠️ {{ $message }}</span>
                    @enderror
                </div>

                <div class="field" style="margin-top: 1.25rem; margin-bottom: 2rem;">
                    <label>Улога</label>
                    <div class="field-static">
                        @if(auth()->user()->is_admin ?? false)
                             Администратор
                        @else
                            Студент
                        @endif
                    </div>
                </div>

                <div style="text-align: right; border-top: 1px solid var(--line); padding-top: 1.5rem;">
                    <button type="submit" class="btn-submit">
                        Зачувај Промени
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsectionv
