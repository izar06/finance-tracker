<div>
    
    <div class="grid grid-cols-2 xl:grid-cols-4 gap-3 sm:gap-5 mb-4 sm:mb-6">

        <div class="bg-white rounded-2xl p-3 sm:p-5 border border-slate-100 shadow-sm">
            <div class="flex items-start justify-between mb-2 sm:mb-3">
                <div class="w-9 h-9 sm:w-11 sm:h-11 bg-emerald-50 rounded-xl flex items-center justify-center text-xl">📈</div>
                <span class="text-xs font-semibold text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded-full hidden xs:block">Bulan Ini</span>
            </div>
            <p class="text-sm sm:text-xl font-bold text-slate-800 mb-0.5 sm:mb-1 leading-tight">Rp <?php echo e(number_format($this->monthlyIncome, 0, ',', '.')); ?></p>
            <p class="text-xs sm:text-sm text-slate-500">Total Pemasukan</p>
        </div>

        <div class="bg-white rounded-2xl p-3 sm:p-5 border border-slate-100 shadow-sm">
            <div class="flex items-start justify-between mb-2 sm:mb-3">
                <div class="w-9 h-9 sm:w-11 sm:h-11 bg-rose-50 rounded-xl flex items-center justify-center text-xl">📉</div>
                <span class="text-xs font-semibold text-rose-600 bg-rose-50 px-1.5 py-0.5 rounded-full hidden xs:block">Bulan Ini</span>
            </div>
            <p class="text-sm sm:text-xl font-bold text-slate-800 mb-0.5 sm:mb-1 leading-tight">Rp <?php echo e(number_format($this->monthlyExpense, 0, ',', '.')); ?></p>
            <p class="text-xs sm:text-sm text-slate-500">Total Pengeluaran</p>
        </div>

        <div class="bg-white rounded-2xl p-3 sm:p-5 border border-slate-100 shadow-sm">
            <div class="flex items-start justify-between mb-2 sm:mb-3">
                <div class="w-9 h-9 sm:w-11 sm:h-11 <?php echo e($this->monthlyBalance >= 0 ? 'bg-blue-50' : 'bg-orange-50'); ?> rounded-xl flex items-center justify-center text-xl">
                    <?php echo e($this->monthlyBalance >= 0 ? '✅' : '⚠️'); ?>

                </div>
                <span class="text-xs font-semibold <?php echo e($this->monthlyBalance >= 0 ? 'text-blue-600 bg-blue-50' : 'text-orange-600 bg-orange-50'); ?> px-2 py-1 rounded-full">
                    <?php echo e($this->monthlyBalance >= 0 ? 'Surplus' : 'Defisit'); ?>

                </span>
            </div>
            <p class="text-sm sm:text-xl font-bold leading-tight <?php echo e($this->monthlyBalance >= 0 ? 'text-slate-800' : 'text-rose-600'); ?> mb-0.5 sm:mb-1">
                Rp <?php echo e(number_format(abs($this->monthlyBalance), 0, ',', '.')); ?>

            </p>
            <p class="text-xs sm:text-sm text-slate-500">Saldo Bulan Ini</p>
        </div>

        <div class="bg-gradient-to-br from-primary-500 to-primary-600 rounded-2xl p-3 sm:p-5 shadow-lg shadow-primary-500/20">
            <div class="flex items-start justify-between mb-3">
                <div class="w-9 h-9 sm:w-11 sm:h-11 bg-white/20 rounded-xl flex items-center justify-center text-lg sm:text-xl">🏦</div>
                <span class="text-xs font-semibold text-white/80 bg-white/20 px-1.5 py-0.5 rounded-full hidden xs:block">Total</span>
            </div>
            <p class="text-sm sm:text-xl font-bold text-white mb-0.5 sm:mb-1 leading-tight">Rp <?php echo e(number_format($this->totalAssets, 0, ',', '.')); ?></p>
            <p class="text-xs sm:text-sm text-white/70">Nilai Aset</p>
        </div>
    </div>

    
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-4 sm:gap-6 mb-4 sm:mb-8">

        
        <div class="xl:col-span-2 bg-white rounded-2xl p-4 sm:p-6 border border-slate-100 shadow-sm">
            <div class="flex items-center justify-between mb-3 sm:mb-5">
                <div>
                    <h3 class="font-bold text-slate-800">Tren Keuangan</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Pemasukan vs Pengeluaran — 12 bulan terakhir</p>
                </div>
            </div>
            <div class="relative w-full" style="height:220px">
                <canvas id="trendChart" style="position:absolute;top:0;left:0;width:100%;height:100%"
                        data-trend='<?php echo json_encode($this->monthlyChartData, 15, 512) ?>'
                        data-category='<?php echo json_encode($this->categoryExpenseChartData, 15, 512) ?>'></canvas>
            </div>
        </div>

        
        <div class="bg-white rounded-2xl p-4 sm:p-6 border border-slate-100 shadow-sm">
            <div class="mb-3 sm:mb-5">
                <h3 class="font-bold text-slate-800">Pengeluaran per Kategori</h3>
                <p class="text-xs text-slate-400 mt-0.5">Bulan ini</p>
            </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(count($this->categoryExpenseChartData['data']) > 0): ?>
                <div class="relative w-full" style="height:210px">
                    <canvas id="categoryChart" style="position:absolute;top:0;left:0;width:100%;height:100%"
                            data-category='<?php echo json_encode($this->categoryExpenseChartData, 15, 512) ?>'></canvas>
                </div>
            <?php else: ?>
                <div class="flex flex-col items-center justify-center h-48 text-slate-400">
                    <span class="text-4xl mb-2">📊</span>
                    <p class="text-sm">Belum ada pengeluaran bulan ini</p>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>

    
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-4 sm:p-6 mb-4 sm:mb-8"
         x-data="{ open: null, filter: 'semua' }">

        
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5">
            <div>
                <h3 class="font-bold text-slate-800 text-base">🧾 History per Metode Pembayaran</h3>
                <p class="text-xs text-slate-400 mt-0.5">Riwayat transaksi dikelompokkan berdasarkan cara bayar</p>
            </div>
            
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

        <?php
            $pmData = $this->paymentMethodChartData;
        ?>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(count($pmData) > 0): ?>
            <div class="space-y-2">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $pmData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $idx => $pm): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php
                        $totalTxAll = $pm['all_income_count'] + $pm['all_expense_count'];
                        $accentBgs  = ['bg-violet-500','bg-blue-500','bg-sky-500','bg-teal-500','bg-amber-500','bg-pink-500','bg-indigo-500','bg-orange-500'];
                        $accentBg   = $accentBgs[$idx % count($accentBgs)];
                        // apakah method ini punya transaksi income / expense?
                        $hasIncome  = $pm['all_income_count'] > 0;
                        $hasExpense = $pm['all_expense_count'] > 0;
                    ?>

                    
                    <div x-show="filter==='semua' || (filter==='income' && <?php echo e($hasIncome ? 'true' : 'false'); ?>) || (filter==='expense' && <?php echo e($hasExpense ? 'true' : 'false'); ?>)"
                         class="rounded-2xl border border-slate-100 overflow-hidden">

                        
                        <button type="button"
                                @click="open = (open === <?php echo e($idx); ?> && filter==='semua') ? null : <?php echo e($idx); ?>"
                                class="w-full flex items-center gap-3 px-4 py-3.5 hover:bg-slate-50 transition-colors text-left">

                            <div class="w-9 h-9 <?php echo e($accentBg); ?> rounded-xl flex items-center justify-center text-base flex-shrink-0 shadow-sm">
                                <?php echo e($pm['icon']); ?>

                            </div>

                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-bold text-slate-800"><?php echo e($pm['method']); ?></p>
                                <div class="flex items-center gap-2 mt-0.5 flex-wrap">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($hasIncome): ?>
                                        <span class="text-xs text-emerald-600 font-medium"><?php echo e($pm['all_income_count']); ?> masuk</span>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($hasExpense): ?>
                                        <span class="text-xs text-rose-500 font-medium"><?php echo e($pm['all_expense_count']); ?> keluar</span>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    <span class="text-xs text-slate-300">·</span>
                                    <span class="text-xs text-slate-400"><?php echo e($totalTxAll); ?> total</span>
                                </div>
                            </div>

                            
                            <div class="text-right flex-shrink-0 mr-2">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($hasIncome): ?>
                                    <p class="text-xs font-semibold text-emerald-600">+Rp <?php echo e(number_format($pm['all_income'], 0, ',', '.')); ?></p>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($hasExpense): ?>
                                    <p class="text-xs font-semibold text-rose-500">−Rp <?php echo e(number_format($pm['all_expense'], 0, ',', '.')); ?></p>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>

                            <div class="text-slate-300 transition-transform duration-200 flex-shrink-0"
                                 :class="(open === <?php echo e($idx); ?> || open === 'all') ? 'rotate-180' : ''">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                        </button>

                        
                        <div x-show="open === <?php echo e($idx); ?> || open === 'all'"
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="opacity-0"
                             x-transition:enter-end="opacity-100"
                             x-transition:leave="transition ease-in duration-100"
                             x-transition:leave-end="opacity-0"
                             class="border-t border-slate-100">

                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(count($pm['recent_transactions']) > 0): ?>
                                <div class="divide-y divide-slate-50">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $pm['recent_transactions']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tx): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        
                                        <div x-show="filter === 'semua' || filter === '<?php echo e($tx['type']); ?>'"
                                             class="flex items-center gap-3 px-4 py-3 hover:bg-slate-50/70 transition-colors">

                                            
                                            <div class="w-2 h-2 rounded-full flex-shrink-0 <?php echo e($tx['type'] === 'income' ? 'bg-emerald-400' : 'bg-rose-400'); ?>"></div>

                                            
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-slate-700 truncate"><?php echo e($tx['title']); ?></p>
                                                <p class="text-xs text-slate-400 mt-0.5">
                                                    <span class="inline-flex items-center gap-1 px-1.5 py-0.5 bg-slate-100 rounded text-slate-500 text-xs"><?php echo e($tx['category']); ?></span>
                                                    <span class="ml-1"><?php echo e($tx['date']); ?></span>
                                                </p>
                                            </div>

                                            
                                            <p class="text-sm font-bold flex-shrink-0 <?php echo e($tx['type'] === 'income' ? 'text-emerald-600' : 'text-rose-500'); ?>">
                                                <?php echo e($tx['type'] === 'income' ? '+' : '−'); ?>Rp <?php echo e(number_format($tx['amount'], 0, ',', '.')); ?>

                                            </p>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>

                                <div class="px-4 py-2.5 bg-slate-50/60 border-t border-slate-100 text-center">
                                    <a href="<?php echo e(route('transactions')); ?>"
                                       class="text-xs text-primary-500 hover:text-primary-700 font-medium transition-colors">
                                        Lihat semua transaksi <?php echo e($pm['method']); ?> →
                                    </a>
                                </div>
                            <?php else: ?>
                                <div class="px-4 py-5 text-center text-xs text-slate-400">Belum ada transaksi</div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>

                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>

        <?php else: ?>
            <div class="flex flex-col items-center justify-center py-14 text-slate-400">
                <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center text-3xl mb-4">🧾</div>
                <p class="text-sm font-semibold text-slate-600">Belum ada riwayat transaksi</p>
                <p class="text-xs text-slate-400 mt-1 text-center max-w-xs">Pilih metode pembayaran saat menambahkan transaksi agar history muncul di sini</p>
                <a href="<?php echo e(route('transactions')); ?>"
                   class="mt-4 px-4 py-2 text-sm font-semibold text-white bg-primary-500 rounded-xl hover:bg-primary-600 transition-colors">
                    + Tambah Transaksi
                </a>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6">

        
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="flex items-center justify-between px-4 sm:px-6 py-3 sm:py-4 border-b border-slate-100">
                <h3 class="font-bold text-slate-800">Transaksi Terbaru</h3>
                <a href="<?php echo e(route('transactions')); ?>" class="text-sm font-medium text-primary-600 hover:text-primary-700 transition-colors">
                    Lihat semua →
                </a>
            </div>
            <div class="divide-y divide-slate-50">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $this->recentTransactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="flex items-center gap-3 px-4 sm:px-6 py-3 sm:py-3.5 hover:bg-slate-50 transition-colors">
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center text-sm flex-shrink-0
                                    <?php echo e($transaction->type === 'income' ? 'bg-emerald-50' : 'bg-rose-50'); ?>">
                            <?php echo e($transaction->type === 'income' ? '📥' : '📤'); ?>

                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-slate-800 truncate"><?php echo e($transaction->title); ?></p>
                            <p class="text-xs text-slate-400 truncate"><?php echo e($transaction->category); ?><span class="hidden xs:inline"> · <?php echo e($transaction->date->translatedFormat('d M Y')); ?></span></p>
                        </div>
                        <p class="text-sm font-bold whitespace-nowrap flex-shrink-0 <?php echo e($transaction->type === 'income' ? 'text-emerald-600' : 'text-rose-600'); ?>">
                            <?php echo e($transaction->type === 'income' ? '+' : '-'); ?> Rp <?php echo e(number_format($transaction->amount, 0, ',', '.')); ?>

                        </p>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="py-12 text-center text-slate-400">
                        <span class="text-3xl block mb-2">📋</span>
                        <p class="text-sm">Belum ada transaksi</p>
                        <a href="<?php echo e(route('transactions')); ?>" class="text-xs text-primary-500 mt-1 block hover:underline">Tambah sekarang →</a>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>

        
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="flex items-center justify-between px-4 sm:px-6 py-3 sm:py-4 border-b border-slate-100">
                <h3 class="font-bold text-slate-800">Tujuan Keuangan Aktif</h3>
                <a href="<?php echo e(route('goals')); ?>" class="text-sm font-medium text-primary-600 hover:text-primary-700 transition-colors">
                    Lihat semua →
                </a>
            </div>
            <div class="divide-y divide-slate-50 px-6">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $this->activeGoals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $goal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="py-4">
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center gap-2 min-w-0">
                                <span class="text-lg flex-shrink-0"><?php echo e($goal->icon); ?></span>
                                <p class="text-sm font-semibold text-slate-800 truncate"><?php echo e($goal->name); ?></p>
                            </div>
                            <span class="text-sm font-bold text-primary-600 flex-shrink-0 ml-2"><?php echo e($goal->progress_percentage); ?>%</span>
                        </div>
                        <div class="w-full bg-slate-100 rounded-full h-2 mb-2">
                            <div class="h-2 rounded-full bg-gradient-to-r from-primary-400 to-primary-600 transition-all duration-700"
                                 style="width: <?php echo e($goal->progress_percentage); ?>%"></div>
                        </div>
                        <div class="flex justify-between text-xs text-slate-400">
                            <span>Terkumpul: Rp <?php echo e(number_format($goal->current_amount, 0, ',', '.')); ?></span>
                            <span>Target: Rp <?php echo e(number_format($goal->target_amount, 0, ',', '.')); ?></span>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="py-12 text-center text-slate-400">
                        <span class="text-3xl block mb-2">🎯</span>
                        <p class="text-sm">Belum ada tujuan keuangan</p>
                        <a href="<?php echo e(route('goals')); ?>" class="text-xs text-primary-500 mt-1 block hover:underline">Buat tujuan pertama →</a>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>

        
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="flex items-center justify-between px-4 sm:px-6 py-3 sm:py-4 border-b border-slate-100">
                <h3 class="font-bold text-slate-800">Anggaran Bulan Ini</h3>
                <a href="<?php echo e(route('budgets')); ?>" class="text-sm font-medium text-primary-600 hover:text-primary-700 transition-colors">
                    Kelola →
                </a>
            </div>
            <div class="px-4 sm:px-6 py-2 divide-y divide-slate-50">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $this->budgetSummary; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $budget): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
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
                    ?>
                    <div class="py-3">
                        <div class="flex items-center justify-between mb-1.5">
                            <p class="text-sm font-medium text-slate-700 truncate pr-2"><?php echo e($budget->category); ?></p>
                            <span class="text-xs font-bold <?php echo e($pctColor); ?> flex-shrink-0"><?php echo e($pct); ?>%</span>
                        </div>
                        <div class="w-full bg-slate-100 rounded-full h-1.5">
                            <div class="<?php echo e($barColor); ?> h-1.5 rounded-full transition-all duration-500"
                                 style="width: <?php echo e(min(100, $pct)); ?>%"></div>
                        </div>
                        <div class="flex justify-between text-xs text-slate-400 mt-1">
                            <span>Rp <?php echo e(number_format($budget->spent, 0, ',', '.')); ?></span>
                            <span>/ Rp <?php echo e(number_format($budget->amount, 0, ',', '.')); ?></span>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="py-12 text-center text-slate-400">
                        <span class="text-3xl block mb-2">💰</span>
                        <p class="text-sm">Belum ada anggaran</p>
                        <a href="<?php echo e(route('budgets')); ?>" class="text-xs text-primary-500 mt-1 block hover:underline">Buat anggaran pertama →</a>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>

    </div>
</div>

<?php $__env->startPush('scripts'); ?>
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
<?php $__env->stopPush(); ?>
<?php /**PATH /Users/izarhairulanam/Downloads/finance-tracker-responsive 7/resources/views/livewire/dashboard/index.blade.php ENDPATH**/ ?>