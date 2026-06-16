@extends('layouts.app')

@section('content')
    <style>
        .timeline-container {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.02);
        }

        .timeline-title {
            font-size: 14px;
            font-weight: 700;
            color: #0f172a;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 1.5rem;
        }

        .timeline-steps {
            display: flex;
            justify-content: space-between;
            position: relative;
            margin-top: 1rem;
        }

        .timeline-steps::before {
            content: '';
            position: absolute;
            top: 14px;
            left: 8%;
            right: 8%;
            height: 3px;
            background: #e2e8f0;
            z-index: 1;
        }

        .timeline-step {
            text-align: center;
            position: relative;
            z-index: 2;
            flex: 1;
        }

        .step-icon {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #ffffff;
            border: 3px solid #cbd5e1;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px auto;
            font-size: 13px;
            font-weight: bold;
            color: #64748b;
        }

        .step-label {
            font-size: 13px;
            font-weight: 600;
            color: #64748b;
        }

        .step-sublabel {
            font-size: 11px;
            color: #94a3b8;
            margin-top: 2px;
        }

        .step-completed .step-icon {
            background: #2563eb;
            border-color: #2563eb;
            color: #ffffff;
            box-shadow: 0 0 0 4px #eff6ff;
        }
        .step-completed .step-label { color: #2563eb; }

        .step-success .step-icon {
            background: #10b981;
            border-color: #10b981;
            color: #ffffff;
            box-shadow: 0 0 0 4px #ecfdf5;
        }
        .step-success .step-label { color: #10b981; }

        .step-danger .step-icon {
            background: #ef4444;
            border-color: #ef4444;
            color: #ffffff;
            box-shadow: 0 0 0 4px #fee2e2;
        }
        .step-danger .step-label { color: #991b1b; }

        .request-details-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.02);
        }

        .request-header-area {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .request-type-badge {
            font-size: 11px;
            font-weight: 700;
            background: #eff6ff;
            color: #2563eb;
            padding: 4px 10px;
            border-radius: 6px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: #334155;
            font-size: 13px;
            font-weight: 600;
            text-decoration: none;
            margin-bottom: 1.5rem;
        }

        .back-btn:hover {
            color: #2563eb;
        }

        .detail-section {
            margin-bottom: 1.5rem;
        }

        .detail-label {
            font-size: 12px;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
        }

        .detail-value {
            font-size: 15px;
            color: #0f172a;
            line-height: 1.6;
            background: #f8fafc;
            padding: 1rem;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            white-space: pre-line;
        }
    </style>

    <a href="{{ route('student.requests.index') }}" class="back-btn">
        ← Назад кон Мои Барања
    </a>

    <div class="timeline-container">
        <div class="timeline-title">📍 Статус на обработка</div>

        <div class="timeline-steps">
            <div class="timeline-step step-completed">
                <div class="step-icon">1</div>
                <div class="step-label">Поднесено</div>
            </div>

            <div class="timeline-step {{ in_array($request->status, ['in_review', 'approved', 'issued']) ? 'step-completed' : '' }}">
                <div class="step-icon">2</div>
                <div class="step-label">Преглед</div>
            </div>

            @if($request->status === 'approved' || $request->status === 'issued')
                <div class="timeline-step step-success">
                    <div class="step-icon">✓</div>
                    <div class="step-label">Одобрено</div>
                </div>
            @elseif($request->status === 'rejected')
                <div class="timeline-step step-danger">
                    <div class="step-icon">✕</div>
                    <div class="step-label">Одбиено</div>
                </div>
            @else
                <div class="timeline-step">
                    <div class="step-icon">3</div>
                    <div class="step-label">Издадено</div>
                </div>
            @endif

            <div class="timeline-step {{ ($request->status === 'approved' || $request->status === 'issued') && $request->payment_status === 'paid' ? 'step-completed' : '' }}">
                <div class="step-icon">4</div>
                <div class="step-label">Плаќање</div>
                <div class="step-sublabel">
                    @if($request->status === 'approved' || $request->status === 'issued')
                        @if($request->payment_status === 'paid')
                            Платено
                        @else
                            Чека уплатница
                        @endif
                    @else
                        По одобрување
                    @endif
                </div>
            </div>
        </div>
    </div>

    @if($request->status === 'approved' && $request->payment_status === 'unpaid' && $request->requestType->price > 0)
        <div style="background: #fefce8; border: 1px solid #fde047; border-radius: 12px; padding: 1.5rem; margin-bottom: 2rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; gap: 1rem; flex-wrap: wrap;">
                <div>
                    <strong style="color: #854d0e;">Барањето е одобрено.</strong><br>
                    <span style="color: #713f12;">Мора да го платите надоместот од <strong>{{ number_format($request->requestType->price) }} ден.</strong></span>
                </div>
                <form method="POST" action="{{ route('student.requests.simulatePayment', $request->id) }}">
                    @csrf
                    <button type="submit" style="background: #059669; color: white; padding: 12px 24px; border-radius: 10px; font-weight: 600; border: none; cursor: pointer;">
                        Плати онлајн
                    </button>
                </form>
            </div>
        </div>
    @endif

    <div class="request-details-card">
        <div class="request-header-area">
            <div>
                <span class="request-type-badge">{{ $request->requestType->name ?? 'Општо барање' }}</span>
                <h2 style="font-family: 'DM Serif Display', serif; font-size: 24px; color: #0f172a; margin-top: 8px;">
                    Барање #{{ $request->id }}
                </h2>
            </div>
            <div style="font-size: 12px; color: #64748b; text-align: right;">
                Последна измена:<br>
                <strong>{{ $request->updated_at->format('d.m.Y во H:i') }}</strong>
            </div>
        </div>

        <div class="detail-section">
            <div class="detail-label">Содржина на барањето</div>
            <div class="detail-value">{{ $request->description }}</div>
        </div>

        @if($request->admin_note)
            <div style="background: #fefce8; border: 1px solid #fde047; border-radius: 12px; padding: 1.25rem; margin-top: 1.5rem;">
                <div style="font-weight: 600; color: #854d0e; margin-bottom: 6px; font-size: 13px;">
                    💬 Белешка од администрацијата:
                </div>
                <div style="color: #713f12; font-size: 14px; line-height: 1.6; white-space: pre-line;">
                    {{ $request->admin_note }}
                </div>

                @if(!in_array($request->status, ['approved', 'issued']))
                    <div style="margin-top: 12px;">
                        <a href="{{ route('student.requests.edit', $request->id) }}"
                           style="display: inline-flex; align-items: center; gap: 6px; background: #d97706; color: white; padding: 8px 16px; border-radius: 8px; font-size: 13px; font-weight: 600; text-decoration: none;">
                            Промени го барањето
                        </a>
                    </div>
                @endif
            </div>
        @endif

        @if($request->status === 'approved' || $request->status === 'issued')
            <div class="detail-section" style="margin-top: 2rem;">
                <div class="detail-label" style="color: #10b981;">Официјален одговор од службата</div>
                <div style="background: #ecfdf5; border: 1px solid #a7f3d0; border-radius: 8px; padding: 1.5rem;">
                    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
                        <div style="flex: 1; min-width: 250px;">
                            @if($request->admin_note)
                                <p style="font-size: 14px; color: #065f46; line-height: 1.6; margin: 0;">
                                    <strong>Белешка:</strong> "{{ $request->admin_note }}"
                                </p>
                            @else
                                <p style="font-size: 14px; color: #065f46; line-height: 1.6; margin: 0;">
                                    Вашето барање е успешно одобрено.
                                </p>
                            @endif
                        </div>

                        @if($request->issued_document)
                            <div>
                                <a href="{{ Storage::url($request->issued_document) }}" target="_blank"
                                   style="display: inline-flex; align-items: center; gap: 8px; padding: 11px 20px; background: #2563eb; border-radius: 8px; font-size: 13px; font-weight: 600; color: #ffffff; text-decoration: none;">
                                    📄 Преземи документ (PDF)
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
