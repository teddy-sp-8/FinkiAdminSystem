@extends('layouts.app')

@section('content')
    <style>
        .config-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 1rem;
        }

        .config-layout {
            display: flex;
            gap: 2rem;
            flex-wrap: wrap;
            margin-top: 1.5rem;
        }

        .config-sidebar {
            flex: 1;
            min-width: 320px;
        }

        .config-main {
            flex: 2;
            min-width: 450px;
        }

        .page-title {
            font-family: 'DM Serif Display', serif;
            font-size: 32px;
            color: var(--ink);
            line-height: 1.2;
            font-weight: 700;
        }

        .page-sub {
            font-size: 14px;
            color: var(--ink3);
            margin-top: 6px;
        }

        .config-card {
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.02);
        }

        .card-title {
            font-family: 'DM Serif Display', serif;
            font-size: 20px;
            color: var(--ink);
            margin-bottom: 1.5rem;
            font-weight: 600;
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-group label {
            display: block;
            font-size: 11px;
            font-weight: 600;
            color: var(--ink2);
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-bottom: 8px;
        }

        .form-control {
            width: 100%;
            background-color: var(--bg);
            border: 1px solid var(--line);
            color: var(--ink);
            border-radius: 8px;
            padding: 12px 14px;
            font-size: 14px;
            font-family: 'DM Sans', sans-serif;
            transition: all 0.2s ease;
            outline: none;
            box-sizing: border-box;
        }

        .form-control:focus {
            border-color: var(--accent-mid);
            background-color: var(--surface);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .form-hint {
            font-size: 11px;
            color: var(--ink3);
            margin-top: 6px;
        }

        .form-error {
            color: var(--red);
            font-size: 12px;
            margin-top: 6px;
            font-weight: 500;
        }

        .btn-royal {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            padding: 12px 24px;
            background: var(--accent) !important;
            border: 1px solid var(--accent) !important;
            border-radius: 8px !important;
            font-size: 13px;
            font-weight: 600;
            color: #ffffff;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: all 0.2s ease;
            cursor: pointer;
            margin-top: 0.5rem;
        }

        .btn-royal:hover {
            background: #1d4ed8 !important;
            border-color: #1d4ed8 !important;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.15);
        }

        .btn-delete {
            background: #fff0ec;
            color: var(--red);
            border: 1px solid #fee2e2;
            padding: 8px 14px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            font-family: 'DM Sans', sans-serif;
        }

        .btn-delete:hover {
            background: var(--red);
            color: white;
            border-color: var(--red);
        }

        .type-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px;
            border: 1px solid var(--line);
            border-radius: 12px;
            background: var(--surface);
            margin-bottom: 12px;
            transition: all 0.2s;
        }

        .type-row:hover {
            border-color: var(--accent-mid);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.01);
        }

        .type-info {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .type-name {
            font-size: 15px;
            font-weight: 600;
            color: var(--ink);
        }

        .type-meta-group {
            display: flex;
            gap: 12px;
            align-items: center;
        }

        .type-usage {
            font-size: 11px;
            color: var(--ink3);
            text-transform: uppercase;
            letter-spacing: 0.4px;
        }

        .badge-price {
            display: inline-block;
            font-size: 11px;
            font-weight: 700;
            padding: 2px 8px;
            background: rgba(37, 99, 235, 0.06);
            color: var(--accent);
            border-radius: 6px;
        }

        .badge-free {
            display: inline-block;
            font-size: 11px;
            font-weight: 700;
            padding: 2px 8px;
            background: rgba(34, 197, 94, 0.08);
            color: #16a34a;
            border-radius: 6px;
        }

        .alert {
            padding: 14px 18px;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .alert-success {
            background: var(--green-light, #f0fdf4);
            color: var(--green, #166534);
            border: 1px solid #bbf7d0;
        }

        .alert-error {
            background: #fff1f2;
            color: var(--red, #991b1b);
            border: 1px solid #fecdd3;
        }
    </style>

    <div class="config-container">
        <div style="margin-bottom: 2.5rem;">
            <h1 class="page-title">Менаџирање на барања</h1>
            <p class="page-sub">Управувајте со типовите на барања во административниот систем</p>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                <span>✨</span> {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-error">
                <span>⚠️</span> {{ session('error') }}
            </div>
        @endif

        <div class="config-layout">
            <div class="config-sidebar">
                <div class="config-card">
                    <h2 class="card-title">Нова Услуга</h2>

                    <form method="POST" action="{{ route('admin.service_config.store') }}">
                        @csrf

                        <div class="form-group">
                            <label for="name">Име на документот</label>
                            <input type="text" id="name" name="name" class="form-control"
                                   placeholder="на пр. Уверение за положени испити" required>
                            @error('name')
                            <p class="form-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="description">Краток опис</label>
                            <textarea id="description" name="description" required rows="3" class="form-control"
                                      placeholder="На пр: Се издава на барање на студентот за потребите на..."></textarea>
                        </div>

                        <div class="form-group">
                            <label for="price">Цена </label>
                            <input type="number" step="0.01" id="price" name="price" placeholder="0" class="form-control">                            <p class="form-hint">Внеси 0 ако е бесплатно</p>
                        </div>

                        <button type="submit" class="btn-royal">
                            Додади барање
                        </button>
                    </form>
                </div>
            </div>

            <div class="config-main">
                <div class="config-card" style="min-height: 250px;">
                    <h2 class="card-title">Достапни барања во системот ({{ $requestTypes->count() }})</h2>

                    @if($requestTypes->isEmpty())
                        <div style="text-align: center; color: var(--ink3); padding: 4rem 0;">
                            <p style="font-size: 14px; font-weight: 500;">Нема дефинирано ниту еден тип на барање.</p>
                        </div>
                    @else
                        @foreach($requestTypes as $type)
                            <div class="type-row">
                                <div class="type-info">
                                    <span class="type-name">{{ $type->name }}</span>
                                    <div class="type-meta-group">
                                        <span class="type-usage">
                                            Поднесено: <strong>{{ $type->administrative_requests_count }} пати</strong>
                                        </span>
                                        @if($type->price > 0)
                                            <span class="badge-price">{{ $type->price }} ден.</span>
                                        @else
                                            <span class="badge-free">Бесплатно</span>
                                        @endif
                                    </div>
                                </div>

                                <div>
                                    <form action="{{ route('admin.service_config.destroy', $type->id) }}" method="POST"
                                          onsubmit="return confirm('Дали сте сигурни дека сакате да го отстраните овој тип на документ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-delete">Отстрани</button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
