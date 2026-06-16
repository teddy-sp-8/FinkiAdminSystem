@extends('layouts.app')

@section('content')

    <style>
        .page-title {
            font-family: 'DM Serif Display', serif;
            font-size: 28px;
            font-weight: 400;
            color: var(--ink);
            line-height: 1.2;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            font-weight: 500;
            color: var(--ink3);
            text-decoration: none;
            margin-bottom: 1.5rem;
            transition: color .15s;
        }

        .back-link:hover {
            color: var(--accent);
        }

        .profile-summary-card {
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: 14px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .avatar-circle {
            width: 64px;
            height: 64px;
            background: var(--accent-light);
            color: var(--accent);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .table-card {
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: 14px;
            overflow: hidden;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead tr {
            background: var(--bg);
            border-bottom: 1px solid var(--line);
        }

        thead th {
            padding: 11px 20px;
            font-size: 11px;
            font-weight: 600;
            color: var(--ink3);
            text-transform: uppercase;
            letter-spacing: 1px;
            text-align: left;
        }

        thead th.center {
            text-align: center;
        }

        tbody tr.clickable-row {
            border-bottom: 1px solid var(--line);
            transition: background .12s;
            cursor: pointer;
        }

        tbody tr:last-child {
            border-bottom: none;
        }

        tbody tr.clickable-row:hover {
            background: #faf9ff;
        }

        tbody td {
            padding: 14px 20px;
            font-size: 13px;
            color: var(--ink2);
            vertical-align: middle;
        }

        .pill {
            display: inline-flex;
            align-items: center;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .pill-approved {
            background: var(--green-light);
            color: var(--green);
        }

        .pill-rejected {
            background: #fff0ec;
            color: var(--red);
        }

        .pill-pending {
            background: #fffbeb;
            color: #d97706;
        }
    </style>

    <div>
        <a href="{{ route('admin.students.index') }}" class="back-link">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
            Назад кон сите студенти
        </a>

        <div class="profile-summary-card">
            <div class="avatar-circle">{{ mb_substr($student->name, 0, 2) }}</div>
            <div>
                <div class="page-title" style="font-size: 22px;">{{ $student->name }}</div>
                <div style="font-size:13px; color: var(--ink2); margin-top: 4px; display:flex; gap:15px;">
                    <span>Индекс: <strong style="color: var(--accent);">{{ $student->index_number }}</strong></span>
                    <span>•</span>
                    <span>Е-Пошта: <strong>{{ $student->email }}</strong></span>
                </div>
            </div>
        </div>

        <div class="page-title" style="font-size: 18px; margin-bottom: .75rem;">Историја на поднесени барања</div>

        @if($requests->isEmpty())
            <div class="table-card" style="padding: 3rem; text-align: center; color: var(--ink3); font-size: 14px;">
                Студентот нема поднесено ниедно административно барање.
            </div>
        @else
            <div class="table-card">
                <div style="overflow-x:auto;">
                    <table>
                        <thead>
                        <tr>
                            <th>Тип на барање</th>
                            <th>Опис</th>
                            <th class="center">Статус</th>
                            <th>Датум на поднесување</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($requests as $req)
                            <tr class="clickable-row"
                                onclick="window.location='{{ route('admin.requests.show', $req->id) }}';">
                                <td style="font-weight: 500; color: var(--ink);">
                                    {{ $req->requestType->name ?? 'Непознато' }}
                                </td>
                                <td>{{ Str::limit($req->description, 65) }}</td>
                                <td class="center">
                                    @if($req->status === 'approved')
                                        <span class="pill pill-approved">Одобрено</span>
                                    @elseif($req->status === 'rejected')
                                        <span class="pill pill-rejected">Одбиено</span>
                                    @else
                                        <span class="pill pill-pending">Во тек</span>
                                    @endif
                                </td>
                                <td>{{ $req->created_at->format('d.m.Y \в\о H:i') }} ч.</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>

@endsection
