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
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
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
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .auth-bg {
            background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 50%, #bfdbfe 100%);
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.7);
        }
        .input-field {
            width: 100%;
            padding: 0.65rem 1rem;
            border: 1.5px solid #bfdbfe;
            border-radius: 0.75rem;
            font-size: 0.875rem;
            color: #1e40af;
            background: rgba(239, 246, 255, 0.6);
            outline: none;
            transition: all 0.2s;
        }
        .input-field:focus {
            border-color: #3b82f6;
            background: #fff;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
        }
        .input-field::placeholder { color: #93c5fd; }
        .btn-primary {
            width: 100%;
            padding: 0.75rem 1rem;
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            color: white;
            font-weight: 600;
            font-size: 0.9rem;
            border-radius: 0.75rem;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
            box-shadow: 0 4px 14px rgba(59, 130, 246, 0.35);
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            box-shadow: 0 6px 20px rgba(59, 130, 246, 0.45);
            transform: translateY(-1px);
        }
        .btn-primary:active { transform: translateY(0); }
        .floating-shape {
            position: absolute;
            border-radius: 50%;
            background: rgba(59, 130, 246, 0.08);
            animation: float 6s ease-in-out infinite;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        .card-enter { animation: cardEnter 0.5s ease-out; }
        @keyframes cardEnter {
            from { opacity: 0; transform: translateY(30px) scale(0.95); }
            to   { opacity: 1; transform: translateY(0) scale(1); }
        }
    </style>
</head>
<body class="h-full auth-bg flex items-center justify-center min-h-screen relative overflow-hidden">

    <!-- Floating decorations -->
    <div class="floating-shape w-64 h-64 -top-16 -left-16" style="animation-delay: 0s;"></div>
    <div class="floating-shape w-48 h-48 top-1/3 -right-12" style="animation-delay: 2s;"></div>
    <div class="floating-shape w-32 h-32 bottom-16 left-1/4" style="animation-delay: 4s;"></div>
    <div class="floating-shape w-20 h-20 bottom-1/3 right-1/4" style="animation-delay: 1s;"></div>

    <div class="w-full max-w-md px-4 py-8 card-enter">
        <!-- Logo & Title -->
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-gradient-to-br from-primary-400 to-primary-600 rounded-2xl flex items-center justify-center shadow-lg shadow-primary-500/30 mx-auto mb-4">
                <span class="text-white text-3xl">💰</span>
            </div>
            <h1 class="text-2xl font-bold text-primary-900">Finance Tracker</h1>
            <p class="text-sm text-primary-400 mt-1">Masuk ke akun Anda</p>
        </div>

        <!-- Card -->
        <div class="glass-card rounded-2xl shadow-xl shadow-primary-200/50 p-8">
            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-5 text-sm flex items-center gap-2">
                    <span>⚠️</span>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            @if(session('status'))
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-5 text-sm">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login.post') }}" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-sm font-semibold text-primary-700 mb-1.5">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                           class="input-field @error('email') border-red-400 @enderror"
                           placeholder="nama@email.com" required autofocus>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-primary-700 mb-1.5">Password</label>
                    <input type="password" name="password"
                           class="input-field @error('password') border-red-400 @enderror"
                           placeholder="••••••••" required>
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded accent-primary-500">
                        <span class="text-sm text-primary-600">Ingat saya</span>
                    </label>
                </div>

                <button type="submit" class="btn-primary mt-2">
                    Masuk
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-sm text-slate-500">
                    Belum punya akun?
                    <a href="{{ route('register') }}" class="font-semibold text-primary-600 hover:text-primary-800 transition-colors">Daftar sekarang</a>
                </p>
            </div>
        </div>

        <p class="text-center text-xs text-primary-300 mt-6">Finance Tracker © {{ now()->year }}</p>
    </div>

</body>
</html>
