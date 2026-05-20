<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dalam Pemeliharaan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center p-6">
    <div class="text-center max-w-md">
        <div class="text-8xl mb-6">🔧</div>
        <h1 class="text-4xl font-black text-slate-800 mb-3">Dalam Pemeliharaan</h1>
        <p class="text-slate-400 mb-4">Aplikasi sedang dalam pemeliharaan. Kami akan segera kembali.</p>
        @if(isset($exception) && $exception->getMessage())
            <p class="text-sm text-slate-400 bg-slate-100 rounded-xl px-4 py-3">{{ $exception->getMessage() }}</p>
        @endif
    </div>
</body>
</html>
