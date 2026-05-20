<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 — Halaman Tidak Ditemukan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center p-6">
    <div class="text-center max-w-md">
        <div class="text-8xl mb-6">🗺️</div>
        <h1 class="text-6xl font-black text-slate-800 mb-3">404</h1>
        <h2 class="text-xl font-bold text-slate-600 mb-3">Halaman Tidak Ditemukan</h2>
        <p class="text-slate-400 mb-8">Halaman yang Anda cari tidak ada atau sudah dipindahkan.</p>
        <a href="{{ url('/dashboard') }}"
           class="inline-flex items-center gap-2 px-6 py-3 bg-green-500 text-white font-semibold rounded-xl hover:bg-green-600 transition-colors shadow-lg shadow-green-500/25">
            🏠 Kembali ke Dashboard
        </a>
    </div>
</body>
</html>
