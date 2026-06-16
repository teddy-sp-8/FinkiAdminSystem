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
        .field:last-of-type { margin-bottom: 0; }

        .field label {
            font-size: 11px; font-weight: 600;
            color: var(--ink3); text-transform: uppercase; letter-spacing: .7px;
        }

        .field select,
        .field textarea,
        .field input[type="text"],
        .field input[type="number"] {
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
            appearance: none;
            -webkit-appearance: none;
        }
        .field select:focus,
        .field textarea:focus,
        .field input:focus {
            border-color: var(--accent-mid);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
            background: var(--surface);
        }
        .field textarea { resize: vertical; min-height: 140px; line-height: 1.6; }
        .field select { background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%238888a8' stroke-width='2.5'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 14px center; padding-right: 36px; cursor: pointer; }

        .divider { border: none; border-top: 1px solid var(--line); margin: 1.5rem 0; }

        .btn-row { display: flex; flex-direction: column; gap: 12px; }
        .btn-group { display: flex; gap: 12px; width: 100%; }

        .btn-ai {
            display: inline-flex; align-items: center; justify-content: center; gap: 8px;
            padding: 11px 20px;
            background: var(--accent-light);
            border: 1px solid var(--accent-mid);
            border-radius: 8px;
            font-size: 13px; font-weight: 600;
            color: var(--accent);
            cursor: pointer; font-family: 'DM Sans', sans-serif;
            transition: all .15s;
            flex: 1;
        }
        .btn-ai:hover { background: var(--accent); color: #fff; border-color: var(--accent); }
        .btn-ai:disabled { opacity: .5; pointer-events: none; }

        .btn-submit {
            display: inline-flex; align-items: center; justify-content: center; gap: 8px;
            padding: 11px 20px;
            background: #10b981;
            border: 1px solid #10b981;
            border-radius: 8px;
            font-size: 13px; font-weight: 600;
            color: #fff;
            cursor: pointer; font-family: 'DM Sans', sans-serif;
            transition: all .15s;
            text-transform: uppercase; letter-spacing: .4px;
            flex: 1.5;
        }
        .btn-submit:hover { background: #059669; border-color: #059669; transform: translateY(-1px); }

        .btn-submit.locked {
            background: #cbd5e1 !important;
            border-color: #cbd5e1 !important;
            color: #64748b !important;
            cursor: not-allowed !important;
            transform: none !important;
            opacity: 0.7;
        }

        .ai-feedback-box {
            background: #eff6ff;
            border: 1px solid #93c5fd;
            border-radius: 10px;
            padding: 16px;
            color: #1e40af;
            margin-bottom: 1.5rem;
            display: none;
            animation: fadeIn 0.3s ease-out;
        }

        @keyframes fadeIn { from { opacity: 0; transform: translateY(-5px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes spin { to { transform: rotate(360deg); } }

        @media (max-width: 480px) { .btn-group { flex-direction: column; } }
    </style>

    <div style="max-width: 640px; margin: 0 auto;">

        <div class="page-title">Ново барање</div>
        <div class="page-sub">Пополнете ги потребните полиња за да го испратите вашето барање до студентската служба</div>

        @if($errors->any())
            <div style="background: #fff0ec; border: 1px solid #ffcdc2; color: var(--red); padding: 12px; border-radius: 8px; margin-bottom: 1.5rem; font-size: 13px;">
                <strong style="display:block; margin-bottom: 4px;">⚠️ Настана грешка при поднесувањето:</strong>
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('student.requests.store') }}" id="requestForm" enctype="multipart/form-data">
            @csrf

            <div class="form-card">

                <div class="field">
                    <label for="request_type">Тип на барање</label>
                    <select name="request_type_id" id="request_type" required>
                        <option value="">— Изберете тип —</option>
                        @foreach($requestTypes as $type)
                            <option value="{{ $type->id }}"
                                    data-fields="{{ $type->requires_fields ?? '' }}"
                                {{ (isset($selectedTypeId) && $selectedTypeId == $type->id) ? 'selected' : '' }}>
                                {{ $type->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div id="dynamicFields"></div>

                <div class="field">
                    <label for="description">Опис</label>
                    <textarea name="description" id="description"
                              placeholder="Опиши го барањето детално..."
                              required></textarea>
                </div>

                <div class="field" style="margin-top: 1.25rem;">
                    <label for="student_attachment">Прикачи потребни документи / Слики</label>
                    <p style="font-size: 12px; color: var(--ink3); margin-top: -4px; margin-bottom: 8px;">
                        Прикачете слика од индекс, пасош, диплома или соодветна уплатница во зависност од барањето.
                    </p>

                    <input type="file" name="student_attachment" id="student_attachment" accept="image/*,.pdf"
                           style="width:100%; padding:10px; background:var(--bg); border:1px solid var(--line); border-radius:8px; font-family:inherit;">

                    <small style="color: var(--ink3); font-size: 11px; display: block; margin-top: 6px;">
                        Дозволени формати: Слики (JPG, PNG) или PDF документи (Максимум 7MB)
                    </small>
                </div>

                <hr class="divider">

                <div id="aiResponseContainer" class="ai-feedback-box">
                    <div style="display: flex; align-items: center; gap: 6px; font-weight: 600; font-size: 14px; margin-bottom: 6px;">
                        <span>🤖 AI Асистентото ве советува:</span>
                    </div>
                    <div id="aiSuggestionText" style="font-size: 13px; line-height: 1.5; white-space: pre-line;"></div>
                </div>
                <input type="hidden" name="ai_feedback" id="aiFeedbackHiddenInput">

                <div class="btn-row">
                    <p id="lockWarningText" style="font-size: 12px; color: var(--ink3); text-align: center; margin-bottom: 4px;">
                        ⚠️ Задолжително извршете AI проверка на описот пред да го испратите барањето.
                    </p>

                    <div class="btn-group">
                        <button type="button" id="aiCheckBtn" class="btn-ai">
                            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                            AI Проверка
                        </button>

                        <button type="submit" id="submitRequestBtn" class="btn-submit locked" disabled>
                            Поднеси барање
                            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                            </svg>
                        </button>
                    </div>
                </div>

            </div>
        </form>
    </div>

    <script>
        const aiCheckBtn = document.getElementById('aiCheckBtn');
        const submitRequestBtn = document.getElementById('submitRequestBtn');
        const aiResponseContainer = document.getElementById('aiResponseContainer');
        const aiSuggestionText = document.getElementById('aiSuggestionText');
        const lockWarningText = document.getElementById('lockWarningText');
        const descriptionField = document.getElementById('description');

        descriptionField.addEventListener('input', function() {
            if (!submitRequestBtn.classList.contains('locked')) {
                submitRequestBtn.classList.add('locked');
                submitRequestBtn.disabled = true;
                lockWarningText.style.display = 'block';
                lockWarningText.innerText = '⚠️ Направивте измени. Потребно е повторно да направите AI проверка.';
            }
        });

        aiCheckBtn.addEventListener('click', async function() {
            const description = descriptionField.value.trim();
            const typeSelect = document.getElementById('request_type');
            const typeName = typeSelect.options[typeSelect.selectedIndex]?.text || '';
            const selectedType = typeSelect.value;
            if (!selectedType) {
                alert("Ве молиме прво изберете тип на барање.");
                return;
            }

            if (!description || description.length < 15) {
                alert("Описот мора да има минимум 15 карактери.");
                return;
            }


            aiCheckBtn.disabled = true;
            const originalHTML = aiCheckBtn.innerHTML;
            aiCheckBtn.innerHTML = `<svg style="animation:spin 1s linear infinite;width:15px;height:15px;" fill="none" viewBox="0 0 24 24"><circle style="opacity:.25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path style="opacity:.75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/></svg> Анализирање...`;

            try {
                const response = await fetch('/ai/check', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ description, request_type: typeName })
                });

                const data = await response.json();

                if (data.success || data.suggestion) {
                    aiSuggestionText.innerText = data.suggestion;
                    aiResponseContainer.style.display = 'block';
                    document.getElementById('aiFeedbackHiddenInput').value = data.suggestion;

                    submitRequestBtn.classList.remove('locked');
                    submitRequestBtn.disabled = false;
                    lockWarningText.style.display = 'none';
                } else {
                    aiSuggestionText.innerText = "Проблем при комуникација со AI асистентот. Обидете се повторно.";
                    aiResponseContainer.style.display = 'block';
                }
            } catch(e) {
                aiSuggestionText.innerText = "Настана грешка, обидете се повторно.";
                aiResponseContainer.style.display = 'block';
            } finally {
                aiCheckBtn.innerHTML = originalHTML;
                aiCheckBtn.disabled = false;
            }
        });
    </script>

@endsection
