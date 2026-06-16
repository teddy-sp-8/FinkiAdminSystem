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

        .stat-badge {
            display: inline-flex; align-items: center; gap: 6px;
            background: var(--surface); border: 1px solid var(--line);
            border-radius: 8px; padding: 6px 14px;
            font-size: 13px; color: var(--ink2);
        }
        .stat-badge strong { color: #2563eb; font-weight: 600; font-size: 14px; } /* Royal Blue Highlight */

        .new-btn {
            display: inline-flex; align-items: center; gap: 6px;
            background: #2563eb; color: #fff;
            font-size: 13px; font-weight: 500;
            padding: 7px 16px; border-radius: 8px;
            text-decoration: none; transition: all .15s;
        }
        .new-btn:hover { background: #1d4ed8; transform: translateY(-1px); } /* Deep Blue Hover */

        .table-card {
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: 14px;
            overflow: hidden;
        }

        table { width: 100%; border-collapse: collapse; }

        thead tr {
            background: var(--bg);
            border-bottom: 1px solid var(--line);
        }
        thead th {
            padding: 11px 20px;
            font-size: 11px; font-weight: 600;
            color: var(--ink3); text-transform: uppercase;
            letter-spacing: .7px; text-align: left;
        }
        thead th.right { text-align: right; }
        thead th.center { text-align: center; }

        /* CLICKABLE ROWS styling */
        tbody tr.clickable-row {
            cursor: pointer;
            border-bottom: 1px solid var(--line);
            transition: background .12s;
        }
        tbody tr:last-child { border-bottom: none; }
        tbody tr.clickable-row:hover { background: #f0f6ff; } /* Fresh Ice-Blue hover tint instead of light purple */

        tbody td {
            padding: 14px 20px;
            font-size: 13px; color: var(--ink2);
            vertical-align: middle;
        }
        tbody td.primary { font-weight: 500; color: var(--ink); }
        tbody td.right { text-align: right; }
        tbody td.center { text-align: center; }

        .date-main { font-size: 13px; font-weight: 500; color: var(--ink); display: block; }
        .date-sub  { font-size: 11px; color: var(--ink3); display: block; margin-top: 2px; }

        .pill {
            display: inline-flex; align-items: center;
            padding: 3px 10px; border-radius: 20px;
            font-size: 11px; font-weight: 600;
            letter-spacing: .3px; text-transform: uppercase;
        }
        .pill-approved   { background: var(--green-light); color: var(--green); }
        .pill-rejected   { background: #fff0ec;            color: var(--red);   }
        .pill-pending    { background: #fffbeb;            color: #d97706;      }
        .pill-processing { background: #eff6ff;            color: #2563eb;      } /* Styled to clean blue accent tone */

        .empty-state {
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: 14px;
            padding: 4rem 2rem;
            text-align: center;
            max-width: 420px;
            margin: 2rem auto;
        }
        .empty-icon {
            width: 48px; height: 48px; border-radius: 12px;
            background: #eff6ff; /* Soft blue tint background */
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 1rem;
            color: #2563eb; font-size: 22px;
        }
        .empty-title { font-size: 15px; font-weight: 600; color: var(--ink); margin-bottom: 6px; }
        .empty-sub   { font-size: 13px; color: var(--ink3); }
    </style>

    <div>
        <div style="margin-bottom: 2rem; display:flex; align-items:flex-start; justify-content:space-between; gap:1rem; flex-wrap:wrap;">
            <div>
                <div class="page-title">Мои Барања</div>
                <div class="page-sub">Следете ја состојбата на вашите поднесени документи</div>
            </div>
            <div style="display:flex; align-items:center; gap:10px; flex-shrink:0; margin-top:4px;">
                <div class="stat-badge">
                    Вкупно: <strong>{{ $myRequests->count() }}</strong>
                </div>
                <a href="{{ route('student.requests.create') }}" class="new-btn">
                    + Ново барање
                </a>
            </div>
        </div>

        @if($myRequests->isEmpty())

            <div class="empty-state">
                <div class="empty-icon">
                    <svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <div class="empty-title">Нема поднесени барања</div>
                <div class="empty-sub" style="margin-bottom:1.5rem;">Сè уште немате поднесено административни барања до студентската служба.</div>
                <a href="{{ route('student.requests.create') }}" class="new-btn" style="display:inline-flex;">
                    + Поднеси прво барање
                </a>
            </div>

        @else

            <div class="section-label">Сите барања (Кликни на барање во тек за измена)</div>

            <div class="table-card">
                <div style="overflow-x:auto;">
                    <table>
                        <thead>
                        <tr>
                            <th>Тип на барање</th>
                            <th>Опис</th>
                            <th class="center">Статус</th>
                            <th class="right">Датум</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($myRequests as $req)
                            <tr class="clickable-row"
                                @if($req->status === 'pending')
                                    onclick="window.location='{{ route('student.requests.show', $req->id) }}';"
                                title="Кликнете за измена на барањето."
                                @else
                                    onclick="window.location='{{ route('student.requests.show', $req->id) }}';"
                                title="Кликнете за преглед на одговорот и деталите."
                                @endif>

                                <td class="primary">
                                    {{ $req->requestType->name ?? 'Непознато' }}
                                </td>
                                <td>{{ Str::limit($req->description, 90) }}</td>
                                <td class="center">
                                    @if($req->status === 'approved')
                                        <span class="pill pill-approved">Одобрено</span>
                                    @elseif($req->status === 'rejected')
                                        <span class="pill pill-rejected">Одбиено</span>
                                    @elseif($req->status === 'processing')
                                        <span class="pill pill-processing">Во обработка</span>
                                    @else
                                        <span class="pill pill-pending">Во тек</span>
                                    @endif
                                </td>
                                <td class="right">
                                    <span class="date-main">{{ $req->created_at->format('d.m.Y') }}</span>
                                    <span class="date-sub">{{ $req->created_at->format('H:i') }} ч.</span>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        @endif
    </div>

@endsection
