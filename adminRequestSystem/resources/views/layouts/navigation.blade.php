<nav
    style="background: var(--surface, #ffffff); border-b: 1px solid var(--line, #e2e8f0); box-shadow: 0 1px 3px rgba(37, 99, 235, 0.05); font-family: 'DM Sans', -apple-system, sans-serif;">

    <div style="max-width: 1280px; margin: 0 auto; padding: 0 1.5rem;">

        <div
            style="display: flex; justify-content: space-between; align-items:center; height: 4.5rem; align-items: center;">

            <div style="display: flex; align-items:center; gap: 0.75rem; align-items: center;">

                <div
                    style="width: 2.5rem; height: 2.5rem; background: #eff6ff; border: 1px solid #93c5fd; border-radius: 8px; display: flex; align-items:center; justify-content: center; text-align: center; color: #2563eb; font-weight: 700; font-size: 14px; align-items: center; justify-content: center;">
                    FI
                </div>

                <a href="{{ auth()->check() ? (auth()->user()->is_admin ? route('admin.requests.index') : route('student.requests.index')) : route('home') }}"
                   style="text-decoration: none; line-height: 1.2;">

                    <div style="font-size: 12px; color: var(--ink3, #64748b); font-weight: 500;">
                        FINKI • Административни Барања
                    </div>
                </a>

            </div>

            <div style="display: flex; align-items:center; gap: 0.75rem; align-items: center;">

                @auth

                    @if(!(auth()->user()->is_admin ?? false))

                        <a href="{{ route('student.requests.index') }}"
                           style="display: inline-flex; align-items: center; padding: 8px 14px; background: transparent; border: 1px solid var(--line, #e2e8f0); border-radius: 8px; color: var(--ink2, #334155); font-size: 13px; font-weight: 600; text-decoration: none; transition: all 0.15s;"
                           onmouseover="this.style.borderColor='#93c5fd'; this.style.color='#2563eb'; this.style.background='#eff6ff';"
                           onmouseout="this.style.borderColor='var(--line, #e2e8f0)'; this.style.color='var(--ink2, #334155)'; this.style.background='transparent';">
                            Мои Барања
                        </a>

                        <a href="{{ route('student.requests.create') }}"
                           style="display: inline-flex; align-items: center; padding: 8px 14px; background: #2563eb; border: 1px solid #2563eb; border-radius: 8px; color: #ffffff; font-size: 13px; font-weight: 600; text-decoration: none; transition: all 0.15s;"
                           onmouseover="this.style.background='#1d4ed8'; this.style.borderColor='#1d4ed8';"
                           onmouseout="this.style.background='#2563eb'; this.style.borderColor='#2563eb';">
                            + Ново Барање
                        </a>

                    @else
                        <a href="{{ route('admin.requests.index') }}"
                           style="display: inline-flex; align-items: center; padding: 8px 14px; background: transparent; border: 1px solid var(--line, #e2e8f0); border-radius: 8px; color: var(--ink2, #334155); font-size: 13px; font-weight: 600; text-decoration: none; transition: all 0.15s;"
                           onmouseover="this.style.borderColor='#93c5fd'; this.style.color='#2563eb'; this.style.background='#eff6ff';"
                           onmouseout="this.style.borderColor='var(--line, #e2e8f0)'; this.style.color='var(--ink2, #334155)'; this.style.background='transparent';">
                            Сите Барања
                        </a>
                    @endif

                    <a href="{{ route('profile.edit') }}"
                       style="display: inline-flex; align-items: center; padding: 8px 14px; background: transparent; border: 1px solid var(--line, #e2e8f0); border-radius: 8px; color: var(--ink2, #334155); font-size: 13px; font-weight: 600; text-decoration: none; transition: all 0.15s;"
                       onmouseover="this.style.borderColor='#93c5fd'; this.style.color='#2563eb'; this.style.background='#eff6ff';"
                       onmouseout="this.style.borderColor='var(--line, #e2e8f0)'; this.style.color='var(--ink2, #334155)'; this.style.background='transparent';">
                        Профил
                    </a>

                    <div
                        style="display: inline-flex; align-items: center; gap: 6px; padding: 7px 12px; background: var(--bg, #f8fafc); border: 1px solid var(--line, #e2e8f0); border-radius: 8px; color: var(--ink2, #475569); font-size: 13px; font-weight: 500;">
                        <span style="color: #2563eb; font-weight: bold;">•</span>
                        <span>{{ auth()->user()->name }}</span>
                        <span
                            style="font-size: 10px; font-weight: 700; background: #e0f2fe; color: #0369a1; padding: 2px 6px; border-radius: 4px; margin-left: 4px; text-transform: uppercase; letter-spacing: 0.3px;">
                            {{ (auth()->user()->is_admin ?? false) ? 'Служба' : 'Студент' }}
                        </span>
                    </div>

                    <form method="POST" action="{{ route('logout') }}" style="margin: 0; display: inline-flex;">
                        @csrf
                        <button type="submit"
                                style="display: inline-flex; align-items: center; padding: 8px 14px; background: #fff1f2; border: 1px solid #fecdd3; border-radius: 8px; color: #e11d48; font-size: 13px; font-weight: 600; cursor: pointer; transition: all 0.15s; font-family: inherit;"
                                onmouseover="this.style.background='#ffe4e6'; this.style.color='#be123c';"
                                onmouseout="this.style.background='#fff1f2'; this.style.color='#e11d48';">
                            Одјави се
                        </button>
                    </form>

                @endauth

            </div>

        </div>

    </div>

</nav>
