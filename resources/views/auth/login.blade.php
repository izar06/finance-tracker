<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login — Finance Tracker</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="icon" href="{{ asset('assets/icon.png') }}">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50:  '#eff6ff',
                            100: '#dbeafe',
                            200: '#bfdbfe',
                            300: '#93c5fd',
                            400: '#60a5fa',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a',
                        },
                    },
                    fontFamily: { sans: ['Plus Jakarta Sans', 'ui-sans-serif', 'system-ui'] },
                    keyframes: {
                        'blob-move': {
                            '0%, 100%': { transform: 'translate(0px, 0px) scale(1)' },
                            '33%':       { transform: 'translate(20px, -25px) scale(1.08)' },
                            '66%':       { transform: 'translate(-15px, 15px) scale(0.95)' },
                        },
                        'float-y': {
                            '0%, 100%': { transform: 'translateY(0px)' },
                            '50%':      { transform: 'translateY(-14px)' },
                        },
                        'fade-up': {
                            from: { opacity: '0', transform: 'translateY(28px)' },
                            to:   { opacity: '1', transform: 'translateY(0)' },
                        },
                        'slide-in-right': {
                            from: { opacity: '0', transform: 'translateX(40px)' },
                            to:   { opacity: '1', transform: 'translateX(0)' },
                        },
                        'pulse-ring': {
                            '0%':   { transform: 'scale(0.95)', boxShadow: '0 0 0 0 rgba(59,130,246,0.35)' },
                            '70%':  { transform: 'scale(1)',    boxShadow: '0 0 0 10px rgba(59,130,246,0)' },
                            '100%': { transform: 'scale(0.95)', boxShadow: '0 0 0 0 rgba(59,130,246,0)' },
                        },
                        'dash-icon': {
                            '0%, 100%': { transform: 'translateY(0) rotate(0deg)' },
                            '25%':      { transform: 'translateY(-4px) rotate(-3deg)' },
                            '75%':      { transform: 'translateY(4px) rotate(3deg)' },
                        },
                    },
                    animation: {
                        'blob1':         'blob-move 9s ease-in-out infinite',
                        'blob2':         'blob-move 12s ease-in-out infinite 2s',
                        'blob3':         'blob-move 8s ease-in-out infinite 4s',
                        'float1':        'float-y 5s ease-in-out infinite',
                        'float2':        'float-y 7s ease-in-out infinite 1.5s',
                        'float3':        'float-y 6s ease-in-out infinite 3s',
                        'fade-up':       'fade-up 0.65s ease-out both',
                        'fade-up-delay': 'fade-up 0.65s ease-out 0.15s both',
                        'fade-up-d2':    'fade-up 0.65s ease-out 0.3s both',
                        'fade-up-d3':    'fade-up 0.65s ease-out 0.45s both',
                        'slide-right':   'slide-in-right 0.6s ease-out both',
                        'pulse-ring':    'pulse-ring 2.2s ease-in-out infinite',
                        'dash-icon':     'dash-icon 4s ease-in-out infinite',
                    },
                },
            },
        };
    </script>
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }

        /* ── Left hero background ── */
        .hero-bg {
            background: linear-gradient(145deg, #1e3a8a 0%, #1d4ed8 35%, #3b82f6 65%, #7c3aed 100%);
        }

        /* ── Glassmorphism helpers ── */
        .glass {
            background: rgba(255,255,255,0.10);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            border: 1px solid rgba(255,255,255,0.18);
        }
        .glass-card {
            background: rgba(255,255,255,0.92);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(255,255,255,0.8);
        }
        .glass-stat {
            background: rgba(255,255,255,0.13);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.2);
        }

        /* ── Blob decorations ── */
        .blob {
            position: absolute;
            border-radius: 9999px;
            filter: blur(70px);
            opacity: 0.35;
            pointer-events: none;
        }

        /* ── Input field ── */
        .input-field {
            width: 100%;
            padding: 0.7rem 1rem 0.7rem 2.75rem;
            border: 1.5px solid #bfdbfe;
            border-radius: 0.75rem;
            font-size: 0.875rem;
            color: #1e40af;
            background: rgba(239,246,255,0.65);
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .input-field:focus {
            border-color: #3b82f6;
            background: #fff;
            box-shadow: 0 0 0 4px rgba(59,130,246,0.14);
        }
        .input-field::placeholder { color: #93c5fd; }

        /* ── Primary button ── */
        .btn-primary {
            width: 100%;
            padding: 0.82rem 1rem;
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 60%, #1d4ed8 100%);
            color: #fff;
            font-weight: 700;
            font-size: 0.9rem;
            border-radius: 0.85rem;
            border: none;
            cursor: pointer;
            transition: transform 0.18s, box-shadow 0.18s;
            box-shadow: 0 5px 18px rgba(59,130,246,0.40);
            letter-spacing: 0.01em;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 26px rgba(59,130,246,0.50);
        }
        .btn-primary:active { transform: translateY(0); }

        /* ── Inline dashboard mini-cards ── */
        .dash-card-inline {
            border-radius: 1rem;
            flex-shrink: 0;
        }

        /* ── Eye toggle ── */
        #togglePassword { cursor: pointer; }

        /* ── 60 / 40 split layout ── */
        .section-hero  { display: none; }
        .section-login { flex: 1; }
        @media (min-width: 1024px) {
            .section-hero  { display: flex; width: 60%; flex-shrink: 0; }
            .section-login { width: 40%;   flex: none; }
        }

        /* ── Staggered animation helpers ── */
        .anim-d0  { animation-delay: 0s; }
        .anim-d1  { animation-delay: 0.1s; }
        .anim-d2  { animation-delay: 0.2s; }
        .anim-d3  { animation-delay: 0.3s; }
        .anim-d4  { animation-delay: 0.4s; }
        .anim-d5  { animation-delay: 0.55s; }
    </style>
</head>

<body style="font-family:'Plus Jakarta Sans',sans-serif; display:flex; width:100vw; min-height:100vh; overflow-x:hidden;">

    <!-- ══════════════════════════════════════════════
         LEFT  ·  Hero / Brand Side
    ══════════════════════════════════════════════ -->
    <section class="hidden lg:flex hero-bg relative overflow-hidden flex-col justify-center p-10 xl:p-14 gap-0 min-h-screen" style="width:60%; flex-shrink:0;">

        <!-- Blobs -->
        <div class="blob w-96 h-96 bg-blue-300   top-[-6rem]  left-[-4rem]  animate-blob1"></div>
        <div class="blob w-80 h-80 bg-violet-400  bottom-[-3rem] right-[-3rem] animate-blob2"></div>
        <div class="blob w-64 h-64 bg-sky-300     top-1/2 left-1/3          animate-blob3"></div>

        <!-- ── Top Logo ── -->
        <div class="relative z-10 animate-fade-up mb-7">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-white/20 border border-white/30 flex items-center justify-center shadow-lg animate-pulse-ring">
                    <span class="text-xl">💰</span>
                </div>
                <span class="text-white font-bold text-lg tracking-tight">Finance Tracker</span>
            </div>
        </div>

        <!-- ── Main Copy ── -->
        <div class="relative z-10 w-full">
            <!-- Badge -->
            <div class="animate-fade-up anim-d1 mb-6 inline-flex">
                <span class="glass text-white/90 text-xs font-semibold px-4 py-1.5 rounded-full tracking-wide">
                    ✦ Platform Keuangan #1 di Indonesia
                </span>
            </div>

            <!-- Headline -->
            <h1 class="animate-fade-up anim-d2 text-4xl xl:text-5xl font-extrabold text-white leading-[1.12] tracking-tight mb-5">
                Atur Keuanganmu<br>
                <span class="text-sky-200">Sesuai Rencana.</span>
            </h1>

            <!-- Subheadline -->
            <p class="animate-fade-up anim-d3 text-white/70 text-base xl:text-lg leading-relaxed max-w-md mb-7">
                Catat, analisis, dan kelola keuangan harian untuk masa depan yang lebih baik — semua dalam satu platform yang cerdas.
            </p>

            <!-- Feature cards -->
            <div class="animate-fade-up anim-d4 grid grid-cols-1 gap-2.5 max-w-md mb-6">

                <div class="glass flex items-start gap-3 p-3.5 rounded-2xl">
                    <div class="w-9 h-9 rounded-xl bg-blue-400/30 border border-white/20 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-sky-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-white font-semibold text-sm">Catat Transaksi Harian</p>
                        <p class="text-white/55 text-xs mt-0.5 leading-relaxed">Rekam pemasukan & pengeluaran secara real-time dengan kategori otomatis.</p>
                    </div>
                </div>

                <div class="glass flex items-start gap-3 p-3.5 rounded-2xl">
                    <div class="w-9 h-9 rounded-xl bg-violet-400/30 border border-white/20 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-violet-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-white font-semibold text-sm">Laporan & Analisis</p>
                        <p class="text-white/55 text-xs mt-0.5 leading-relaxed">Visualisasi data keuangan dengan chart interaktif dan insight cerdas.</p>
                    </div>
                </div>

                <div class="glass flex items-start gap-3 p-3.5 rounded-2xl">
                    <div class="w-9 h-9 rounded-xl bg-green-400/30 border border-white/20 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-green-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-white font-semibold text-sm">Aman &amp; Terpercaya</p>
                        <p class="text-white/55 text-xs mt-0.5 leading-relaxed">Enkripsi end-to-end dan perlindungan data privasi standar industri.</p>
                    </div>
                </div>
            </div>

            <!-- ── Fintech Illustration Row (in-flow, no absolute) ── -->
            <div class="animate-fade-up anim-d5 flex items-end gap-3 max-w-md" aria-hidden="true">

                <!-- Wallet card -->
                <div class="glass dash-card-inline p-4 flex-1 animate-float1">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-8 h-8 rounded-lg bg-blue-400/40 flex items-center justify-center text-base flex-shrink-0">💳</div>
                        <div>
                            <p class="text-white/70 text-[10px] font-semibold leading-none">Total Saldo</p>
                            <p class="text-sky-200 text-sm font-extrabold mt-0.5">Rp 8.450.000</p>
                        </div>
                    </div>
                    <div class="h-1 rounded-full bg-white/10">
                        <div class="h-1 rounded-full bg-sky-300/80 w-3/5"></div>
                    </div>
                </div>

                <!-- Chart mini -->
                <div class="glass dash-card-inline p-3 flex-1 animate-float2">
                    <p class="text-white/70 text-[10px] font-semibold mb-2">Pengeluaran</p>
                    <div class="flex items-end gap-1 h-10">
                        <div class="flex-1 rounded-t-sm bg-violet-300/60" style="height:40%"></div>
                        <div class="flex-1 rounded-t-sm bg-violet-300/60" style="height:70%"></div>
                        <div class="flex-1 rounded-t-sm bg-sky-300/80"    style="height:55%"></div>
                        <div class="flex-1 rounded-t-sm bg-violet-300/60" style="height:85%"></div>
                        <div class="flex-1 rounded-t-sm bg-sky-300/80"    style="height:60%"></div>
                        <div class="flex-1 rounded-t-sm bg-sky-200"       style="height:100%"></div>
                    </div>
                </div>

                <!-- Coin emoji pill -->
                <div class="glass dash-card-inline px-4 py-3 flex items-center gap-2 animate-float3">
                    <span class="text-2xl animate-dash-icon inline-block">🪙</span>
                    <div>
                        <p class="text-white/70 text-[10px] font-semibold leading-none">Tabungan</p>
                        <p class="text-yellow-200 text-sm font-bold">+12%</p>
                    </div>
                </div>

            </div>

        </div>
        <!-- END main copy -->

    </section>


    <!-- ══════════════════════════════════════════════
         RIGHT  ·  Login Form
    ══════════════════════════════════════════════ -->
    <section class="flex items-center justify-center px-5 py-10 min-h-screen relative overflow-hidden" style="flex: 1; background: linear-gradient(160deg, #eff6ff 0%, #dbeafe 40%, #bfdbfe 75%, #a5b4fc 100%);">

        <!-- Subtle background blobs for right side -->
        <div class="absolute w-72 h-72 rounded-full bg-blue-300/30 blur-3xl -top-16 -right-16 pointer-events-none"></div>
        <div class="absolute w-56 h-56 rounded-full bg-violet-300/20 blur-3xl bottom-10 -left-10 pointer-events-none"></div>

        <div class="w-full max-w-md relative z-10">

            <!-- Mobile logo (shown only on small screens) -->
            <div class="lg:hidden text-center mb-8 animate-fade-up">
                <div class="w-14 h-14 bg-gradient-to-br from-primary-400 to-primary-700 rounded-2xl flex items-center justify-center shadow-lg shadow-primary-500/30 mx-auto mb-3">
                    <span class="text-2xl">💰</span>
                </div>
                <h1 class="text-xl font-bold text-primary-900">Finance Tracker</h1>
                <p class="text-sm text-primary-400 mt-0.5">Platform keuangan personal terbaik</p>
            </div>

            <!-- Card -->
            <div class="glass-card rounded-3xl shadow-2xl shadow-primary-200/40 p-8 xl:p-9 animate-slide-right">

                <!-- Heading -->
                <div class="mb-7">
                    <h2 class="text-2xl font-extrabold text-slate-800 tracking-tight">Masuk ke akun Anda</h2>
                    <p class="text-sm text-slate-400 mt-1">Selamat datang kembali 👋</p>
                </div>

                <!-- ── Alerts ── -->
                @if($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-600 px-4 py-3 rounded-xl mb-5 text-sm flex items-center gap-2">
                        <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                @if(session('status'))
                    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-5 text-sm flex items-center gap-2">
                        <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        <span>{{ session('status') }}</span>
                    </div>
                @endif

                <!-- ── Form ── -->
                <form method="POST" action="{{ route('login.post') }}" class="space-y-5" novalidate>
                    @csrf

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-semibold text-slate-600 mb-1.5">Email</label>
                        <div class="relative">
                            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-primary-300 pointer-events-none">
                                <svg class="w-4.5 h-4.5 w-[18px] h-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </span>
                            <input id="email" type="email" name="email" value="{{ old('email') }}"
                                   class="input-field @error('email') !border-red-400 @enderror"
                                   placeholder="nama@email.com" required autofocus autocomplete="email">
                        </div>
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-semibold text-slate-600 mb-1.5">Password</label>
                        <div class="relative">
                            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-primary-300 pointer-events-none">
                                <svg class="w-[18px] h-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                            </span>
                            <input id="password" type="password" name="password"
                                   class="input-field pr-11 @error('password') !border-red-400 @enderror"
                                   placeholder="••••••••" required autocomplete="current-password">
                            <!-- Eye toggle -->
                            <button type="button" id="togglePassword"
                                    class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-primary-500 transition-colors"
                                    aria-label="Toggle password visibility">
                                <svg id="eyeOpen" class="w-[18px] h-[18px]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                <svg id="eyeClosed" class="w-[18px] h-[18px] hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Remember me -->
                    <div class="flex items-center justify-between">
                        <label class="flex items-center gap-2 cursor-pointer select-none">
                            <input type="checkbox" name="remember"
                                   class="w-4 h-4 rounded accent-primary-500 cursor-pointer">
                            <span class="text-sm text-slate-500">Ingat saya</span>
                        </label>
                        {{-- Uncomment & wire route if forgot-password exists --}}
                        {{-- <a href="{{ route('password.request') }}" class="text-sm font-semibold text-primary-600 hover:text-primary-800 transition-colors">Lupa password?</a> --}}
                        <span class="text-sm font-semibold text-primary-600 hover:text-primary-800 transition-colors cursor-pointer">Lupa password?</span>
                    </div>

                    <!-- Submit -->
                    <button type="submit" class="btn-primary mt-1">
                        Masuk Sekarang
                    </button>
                </form>

                <!-- Register link -->
                <p class="text-center text-sm text-slate-500 mt-6">
                    Belum punya akun?
                    <a href="{{ route('register') }}" class="font-semibold text-primary-600 hover:text-primary-800 transition-colors">Daftar sekarang</a>
                </p>

            </div>

            <p class="text-center text-xs text-black mt-5">
                Finance Tracker &copy; {{ now()->year }} &nbsp;·&nbsp; All rights reserved
            </p>
        </div>
    </section>

    <!-- ══════════════════════════════════════════════
         JS  ·  Password toggle
    ══════════════════════════════════════════════ -->
    <script>
        const toggleBtn   = document.getElementById('togglePassword');
        const passwordInp = document.getElementById('password');
        const eyeOpen     = document.getElementById('eyeOpen');
        const eyeClosed   = document.getElementById('eyeClosed');

        toggleBtn.addEventListener('click', () => {
            const isPassword = passwordInp.type === 'password';
            passwordInp.type = isPassword ? 'text' : 'password';
            eyeOpen.classList.toggle('hidden', isPassword);
            eyeClosed.classList.toggle('hidden', !isPassword);
        });
    </script>

</body>
</html>