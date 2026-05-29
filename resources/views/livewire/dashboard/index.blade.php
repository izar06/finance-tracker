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

    {{-- History Transaksi per Metode Pembayaran --}}
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-4 sm:p-6 mb-4 sm:mb-8"
         x-data="{ open: null, filter: 'semua' }">

        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5">
            <div>
                <h3 class="font-bold text-slate-800 text-base">🧾 History per Metode Pembayaran</h3>
                <p class="text-xs text-slate-400 mt-0.5">Riwayat transaksi dikelompokkan berdasarkan cara bayar</p>
            </div>
            {{-- Filter pemasukan/pengeluaran --}}
            <div class="flex items-center gap-1.5 bg-slate-100 rounded-xl p-1 self-start flex-shrink-0">
                <button @click="filter='semua'; open=null"
                        :class="filter==='semua' ? 'bg-white shadow-sm text-slate-800 font-semibold' : 'text-slate-500 hover:text-slate-700'"
                        class="px-3 py-1.5 text-xs rounded-lg transition-all">Semua</button>
                <button @click="filter='income'; open='all'"
                        :class="filter==='income' ? 'bg-white shadow-sm text-emerald-600 font-semibold' : 'text-slate-500 hover:text-slate-700'"
                        class="px-3 py-1.5 text-xs rounded-lg transition-all">📥 Masuk</button>
                <button @click="filter='expense'; open='all'"
                        :class="filter==='expense' ? 'bg-white shadow-sm text-rose-600 font-semibold' : 'text-slate-500 hover:text-slate-700'"
                        class="px-3 py-1.5 text-xs rounded-lg transition-all">📤 Keluar</button>
            </div>
        </div>

        @php
            $pmData = $this->paymentMethodChartData;
        @endphp

        @if(count($pmData) > 0)
            <div class="space-y-2">
                @foreach($pmData as $idx => $pm)
                    @php
                        $totalTxAll = $pm['all_income_count'] + $pm['all_expense_count'];
                        $accentBgs  = ['bg-violet-500','bg-blue-500','bg-sky-500','bg-teal-500','bg-amber-500','bg-pink-500','bg-indigo-500','bg-orange-500'];
                        $accentBg   = $accentBgs[$idx % count($accentBgs)];
                        // apakah method ini punya transaksi income / expense?
                        $hasIncome  = $pm['all_income_count'] > 0;
                        $hasExpense = $pm['all_expense_count'] > 0;
                    @endphp

                    {{-- Sembunyikan card jika filter aktif tapi method tidak punya tipe itu --}}
                    <div x-show="filter==='semua' || (filter==='income' && {{ $hasIncome ? 'true' : 'false' }}) || (filter==='expense' && {{ $hasExpense ? 'true' : 'false' }})"
                         class="rounded-2xl border border-slate-100 overflow-hidden">

                        {{-- Method Header — click to expand --}}
                        <button type="button"
                                @click="open = (open === {{ $idx }} && filter==='semua') ? null : {{ $idx }}"
                                class="w-full flex items-center gap-3 px-4 py-3.5 hover:bg-slate-50 transition-colors text-left">

                            <div class="w-9 h-9 {{ $accentBg }} rounded-xl flex items-center justify-center text-base flex-shrink-0 shadow-sm">
                                {{ $pm['icon'] }}
                            </div>

                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-bold text-slate-800">{{ $pm['method'] }}</p>
                                <div class="flex items-center gap-2 mt-0.5 flex-wrap">
                                    @if($hasIncome)
                                        <span class="text-xs text-emerald-600 font-medium">{{ $pm['all_income_count'] }} masuk</span>
                                    @endif
                                    @if($hasExpense)
                                        <span class="text-xs text-rose-500 font-medium">{{ $pm['all_expense_count'] }} keluar</span>
                                    @endif
                                    <span class="text-xs text-slate-300">·</span>
                                    <span class="text-xs text-slate-400">{{ $totalTxAll }} total</span>
                                </div>
                            </div>

                            {{-- Ringkasan kanan --}}
                            <div class="text-right flex-shrink-0 mr-2" x-data="{ get hidden() { return $store.finance.hidden; } }">
                                @if($hasIncome)
                                    <p class="text-xs font-semibold text-emerald-600">
                                        <span x-show="!hidden">+Rp {{ number_format($pm['all_income'], 0, ',', '.') }}</span>
                                        <span x-show="hidden" class="tracking-widest text-emerald-300">+Rp ••••••</span>
                                    </p>
                                @endif
                                @if($hasExpense)
                                    <p class="text-xs font-semibold text-rose-500">
                                        <span x-show="!hidden">−Rp {{ number_format($pm['all_expense'], 0, ',', '.') }}</span>
                                        <span x-show="hidden" class="tracking-widest text-rose-300">−Rp ••••••</span>
                                    </p>
                                @endif
                            </div>

                            <div class="text-slate-300 transition-transform duration-200 flex-shrink-0"
                                 :class="(open === {{ $idx }} || open === 'all') ? 'rotate-180' : ''">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                        </button>

                        {{-- Transaction list — terbuka jika diklik atau filter aktif --}}
                        <div x-show="open === {{ $idx }} || open === 'all'"
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="opacity-0"
                             x-transition:enter-end="opacity-100"
                             x-transition:leave="transition ease-in duration-100"
                             x-transition:leave-end="opacity-0"
                             class="border-t border-slate-100">

                            @if(count($pm['recent_transactions']) > 0)
                                <div class="divide-y divide-slate-50">
                                    @foreach($pm['recent_transactions'] as $tx)
                                        {{-- Tampilkan baris sesuai filter --}}
                                        <div x-show="filter === 'semua' || filter === '{{ $tx['type'] }}'"
                                             class="flex items-center gap-3 px-4 py-3 hover:bg-slate-50/70 transition-colors">

                                            {{-- Type dot --}}
                                            <div class="w-2 h-2 rounded-full flex-shrink-0 {{ $tx['type'] === 'income' ? 'bg-emerald-400' : 'bg-rose-400' }}"></div>

                                            {{-- Info --}}
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-slate-700 truncate">{{ $tx['title'] }}</p>
                                                <p class="text-xs text-slate-400 mt-0.5">
                                                    <span class="inline-flex items-center gap-1 px-1.5 py-0.5 bg-slate-100 rounded text-slate-500 text-xs">{{ $tx['category'] }}</span>
                                                    <span class="ml-1">{{ $tx['date'] }}</span>
                                                </p>
                                            </div>

                                            {{-- Amount --}}
                                            <p class="text-sm font-bold flex-shrink-0 {{ $tx['type'] === 'income' ? 'text-emerald-600' : 'text-rose-500' }}"
                                               x-data="{ get hidden() { return $store.finance.hidden; } }">
                                                <span x-show="!hidden">{{ $tx['type'] === 'income' ? '+' : '−' }}Rp {{ number_format($tx['amount'], 0, ',', '.') }}</span>
                                                <span x-show="hidden" class="tracking-widest {{ $tx['type'] === 'income' ? 'text-emerald-300' : 'text-rose-300' }}">••••••</span>
                                            </p>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="px-4 py-2.5 bg-slate-50/60 border-t border-slate-100 text-center">
                                    <a href="{{ route('transactions') }}"
                                       class="text-xs text-primary-500 hover:text-primary-700 font-medium transition-colors">
                                        Lihat semua transaksi {{ $pm['method'] }} →
                                    </a>
                                </div>
                            @else
                                <div class="px-4 py-5 text-center text-xs text-slate-400">Belum ada transaksi</div>
                            @endif
                        </div>

                    </div>
                @endforeach
            </div>

        @else
            <div class="flex flex-col items-center justify-center py-14 text-slate-400">
                <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center text-3xl mb-4">🧾</div>
                <p class="text-sm font-semibold text-slate-600">Belum ada riwayat transaksi</p>
                <p class="text-xs text-slate-400 mt-1 text-center max-w-xs">Pilih metode pembayaran saat menambahkan transaksi agar history muncul di sini</p>
                <a href="{{ route('transactions') }}"
                   class="mt-4 px-4 py-2 text-sm font-semibold text-white bg-primary-500 rounded-xl hover:bg-primary-600 transition-colors">
                    + Tambah Transaksi
                </a>
            </div>
        @endif
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