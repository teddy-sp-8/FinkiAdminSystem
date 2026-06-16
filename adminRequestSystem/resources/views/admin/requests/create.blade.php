@extends('layouts.app')

@section('content')
    <style>
        .form-container {
            max-width: 650px;
            margin: 2rem auto;
        }

        .form-card {
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: 14px;
            padding: 2.5rem 2rem;
            box-shadow: 0 4px 20px rgba(37, 99, 235, 0.01);
        }

        .form-header {
            margin-bottom: 2rem;
        }

        .form-title {
            font-family: 'DM Serif Display', serif;
            font-size: 24px;
            color: var(--ink);
            margin-bottom: 0.25rem;
        }

        .form-subtitle {
            font-size: 13px;
            color: var(--ink3);
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            font-size: 11px;
            font-weight: 600;
            color: var(--ink2);
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 6px;
        }

        select, textarea {
            width: 100%;
            background-color: var(--bg) !important;
            border: 1px solid var(--line) !important;
            color: var(--ink) !important;
            border-radius: 8px !important;
            padding: 12px 14px !important;
            font-size: 14px !important;
            font-family: 'DM Sans', sans-serif;
            transition: all 0.15s ease !important;
            outline: none;
        }

        select:focus, textarea:focus {
            border-color: var(--accent-mid) !important;
            background-color: var(--surface) !important;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1) !important;
        }

        textarea {
            resize: vertical;
            min-height: 120px;
        }

        .error-text {
            color: var(--red);
            font-size: 12px;
            margin-top: 4px;
            font-weight: 500;
        }

        .form-actions {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 12px;
            margin-top: 2rem;
            border-top: 1px solid var(--line);
            padding-top: 1.5rem;
        }

        .btn-secondary {
            padding: 11px 20px;
            background: transparent;
            border: 1px solid var(--line);
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            color: var(--ink2);
            text-decoration: none;
            transition: all 0.15s;
        }

        .btn-secondary:hover {
            background: #f1f5f9;
            color: var(--ink);
        }

        .btn-royal {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 11px 24px;
            background: var(--accent) !important;
            border: 1px solid var(--accent) !important;
            border-radius: 8px !important;
            font-size: 13px !important;
            font-weight: 600 !important;
            color: #ffffff !important;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: all 0.15s ease;
            cursor: pointer;
        }

        .btn-royal:hover {
            background: #1d4ed8 !important;
            border-color: #1d4ed8 !important;
            transform: translateY(-1px);
        }
    </style>

    <div class="form-container">
        <div class="form-card">

            <div class="form-header">
                <h1 class="form-title">Креирај ново барање</h1>
                <p class="form-subtitle">Внесете ги потребните податоци за да поднесете ново барање во име на
                    студент.</p>
            </div>

            <form method="POST" action="{{ route('admin.requests.store') }}">
                @csrf

                <div class="form-group">
                    <label for="user_id">Избери Студент</label>
                    <select id="user_id" name="user_id" required>
                        <option value="" disabled selected>-- Избери студент --</option>
                        @foreach($students as $student)
                            <option value="{{ $student->id }}" {{ old('user_id') == $student->id ? 'selected' : '' }}>
                                {{ $student->name }} ({{ $student->email }})
                            </option>
                        @endforeach
                    </select>
                    @error('user_id')
                    <p class="error-text">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="request_type_id">Тип на документ</label>
                    <select id="request_type_id" name="request_type_id" required>
                        <option value="" disabled selected>-- Избери тип на барање --</option>
                        @foreach($requestTypes as $type)
                            <option value="{{ $type->id }}" {{ old('request_type_id') == $type->id ? 'selected' : '' }}>
                                {{ $type->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('request_type_id')
                    <p class="error-text">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="description">Опис на барањето</label>
                    <textarea id="description" name="description"
                              placeholder="Внесете ги деталите или причината за поднесување на ова барање..."
                              required>{{ old('description') }}</textarea>
                    @error('description')
                    <p class="error-text">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-actions">
                    <a href="{{ route('admin.requests.index') }}" class="btn-secondary">
                        Откажи
                    </a>
                    <button type="submit" class="btn-royal">
                        Зачувај и поднеси
                    </button>
                </div>

            </form>

        </div>
    </div>
@endsection
