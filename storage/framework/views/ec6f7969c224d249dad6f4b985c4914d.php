<div>

    
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Anggaran</h2>
            <p class="text-sm text-slate-500">Tetapkan batas pengeluaran per kategori</p>
        </div>
        <div class="flex items-center gap-2 self-start sm:self-auto">
            
            <button wire:click="openCopyModal"
                    class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold text-slate-700 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition-colors whitespace-nowrap">
                📋 Salin Bulan Lalu
            </button>
            <button wire:click="openForm"
                    class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold text-white bg-primary-500 rounded-xl hover:bg-primary-600 transition-colors shadow-md shadow-primary-500/25 whitespace-nowrap">
                + Tambah Anggaran
            </button>
        </div>
    </div>

    
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-4 mb-5">
        <div class="flex items-center justify-between gap-4">
            <button wire:click="prevMonth"
                    class="w-9 h-9 flex items-center justify-center rounded-xl border border-slate-200 hover:bg-slate-50 text-slate-600 transition-colors flex-shrink-0">←</button>
            <div class="text-center">
                <p class="font-bold text-slate-800 text-base sm:text-lg">
                    <?php echo e(\Carbon\Carbon::create($year, $month)->translatedFormat('F Y')); ?>

                </p>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($month == now()->month && $year == now()->year): ?>
                    <span class="text-xs text-primary-500 font-medium">Bulan ini</span>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
            <button wire:click="nextMonth"
                    class="w-9 h-9 flex items-center justify-center rounded-xl border border-slate-200 hover:bg-slate-50 text-slate-600 transition-colors flex-shrink-0">→</button>
        </div>
    </div>

    
    <?php $s = $this->summary; ?>
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-5">
        <div class="bg-white rounded-2xl p-3 sm:p-5 border border-slate-100 shadow-sm">
            <p class="text-xs sm:text-sm text-slate-500 mb-1">Total Anggaran</p>
            <p class="text-sm sm:text-xl font-bold text-slate-800 leading-tight">Rp <?php echo e(number_format($s['totalBudget'], 0, ',', '.')); ?></p>
            <p class="text-xs text-slate-400 mt-0.5"><?php echo e($this->budgets->count()); ?> kategori</p>
        </div>
        <div class="bg-white rounded-2xl p-3 sm:p-5 border border-slate-100 shadow-sm">
            <p class="text-xs sm:text-sm text-slate-500 mb-1">Terpakai</p>
            <p class="text-sm sm:text-xl font-bold text-slate-800 leading-tight">Rp <?php echo e(number_format($s['totalSpent'], 0, ',', '.')); ?></p>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($s['totalBudget'] > 0): ?>
                <p class="text-xs text-slate-400 mt-0.5"><?php echo e(round(($s['totalSpent'] / $s['totalBudget']) * 100)); ?>% dari anggaran</p>
            <?php else: ?>
                <p class="text-xs text-slate-400 mt-0.5">—</p>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
        <div class="bg-white rounded-2xl p-3 sm:p-5 border border-slate-100 shadow-sm">
            <p class="text-xs sm:text-sm text-slate-500 mb-1">Sisa</p>
            <?php $sisa = $s['totalBudget'] - $s['totalSpent']; ?>
            <p class="text-sm sm:text-xl font-bold leading-tight <?php echo e($sisa >= 0 ? 'text-emerald-600' : 'text-rose-600'); ?>">
                Rp <?php echo e(number_format(abs($sisa), 0, ',', '.')); ?>

            </p>
            <p class="text-xs mt-0.5 <?php echo e($sisa >= 0 ? 'text-emerald-500' : 'text-rose-500'); ?>">
                <?php echo e($sisa >= 0 ? 'masih tersedia' : 'melebihi anggaran'); ?>

            </p>
        </div>
        <div class="bg-white rounded-2xl p-3 sm:p-5 border border-slate-100 shadow-sm">
            <p class="text-xs sm:text-sm text-slate-500 mb-1.5">Status</p>
            <div class="flex flex-wrap gap-1.5">
                <span class="inline-flex items-center gap-1 text-xs font-medium text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full">✓ <?php echo e($s['onTrack']); ?> aman</span>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($s['warning'] > 0): ?>
                    <span class="inline-flex items-center gap-1 text-xs font-medium text-amber-700 bg-amber-50 px-2 py-0.5 rounded-full">⚠ <?php echo e($s['warning']); ?> hampir</span>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($s['overBudget'] > 0): ?>
                    <span class="inline-flex items-center gap-1 text-xs font-medium text-rose-700 bg-rose-50 px-2 py-0.5 rounded-full">✕ <?php echo e($s['overBudget']); ?> lewat</span>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
    </div>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($this->budgets->count() > 0): ?>
        <div class="space-y-3 mb-5">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $this->budgets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $budget): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $pct      = $budget->percentage;
                    $color    = $budget->status_color;
                    $barColor = match($color) { 'rose' => 'bg-rose-500', 'amber' => 'bg-amber-400', default => 'bg-emerald-500' };
                    $textColor = match($color) { 'rose' => 'text-rose-600', 'amber' => 'text-amber-600', default => 'text-emerald-600' };
                    $bgLight  = match($color) { 'rose' => 'bg-rose-50 border-rose-100', 'amber' => 'bg-amber-50 border-amber-100', default => 'bg-white border-slate-100' };
                ?>
                <div class="rounded-2xl border <?php echo e($bgLight); ?> shadow-sm p-4 sm:p-5">
                    <div class="flex items-start justify-between gap-3 mb-3">
                        <div class="min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <h3 class="font-semibold text-slate-800 text-sm sm:text-base"><?php echo e($budget->category); ?></h3>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($budget->is_over_budget): ?>
                                    <span class="text-xs font-semibold text-rose-600 bg-rose-100 px-2 py-0.5 rounded-full">Melebihi!</span>
                                <?php elseif($pct >= 80): ?>
                                    <span class="text-xs font-semibold text-amber-600 bg-amber-100 px-2 py-0.5 rounded-full">Hampir habis</span>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($budget->notes): ?>
                                <p class="text-xs text-slate-400 mt-0.5 truncate"><?php echo e($budget->notes); ?></p>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                        <div class="flex items-center gap-1 flex-shrink-0">
                            <button wire:click="editBudget(<?php echo e($budget->id); ?>)"
                                    class="p-1.5 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">✏️</button>
                            <button wire:click="confirmDelete(<?php echo e($budget->id); ?>)"
                                    class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors">🗑️</button>
                        </div>
                    </div>
                    <div class="mb-2.5">
                        <div class="w-full bg-slate-100 rounded-full h-2.5 overflow-hidden">
                            <div class="<?php echo e($barColor); ?> h-2.5 rounded-full transition-all duration-500" style="width: <?php echo e(min(100, $pct)); ?>%"></div>
                        </div>
                    </div>
                    <div class="flex items-center justify-between gap-2 text-xs sm:text-sm">
                        <span class="text-slate-500">Terpakai: <span class="<?php echo e($textColor); ?> font-semibold">Rp <?php echo e(number_format($budget->spent, 0, ',', '.')); ?></span></span>
                        <div class="flex items-center gap-3 text-right">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($budget->is_over_budget): ?>
                                <span class="text-rose-500">Lebih Rp <?php echo e(number_format($budget->overspent, 0, ',', '.')); ?></span>
                            <?php else: ?>
                                <span class="text-slate-400">Sisa Rp <?php echo e(number_format($budget->remaining, 0, ',', '.')); ?></span>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <span class="<?php echo e($textColor); ?> font-bold"><?php echo e($pct); ?>%</span>
                        </div>
                    </div>
                    <div class="mt-1 text-xs text-slate-400">Anggaran: Rp <?php echo e(number_format($budget->amount, 0, ',', '.')); ?></div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    <?php else: ?>
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm py-16 text-center mb-5">
            <span class="text-5xl block mb-4">💰</span>
            <p class="font-semibold text-slate-700 mb-1">Belum ada anggaran</p>
            <p class="text-sm text-slate-400 mb-5">Tetapkan batas pengeluaran untuk bulan ini</p>
            <div class="flex items-center justify-center gap-3">
                <button wire:click="openCopyModal"
                        class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold text-slate-700 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition-colors">
                    📋 Salin dari Bulan Lalu
                </button>
                <button wire:click="openForm"
                        class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-white bg-primary-500 rounded-xl hover:bg-primary-600 transition-colors">
                    + Tambah Anggaran
                </button>
            </div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($this->unbudgetedExpenses->count() > 0): ?>
        <div class="bg-amber-50 border border-amber-100 rounded-2xl p-4 sm:p-5">
            <div class="flex items-start gap-3 mb-3">
                <span class="text-xl flex-shrink-0">⚠️</span>
                <div>
                    <p class="font-semibold text-amber-800 text-sm">Pengeluaran Tanpa Anggaran</p>
                    <p class="text-xs text-amber-600">Kategori berikut ada transaksi tapi belum dianggarkan bulan ini</p>
                </div>
            </div>
            <div class="space-y-2">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $this->unbudgetedExpenses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ue): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="flex items-center justify-between bg-white rounded-xl px-4 py-2.5 border border-amber-100">
                        <div>
                            <span class="text-sm font-medium text-slate-700"><?php echo e($ue->category); ?></span>
                            <span class="text-xs text-slate-400 ml-2"><?php echo e($ue->count); ?> transaksi</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="text-sm font-bold text-rose-600">Rp <?php echo e(number_format($ue->total, 0, ',', '.')); ?></span>
                            <button wire:click="openFormWithCategory('<?php echo e($ue->category); ?>')"
                                    class="text-xs text-primary-500 hover:text-primary-700 font-medium whitespace-nowrap">+ Anggarkan</button>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    
    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showCopyModal): ?>
        <div class="fixed inset-0 z-50 flex items-end sm:items-center justify-center p-0 sm:p-4"
             x-data x-on:keydown.escape.window="$wire.closeCopyModal()">
            <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" wire:click="closeCopyModal"></div>
            <div class="relative bg-white w-full sm:max-w-lg sm:rounded-2xl rounded-t-2xl shadow-xl z-10 max-h-[90vh] flex flex-col">

                
                <div class="flex items-center justify-between p-6 border-b border-slate-100 flex-shrink-0">
                    <div>
                        <h3 class="font-bold text-slate-800 text-lg">📋 Salin Anggaran</h3>
                        <p class="text-sm text-slate-500 mt-0.5">
                            Salin ke <span class="font-medium text-slate-700"><?php echo e(\Carbon\Carbon::create($year, $month)->translatedFormat('F Y')); ?></span>
                        </p>
                    </div>
                    <button wire:click="closeCopyModal" class="text-slate-400 hover:text-slate-600 text-xl leading-none p-1">✕</button>
                </div>

                
                <div class="p-6 overflow-y-auto flex-1 space-y-5">

                    
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Salin dari bulan</label>
                        <div class="grid grid-cols-2 gap-3">
                            <select wire:model.live="copyFromMonth"
                                    class="px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-300 outline-none bg-white">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = range(1, 12); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($m); ?>"><?php echo e(\Carbon\Carbon::create()->month($m)->translatedFormat('F')); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </select>
                            <select wire:model.live="copyFromYear"
                                    class="px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-300 outline-none bg-white">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = range(now()->year, now()->year - 3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $y): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($y); ?>"><?php echo e($y); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </select>
                        </div>
                    </div>

                    
                    <label class="flex items-center gap-3 p-3.5 border border-slate-200 rounded-xl cursor-pointer hover:bg-slate-50 transition-colors">
                        <input type="checkbox" wire:model.live="copyOverwrite" class="w-4 h-4 rounded accent-primary-500">
                        <div>
                            <p class="text-sm font-medium text-slate-700">Timpa anggaran yang sudah ada</p>
                            <p class="text-xs text-slate-400">Jika tidak dicentang, kategori yang sudah ada di bulan tujuan akan dilewati</p>
                        </div>
                    </label>

                    
                    <div>
                        <p class="text-sm font-medium text-slate-700 mb-2">
                            Preview
                            <span class="text-slate-400 font-normal">(<?php echo e($copySourceLabelProperty ?? \Carbon\Carbon::create($copyFromYear, $copyFromMonth)->translatedFormat('F Y')); ?>)</span>
                        </p>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(empty($copyPreview)): ?>
                            <div class="text-center py-8 bg-slate-50 rounded-xl border border-slate-100">
                                <p class="text-sm text-slate-400">Tidak ada anggaran di bulan tersebut</p>
                            </div>
                        <?php else: ?>
                            <div class="space-y-2">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $copyPreview; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="flex items-center justify-between px-4 py-2.5 rounded-xl border
                                        <?php echo e($item['will_copy']
                                            ? ($item['already_exists'] ? 'bg-amber-50 border-amber-100' : 'bg-emerald-50 border-emerald-100')
                                            : 'bg-slate-50 border-slate-100 opacity-50'); ?>">
                                        <div class="flex items-center gap-2">
                                            <span class="text-sm">
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$item['will_copy']): ?>
                                                    ⏭
                                                <?php elseif($item['already_exists']): ?>
                                                    🔄
                                                <?php else: ?>
                                                    ✅
                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            </span>
                                            <span class="text-sm font-medium text-slate-700"><?php echo e($item['category']); ?></span>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($item['already_exists'] && $item['will_copy']): ?>
                                                <span class="text-xs text-amber-600 bg-amber-100 px-1.5 py-0.5 rounded-md">ditimpa</span>
                                            <?php elseif($item['already_exists'] && !$item['will_copy']): ?>
                                                <span class="text-xs text-slate-400 bg-slate-100 px-1.5 py-0.5 rounded-md">dilewati</span>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </div>
                                        <span class="text-sm font-semibold <?php echo e($item['will_copy'] ? 'text-slate-800' : 'text-slate-400'); ?>">
                                            Rp <?php echo e(number_format($item['amount'], 0, ',', '.')); ?>

                                        </span>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>

                            
                            <?php
                                $willCopy   = collect($copyPreview)->where('will_copy', true)->count();
                                $willSkip   = collect($copyPreview)->where('will_copy', false)->count();
                                $totalAmount = collect($copyPreview)->where('will_copy', true)->sum('amount');
                            ?>
                            <div class="mt-3 px-4 py-2.5 bg-slate-50 rounded-xl border border-slate-100 text-xs text-slate-500 flex items-center justify-between">
                                <span><?php echo e($willCopy); ?> akan disalin<?php echo e($willSkip > 0 ? ", {$willSkip} dilewati" : ''); ?></span>
                                <span class="font-semibold text-slate-700">Total: Rp <?php echo e(number_format($totalAmount, 0, ',', '.')); ?></span>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>

                
                <div class="flex gap-3 p-6 border-t border-slate-100 flex-shrink-0">
                    <button wire:click="closeCopyModal"
                            class="flex-1 px-4 py-2.5 text-sm font-medium text-slate-600 bg-slate-100 rounded-xl hover:bg-slate-200 transition-colors">
                        Batal
                    </button>
                    <button wire:click="copyBudgets"
                            wire:loading.attr="disabled"
                            <?php if(empty($copyPreview) || collect($copyPreview)->where('will_copy', true)->count() === 0): echo 'disabled'; endif; ?>
                            class="flex-1 px-4 py-2.5 text-sm font-semibold text-white bg-primary-500 rounded-xl hover:bg-primary-600 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                        <span wire:loading.remove wire:target="copyBudgets">📋 Salin Sekarang</span>
                        <span wire:loading wire:target="copyBudgets">Menyalin...</span>
                    </button>
                </div>
            </div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showForm): ?>
        <div class="fixed inset-0 z-50 flex items-end sm:items-center justify-center p-0 sm:p-4"
             x-data x-on:keydown.escape.window="$wire.closeForm()">
            <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" wire:click="closeForm"></div>
            <div class="relative bg-white w-full sm:max-w-md sm:rounded-2xl rounded-t-2xl shadow-xl p-6 z-10 max-h-[90vh] overflow-y-auto">
                <div class="flex items-center justify-between mb-5">
                    <h3 class="font-bold text-slate-800 text-lg"><?php echo e($editingId ? 'Edit Anggaran' : 'Tambah Anggaran'); ?></h3>
                    <button wire:click="closeForm" class="text-slate-400 hover:text-slate-600 text-xl leading-none">✕</button>
                </div>
                <div class="space-y-4">
                    <div class="bg-primary-50 rounded-xl px-4 py-2.5 text-sm text-primary-700 font-medium">
                        📅 Periode: <?php echo e(\Carbon\Carbon::create($year, $month)->translatedFormat('F Y')); ?>

                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Kategori</label>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($editingId): ?>
                            <div class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm bg-slate-50 text-slate-600"><?php echo e($category); ?></div>
                        <?php else: ?>
                            <select wire:model="category"
                                    class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-300 focus:border-primary-400 outline-none bg-white <?php $__errorArgs = ['category'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-rose-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                <option value="">-- Pilih Kategori --</option>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = \App\Models\Category::expense()->whereNotIn('name', \App\Models\Budget::where('month', $month)->where('year', $year)->when($editingId, fn($q) => $q->where('id', '!=', $editingId))->pluck('category')->toArray())->orderBy('name')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($cat->name); ?>"><?php echo e($cat->icon); ?> <?php echo e($cat->name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </select>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['category'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-xs text-rose-500 mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$editingId && count($this->availableCategories) === 0): ?>
                            <p class="text-xs text-amber-500 mt-1">Semua kategori sudah memiliki anggaran bulan ini.</p>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Batas Anggaran</label>
                        <div class="relative">
                            <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-sm text-slate-400 font-medium">Rp</span>
                            <input type="hidden" wire:model="amount" data-currency-model>
                            <input type="text" data-currency inputmode="numeric" placeholder="0" autocomplete="off"
                                   class="w-full pl-10 pr-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-300 focus:border-primary-400 outline-none <?php $__errorArgs = ['amount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-rose-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        </div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['amount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-xs text-rose-500 mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Catatan <span class="text-slate-400 font-normal">(opsional)</span></label>
                        <textarea wire:model="notes" rows="2" placeholder="Contoh: termasuk makan siang kantor"
                                  class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-300 focus:border-primary-400 outline-none resize-none"></textarea>
                    </div>
                </div>
                <div class="flex gap-3 mt-6">
                    <button wire:click="closeForm"
                            class="flex-1 px-4 py-2.5 text-sm font-medium text-slate-600 bg-slate-100 rounded-xl hover:bg-slate-200 transition-colors">Batal</button>
                    <button wire:click="saveBudget" wire:loading.attr="disabled"
                            class="flex-1 px-4 py-2.5 text-sm font-semibold text-white bg-primary-500 rounded-xl hover:bg-primary-600 transition-colors disabled:opacity-60">
                        <span wire:loading.remove wire:target="saveBudget"><?php echo e($editingId ? 'Simpan Perubahan' : 'Tambah Anggaran'); ?></span>
                        <span wire:loading wire:target="saveBudget">Menyimpan...</span>
                    </button>
                </div>
            </div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showDeleteModal): ?>
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" wire:click="$set('showDeleteModal', false)"></div>
            <div class="relative bg-white rounded-2xl shadow-xl p-6 w-full max-w-sm z-10">
                <div class="text-center mb-5">
                    <span class="text-4xl block mb-3">🗑️</span>
                    <h3 class="font-bold text-slate-800 text-lg mb-1">Hapus Anggaran?</h3>
                    <p class="text-sm text-slate-500">Anggaran ini akan dihapus permanen. Transaksi tidak terpengaruh.</p>
                </div>
                <div class="flex gap-3">
                    <button wire:click="$set('showDeleteModal', false)"
                            class="flex-1 px-4 py-2.5 text-sm font-medium text-slate-600 bg-slate-100 rounded-xl hover:bg-slate-200 transition-colors">Batal</button>
                    <button wire:click="deleteBudget"
                            class="flex-1 px-4 py-2.5 text-sm font-semibold text-white bg-rose-500 rounded-xl hover:bg-rose-600 transition-colors">Ya, Hapus</button>
                </div>
            </div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

</div><?php /**PATH /Users/izarhairulanam/Downloads/finance-tracker-responsive 7/resources/views/livewire/budgets/index.blade.php ENDPATH**/ ?>