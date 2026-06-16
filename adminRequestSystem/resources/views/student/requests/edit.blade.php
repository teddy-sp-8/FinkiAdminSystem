@extends('layouts.app')

@section('content')

    <style>
        .page-title { font-family: 'DM Serif Display', serif; font-size: 28px; font-weight: 400; color: var(--ink); line-height: 1.2; }
        .page-sub   { font-size: 14px; color: var(--ink3); margin-top: 6px; }

        .back-link {
            display: inline-flex; align-items: center; gap: 6px;
            font-size: 13px; font-weight: 500; color: var(--ink3);
            text-decoration: none; margin-bottom: 1.5rem;
            transition: color .15s;
        }
        .back-link:hover { color: var(--accent); }

        .card {
            background: var(--surface); border: 1px solid var(--line);
            border-radius: 14px; padding: 1.5rem 1.75rem; margin-top: 1.5rem;
        }

        .field { display: flex; flex-direction: column; gap: 7px; margin-bottom: 1.25rem; }
        .field label { font-size: 11px; font-weight: 600; color: var(--ink3); text-transform: uppercase; letter-spacing: .7px; }

        .field select,
        .field textarea {
            width: 100%; background: var(--bg); border: 1px solid var(--line);
            border-radius: 8px; padding: 10px 14px; font-size: 14px; color: var(--ink);
            font-family: 'DM Sans', sans-serif; transition: all .15s; outline: none;
        }
        .field select:focus,
        .field textarea:focus {
            border-color: var(--accent-mid); background: var(--surface);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }
        .field select {
            appearance: none; -webkit-appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%238888a8' stroke-width='2.5'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
            background-repeat: no-repeat; background-position: right 14px center;
            padding-right: 36px; cursor: pointer;
        }
        .field textarea { resize: vertical; min-height: 140px; line-height: 1.6; }

        .btn-submit {
            display: inline-flex; align-items: center; justify-content: center;
            padding: 10px 24px; background: var(--accent); border: 1px solid var(--accent);
            border-radius: 8px; font-size: 13px; font-weight: 600; color: #fff;
            cursor: pointer; transition: all .15s; text-transform: uppercase; letter-spacing: .4px;
        }
        .btn-submit:hover { background: #1d4ed8; border-color: #1d4ed8; transform: translateY(-1px); }
    </style>

    <div style="max-width: 640px; margin: 0 auto;">

        <a href="{{ route('student.requests.index') }}" class="back-link">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
            Назад кон моите барања
        </a>

        <div class="page-title">Промени го барањето</div>
        <div class="page-sub">Направете ги потребните измени на вашето поднесено барање</div>

        @if($errors->any())
            <div style="background: #fff0ec; border: 1px solid #ffcdc2; color: var(--red); padding: 12px; border-radius: 8px; margin-top: 1rem; font-size: 13px;">
                <strong style="display:block; margin-bottom: 4px;">⚠️ Настана грешка при ажурирањето:</strong>
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if($request->admin_note)
            <div style="background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 12px; padding: 16px; margin-top: 1.5rem; box-shadow: 0 1px 2px rgba(37,99,235,0.03);">
                <div style="font-weight: 600; font-size: 13px; color: #1e40af; margin-bottom: 6px; display: flex; align-items: center; gap: 6px;">
                    <span>💡 Барање за корекција од администрацијата:</span>
                </div>
                <div style="font-size: 13px; line-height: 1.5; color: #1e3a8a; background: #ffffff; padding: 12px; border-radius: 8px; border: 1px solid #93c5fd;">
                    "${{ $request->admin_note }}"
                </div>
            </div>
        @endif

        <div class="card">
            <form method="POST" action="{{ route('student.requests.update', $request->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="field">
                    <label for="request_type_id">Тип на документ / услуга</label>

                    <select id="request_type_id" disabled style="background: #e2e8f0; cursor: not-allowed; color: var(--ink3);">
                        @foreach($requestTypes as $type)
                            <option value="{{ $type->id }}" {{ $request->request_type_id == $type->id ? 'selected' : '' }}>
                                {{ $type->name }}
                            </option>
                        @endforeach
                    </select>

                    <input type="hidden" name="request_type_id" value="{{ $request->request_type_id }}">

                    <p style="font-size: 11px; color: var(--ink3); margin-top: 2px;">
                        * Типот на барањето не може да се менува откако е веќе поднесено.
                    </p>
                </div>
                <div class="field">
                    <label for="description">Детален опис на барањето</label>
                    <textarea name="description" id="description" placeholder="Образложете ја вашата потреба тука...">{{ old('description', $request->description) }}</textarea>
                </div>

                <div class="field" style="margin-top: 1.25rem;">
                    <label for="student_attachment">Прикачен документ / Слика</label>

                    @if($request->student_attachment)
                        <div style="margin-bottom: 10px; padding: 8px 12px; background: var(--bg); border: 1px solid var(--line); border-radius: 6px; font-size: 13px;">
                            <span>Тековно прикачен документ: </span>
                            <a href="{{ asset('storage/' . $request->student_attachment) }}" target="_blank" style="color: var(--accent); font-weight: 500; text-decoration: none;">
                                Прегледај го документот ↗
                            </a>
                        </div>
                    @endif

                    <p style="font-size: 12px; color: var(--ink3); margin-top: 4px; margin-bottom: 8px;">
                        Изберете нова слика или PDF доколку сакате да го замените тековниот документ.
                    </p>

                    <input type="file" name="student_attachment" id="student_attachment" accept="image/*,.pdf"
                           style="width:100%; padding:10px; background:var(--bg); border:1px solid var(--line); border-radius:8px; font-family:inherit;">
                </div>

                <div style="margin-top: 1.5rem; padding-top: 1rem; border-top: 1px solid var(--line);">
                    <button type="submit" class="btn-submit">Зачувај Измени</button>
                </div>

            </form>
        </div>
    </div>
@endsection
