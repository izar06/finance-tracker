<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Finance Tracker' }} — Finance Tracker</title>
    <link rel="icon" href="{{ asset('assets/icon.png') }}">
    {{-- Anti-FOUC: apply dark class before render --}}
    <script>if(localStorage.getItem('darkMode')==='true')document.documentElement.classList.add('dark');</script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    screens: {
                        'xs': '475px',
                    },
                    colors: {
                        primary: {
                            50:'#eff6ff', 100:'#dbeafe', 200:'#bfdbfe', 300:'#93c5fd',
                            400:'#60a5fa', 500:'#3b82f6', 600:'#2563eb', 700:'#1d4ed8',
                            800:'#1e40af', 900:'#1e3a8a',
                        },
                    },
                    fontFamily: { sans: ['Plus Jakarta Sans','ui-sans-serif','system-ui'] },
                },
            },
        };
    </script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

    @livewireStyles

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        [x-cloak] { display: none !important; }

        /* ── SIDEBAR ── */
        #sidebar {
            width: 256px;
            transition: width 0.25s ease, min-width 0.25s ease;
        }
        #sidebar.collapsed {
            width: 72px;
        }

        /* nav links */
        .sidebar-link {
            display: flex; align-items: center; gap: .75rem;
            padding: .65rem 1rem; border-radius: .75rem;
            font-size: .875rem; font-weight: 500;
            color: #475569; text-decoration: none;
            transition: background .2s, color .2s;
            white-space: nowrap; overflow: hidden;
        }
        .sidebar-link:hover { background: #eff6ff; color: #1d4ed8; }
        .sidebar-link.active {
            background: #3b82f6; color: #fff;
            box-shadow: 0 4px 14px rgba(59,130,246,.25);
        }
        .sidebar-link.active:hover { background: #2563eb; }

        /* collapsed state hides text */
        #sidebar.collapsed .sidebar-link { justify-content: center; padding: .65rem; }
        #sidebar.collapsed .link-text { display: none; }
        #sidebar.collapsed .sidebar-section-label { display: none; }
        #sidebar.collapsed .brand-text { display: none; }
        #sidebar.collapsed .date-box { display: none; }
        #sidebar.collapsed .brand-logo { margin: 0 auto; }

        /* progress bar */
        #nprogress-bar {
            position: fixed; top: 0; left: 0; height: 3px; width: 0;
            background: linear-gradient(90deg, #3b82f6, #93c5fd);
            z-index: 9999; transition: width .3s ease;
            box-shadow: 0 0 8px rgba(59,130,246,.6);
        }

        .page-content { animation: fadeUp .2s ease-out; }
        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(6px); }
            to   { opacity: 1; transform: translateY(0); }
        }


        /* ── MOBILE RESPONSIVE ── */
        @media (max-width: 1023px) {
            /* Prevent content overflow on small screens */
            main { min-width: 0; }
            .page-content { overflow-x: hidden; }
        }

        /* Safe area support for iOS notch/Dynamic Island */
        @supports (padding-top: env(safe-area-inset-top)) {
            header {
                padding-top: max(0px, env(safe-area-inset-top));
            }
            #sidebar {
                padding-bottom: env(safe-area-inset-bottom);
            }
        }

        /* Better tap targets on mobile */
        @media (max-width: 767px) {
            .sidebar-link { min-height: 44px; }
            button, a { -webkit-tap-highlight-color: transparent; }
            /* Prevent text overflow on cards */
            .break-all { word-break: break-all; }
        }


        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: #f8fafc; }
        ::-webkit-scrollbar-thumb { background: #bfdbfe; border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: #93c5fd; }

        /* ── DARK MODE SCROLLBAR ── */
        .dark ::-webkit-scrollbar-track { background: #1e293b; }
        .dark ::-webkit-scrollbar-thumb { background: #334155; }
        .dark ::-webkit-scrollbar-thumb:hover { background: #475569; }

        /* ── DARK MODE: Layout shells ── */
        .dark body                      { background-color: #0f172a; color: #e2e8f0; }
        .dark #sidebar                  { background: #1e293b; border-color: #334155; }
        .dark aside .brand-text p.font-bold { color: #f1f5f9; }
        .dark aside .brand-text p.text-xs   { color: #94a3b8; }
        .dark .sidebar-link             { color: #94a3b8; }
        .dark .sidebar-link:hover       { background: #334155; color: #93c5fd; }
        .dark .sidebar-link.active      { background: #3b82f6; color: #fff; }
        .dark aside .border-t           { border-color: #334155; }
        .dark aside .sidebar-section-label { color: #64748b; }
        .dark aside .date-box           { background: linear-gradient(135deg,#1e3a5f,#1e40af); }
        .dark aside .date-box p.text-primary-400 { color: #93c5fd; }
        .dark aside .date-box p.text-primary-700 { color: #bfdbfe; }

        /* sidebar toggle button */
        .dark aside button.border      { background: #1e293b; border-color: #334155; color: #94a3b8; }

        /* header */
        .dark header                    { background: #1e293b; border-color: #334155; }
        .dark header h1                 { color: #f1f5f9; }
        .dark header p.text-slate-400   { color: #64748b; }
        .dark .user-dropdown-menu       { background: #1e293b; border-color: #334155; box-shadow: 0 10px 30px rgba(0,0,0,.4); }
        .dark .user-dropdown-menu a,
        .dark .user-dropdown-menu button { color: #94a3b8; }
        .dark .user-dropdown-menu a:hover,
        .dark .user-dropdown-menu button:hover { background: #334155; color: #93c5fd; }
        .dark .user-dropdown-menu .logout-btn:hover { background: #450a0a; color: #fca5a5; }
        .dark .user-dropdown-menu .bg-primary-50  { background: #1e3a5f; border-color: #1e40af; }
        .dark .user-dropdown-menu .text-primary-700 { color: #93c5fd; }
        .dark .user-dropdown-menu .text-primary-400 { color: #60a5fa; }

        /* user button */
        .dark .bg-primary-50.border.border-primary-200 { background: #1e3a5f; border-color: #1e40af; }
        .dark .text-primary-700 { color: #93c5fd; }

        /* main & footer */
        .dark main                      { background: #0f172a; }
        .dark footer                    { background: #1e293b; border-color: #334155; }
        .dark footer p                  { color: #475569; }

        /* ── DARK MODE: Cards & common elements ── */
        .dark .bg-white                 { background-color: #1e293b !important; }
        .dark .bg-slate-50              { background-color: #0f172a !important; }
        .dark .border-slate-100         { border-color: #334155 !important; }
        .dark .border-slate-200         { border-color: #334155 !important; }
        .dark .text-slate-800           { color: #f1f5f9 !important; }
        .dark .text-slate-700           { color: #e2e8f0 !important; }
        .dark .text-slate-600           { color: #cbd5e1 !important; }
        .dark .text-slate-500           { color: #94a3b8 !important; }
        .dark .text-slate-400           { color: #64748b !important; }
        .dark .text-slate-300           { color: #475569 !important; }

        /* Hover states */
        .dark .hover\:bg-slate-50:hover  { background-color: #334155 !important; }
        .dark .hover\:bg-slate-100:hover { background-color: #334155 !important; }
        .dark .hover\:bg-slate-200:hover { background-color: #475569 !important; }
        .dark .bg-slate-100              { background-color: #334155 !important; }

        /* Inputs & selects */
        .dark input[type="text"],
        .dark input[type="number"],
        .dark input[type="date"],
        .dark input[type="email"],
        .dark input[type="password"],
        .dark select,
        .dark textarea                  { background-color: #0f172a !important; border-color: #334155 !important; color: #e2e8f0 !important; }
        .dark input::placeholder,
        .dark textarea::placeholder     { color: #475569 !important; }
        .dark select option             { background-color: #1e293b; color: #e2e8f0; }

        /* Dividers */
        .dark .divide-slate-50 > * + *  { border-color: #334155 !important; }
        .dark .divide-slate-100 > * + * { border-color: #334155 !important; }
        .dark .divide-y > * + *         { border-color: #334155; }
        .dark .border-b                 { border-color: #334155 !important; }
        .dark .border-t                 { border-color: #334155 !important; }

        /* Table */
        .dark .bg-slate-50.border-b,
        .dark thead.bg-slate-50         { background-color: #162032 !important; }
        .dark tbody tr:hover            { background-color: #253347 !important; }
        .dark .bg-violet-50\/30         { background-color: rgba(46,16,101,.2) !important; }

        /* Badges */
        .dark .bg-slate-100.text-slate-700 { background-color: #334155 !important; color: #cbd5e1 !important; }
        .dark .bg-emerald-50            { background-color: #052e16 !important; }
        .dark .bg-rose-50               { background-color: #450a0a !important; }
        .dark .bg-violet-50             { background-color: #2e1065 !important; }
        .dark .bg-blue-50               { background-color: #0c1a3a !important; }
        .dark .bg-amber-50              { background-color: #431407 !important; }

        /* Modals */
        .dark .bg-black\/50             { background-color: rgba(0,0,0,.75) !important; }
        .dark .shadow-2xl               { box-shadow: 0 25px 50px rgba(0,0,0,.6) !important; }

        /* Logout modal */
        .dark .logout-modal-bg          { background: rgba(0,0,0,.65); }

        /* Pagination */
        .dark nav[aria-label="Pagination"] span,
        .dark nav[aria-label="Pagination"] a { background-color: #1e293b; border-color: #334155; color: #94a3b8; }
        .dark nav[aria-label="Pagination"] a:hover { background-color: #334155; }

        /* Progress bar nprogress */
        .dark #nprogress-bar            { background: linear-gradient(90deg, #60a5fa, #bfdbfe); }

        /* Notification */
        .dark .bg-blue-50.border-blue-200  { background-color: #0c1a3a !important; border-color: #1e40af !important; }
        .dark .text-blue-800               { color: #93c5fd !important; }

        /* Dark toggle button */
        #dark-toggle {
            transition: background .2s, border-color .2s;
        }

        /* ── DARK MODE: Bill Reminder dropdown ── */
        .dark .bill-reminder-panel       { background: #1e293b; border-color: #334155; }
        .dark .bill-reminder-panel .border-b { border-color: #334155 !important; }
        .dark .bill-reminder-panel .divide-slate-50 > * + * { border-color: #1e293b !important; }
        .dark .bill-reminder-panel .hover\:bg-slate-50:hover { background-color: #253347 !important; }

        /* user dropdown */
        .user-dropdown-menu {
            position: absolute; right: 0; top: calc(100% + 8px);
            background: white; border: 1px solid #e2e8f0;
            border-radius: .75rem;
            box-shadow: 0 10px 30px rgba(0,0,0,.1);
            min-width: 180px; z-index: 50; overflow: hidden;
        }
        .user-dropdown-menu a,
        .user-dropdown-menu button {
            display: flex; align-items: center; gap: .5rem;
            width: 100%; padding: .65rem 1rem;
            font-size: .875rem; color: #475569;
            text-decoration: none; background: none;
            border: none; cursor: pointer;
            transition: background .15s; text-align: left;
        }
        .user-dropdown-menu a:hover,
        .user-dropdown-menu button:hover { background: #eff6ff; color: #1d4ed8; }
        .user-dropdown-menu .logout-btn:hover { background: #fef2f2; color: #dc2626; }
    </style>
</head>
<body class="h-full bg-slate-50"
      x-data="{
          sidebarOpen: false,
          sidebarCollapsed: localStorage.getItem('sidebarCollapsed') === 'true',
          userMenuOpen: false,
          darkMode: localStorage.getItem('darkMode') === 'true',
          toggleSidebar() {
              this.sidebarCollapsed = !this.sidebarCollapsed;
              localStorage.setItem('sidebarCollapsed', this.sidebarCollapsed);
          },
          toggleDark() {
              this.darkMode = !this.darkMode;
              localStorage.setItem('darkMode', this.darkMode);
              document.documentElement.classList.toggle('dark', this.darkMode);
          },

      }"
      x-init="
          $watch('sidebarCollapsed', v => {
              document.getElementById('sidebar').classList.toggle('collapsed', v);
          });
          if (sidebarCollapsed) document.getElementById('sidebar').classList.add('collapsed');
          document.documentElement.classList.toggle('dark', darkMode);
      ">

    <div id="nprogress-bar"
         wire:loading.style="width:70%;opacity:1"
         wire:loading.delay
         wire:loading.remove.style="width:0;opacity:0"></div>

    {{-- Mobile overlay --}}
    <div x-show="sidebarOpen" x-cloak @click="sidebarOpen=false"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-20 bg-black/50 lg:hidden"></div>

    <div class="flex h-full min-h-screen">

        {{-- ═══ SIDEBAR ═══ --}}
        <aside id="sidebar"
               class="fixed inset-y-0 left-0 z-30 bg-white border-r border-slate-200 flex flex-col
                      lg:translate-x-0 lg:static lg:z-auto transition-all duration-300"
               :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'">

            {{-- Brand --}}
            <div class="flex items-center gap-3 px-4 py-5 border-b border-slate-100 relative">
                <div class="brand-logo w-10 h-10 bg-gradient-to-br from-primary-400 to-primary-600 rounded-xl flex items-center justify-center shadow-md shadow-primary-500/30 flex-shrink-0">
                    <span class="text-white text-xl">💰</span>
                </div>
                <div class="brand-text overflow-hidden">
                    <p class="font-bold text-slate-800 text-sm leading-tight whitespace-nowrap">Finance Tracker</p>
                    <p class="text-xs text-slate-400 whitespace-nowrap">Kelola keuanganmu</p>
                </div>
                {{-- Toggle button (desktop) --}}
                <button @click="toggleSidebar()"
                        class="hidden lg:flex absolute -right-3.5 top-1/2 -translate-y-1/2 w-7 h-7
                               bg-white border border-slate-200 rounded-full shadow-sm
                               items-center justify-center text-slate-400 hover:text-primary-600
                               hover:border-primary-300 transition-colors z-10"
                        :title="sidebarCollapsed ? 'Perluas sidebar' : 'Perkecil sidebar'">
                    <svg x-show="!sidebarCollapsed" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
                    </svg>
                    <svg x-show="sidebarCollapsed" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
            </div>

            {{-- Nav --}}
            <nav class="flex-1 px-3 py-4 space-y-0.5 overflow-y-auto overflow-x-hidden">
                <p class="sidebar-section-label text-xs font-semibold text-slate-400 uppercase tracking-wider px-3 mb-3 mt-1">Menu Utama</p>

                <a href="{{ route('dashboard') }}"
                   class="sidebar-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                   title="Dashboard">
                    <span class="text-lg flex-shrink-0">📊</span>
                    <span class="link-text">Dashboard</span>
                </a>
                <a href="{{ route('transactions') }}"
                   class="sidebar-link {{ request()->routeIs('transactions') ? 'active' : '' }}"
                   title="Transaksi">
                    <span class="text-lg flex-shrink-0">💳</span>
                    <span class="link-text">Transaksi</span>
                </a>
                <a href="{{ route('bills') }}"
                   class="sidebar-link {{ request()->routeIs('bills') ? 'active' : '' }}"
                   title="Tagihan">
                    <span class="text-lg flex-shrink-0">🧾</span>
                    <span class="link-text">Tagihan</span>
                </a>
                <a href="{{ route('goals') }}"
                   class="sidebar-link {{ request()->routeIs('goals') ? 'active' : '' }}"
                   title="Tujuan Keuangan">
                    <span class="text-lg flex-shrink-0">🎯</span>
                    <span class="link-text">Tujuan Keuangan</span>
                </a>
                <a href="{{ route('assets') }}"
                   class="sidebar-link {{ request()->routeIs('assets') ? 'active' : '' }}"
                   title="Aset">
                    <span class="text-lg flex-shrink-0">🏦</span>
                    <span class="link-text">Aset</span>
                </a>
                <a href="{{ route('budgets') }}"
                   class="sidebar-link {{ request()->routeIs('budgets') ? 'active' : '' }}"
                   title="Anggaran">
                    <span class="text-lg flex-shrink-0">💰</span>
                    <span class="link-text">Anggaran</span>
                </a>
                <a href="{{ route('categories') }}"
                   class="sidebar-link {{ request()->routeIs('categories') ? 'active' : '' }}"
                   title="Kategori">
                    <span class="text-lg flex-shrink-0">🏷️</span>
                    <span class="link-text">Kategori</span>
                </a>
                <p class="sidebar-section-label text-xs font-semibold text-slate-400 uppercase tracking-wider px-3 mt-5 mb-3">Analisis</p>

                <a href="{{ route('reports') }}"
                   class="sidebar-link {{ request()->routeIs('reports') ? 'active' : '' }}"
                   title="Laporan">
                    <span class="text-lg flex-shrink-0">📈</span>
                    <span class="link-text">Laporan</span>
                </a>

                @if(auth()->user()?->isSuperAdmin())
                <p class="sidebar-section-label text-xs font-semibold text-amber-500 uppercase tracking-wider px-3 mt-5 mb-3">Admin</p>
                <a href="{{ route('superadmin') }}"
                   class="sidebar-link {{ request()->routeIs('superadmin') ? 'active' : '' }}"
                   title="Manajemen User"
                   style="{{ request()->routeIs('superadmin') ? '' : 'color:#b45309' }}">
                    <span class="text-lg flex-shrink-0">🛡️</span>
                    <span class="link-text">Manajemen User</span>
                </a>
                @endif
            </nav>

            {{-- Date box --}}
            <div class="px-3 py-4 border-t border-slate-100 flex-shrink-0">
                <div class="date-box bg-gradient-to-br from-primary-50 to-primary-100 rounded-xl p-3 text-center">
                    <p class="text-xs text-primary-400">{{ now()->translatedFormat('l') }}</p>
                    <p class="text-sm font-bold text-primary-700">{{ now()->translatedFormat('d F Y') }}</p>
                </div>
            </div>
        </aside>

        {{-- ═══ MAIN ═══ --}}
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">

            <header class="bg-white border-b border-slate-200 px-4 lg:px-8 h-16 flex items-center justify-between sticky top-0 z-10 flex-shrink-0">
                <div class="flex items-center gap-4">
                    {{-- Mobile hamburger --}}
                    <button @click="sidebarOpen=!sidebarOpen"
                            class="lg:hidden p-2 rounded-xl text-slate-500 hover:bg-slate-100 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                    <div>
                        <h1 class="text-base font-bold text-slate-800 leading-tight">{{ $title ?? 'Dashboard' }}</h1>
                        <p class="text-xs text-slate-400 leading-tight hidden sm:block">Finance Tracker</p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <div wire:loading.delay class="flex items-center gap-2 text-xs text-slate-400">
                        <svg class="animate-spin w-4 h-4 text-primary-500" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                        </svg>
                        <span class="hidden sm:inline text-xs">Memuat...</span>
                    </div>

                    {{-- Dark Mode Toggle --}}
                    <button id="dark-toggle" @click="toggleDark()"
                            class="p-2 rounded-xl border border-slate-200 text-slate-500 hover:bg-slate-100 hover:text-slate-700 transition-colors"
                            :title="darkMode ? 'Mode Terang' : 'Mode Gelap'">
                        {{-- Sun icon (shown in dark mode) --}}
                        <svg x-show="darkMode" class="w-4 h-4 text-amber-400" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2.25a.75.75 0 01.75.75v2.25a.75.75 0 01-1.5 0V3a.75.75 0 01.75-.75zM7.5 12a4.5 4.5 0 119 0 4.5 4.5 0 01-9 0zM18.894 6.166a.75.75 0 00-1.06-1.06l-1.591 1.59a.75.75 0 101.06 1.061l1.591-1.59zM21.75 12a.75.75 0 01-.75.75h-2.25a.75.75 0 010-1.5H21a.75.75 0 01.75.75zM17.834 18.894a.75.75 0 001.06-1.06l-1.59-1.591a.75.75 0 10-1.061 1.06l1.59 1.591zM12 18a.75.75 0 01.75.75V21a.75.75 0 01-1.5 0v-2.25A.75.75 0 0112 18zM7.758 17.303a.75.75 0 00-1.061-1.06l-1.591 1.59a.75.75 0 001.06 1.061l1.591-1.59zM6 12a.75.75 0 01-.75.75H3a.75.75 0 010-1.5h2.25A.75.75 0 016 12zM6.697 7.757a.75.75 0 001.06-1.06l-1.59-1.591a.75.75 0 00-1.061 1.06l1.59 1.591z"/>
                        </svg>
                        {{-- Moon icon (shown in light mode) --}}
                        <svg x-show="!darkMode" class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path fill-rule="evenodd" d="M9.528 1.718a.75.75 0 01.162.819A8.97 8.97 0 009 6a9 9 0 009 9 8.97 8.97 0 003.463-.69.75.75 0 01.981.98 10.503 10.503 0 01-9.694 6.46c-5.799 0-10.5-4.701-10.5-10.5 0-4.368 2.667-8.112 6.46-9.694a.75.75 0 01.818.162z" clip-rule="evenodd"/>
                        </svg>
                    </button>

                    {{-- Bill Reminder Bell --}}
                    @livewire('bill-reminder')

                    {{-- User Dropdown --}}
                    <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                        <button @click="open = !open"
                                class="flex items-center gap-2 bg-primary-50 border border-primary-200 rounded-xl px-3 py-2 hover:bg-primary-100 transition-colors">
                            <div class="w-6 h-6 bg-primary-500 rounded-lg flex items-center justify-center flex-shrink-0">
                                <span class="text-white text-xs font-bold">{{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}</span>
                            </div>
                            <span class="text-sm font-medium text-primary-700 hidden sm:inline">{{ auth()->user()->name ?? 'Pengguna' }}</span>
                            <svg class="w-4 h-4 text-primary-400 hidden sm:block transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <div x-show="open" x-cloak
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-100"
                             x-transition:leave-start="opacity-100 scale-100"
                             x-transition:leave-end="opacity-0 scale-95"
                             class="user-dropdown-menu">
                            <div class="px-4 py-3 bg-primary-50 border-b border-primary-100">
                                <p class="text-xs font-semibold text-primary-700">{{ auth()->user()->name ?? 'Pengguna' }}</p>
                                <p class="text-xs text-primary-400 truncate">{{ auth()->user()->email ?? '' }}</p>
                            </div>
                            <button type="button" class="logout-btn" @click="$store.logout.open()">
                                <span>🚪</span> Keluar
                            </button>
                        </div>
                    </div>
                </div>
            </header>

            {{-- Flash messages --}}
            @if(session('success'))
                <div x-data="{show:true}" x-show="show" x-init="setTimeout(()=>show=false,4500)"
                     x-transition:leave="transition ease-in duration-300"
                     x-transition:leave-start="opacity-100 translate-y-0"
                     x-transition:leave-end="opacity-0 -translate-y-2"
                     class="mx-3 sm:mx-4 lg:mx-8 mt-3 sm:mt-4 bg-blue-50 border border-blue-200 text-blue-800 px-4 py-3 rounded-xl flex items-center justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <span class="text-lg">✅</span>
                        <p class="text-sm font-medium">{{ session('success') }}</p>
                    </div>
                    <button @click="show=false" class="text-blue-400 hover:text-blue-700">✕</button>
                </div>
            @endif
            @if(session('error'))
                <div x-data="{show:true}" x-show="show" x-init="setTimeout(()=>show=false,5000)"
                     class="mx-3 sm:mx-4 lg:mx-8 mt-3 sm:mt-4 bg-rose-50 border border-rose-200 text-rose-800 px-4 py-3 rounded-xl flex items-center gap-3">
                    <span class="text-lg">❌</span>
                    <p class="text-sm font-medium">{{ session('error') }}</p>
                </div>
            @endif

            <main class="flex-1 p-3 sm:p-4 lg:p-8 overflow-x-hidden overflow-y-auto page-content">
                {{ $slot }}
            </main>

            <footer class="px-4 lg:px-8 py-3 border-t border-slate-100 bg-white flex-shrink-0">
                <p class="text-xs text-slate-400 text-center">
                    Finance Tracker &copy; {{ now()->year }}
                </p>
            </footer>
        </div>
    </div>

    {{-- ═══ LOGOUT CONFIRMATION MODAL ═══ --}}
    <div x-show="$store.logout.show" x-cloak
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-center justify-center p-4"
         style="background: rgba(0,0,0,0.55); backdrop-filter: blur(4px);"
         @keydown.escape.window="$store.logout.close()">

        <div x-show="$store.logout.show"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95 translate-y-2"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-95 translate-y-2"
             @click.outside="$store.logout.close()"
             class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl w-full max-w-sm overflow-hidden">

            {{-- Modal Header --}}
            <div class="px-6 pt-6 pb-4 text-center">
                <div class="w-16 h-16 bg-rose-100 dark:bg-rose-900/40 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-slate-800 dark:text-slate-100 mb-1">Keluar dari Akun?</h3>
                <p class="text-sm text-slate-500 dark:text-slate-400">
                    Kamu akan keluar dari <span class="font-semibold text-slate-700 dark:text-slate-300">Finance Tracker</span>. Pastikan semua perubahan sudah tersimpan.
                </p>
            </div>

            {{-- User info strip --}}
            <div class="mx-6 mb-5 bg-slate-50 dark:bg-slate-700/50 rounded-xl px-4 py-3 flex items-center gap-3">
                <div class="w-9 h-9 bg-primary-500 rounded-xl flex items-center justify-center flex-shrink-0">
                    <span class="text-white text-sm font-bold">{{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}</span>
                </div>
                <div class="min-w-0">
                    <p class="text-sm font-semibold text-slate-700 dark:text-slate-200 truncate">{{ auth()->user()->name ?? 'Pengguna' }}</p>
                    <p class="text-xs text-slate-400 dark:text-slate-500 truncate">{{ auth()->user()->email ?? '' }}</p>
                </div>
            </div>

            {{-- Action buttons --}}
            <div class="px-6 pb-6 flex gap-3">
                <button type="button"
                        @click="$store.logout.close()"
                        class="flex-1 px-4 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600
                               text-sm font-semibold text-slate-600 dark:text-slate-300
                               bg-white dark:bg-slate-700
                               hover:bg-slate-50 dark:hover:bg-slate-600
                               transition-colors focus:outline-none focus:ring-2 focus:ring-slate-300">
                    Batal
                </button>
                <button type="button"
                        @click="$store.logout.doLogout()"
                        class="flex-1 px-4 py-2.5 rounded-xl
                               text-sm font-semibold text-white
                               bg-rose-500 hover:bg-rose-600 active:bg-rose-700
                               transition-colors focus:outline-none focus:ring-2 focus:ring-rose-400 focus:ring-offset-2
                               flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Ya, Keluar
                </button>
            </div>
        </div>
    </div>

    @livewire('notification')

    @livewireScripts(['defer' => true])

    {{-- ── Livewire 419 CSRF handler: refresh token & retry silently, no browser popup ── --}}
    <script>
    (function () {
        // Override fetch globally so Livewire's internal requests
        // are intercepted before the browser can show a native dialog.
        var _origFetch = window.fetch;
        window.fetch = async function (input, init) {
            var res = await _origFetch(input, init);

            // Only intercept Livewire update/upload calls returning 419
            var url = (typeof input === 'string') ? input : (input.url || '');
            if (res.status === 419 && url.indexOf('/livewire/') !== -1) {
                // 1. Silently get a fresh CSRF token via a HEAD request to the current page
                try {
                    var refresh = await _origFetch(window.location.href, {
                        method: 'GET',
                        credentials: 'same-origin',
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    });
                    var html = await refresh.text();
                    var match = html.match(/<meta name="csrf-token" content="([^"]+)"/);
                    if (match) {
                        var newToken = match[1];
                        // Update meta tag
                        var meta = document.querySelector('meta[name="csrf-token"]');
                        if (meta) meta.setAttribute('content', newToken);
                        // Update init headers with new token and retry
                        var newInit = Object.assign({}, init);
                        newInit.headers = Object.assign({}, init && init.headers, {
                            'X-CSRF-TOKEN': newToken
                        });
                        return _origFetch(input, newInit);
                    }
                } catch (e) {}

                // If refresh failed, redirect to login cleanly (no browser dialog)
                window.location.href = '{{ route("login") }}';
                // Return a dummy response to stop further processing
                return new Response('', { status: 200 });
            }

            return res;
        };
    })();
    </script>

    <script>
    document.addEventListener('alpine:init', function () {
        Alpine.store('logout', {
            show: false,
            open() { this.show = true; },
            close() { this.show = false; },
            async doLogout() {
                var token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                try {
                    await fetch('{{ route("logout") }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': token,
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                        },
                        credentials: 'same-origin',
                    });
                } catch(e) {}
                window.location.href = '{{ route("login") }}';
            }
        });
    });
    </script>

    {{-- ── Currency Formatter Script (global) ── --}}
    <script>
    /**
     * Strategi: input display (data-currency) hanya untuk tampilan & formatting.
     * Nilai raw dikirim ke Livewire lewat hidden input (wire:model, data-currency-model).
     *
     * Struktur HTML per field:
     *   <input type="hidden"  wire:model="fieldName" data-currency-model>
     *   <input type="text"    data-currency inputmode="numeric" placeholder="0" autocomplete="off">
     */

    function currencyStripDots(val) {
        // Hapus titik ribuan dan karakter non-digit
        return String(val).replace(/\./g, '').replace(/[^0-9]/g, '');
    }

    function currencyFormat(raw) {
        if (!raw || raw === '0' || raw === '') return '';
        var num = parseInt(raw, 10);
        if (isNaN(num)) return '';
        // Format dengan pemisah ribuan titik (locale id-ID)
        return num.toLocaleString('id-ID');
    }

    function syncHiddenInput(hidden, rawValue) {
        // Gunakan native setter agar Livewire/Alpine mendeteksi perubahan
        var nativeSetter = Object.getOwnPropertyDescriptor(window.HTMLInputElement.prototype, 'value').set;
        nativeSetter.call(hidden, rawValue);
        hidden.dispatchEvent(new Event('input', { bubbles: true }));
    }

    function bindCurrencyInputs() {
        document.querySelectorAll('input[data-currency]').forEach(function(display) {
            if (display._currencyBound) return;
            display._currencyBound = true;

            // Hidden input harus tepat SEBELUM display input di DOM
            var hidden = display.previousElementSibling;
            var hasHidden = hidden && hidden.hasAttribute('data-currency-model');

            // Tampilkan nilai awal (saat mode edit) — ambil raw dari hidden input
            // Hidden input mungkin berisi angka murni (dari PHP/Livewire)
            if (hasHidden && hidden.value) {
                var initRaw = currencyStripDots(hidden.value);
                display.value = currencyFormat(initRaw);
            }

            // ── Real-time formatting saat mengetik ──
            display.addEventListener('input', function() {
                var cursorPos = this.selectionStart;
                var oldLen    = this.value.length;

                var raw = currencyStripDots(this.value);
                var formatted = currencyFormat(raw);
                this.value = formatted;

                // Jaga posisi kursor tetap natural
                var newLen = this.value.length;
                var delta  = newLen - oldLen;
                try { this.setSelectionRange(cursorPos + delta, cursorPos + delta); } catch(e) {}

                // Sync raw value ke hidden input → Livewire property
                if (hasHidden) {
                    syncHiddenInput(hidden, raw);
                }
            });

            // ── Paste: strip dulu, lalu format ──
            display.addEventListener('paste', function(e) {
                e.preventDefault();
                var pasted = (e.clipboardData || window.clipboardData).getData('text');
                var raw = currencyStripDots(pasted);
                this.value = currencyFormat(raw);
                if (hasHidden) syncHiddenInput(hidden, raw);
            });

            // ── Blur: pastikan tampilan tetap rapi ──
            display.addEventListener('blur', function() {
                var raw = currencyStripDots(this.value);
                this.value = currencyFormat(raw);
                if (hasHidden && raw !== hidden.value) {
                    syncHiddenInput(hidden, raw);
                }
            });
        });
    }

    function rebindCurrencyInputs() {
        // Reset flag supaya input yang baru muncul (dari modal re-render) ikut di-bind
        document.querySelectorAll('input[data-currency]').forEach(function(el) {
            delete el._currencyBound;
        });
        bindCurrencyInputs();
    }

    // Bind saat halaman pertama kali load
    document.addEventListener('DOMContentLoaded', bindCurrencyInputs);

    // Bind ulang setelah navigasi Livewire (SPA-style)
    document.addEventListener('livewire:navigated', rebindCurrencyInputs);

    // Bind ulang setelah setiap Livewire re-render (modal buka/tutup, dsb)
    // Gunakan livewire:morph-updated (Livewire v3) dan livewire:update sebagai fallback
    document.addEventListener('livewire:morph-updated', function() { setTimeout(rebindCurrencyInputs, 20); });
    document.addEventListener('livewire:update',        function() { setTimeout(rebindCurrencyInputs, 20); });

    // Bind ulang saat custom event di-dispatch (mis. dari komponen Livewire)
    document.addEventListener('currency:rebind', rebindCurrencyInputs);
    </script>

    @stack('scripts')
</body>
</html>