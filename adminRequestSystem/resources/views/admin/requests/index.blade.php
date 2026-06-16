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

        .stat-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: 8px;
            padding: 8px 14px;
            font-size: 13px;
            color: var(--ink2);
        }

        .stat-badge strong {
            color: var(--accent);
            font-weight: 600;
            font-size: 14px;
        }

        .filter-container {
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: 14px;
            padding: 16px;
            margin-top: 1.5rem;
        }

        .filter-form {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            align-items: center;
        }

        .search-wrapper {
            position: relative;
            flex: 2;
            min-width: 200px;
        }

        .filter-select {
            flex: 1;
            min-width: 150px;
            background: var(--bg);
            border: 1px solid var(--line);
            border-radius: 8px;
            padding: 10px 14px;
            font-size: 13px;
            color: var(--ink);
            font-family: 'DM Sans', sans-serif;
            outline: none;
            cursor: pointer;
            transition: all .15s;
        }

        .filter-select:focus, .filter-input:focus {
            border-color: var(--accent-mid);
            background: var(--surface);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .filter-input {
            width: 100%;
            background: var(--bg);
            border: 1px solid var(--line);
            border-radius: 8px;
            padding: 10px 14px 10px 38px;
            font-size: 13px;
            color: var(--ink);
            font-family: 'DM Sans', sans-serif;
            transition: all .15s;
            outline: none;
        }

        .search-icon-svg {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--ink3);
            pointer-events: none;
        }

        .btn-filter {
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

        .btn-filter:hover {
            background: #1d4ed8;
            border-color: #1d4ed8;
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

        .btn-royal {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 10px 20px;
            background: var(--accent) !important;
            border: 1px solid var(--accent) !important;
            border-radius: 8px !important;
            font-size: 13px !important;
            font-weight: 600 !important;
            color: #ffffff !important;
            transition: all 0.15s ease;
            cursor: pointer;
        }

        .btn-royal:hover {
            background: #1d4ed8 !important;
            border-color: #1d4ed8 !important;
            transform: translateY(-1px);
        }

        .table-card {
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: 14px;
            overflow: hidden;
            margin-top: 1.5rem;
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
            padding: 14px 20px;
            font-size: 11px;
            font-weight: 600;
            color: var(--ink3);
            text-transform: uppercase;
            letter-spacing: 1px;
            text-align: left;
            white-space: nowrap;
        }

        thead th.center {
            text-align: center;
        }

        thead th.right {
            text-align: right;
        }

        tbody tr.admin-clickable-row {
            border-bottom: 1px solid var(--line);
            transition: background .12s;
            cursor: pointer;
        }

        tbody tr:last-child {
            border-bottom: none;
        }

        tbody tr.admin-clickable-row:hover {
            background: #fafbfc;
        }

        tbody td {
            padding: 14px 20px;
            font-size: 13px;
            color: var(--ink2);
            vertical-align: middle;
        }

        tbody td.center {
            text-align: center;
        }

        tbody td.right {
            text-align: right;
            white-space: nowrap;
        }

        .student-name {
            font-size: 13px;
            font-weight: 500;
            color: var(--ink);
            display: block;
        }

        .student-email {
            font-size: 11px;
            color: var(--ink3);
            display: block;
            margin-top: 2px;
        }

        .req-type {
            font-weight: 500;
            color: var(--ink);
        }

        .date-main {
            font-size: 13px;
            font-weight: 500;
            color: var(--ink);
            display: block;
        }

        .date-sub {
            font-size: 11px;
            color: var(--ink3);
            display: block;
            margin-top: 2px;
        }

        .pill {
            display: inline-flex;
            align-items: center;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: 0;
            text-transform: uppercase;
        }

        .pill-approved {
            background: #dcfce7;
            color: #166534;
        }

        .pill-rejected {
            background: #fff0ec;
            color: var(--red);
        }

        .pill-processing {
            background: var(--accent-light);
            color: var(--accent);
        }

        .pill-pending {
            background: #fffbeb;
            color: #d97706;
        }

        .action-group {
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .action-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 6px 14px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: all .15s;
        }

        .btn-approve {
            background: #dcfce7;
            color: #166534;
        }

        .btn-approve:hover {
            background: #166534;
            color: #fff;
        }

        .btn-reject {
            background: #fee2e2;
            color: #991b1b;
        }

        .btn-reject:hover {
            background: #991b1b;
            color: #fff;
        }
        .detail-btn {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 6px 12px;
            border: 1px solid var(--line);
            border-radius: 8px;
            font-size: 12px;
            font-weight: 500;
            color: var(--ink2);
            text-decoration: none;
            transition: all .15s;
        }

        .detail-btn:hover {
            border-color: var(--accent-mid);
            color: var(--accent);
            background: var(--accent-light);
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

        .pagination-wrap {
            margin-top: 1.5rem;
        }
    </style>

    <div>
        <div
            style="display: flex; justify-content: space-between; align-items: flex-start; gap: 1.5rem; flex-wrap: wrap; margin-bottom: 2rem;">
            <div>
                <h1 class="page-title">Студентски Барања</h1>
                <p class="page-sub">Преглед, управување и филтрирање на поднесените барања</p>
            </div>

            <div style="display: flex; align-items: center; gap: 12px; flex-shrink: 0;">
                <div class="stat-badge">
                    Пронајдени барања: <strong>{{ $requests->total() }}</strong>
                </div>

                <a href="{{ route('admin.requests.create') }}" class="btn-royal"
                   style="text-decoration: none; gap: 6px;">
                    ➕ Креирај ново барање
                </a>
            </div>
        </div>

        <div class="filter-container">
            <form method="GET" action="{{ route('admin.requests.index') }}" class="filter-form">

                <div class="search-wrapper">
                    <svg class="search-icon-svg" width="15" height="15" fill="none" stroke="currentColor"
                         stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" name="search" class="filter-input"
                           placeholder="Пребарај по студент или индекс..." value="{{ $search ?? '' }}">
                </div>

                <select name="type_id" class="filter-select">
                    <option value="">Тип на барања</option>
                    @foreach($requestTypes as $type)
                        <option value="{{ $type->id }}" {{ ($typeId == $type->id) ? 'selected' : '' }}>
                            {{ $type->name }}
                        </option>
                    @endforeach
                </select>

                <select name="status" class="filter-select">
                    <option value="">Статус</option>
                    <option value="pending" {{ $status === 'pending' ? 'selected' : '' }}> Испратени</option>
                    <option value="processing" {{ $status === 'processing' ? 'selected' : '' }}>Во обработка</option>
                    <option value="approved" {{ $status === 'approved' ? 'selected' : '' }}>Одобрени</option>
                    <option value="rejected" {{ $status === 'rejected' ? 'selected' : '' }}>Одбиени</option>
                </select>

                <button type="submit" class="btn-filter">Филтрирај</button>

                @if(!empty($search) || !empty($typeId) || !empty($status))
                    <a href="{{ route('admin.requests.index') }}" class="btn-clear">Исчисти</a>
                @endif
            </form>
        </div>

        @if($requests->isEmpty())

            <div class="empty-state">
                <div style="font-size: 24px; margin-bottom: 0.5rem;">⚙️</div>
                <div style="font-size: 15px; font-weight: 600; color: var(--ink); margin-bottom: 6px;">Нема пронајдено
                    барања
                </div>
                <div style="font-size: 13px; color: var(--ink3);">Нема резултати
                </div>
            </div>

        @else

            <div class="section-label">Листа на барања</div>

            <div class="table-card">
                <div style="overflow-x:auto;">
                    <table>
                        <thead>
                        <tr>
                            <th>Студент</th>
                            <th>Тип на барање</th>
                            <th>Опис</th>
                            <th class="center">Статус</th>
                            <th class="center">Датум</th>
                            <th class="right">Акции</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($requests as $req)
                            <tr class="admin-clickable-row"
                                onclick="window.location='{{ route('admin.requests.show', $req) }}';">
                                <td>
                                    <span class="student-name" style="color: var(--accent);">
                                        {{ $req->user->name ?? 'Непознат студент' }}
                                    </span>
                                    <span class="student-email">{{ $req->user->email ?? '' }}</span>
                                </td>
                                <td>
                                    <span class="req-type">{{ $req->requestType->name ?? 'Непознато' }}</span>
                                </td>
                                <td>{{ Str::limit($req->description, 55) }}</td>
                                <td class="center">
                                    @if($req->status === 'approved')
                                        <span class="pill pill-approved">Одобрено</span>
                                    @elseif($req->status === 'rejected')
                                        <span class="pill pill-rejected">Одбиено</span>
                                    @elseif($req->status === 'processing')
                                        <span class="pill pill-processing">Во обработка</span>
                                    @else
                                        <span class="pill pill-pending">Испратено</span>
                                    @endif
                                </td>
                                <td class="center">
                                    <span class="date-main">{{ $req->created_at->format('d.m.Y') }}</span>
                                    <span class="date-sub">{{ $req->created_at->format('H:i') }} ч.</span>
                                </td>
                                <td class="right">
                                    <div class="action-group" onclick="event.stopPropagation();">
                                        @if($req->status === 'pending' || $req->status === 'processing')
                                            <form
                                                action="{{ route('admin.requests.updateStatus', ['adminRequest' => $req->id]) }}"
                                                method="POST" style="display:inline;">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="status" value="approved">
                                                <button type="submit" class="action-btn btn-approve">Одобри</button>
                                            </form>

                                            <form
                                                action="{{ route('admin.requests.updateStatus', ['adminRequest' => $req->id]) }}"
                                                method="POST" style="display:inline;">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="status" value="rejected">
                                                <button type="submit" class="action-btn btn-reject">Одбиј</button>
                                            </form>
                                        @else
                                            <form
                                                action="{{ route('admin.requests.updateStatus', ['adminRequest' => $req->id]) }}"
                                                method="POST" style="display:inline;">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="status" value="processing">
                                                <button type="submit" class="action-btn"
                                                        style="background: #1d4ed8; color: #ffffff; ">
                                                    Ревидирај
                                                </button>
                                            </form>
                                        @endif

                                            <a href="{{ route('admin.requests.show', $req) }}" class="detail-btn">
                                                Детали
                                                <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                                                </svg>
                                            </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="pagination-wrap">
                {{ $requests->links() }}
            </div>

        @endif
    </div>

@endsection
