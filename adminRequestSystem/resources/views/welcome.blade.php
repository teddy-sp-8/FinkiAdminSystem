@extends('layouts.app')

@section('content')

    <style>
        .page-title { font-family: 'DM Serif Display', serif; font-size: 28px; font-weight: 400; color: var(--ink); line-height: 1.2; }
        .page-sub   { font-size: 14px; color: var(--ink3); margin-top: 6px; }

        .section-label {
            font-size: 11px; font-weight: 600; color: var(--ink3);
            letter-spacing: .8px; text-transform: uppercase;
            margin-bottom: .75rem; margin-top: 2rem;
        }

        .auth-header-buttons {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            flex-shrink: 0;
            margin-top: 4px;
        }
        .auth-btn {
            display: inline-flex; align-items: center; justify-content: center;
            padding: 8px 16px; border-radius: 8px;
            font-size: 13px; font-weight: 600;
            text-decoration: none; transition: all .15s;
        }
        .auth-login {
            background: transparent; color: var(--ink2);
            border: 1px solid var(--line);
        }
        .auth-login:hover {
            border-color: #2563eb;
            color: #2563eb;
            background: #eff6ff;
        }
        .auth-register {
            background: #2563eb; color: #fff;
            border: 1px solid #2563eb;
        }
        .auth-register:hover {
            background: #1d4ed8; border-color: #1d4ed8;
            transform: translateY(-1px);
        }

        .cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
            gap: 14px;
        }

        .req-card {
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: 14px;
            padding: 1.25rem 1.4rem;
            display: flex;
            flex-direction: column;
            gap: 10px;
            transition: all .2s;
        }
        .req-card:hover {
            border-color: #93c5fd;
            box-shadow: 0 4px 20px rgba(37,99,235,.06);
            transform: translateY(-2px);
        }

        .card-top {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 10px;
        }

        .card-icon {
            width: 40px; height: 40px; flex-shrink: 0;
            background: #eff6ff;
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            color: #2563eb; font-size: 18px;
        }

        .badge-price {
            background: #dbeafe;
            color: #1e40af;
            font-size: 10px;
            font-weight: 700;
            padding: 3px 9px;
            border-radius: 20px;
            letter-spacing: .4px;
            text-transform: uppercase;
            flex-shrink: 0;
            margin-top: 2px;
        }

        .badge-free {
            background: #dcfce7;
            color: #166534;
            font-size: 10px;
            font-weight: 700;
            padding: 3px 9px;
            border-radius: 20px;
            letter-spacing: .4px;
            text-transform: uppercase;
            flex-shrink: 0;
            margin-top: 2px;
        }

        .card-name {
            font-size: 14px; font-weight: 600;
            color: var(--ink); line-height: 1.4;
            flex: 1;
        }

        .card-desc {
            font-size: 13px; color: var(--ink3);
            line-height: 1.55;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .card-btn {
            display: flex; align-items: center; justify-content: center; gap: 6px;
            padding: 8px 0;
            border: 1px solid var(--line);
            border-radius: 8px;
            font-size: 12px; font-weight: 600;
            color: var(--ink2);
            text-decoration: none;
            text-transform: uppercase; letter-spacing: .4px;
            transition: all .15s;
            margin-top: 4px;
        }
        .card-btn:hover {
            background: #2563eb;
            border-color: #2563eb;
            color: #fff;
        }
        .card-btn svg { transition: transform .15s; }
        .card-btn:hover svg { transform: translateX(3px); }

        .empty-state {
            background: var(--surface); border: 1px solid var(--line);
            border-radius: 14px; padding: 4rem 2rem;
            text-align: center; max-width: 420px; margin: 2rem auto;
        }
        .empty-icon {
            width: 48px; height: 48px; border-radius: 12px;
            background: #eff6ff;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 1rem; color: #2563eb; font-size: 22px;
        }
        .empty-title { font-size: 15px; font-weight: 600; color: var(--ink); margin-bottom: 6px; }
        .empty-sub   { font-size: 13px; color: var(--ink3); }
    </style>

    <div>
        <div style="display:flex; align-items:flex-start; justify-content:space-between; gap:1rem; flex-wrap:wrap; margin-bottom: 2rem;">
            <div>
                <div class="page-title">Барања и Документи</div>
                <div class="page-sub">Изберете го типот на документ или услуга што сакате да ја побарате</div>
            </div>

            @guest
                <div class="auth-header-buttons">
                    <a href="{{ route('login') }}" class="auth-btn auth-login">
                        Логирај се
                    </a>
                    <a href="{{ route('register') }}" class="auth-btn auth-register">
                        Регистрација
                    </a>
                </div>
            @endguest
        </div>

        @if($requestTypes->isEmpty())

            <div class="empty-state">
                <div class="empty-icon">
                    <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div class="empty-title">Нема достапни барања</div>
                <div class="empty-sub">Нема достапни типови на барања во моментов.</div>
            </div>

        @else

            <div class="section-label">Достапни услуги</div>

            <div class="cards-grid">
                @foreach($requestTypes as $type)
                    <div class="req-card">
                        <div class="card-top">
                            <div class="card-icon">
                                <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>

                            @if($type->price > 0)
                                <span class="badge-price">{{ number_format($type->price, 0) }} ден.</span>
                            @else
                                <span class="badge-free">Бесплатно</span>
                            @endif
                        </div>

                        <div class="card-name">{{ $type->name }}</div>

                        @if($type->description)
                            <div class="card-desc">{{ Str::limit($type->description, 90) }}</div>
                        @endif

                        @auth
                            <a href="{{ route('student.requests.create', ['type' => $type->id]) }}" class="card-btn">
                                Поднеси барање
                                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="card-btn" title="Мора да се најавите">
                                Најави се и поднеси
                                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        @endauth
                    </div>
                @endforeach
            </div>

        @endif
    </div>

@endsection
