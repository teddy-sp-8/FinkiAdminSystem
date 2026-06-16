@php
    use Illuminate\Support\Facades\Storage;
@endphp

@extends('layouts.app')

@section('content')

    <style>
        /* Base Layout and Typography */
        .profile-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 1rem;
        }

        .page-title {
            font-family: 'DM Serif Display', serif;
            font-size: 32px;
            font-weight: 700;
            color: var(--ink);
            line-height: 1.2;
        }

        .page-sub {
            font-size: 14px;
            color: var(--ink3);
            margin-top: 6px;
        }

        .profile-layout {
            display: grid;
            grid-template-columns: 260px 1fr;
            gap: 2rem;
            margin-top: 2rem;
        }

        @media (max-width: 768px) {
            .profile-layout {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }
        }

        /* SIDEBAR CARD */
        .profile-side {
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: 16px;
            padding: 2rem 1.5rem;
            text-align: center;
            height: fit-content;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.02);
        }

        .avatar-circle {
            width: 84px;
            height: 84px;
            background: var(--accent-light);
            color: var(--accent);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 30px;
            font-weight: 700;
            margin: 0 auto 1.25rem;
            text-transform: uppercase;
            border: 3px solid var(--surface);
            box-shadow: 0 0 0 1px var(--accent-mid);
        }

        .profile-name {
            font-size: 18px;
            font-weight: 600;
            color: var(--ink);
            margin-bottom: 6px;
        }

        .profile-role {
            display: inline-flex;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: .5px;
            text-transform: uppercase;
        }

        .role-admin {
            background: #ffe4e6;
            color: var(--red);
        }

        .role-student {
            background: var(--accent-light);
            color: var(--accent);
        }

        .side-meta {
            margin-top: 2rem;
            padding-top: 1.25rem;
            border-top: 1px solid var(--line);
            text-align: left;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .side-meta-item {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .side-meta-key {
            font-size: 10px;
            color: var(--ink3);
            text-transform: uppercase;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .side-meta-val {
            font-size: 13px;
            color: var(--ink2);
            font-weight: 500;
        }

        .profile-main {
            display: flex;
            flex-direction: column;
            gap: 2rem;
        }

        /* THEMED CARDS */
        .card {
            background: var(--surface);
            border: 1px solid var(--line);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.02);
        }

        .card-header {
            padding: 1.25rem 1.5rem;
            background: var(--bg);
            border-bottom: 1px solid var(--line);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card-title {
            font-size: 13px;
            font-weight: 600;
            color: var(--ink);
            text-transform: uppercase;
            letter-spacing: .7px;
        }

        .card-count {
            font-size: 12px;
            color: var(--ink3);
            font-weight: 500;
        }

        .card-body {
            padding: 1.5rem;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
        }

        @media (max-width: 576px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
        }

        .field {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .field.full-width {
            grid-column: span 2;
        }

        @media (max-width: 576px) {
            .field.full-width {
                grid-column: span 1;
            }
        }

        .field label {
            font-size: 11px;
            font-weight: 600;
            color: var(--ink2);
            text-transform: uppercase;
            letter-spacing: .7px;
        }

        .field input, .field select {
            width: 100%;
            background: var(--bg);
            border: 1px solid var(--line);
            border-radius: 8px;
            padding: 11px 14px;
            font-size: 14px;
            color: var(--ink);
            font-family: 'DM Sans', sans-serif;
            transition: all .15s;
            outline: none;
            box-sizing: border-box;
        }

        .field input:focus, .field select:focus {
            border-color: var(--accent-mid);
            background: var(--surface);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .field input:disabled {
            background: var(--bg);
            color: var(--ink3);
            cursor: not-allowed;
            opacity: 0.75;
        }

        .btn-save {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 12px 24px;
            background: var(--accent);
            border: 1px solid var(--accent);
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            color: #fff;
            cursor: pointer;
            transition: all .15s;
            text-transform: uppercase;
            letter-spacing: .4px;
            width: fit-content;
            margin-top: 0.5rem;
        }

        .btn-save:hover {
            background: #1d4ed8;
            border-color: #1d4ed8;
            transform: translateY(-1px);
        }

        .themed-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }

        .themed-table th {
            padding: 1rem 1.5rem;
            font-size: 11px;
            font-weight: 600;
            color: var(--ink2);
            text-transform: uppercase;
            letter-spacing: 0.6px;
            border-bottom: 1px solid var(--line);
            background: rgba(0, 0, 0, 0.01);
        }

        .themed-table td {
            padding: 1.25rem 1.5rem;
            font-size: 14px;
            color: var(--ink);
            border-bottom: 1px solid var(--line);
            vertical-align: middle;
        }

        .themed-table tr:last-child td {
            border-bottom: none;
        }

        .themed-table tr:hover td {
            background: rgba(0, 0, 0, 0.005);
        }

        .doc-title {
            font-weight: 600;
            color: var(--ink);
        }

        .doc-date {
            color: var(--ink3);
            font-size: 13px;
        }

        .btn-download {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            background: #10b981;
            border: 1px solid #10b981;
            color: #ffffff !important;
            font-size: 13px;
            font-weight: 600;
            border-radius: 8px;
            text-decoration: none;
            transition: all 0.15s ease;
        }

        .btn-download:hover {
            background: #059669;
            border-color: #059669;
            transform: translateY(-1px);
        }

        .empty-docs-box {
            text-align: center;
            padding: 3.5rem 1.5rem;
        }

        .empty-docs-icon {
            font-size: 40px;
            margin-bottom: 1rem;
        }

        .empty-docs-title {
            font-size: 16px;
            font-weight: 600;
            color: var(--ink);
            margin-bottom: 6px;
        }

        .empty-docs-p {
            font-size: 14px;
            color: var(--ink3);
        }

        .alert-success {
            background: var(--accent-light);
            border: 1px solid var(--accent-mid);
            color: #1e40af;
            padding: 14px 18px;
            border-radius: 12px;
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }
    </style>

    <div class="profile-container">
        <h1 class="page-title">Мој Профил</h1>
        <p class="page-sub">Прегледајте ги и управувајте со вашите лични податоци</p>

        @if (session('status') === 'profile-updated' || session('success'))
            <div class="alert-success">
                <span>✅</span> Промените се успешно зачувани!
            </div>
        @endif

        <div class="profile-layout">
            <div class="profile-side">
                <div class="avatar-circle">
                    {{ mb_substr(auth()->user()->name, 0, 2) }}
                </div>
                <div class="profile-name">{{ auth()->user()->name }}</div>

                @if(auth()->user()->is_admin)
                    <span class="profile-role role-admin">Администратор</span>
                @else
                    <span class="profile-role role-student">Студент</span>
                @endif

                <div class="side-meta">
                    <div class="side-meta-item">
                        <span class="side-meta-key">Системско ID</span>
                        <span class="side-meta-val">#{{ auth()->id() }}</span>
                    </div>
                    <div class="side-meta-item">
                        <span class="side-meta-key">Член од</span>
                        <span
                            class="side-meta-val">{{ auth()->user()->created_at ? auth()->user()->created_at->format('d.m.Y') : 'Неврзано' }}</span>
                    </div>
                </div>
            </div>

            <div class="profile-main">

                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Општи Информации</div>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('profile.update') }}" class="form-grid">
                            @csrf
                            @method('patch')

                            <div class="field">
                                <label for="name">Име и презиме</label>
                                <input type="text" id="name" name="name" value="{{ old('name', auth()->user()->name) }}"
                                       required autocomplete="name">
                            </div>

                            <div class="field">
                                <label for="email">Е-пошта</label>
                                <input type="email" id="email" name="email"
                                       value="{{ old('email', auth()->user()->email) }}" required
                                       autocomplete="username">
                            </div>

                            <div class="field full-width">
                                <button type="submit" class="btn-save">Зачувај општи информации</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Мои Документи</div>
                        <div class="card-count">{{ $issuedDocuments->count() }} документи</div>
                    </div>

                    @if($issuedDocuments->isEmpty())
                        <div class="empty-docs-box">
                            <div class="empty-docs-icon">📄</div>
                            <h3 class="empty-docs-title">Нема издадени документи</h3>
                            <p class="empty-docs-p">Кога некое од твоите барања ќе биде одобрено и издадено, документот
                                ќе се прикаже тука.</p>
                        </div>
                    @else
                        <div style="overflow-x: auto;">
                            <table class="themed-table">
                                <thead>
                                <tr>
                                    <th>Документ</th>
                                    <th>Датум на издавање</th>
                                    <th style="text-align: right;">Акција</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($issuedDocuments as $doc)
                                    <tr>
                                        <td>
                                            <div class="doc-title">{{ $doc->requestType->name }}</div>
                                        </td>
                                        <td>
                                            <span class="doc-date">{{ $doc->updated_at->format('d.m.Y') }}</span>
                                        </td>
                                        <td style="text-align: right;">
                                            <a href="{{ Storage::url($doc->issued_document) }}" target="_blank"
                                               class="btn-download">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4"
                                                     style="width:16px; height:16px;" fill="none" viewBox="0 0 24 24"
                                                     stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                          stroke-width="2"
                                                          d="M4 16v-4m0 0l4 4m-4-4l4-4m12 0v4m0 0l-4-4m4 4l-4 4"/>
                                                </svg>
                                                Превземи PDF
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>

                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Универзитетски Податоци</div>
                    </div>
                    <div class="card-body">
                        <div class="form-grid">
                            <div class="field">
                                <label>Институција</label>
                                <input type="text" value="Универзитет Св. Кирил и Методиј" disabled>
                            </div>

                            <div class="field">
                                <label>Факултет</label>
                                <input type="text"
                                       value="Факултет за информатички науки и компјутерско инженерство (ФИНКИ)"
                                       disabled>
                            </div>

                            @if(!auth()->user()->is_admin)
                                <div class="field">
                                    <label>Број на Индекс</label>
                                    <input type="text" value="{{ auth()->user()->index_number ?? 'Недоделено' }}"
                                           disabled>
                                </div>

                                <div class="field">
                                    <label>Циклус на студии</label>
                                    <input type="text" value="Прв циклус (Додипломски студии)" disabled>
                                </div>
                            @else
                                <div class="field">
                                    <label>Сектор</label>
                                    <input type="text" value="Служба за студентски прашања" disabled>
                                </div>

                                <div class="field">
                                    <label>Ниво на пристап</label>
                                    <input type="text" value="Целосен администраторски пристап" disabled>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection
