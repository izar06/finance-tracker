<div>
    {{-- Stats Overview --}}
    <div x-data="{ get hidden() { return $store.finance.hidden; }, toggle() { $store.finance.toggle(); } }"
         class="mb-4 sm:mb-6">

        {{-- Section header with toggle button --}}
        <div class="flex items-center justify-between mb-3">
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Ringkasan Keuangan</p>
            <button @click="toggle()"
                    class="flex items-center gap-1.5 text-xs font-medium text-slate-500 hover:text-slate-700 bg-slate-100 hover:bg-slate-200 px-2.5 py-1.5 rounded-lg transition-all duration-200 select-none">
                <span x-show="!hidden" class="flex items-center gap-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                    </svg>
                    Sembunyikan
                </span>
                <span x-show="hidden" class="flex items-center gap-1.5">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    Tampilkan
                </span>
            </button>
        </div>

        <div class="grid grid-cols-2 xl:grid-cols-4 gap-3 sm:gap-5">

            <div class="bg-white rounded-2xl p-3 sm:p-5 border border-slate-100 shadow-sm">
                <div class="flex items-start justify-between mb-2 sm:mb-3">
                    <div class="w-9 h-9 sm:w-11 sm:h-11 bg-emerald-50 rounded-xl flex items-center justify-center text-xl">📈</div>
                    <span class="text-xs font-semibold text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded-full hidden xs:block">Bulan Ini</span>
                </div>
                <p class="text-sm sm:text-xl font-bold text-slate-800 mb-0.5 sm:mb-1 leading-tight">
                    <span x-show="!hidden">Rp {{ number_format($this->monthlyIncome, 0, ',', '.') }}</span>
                    <span x-show="hidden" class="tracking-widest text-slate-400">Rp ••••••</span>
                </p>
                <p class="text-xs sm:text-sm text-slate-500">Total Pemasukan</p>
            </div>

            <div class="bg-white rounded-2xl p-3 sm:p-5 border border-slate-100 shadow-sm">
                <div class="flex items-start justify-between mb-2 sm:mb-3">
                    <div class="w-9 h-9 sm:w-11 sm:h-11 bg-rose-50 rounded-xl flex items-center justify-center text-xl">📉</div>
                    <span class="text-xs font-semibold text-rose-600 bg-rose-50 px-1.5 py-0.5 rounded-full hidden xs:block">Bulan Ini</span>
                </div>
                <p class="text-sm sm:text-xl font-bold text-slate-800 mb-0.5 sm:mb-1 leading-tight">
                    <span x-show="!hidden">Rp {{ number_format($this->monthlyExpense, 0, ',', '.') }}</span>
                    <span x-show="hidden" class="tracking-widest text-slate-400">Rp ••••••</span>
                </p>
                <p class="text-xs sm:text-sm text-slate-500">Total Pengeluaran</p>
            </div>

            <div class="bg-white rounded-2xl p-3 sm:p-5 border border-slate-100 shadow-sm">
                <div class="flex items-start justify-between mb-2 sm:mb-3">
                    <div class="w-9 h-9 sm:w-11 sm:h-11 {{ $this->monthlyBalance >= 0 ? 'bg-blue-50' : 'bg-orange-50' }} rounded-xl flex items-center justify-center text-xl">
                        {{ $this->monthlyBalance >= 0 ? '✅' : '⚠️' }}
                    </div>
                    <span class="text-xs font-semibold {{ $this->monthlyBalance >= 0 ? 'text-blue-600 bg-blue-50' : 'text-orange-600 bg-orange-50' }} px-2 py-1 rounded-full">
                        {{ $this->monthlyBalance >= 0 ? 'Surplus' : 'Defisit' }}
                    </span>
                </div>
                <p class="text-sm sm:text-xl font-bold leading-tight {{ $this->monthlyBalance >= 0 ? 'text-slate-800' : 'text-rose-600' }} mb-0.5 sm:mb-1">
                    <span x-show="!hidden">Rp {{ number_format(abs($this->monthlyBalance), 0, ',', '.') }}</span>
                    <span x-show="hidden" class="tracking-widest {{ $this->monthlyBalance >= 0 ? 'text-slate-400' : 'text-rose-300' }}">Rp ••••••</span>
                </p>
                <p class="text-xs sm:text-sm text-slate-500">Saldo Bulan Ini</p>
            </div>

            <div class="bg-gradient-to-br from-primary-500 to-primary-600 rounded-2xl p-3 sm:p-5 shadow-lg shadow-primary-500/20">
                <div class="flex items-start justify-between mb-3">
                    <div class="w-9 h-9 sm:w-11 sm:h-11 bg-white/20 rounded-xl flex items-center justify-center text-lg sm:text-xl">🏦</div>
                    <span class="text-xs font-semibold text-white/80 bg-white/20 px-1.5 py-0.5 rounded-full hidden xs:block">Total</span>
                </div>
                <p class="text-sm sm:text-xl font-bold text-white mb-0.5 sm:mb-1 leading-tight">
                    <span x-show="!hidden">Rp {{ number_format($this->totalAssets, 0, ',', '.') }}</span>
                    <span x-show="hidden" class="tracking-widest text-white/40">Rp ••••••</span>
                </p>
                <p class="text-xs sm:text-sm text-white/70">Nilai Aset</p>
            </div>
        </div>
    </div>

    {{-- Charts --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-4 sm:gap-6 mb-4 sm:mb-8">

        {{-- 12-month trend --}}
        <div class="xl:col-span-2 bg-white rounded-2xl p-4 sm:p-6 border border-slate-100 shadow-sm">
            <div class="flex items-center justify-between mb-3 sm:mb-5">
                <div>
                    <h3 class="font-bold text-slate-800">Tren Keuangan</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Pemasukan vs Pengeluaran — 12 bulan terakhir</p>
                </div>
            </div>
            <div class="relative w-full" style="height:220px">
                <canvas id="trendChart" style="position:absolute;top:0;left:0;width:100%;height:100%"
                        data-trend='@json($this->monthlyChartData)'
                        data-category='@json($this->categoryExpenseChartData)'></canvas>
            </div>
        </div>

        {{-- Category donut --}}
        <div class="bg-white rounded-2xl p-4 sm:p-6 border border-slate-100 shadow-sm">
            <div class="mb-3 sm:mb-5">
                <h3 class="font-bold text-slate-800">Pengeluaran per Kategori</h3>
                <p class="text-xs text-slate-400 mt-0.5">Bulan ini</p>
            </div>
            @if(count($this->categoryExpenseChartData['data']) > 0)
                <div class="relative w-full" style="height:210px">
                    <canvas id="categoryChart" style="position:absolute;top:0;left:0;width:100%;height:100%"
                            data-category='@json($this->categoryExpenseChartData)'></canvas>
                </div>
            @else
                <div class="flex flex-col items-center justify-center h-48 text-slate-400">
                    <span class="text-4xl mb-2">📊</span>
                    <p class="text-sm">Belum ada pengeluaran bulan ini</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Widget Row: Top Expenses + Urgent Goals --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6 mb-4 sm:mb-8">

        {{-- Top 5 Pengeluaran Terbesar Bulan Ini --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="flex items-center justify-between px-4 sm:px-6 py-3 sm:py-4 border-b border-slate-100">
                <div>
                    <h3 class="font-bold text-slate-800">💸 Pengeluaran Terbesar</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Top 5 bulan ini</p>
                </div>
                <a href="{{ route('transactions') }}" class="text-sm font-medium text-primary-600 hover:text-primary-700 transition-colors flex-shrink-0">
                    Lihat semua →
                </a>
            </div>

            @php
                $topExpenses      = $this->topExpenses;
                $topExpensesMax   = $topExpenses->max('amount') ?: 1;
                $rankColors       = ['bg-rose-500', 'bg-rose-400', 'bg-rose-300', 'bg-rose-200', 'bg-rose-100'];
                $rankTextColors   = ['text-rose-700', 'text-rose-600', 'text-rose-500', 'text-rose-400', 'text-rose-400'];
            @endphp

            @if($topExpenses->count() > 0)
                <div class="divide-y divide-slate-50">
                    @foreach($topExpenses as $i => $tx)
                        @php $pct = round(($tx->amount / $topExpensesMax) * 100); @endphp
                        <div class="flex items-center gap-3 px-4 sm:px-5 py-3 hover:bg-slate-50 transition-colors">
                            {{-- Rank badge --}}
                            <span class="w-5 h-5 rounded-full {{ $rankColors[$i] }} flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                                {{ $i + 1 }}
                            </span>

                            {{-- Info + bar --}}
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-slate-800 truncate leading-tight">{{ $tx->title }}</p>
                                <div class="flex items-center gap-2 mt-1">
                                    <div class="flex-1 bg-slate-100 rounded-full h-1.5">
                                        <div class="{{ $rankColors[$i] }} h-1.5 rounded-full transition-all duration-500"
                                             style="width: {{ $pct }}%"></div>
                                    </div>
                                    <span class="text-xs text-slate-400 flex-shrink-0">{{ $tx->category }}</span>
                                </div>
                            </div>

                            {{-- Amount --}}
                            <p class="text-sm font-bold {{ $rankTextColors[$i] }} flex-shrink-0 whitespace-nowrap"
                               x-data="{ get hidden() { return $store.finance.hidden; } }">
                                <span x-show="!hidden">Rp {{ number_format($tx->amount, 0, ',', '.') }}</span>
                                <span x-show="hidden" class="tracking-widest text-rose-300">••••••</span>
                            </p>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="flex flex-col items-center justify-center py-12 text-slate-400">
                    <span class="text-3xl block mb-2">💸</span>
                    <p class="text-sm">Belum ada pengeluaran bulan ini</p>
                    <a href="{{ route('transactions') }}" class="text-xs text-primary-500 mt-1 hover:underline">Tambah transaksi →</a>
                </div>
            @endif
        </div>

        {{-- Goals Paling Dekat Deadline --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="flex items-center justify-between px-4 sm:px-6 py-3 sm:py-4 border-b border-slate-100">
                <div>
                    <h3 class="font-bold text-slate-800">⏳ Deadline Terdekat</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Tujuan keuangan yang mendesak</p>
                </div>
                <a href="{{ route('goals') }}" class="text-sm font-medium text-primary-600 hover:text-primary-700 transition-colors flex-shrink-0">
                    Lihat semua →
                </a>
            </div>

            @php $urgentGoals = $this->urgentGoals; @endphp

            @if($urgentGoals->count() > 0)
                <div class="divide-y divide-slate-50 px-4 sm:px-6">
                    @foreach($urgentGoals as $goal)
                        @php
                            $daysLeft   = (int) now()->startOfDay()->diffInDays($goal->deadline->startOfDay(), false);
                            $pct        = $goal->progress_percentage;
                            $isOverdue  = $daysLeft < 0;
                            $isUrgent   = $daysLeft >= 0 && $daysLeft <= 30;

                            // Warna bar & badge berdasarkan sisa hari
                            if ($isOverdue) {
                                $barColor    = 'bg-rose-500';
                                $badgeBg     = 'bg-rose-50';
                                $badgeText   = 'text-rose-600';
                                $daysLabel   = abs($daysLeft) . ' hari terlambat';
                            } elseif ($daysLeft === 0) {
                                $barColor    = 'bg-rose-500';
                                $badgeBg     = 'bg-rose-50';
                                $badgeText   = 'text-rose-600';
                                $daysLabel   = 'Hari ini!';
                            } elseif ($isUrgent) {
                                $barColor    = 'bg-amber-400';
                                $badgeBg     = 'bg-amber-50';
                                $badgeText   = 'text-amber-600';
                                $daysLabel   = $daysLeft . ' hari lagi';
                            } else {
                                $barColor    = 'bg-primary-500';
                                $badgeBg     = 'bg-primary-50';
                                $badgeText   = 'text-primary-600';
                                $daysLabel   = $daysLeft . ' hari lagi';
                            }

                            // Estimasi apakah target bisa tercapai
                            $remaining      = max(0, $goal->target_amount - $goal->current_amount);
                            $dailyNeeded    = $daysLeft > 0 ? $remaining / $daysLeft : null;
                        @endphp

                        <div class="py-4">
                            {{-- Header: icon + nama + badge hari --}}
                            <div class="flex items-center justify-between mb-2 gap-2">
                                <div class="flex items-center gap-2 min-w-0">
                                    <span class="text-xl flex-shrink-0">{{ $goal->icon }}</span>
                                    <p class="text-sm font-semibold text-slate-800 truncate">{{ $goal->name }}</p>
                                </div>
                                <span class="text-xs font-semibold {{ $badgeBg }} {{ $badgeText }} px-2 py-0.5 rounded-full flex-shrink-0 whitespace-nowrap">
                                    {{ $daysLabel }}
                                </span>
                            </div>

                            {{-- Progress bar --}}
                            <div class="w-full bg-slate-100 rounded-full h-2 mb-2">
                                <div class="{{ $barColor }} h-2 rounded-full transition-all duration-700"
                                     style="width: {{ $pct }}%"></div>
                            </div>

                            {{-- Detail bawah --}}
                            <div class="flex items-center justify-between text-xs"
                                 x-data="{ get hidden() { return $store.finance.hidden; } }">
                                <span class="text-slate-400">
                                    <span x-show="!hidden">
                                        Rp {{ number_format($goal->current_amount, 0, ',', '.') }}
                                        <span class="text-slate-300">/</span>
                                        Rp {{ number_format($goal->target_amount, 0, ',', '.') }}
                                    </span>
                                    <span x-show="hidden" class="tracking-widest text-slate-300">•••••• / ••••••</span>
                                </span>
                                <span class="font-bold {{ $pct >= 100 ? 'text-emerald-600' : ($isOverdue ? 'text-rose-500' : 'text-slate-500') }}">
                                    {{ $pct }}%
                                </span>
                            </div>

                            {{-- Estimasi nabung per hari (hanya jika belum selesai & deadline belum lewat) --}}
                            @if($dailyNeeded !== null && $pct < 100 && !$isOverdue)
                                <p class="text-xs text-slate-400 mt-1.5"
                                   x-data="{ get hidden() { return $store.finance.hidden; } }">
                                    Perlu nabung ≈
                                    <span x-show="!hidden" class="font-medium text-slate-600">
                                        Rp {{ number_format($dailyNeeded, 0, ',', '.') }}/hari
                                    </span>
                                    <span x-show="hidden" class="tracking-widest text-slate-300">••••••</span>
                                </p>
                            @elseif($pct >= 100)
                                <p class="text-xs text-emerald-600 font-medium mt-1.5">✅ Target tercapai!</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <div class="flex flex-col items-center justify-center py-12 text-slate-400">
                    <span class="text-3xl block mb-2">🎯</span>
                    <p class="text-sm">Belum ada tujuan keuangan aktif</p>
                    <a href="{{ route('goals') }}" class="text-xs text-primary-500 mt-1 hover:underline">Buat tujuan pertama →</a>
                </div>
            @endif
        </div>

    </div>

    {{-- Bottom Row --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6">

        {{-- Recent Transactions --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="flex items-center justify-between px-4 sm:px-6 py-3 sm:py-4 border-b border-slate-100">
                <h3 class="font-bold text-slate-800">Transaksi Terbaru</h3>
                <a href="{{ route('transactions') }}" class="text-sm font-medium text-primary-600 hover:text-primary-700 transition-colors">
                    Lihat semua →
                </a>
            </div>
            <div class="divide-y divide-slate-50">
                @forelse($this->recentTransactions as $transaction)
                    <div class="flex items-center gap-3 px-4 sm:px-6 py-3 sm:py-3.5 hover:bg-slate-50 transition-colors">
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center text-sm flex-shrink-0
                                    {{ $transaction->type === 'income' ? 'bg-emerald-50' : 'bg-rose-50' }}">
                            {{ $transaction->type === 'income' ? '📥' : '📤' }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-slate-800 truncate">{{ $transaction->title }}</p>
                            <p class="text-xs text-slate-400 truncate">{{ $transaction->category }}<span class="hidden xs:inline"> · {{ $transaction->date->translatedFormat('d M Y') }}</span></p>
                        </div>
                        <p class="text-sm font-bold whitespace-nowrap flex-shrink-0 {{ $transaction->type === 'income' ? 'text-emerald-600' : 'text-rose-600' }}"
                           x-data="{ get hidden() { return $store.finance.hidden; } }">
                            <span x-show="!hidden">{{ $transaction->type === 'income' ? '+' : '-' }} Rp {{ number_format($transaction->amount, 0, ',', '.') }}</span>
                            <span x-show="hidden" class="tracking-widest {{ $transaction->type === 'income' ? 'text-emerald-300' : 'text-rose-300' }}">••••••</span>
                        </p>
                    </div>
                @empty
                    <div class="py-12 text-center text-slate-400">
                        <span class="text-3xl block mb-2">📋</span>
                        <p class="text-sm">Belum ada transaksi</p>
                        <a href="{{ route('transactions') }}" class="text-xs text-primary-500 mt-1 block hover:underline">Tambah sekarang →</a>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Goals Progress --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="flex items-center justify-between px-4 sm:px-6 py-3 sm:py-4 border-b border-slate-100">
                <h3 class="font-bold text-slate-800">Tujuan Keuangan Aktif</h3>
                <a href="{{ route('goals') }}" class="text-sm font-medium text-primary-600 hover:text-primary-700 transition-colors">
                    Lihat semua →
                </a>
            </div>
            <div class="divide-y divide-slate-50 px-6">
                @forelse($this->activeGoals as $goal)
                    <div class="py-4">
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center gap-2 min-w-0">
                                <span class="text-lg flex-shrink-0">{{ $goal->icon }}</span>
                                <p class="text-sm font-semibold text-slate-800 truncate">{{ $goal->name }}</p>
                            </div>
                            <span class="text-sm font-bold text-primary-600 flex-shrink-0 ml-2">{{ $goal->progress_percentage }}%</span>
                        </div>
                        <div class="w-full bg-slate-100 rounded-full h-2 mb-2">
                            <div class="h-2 rounded-full bg-gradient-to-r from-primary-400 to-primary-600 transition-all duration-700"
                                 style="width: {{ $goal->progress_percentage }}%"></div>
                        </div>
                        <div class="flex justify-between text-xs text-slate-400"
                             x-data="{ get hidden() { return $store.finance.hidden; } }">
                            <span>Terkumpul:
                                <span x-show="!hidden">Rp {{ number_format($goal->current_amount, 0, ',', '.') }}</span>
                                <span x-show="hidden" class="tracking-widest">••••••</span>
                            </span>
                            <span>Target:
                                <span x-show="!hidden">Rp {{ number_format($goal->target_amount, 0, ',', '.') }}</span>
                                <span x-show="hidden" class="tracking-widest">••••••</span>
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="py-12 text-center text-slate-400">
                        <span class="text-3xl block mb-2">🎯</span>
                        <p class="text-sm">Belum ada tujuan keuangan</p>
                        <a href="{{ route('goals') }}" class="text-xs text-primary-500 mt-1 block hover:underline">Buat tujuan pertama →</a>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Budget Overview --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="flex items-center justify-between px-4 sm:px-6 py-3 sm:py-4 border-b border-slate-100">
                <h3 class="font-bold text-slate-800">Anggaran Bulan Ini</h3>
                <a href="{{ route('budgets') }}" class="text-sm font-medium text-primary-600 hover:text-primary-700 transition-colors">
                    Kelola →
                </a>
            </div>
            <div class="px-4 sm:px-6 py-2 divide-y divide-slate-50">
                @forelse($this->budgetSummary as $budget)
                    @php
                        $pct = $budget->percentage;
                        $barColor = match($budget->status_color) {
                            'rose'  => 'bg-rose-500',
                            'amber' => 'bg-amber-400',
                            default => 'bg-emerald-500',
                        };
                        $pctColor = match($budget->status_color) {
                            'rose'  => 'text-rose-600',
                            'amber' => 'text-amber-500',
                            default => 'text-emerald-600',
                        };
                    @endphp
                    <div class="py-3">
                        <div class="flex items-center justify-between mb-1.5">
                            <p class="text-sm font-medium text-slate-700 truncate pr-2">{{ $budget->category }}</p>
                            <span class="text-xs font-bold {{ $pctColor }} flex-shrink-0">{{ $pct }}%</span>
                        </div>
                        <div class="w-full bg-slate-100 rounded-full h-1.5">
                            <div class="{{ $barColor }} h-1.5 rounded-full transition-all duration-500"
                                 style="width: {{ min(100, $pct) }}%"></div>
                        </div>
                        <div class="flex justify-between text-xs text-slate-400 mt-1"
                             x-data="{ get hidden() { return $store.finance.hidden; } }">
                            <span>
                                <span x-show="!hidden">Rp {{ number_format($budget->spent, 0, ',', '.') }}</span>
                                <span x-show="hidden" class="tracking-widest">••••••</span>
                            </span>
                            <span>/
                                <span x-show="!hidden">Rp {{ number_format($budget->amount, 0, ',', '.') }}</span>
                                <span x-show="hidden" class="tracking-widest">••••••</span>
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="py-12 text-center text-slate-400">
                        <span class="text-3xl block mb-2">💰</span>
                        <p class="text-sm">Belum ada anggaran</p>
                        <a href="{{ route('budgets') }}" class="text-xs text-primary-500 mt-1 block hover:underline">Buat anggaran pertama →</a>
                    </div>
                @endforelse
            </div>
        </div>

    </div>

    {{-- Tagihan Jatuh Tempo --}}
    @if($this->upcomingBills->count() > 0)
    <div class="mt-4 sm:mt-6 bg-amber-50 border border-amber-100 rounded-2xl overflow-hidden">
        <div class="flex items-center justify-between px-4 sm:px-6 py-3 sm:py-4 border-b border-amber-100">
            <div class="flex items-center gap-2">
                <span>⏰</span>
                <h3 class="font-bold text-amber-800">Tagihan Segera Jatuh Tempo</h3>
            </div>
            <a href="{{ route('bills') }}" class="text-sm font-medium text-amber-600 hover:text-amber-700">
                Lihat semua →
            </a>
        </div>
        <div class="divide-y divide-amber-100">
            @foreach($this->upcomingBills as $bill)
                @php
                    $days = $bill->days_until_due;
                    $dueLabel = match(true) {
                        $days < 0  => abs($days).' hari terlambat',
                        $days === 0 => 'Hari ini!',
                        $days === 1 => 'Besok',
                        default    => $days.' hari lagi',
                    };
                    $dueColor = $days <= 0 ? 'text-rose-600' : ($days <= 3 ? 'text-amber-600' : 'text-blue-600');
                @endphp
                <div class="flex items-center gap-3 px-4 sm:px-6 py-3">
                    <span class="text-lg flex-shrink-0">{{ $bill->icon }}</span>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-slate-800 truncate">{{ $bill->name }}</p>
                        <p class="text-xs text-slate-500">Tgl {{ $bill->due_day }} · {{ $bill->category }}</p>
                    </div>
                    <div class="text-right flex-shrink-0"
                         x-data="{ get hidden() { return $store.finance.hidden; } }">
                        <p class="text-sm font-bold text-slate-800">
                            <span x-show="!hidden">Rp {{ number_format($bill->amount, 0, ',', '.') }}</span>
                            <span x-show="hidden" class="tracking-widest text-slate-400">••••••</span>
                        </p>
                        <p class="text-xs font-semibold {{ $dueColor }}">{{ $dueLabel }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif

</div>

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.store('finance', {
        hidden: localStorage.getItem('financeHideBalance') === 'true',
        toggle() {
            this.hidden = !this.hidden;
            localStorage.setItem('financeHideBalance', this.hidden);
        }
    });
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    initDashboardCharts();

    Livewire.hook('commit', ({ component, commit, respond, succeed, fail }) => {
        succeed(({ snapshot, effect }) => {
            window.setTimeout(() => {
                const t = document.getElementById('trendChart');
                const c = document.getElementById('categoryChart');
                if (t) { const ex = Chart.getChart(t); if (ex) ex.destroy(); }
                if (c) { const ex = Chart.getChart(c); if (ex) ex.destroy(); }
                initDashboardCharts();
            }, 50);
        });
    });

    function initDashboardCharts() {
        const trendCanvas = document.getElementById('trendChart');
        if (!trendCanvas) return;

        const chartData = JSON.parse(trendCanvas.getAttribute('data-trend') || '{}');
        if (!chartData.labels) return;

        // Pastikan canvas mengisi parent sebelum inisialisasi
        const trendParent = trendCanvas.parentElement;
        if (trendParent) {
            trendCanvas.width  = trendParent.offsetWidth;
            trendCanvas.height = trendParent.offsetHeight;
        }

        // ---- Trend Line Chart ----
        const existingTrend = Chart.getChart(trendCanvas);
        if (existingTrend) existingTrend.destroy();

        new Chart(trendCanvas, {
            type: 'line',
            data: {
                labels: chartData.labels,
                datasets: [
                    {
                        label: 'Pemasukan',
                        data: chartData.income,
                        borderColor: '#22c55e',
                        backgroundColor: 'rgba(34,197,94,0.08)',
                        fill: true, tension: 0.4,
                        pointBackgroundColor: '#22c55e',
                        pointBorderColor: '#fff', pointBorderWidth: 2,
                        pointRadius: 4, pointHoverRadius: 6,
                    },
                    {
                        label: 'Pengeluaran',
                        data: chartData.expense,
                        borderColor: '#f43f5e',
                        backgroundColor: 'rgba(244,63,94,0.08)',
                        fill: true, tension: 0.4,
                        pointBackgroundColor: '#f43f5e',
                        pointBorderColor: '#fff', pointBorderWidth: 2,
                        pointRadius: 4, pointHoverRadius: 6,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                plugins: {
                    legend: { position: 'top', labels: { font: { family: 'Plus Jakarta Sans', size: 11 }, usePointStyle: true, boxWidth: 8 } },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        callbacks: { label: ctx => ' Rp ' + ctx.raw.toLocaleString('id-ID') }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: { callback: v => 'Rp ' + (v / 1_000_000).toFixed(0) + 'jt', font: { size: 10 }, maxTicksLimit: 5 },
                        grid: { color: 'rgba(0,0,0,0.04)' }
                    },
                    x: { ticks: { font: { size: 9 }, maxRotation: 45, autoSkip: true, maxTicksLimit: 6 }, grid: { display: false } }
                }
            }
        });

        // ---- Category Donut Chart ----
        const catCanvas = document.getElementById('categoryChart');
        if (catCanvas) {
            const catData = JSON.parse(catCanvas.getAttribute('data-category') || '{"labels":[],"data":[]}');
            const existingCat = Chart.getChart(catCanvas);
            if (existingCat) existingCat.destroy();

            if (catData.data && catData.data.length > 0) {
                const palette = ['#22c55e','#3b82f6','#f59e0b','#ef4444','#8b5cf6','#ec4899','#14b8a6','#f97316','#6366f1','#84cc16'];
                new Chart(catCanvas, {
                    type: 'doughnut',
                    data: {
                        labels: catData.labels,
                        datasets: [{ data: catData.data, backgroundColor: palette.slice(0, catData.data.length), borderWidth: 0, hoverOffset: 5 }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '65%',
                        plugins: {
                            legend: { position: 'bottom', labels: { font: { size: 10 }, boxWidth: 10, padding: 8, usePointStyle: true } },
                            tooltip: { backgroundColor: '#1e293b', callbacks: { label: ctx => ' Rp ' + ctx.raw.toLocaleString('id-ID') } }
                        }
                    }
                });
            }
        }

        // ---- Payment Method: pure CSS progress bars, no chart needed ----
    }
});
</script>
@endpush