@extends('layouts.app')

@section('content')
    <style>
        .page-title {
            font-family: 'DM Serif Display', serif;
            font-size: 28px;
            color: var(--ink);
        }

        .card {
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: 14px;
            padding: 2rem;
            margin-top: 1.5rem;
        }

        .section-label {
            font-size: 11px;
            font-weight: 600;
            color: var(--ink3);
            text-transform: uppercase;
            letter-spacing: .7px;
            margin-bottom: 0.5rem;
        }

        .desc-box {
            background: var(--bg);
            border: 1px solid var(--line);
            border-radius: 8px;
            padding: 14px;
            font-size: 14px;
            color: var(--ink2);
            line-height: 1.6;
        }

        .field {
            display: flex;
            flex-direction: column;
            gap: 6px;
            margin-bottom: 1.25rem;
        }

        .field label {
            font-size: 11px;
            font-weight: 600;
            color: var(--ink3);
            text-transform: uppercase;
        }

        .field select, .field textarea {
            width: 100%;
            background: var(--bg);
            border: 1px solid var(--line);
            border-radius: 8px;
            padding: 10px;
            font-size: 14px;
        }

        .btn-save {
            background: var(--accent);
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            text-transform: uppercase;
        }

        .pill-info {
            background: var(--accent-light);
            color: var(--accent);
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 500;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
    </style>

    <div style="max-width: 700px; margin: 0 auto;">
        <div class="page-title">Преглед на Барање #{{ $request->id }}</div>
        <p style="color: var(--ink3);">Поднесено од: <strong>{{ $request->user->name }}</strong> ({{ $request->user->email }})</p>

        <div class="card">

            <div class="field">
                <div class="section-label">Опис на студентот</div>
                <div class="desc-box">{{ $request->description }}</div>
            </div>

            @if($request->student_attachment)
                <div style="margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px dashed var(--line); margin-bottom: 1.5rem;">
                    <div class="section-label">Прикачени документи</div>
                    @php
                        $ext = pathinfo($request->student_attachment, PATHINFO_EXTENSION);
                        $isImage = in_array(strtolower($ext), ['jpg', 'jpeg', 'png', 'gif']);
                    @endphp

                    @if($isImage)
                        <div style="border: 1px solid var(--line); border-radius: 8px; overflow: hidden; background: var(--bg); max-width: 320px;">
                            <div style="padding: 8px 12px; font-size: 11px; font-weight: 600; text-transform: uppercase; color: var(--ink3); background: var(--surface); border-bottom: 1px solid var(--line); display:flex; justify-content:space-between;">
                                <span>Слика (.{{ $ext }})</span>
                                <a href="{{ asset('storage/' . $request->student_attachment) }}" target="_blank" style="color: var(--accent); text-decoration:none;">Отвори цела ↗</a>
                            </div>
                            <div style="padding: 10px; text-align: center;">
                                <img src="{{ asset('storage/' . $request->student_attachment) }}" style="max-width: 100%; max-height: 200px; border-radius: 4px; object-fit: contain;">
                            </div>
                        </div>
                    @else
                        <a href="{{ asset('storage/' . $request->student_attachment) }}" target="_blank" class="pill-info">
                            Прегледај го PDF документот ↗
                        </a>
                    @endif
                </div>
            @endif

            @if($request->ai_feedback)
                <div style="background: #f0f7ff; border: 1px solid #b9ddff; border-radius: 12px; padding: 1.5rem; margin-top: 1.5rem;">
                    <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 10px;">
                        <strong style="color: #1e40af; font-size: 14px;">Автоматска AI Ревзија на Описот</strong>
                        <span style="background: #dbeafe; color: #1e40af; font-size: 11px; font-weight: 600; padding: 2px 8px; border-radius: 20px; margin-left: auto;">Llama 3.2</span>
                    </div>
                    <p style="color: #2563eb; font-size: 13.5px; line-height: 1.6; margin: 0; white-space: pre-line; font-style: italic;">
                        {{ $request->ai_feedback }}
                    </p>
                </div>
            @endif



            @if($request->admin_note)
                <div style="background: #fefce8; border: 1px solid #fde047; border-radius: 12px; padding: 1.25rem; margin-top: 1.5rem; margin-bottom: 1.5rem;">
                    <div style="font-weight: 600; color: #854d0e; margin-bottom: 6px; font-size: 13px;">
                        💬 Претходна белешка од администрацијата:
                    </div>
                    <div style="color: #713f12; font-size: 14px; line-height: 1.6; white-space: pre-line;">
                        {{ $request->admin_note }}
                    </div>
                </div>
            @endif

            <hr style="border: none; border-top: 1px solid var(--line); margin: 2rem 0;">

            <form method="POST" action="{{ route('admin.requests.updateStatus', $request->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="section-label" style="margin-bottom: 1rem; color: var(--accent);">Одговор од администрација</div>

                <div class="field">
                    <label for="status">Промени Статус</label>
                    <select name="status" id="status" required>
                        <option value="pending" {{ $request->status === 'pending' ? 'selected' : '' }}>Испратено</option>
                        <option value="processing" {{ $request->status === 'processing' ? 'selected' : '' }}>Во обработка</option>
                        <option value="approved" {{ $request->status === 'approved' ? 'selected' : '' }}>Одобрено</option>
                        <option value="rejected" {{ $request->status === 'rejected' ? 'selected' : '' }}>Одбиено</option>
                    </select>
                </div>

                <div class="field">
                    <label for="admin_note">Белешка до студентот</label>
                    <textarea name="admin_note" id="admin_note" placeholder="Внесете официјален одговор или забелешка за студентот...">{{ old('admin_note', $request->admin_note) }}</textarea>
                </div>

                <div class="field" style="margin-top: 1.25rem; margin-bottom: 2rem;">
                    <label for="issued_document">Прикачи документ (Само PDF)</label>

                    @if($request->issued_document)
                        <div style="margin-bottom: 8px; font-size: 13px; color: var(--green);">
                            ✅ Веќе има прикачено документ:
                            <a href="{{ asset('storage/' . $request->issued_document) }}" target="_blank" style="color:var(--accent); font-weight:600; text-decoration:none;">Отвори го PDF ↗</a>
                        </div>
                    @endif

                    <input type="file" name="issued_document" id="issued_document" accept=".pdf" style="width: 100%; background: var(--bg); border: 1px solid var(--line); border-radius: 8px; padding: 10px;">
                    <small style="color: var(--ink3); font-size: 11px; margin-top: 4px;">Доколку го одобрувате барањето, прикачете го документот.</small>
                </div>

                <button type="submit" class="btn-save">Зачувај промени</button>
            </form>
        </div>
    </div>
@endsection
