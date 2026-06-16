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

        .page-sub {
            font-size: 14px;
            color: var(--ink3);
            margin-top: 6px;
        }

        .section-label {
            font-size: 11px;
            font-weight: 600;
            color: var(--ink3);
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-bottom: .75rem;
            margin-top: 2rem;
        }

        .search-container {
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: 12px;
            padding: 12px;
            margin-top: 1.5rem;
            display: flex;
            gap: 10px;
        }

        .search-form {
            display: flex;
            flex: 1;
            gap: 10px;
        }

        .search-input-wrapper {
            position: relative;
            flex: 1;
        }

        .search-input {
            width: 100%;
            background: var(--bg);
            border: 1px solid var(--line);
            border-radius: 8px;
            padding: 10px 14px 10px 38px;
            font-size: 14px;
            color: var(--ink);
            font-family: 'DM Sans', sans-serif;
            transition: all .15s;
            outline: none;
        }

        .search-input:focus {
            border-color: var(--accent-mid);
            background: var(--surface);
            box-shadow: 0 0 0 3px rgba(108, 92, 231, .1);
        }

        .search-icon-svg {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--ink3);
            pointer-events: none;
        }

        .btn-search {
            padding: 10px 20px;
            background: var(--accent);
            border: 1px solid var(--accent);
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            color: #fff;
            cursor: pointer;
            transition: all .15s;
            font-family: 'DM Sans', sans-serif;
        }

        .btn-search:hover {
            background: #5a4ad1;
            border-color: #5a4ad1;
        }

        .btn-clear {
            display: inline-flex;
            align-items: center;
            padding: 10px 16px;
            background: var(--bg);
            border: 1px solid var(--line);
            border-radius: 8px;
            font-size: 13px;
            font-weight: 500;
            color: var(--ink2);
            text-decoration: none;
            transition: all .15s;
            font-family: 'DM Sans', sans-serif;
        }

        .btn-clear:hover {
            background: var(--line);
            color: var(--ink);
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

        tbody td.primary {
            font-weight: 500;
            color: var(--ink);
        }

        tbody td.center {
            text-align: center;
        }

        .index-badge {
            background: var(--accent-light);
            color: var(--accent);
            padding: 4px 10px;
            border-radius: 6px;
            font-family: monospace;
            font-size: 12px;
            font-weight: 600;
        }

        .req-counter {
            background: var(--bg);
            color: var(--ink);
            padding: 3px 9px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 600;
        }

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
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background: var(--bg);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            color: var(--ink3);
            font-size: 20px;
        }
    </style>

    <div>
        <div style="margin-bottom: 1rem;">
            <div class="page-title">Именик на Студенти</div>
            <div class="page-sub">Преглед на сите регистрирани студенти во системот и нивната активност</div>
        </div>

        <div class="search-container">
            <form method="GET" action="{{ route('admin.students.index') }}" class="search-form">
                <div class="search-input-wrapper">
                    <svg class="search-icon-svg" width="16" height="16" fill="none" stroke="currentColor"
                         stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" name="search" class="search-input"
                           placeholder="Пребарај студент по име, е-пошта или индекс..." value="{{ $search ?? '' }}">
                </div>
                <button type="submit" class="btn-search">Пребарај</button>
                @if(!empty($search))
                    <a href="{{ route('admin.students.index') }}" class="btn-clear">Исчисти</a>
                @endif
            </form>
        </div>

        @if($students->isEmpty())
            <div class="empty-state">
                <div class="empty-icon">🔍</div>
                <div style="font-size: 15px; font-weight: 600; color: var(--ink); margin-bottom: 6px;">Нема резултати
                </div>
                <div style="font-size: 13px; color: var(--ink3);">Не се пронајдени студенти".</div>
            </div>
        @else
            <div class="section-label">
                @if(!empty($search))
                    Резултати од пребарувањето
                @else
                    Регистрирани студенти
                @endif
            </div>

            <div class="table-card">
                <div style="overflow-x:auto;">
                    <table>
                        <thead>
                        <tr>
                            <th>Индекс</th>
                            <th>Име и Презиме</th>
                            <th>Е-Пошта</th>
                            <th class="center">Поднесени Барања</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($students as $student)
                            <tr class="clickable-row"
                                onclick="window.location='{{ route('admin.students.show', $student->id) }}';">
                                <td>
                                    <span class="index-badge">{{ $student->index_number }}</span>
                                </td>
                                <td class="primary">{{ $student->name }}</td>
                                <td>{{ $student->email }}</td>
                                <td class="center">
                                    <span class="req-counter">{{ $student->requests_count }}</span>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div style="margin-top: 1.25rem;">
                {{ $students->links() }}
            </div>
        @endif
    </div>

@endsection
