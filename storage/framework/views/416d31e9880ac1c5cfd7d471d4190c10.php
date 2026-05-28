<div>

    
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Daftar Transaksi</h2>
            <p class="text-sm text-slate-500">Kelola pemasukan dan pengeluaran Anda</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(count($selectedIds) > 0): ?>
                <button wire:click="confirmBulkDelete"
                        class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-white bg-rose-500 border border-rose-500 rounded-xl hover:bg-rose-600 transition-colors">
                    <span>🗑️</span> Hapus (<?php echo e(count($selectedIds)); ?>)
                </button>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <button wire:click="exportExcel"
                    class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition-colors">
                <span>📊</span> Excel
            </button>
            <button wire:click="exportPdf"
                    class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition-colors">
                <span>📄</span> PDF
            </button>
            <button wire:click="openImportModal"
                    class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition-colors">
                <span>📥</span> Import
            </button>
            <button wire:click="openForm"
                    class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold text-white bg-primary-500 rounded-xl hover:bg-primary-600 transition-colors shadow-md shadow-primary-500/25">
                <span>+</span> Tambah Transaksi
            </button>
        </div>
    </div>

    
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-4 mb-5">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
            
            <div class="lg:col-span-2">
                <input wire:model.live.debounce.400ms="search" type="text"
                       placeholder="🔍 Cari transaksi..."
                       class="w-full px-4 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary-300 focus:border-primary-400 outline-none">
            </div>
            <select wire:model.live="filterType"
                    class="px-4 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary-300 outline-none bg-white">
                <option value="">Semua Tipe</option>
                <option value="income">Pemasukan</option>
                <option value="expense">Pengeluaran</option>
            </select>
            <select wire:model.live="filterCategory"
                    class="px-4 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary-300 outline-none bg-white <?php echo e($filterCategory ? 'border-primary-400 ring-2 ring-primary-200' : ''); ?>">
                <option value="">Semua Kategori</option>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $this->categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($cat); ?>"><?php echo e($cat); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </select>

            
            <select wire:model.live="filterPaymentMethod"
                    class="px-4 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary-300 outline-none bg-white">
                <option value="">Semua Metode</option>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = \App\Models\PaymentMethod::ordered(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pm): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($pm->name); ?>"><?php echo e($pm->icon); ?> <?php echo e($pm->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </select>
            <select wire:model.live="filterMonth"
                    class="px-4 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary-300 outline-none bg-white">
                <option value="">Semua Bulan</option>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = range(1, 12); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $m): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($m); ?>"><?php echo e(\Carbon\Carbon::create()->month($m)->translatedFormat('F')); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </select>
            <select wire:model.live="filterYear"
                    class="px-4 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary-300 outline-none bg-white">
                <option value="">Semua Tahun</option>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = range(now()->year, now()->year - 3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $y): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($y); ?>"><?php echo e($y); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </select>
            <select wire:model.live="filterRecurring"
                    class="px-4 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary-300 outline-none bg-white">
                <option value="">Semua</option>
                <option value="recurring">🔁 Berulang saja</option>
                <option value="not_recurring">Sekali saja</option>
            </select>
        </div>

        
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($filterCategory): ?>
            <div class="mt-3 flex items-center gap-2">
                <span class="text-xs text-slate-400">Filter aktif:</span>
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium bg-primary-50 text-primary-700 border border-primary-200 rounded-lg">
                    🏷️ <?php echo e($filterCategory); ?>

                    <button wire:click="$set('filterCategory', '')" class="hover:text-primary-900 font-bold leading-none">✕</button>
                </span>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">

        
        <div class="md:hidden divide-y divide-slate-100">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tx): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="p-4 hover:bg-slate-50 transition-colors">
                    <div class="flex items-start justify-between gap-3 mb-2">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-9 h-9 rounded-xl flex items-center justify-center text-sm flex-shrink-0
                                        <?php echo e($tx->type === 'income' ? 'bg-emerald-50' : 'bg-rose-50'); ?>">
                                <?php echo e($tx->is_recurring ? '🔁' : ($tx->type === 'income' ? '📥' : '📤')); ?>

                            </div>
                            <div class="min-w-0">
                                <p class="font-semibold text-slate-800 text-sm truncate"><?php echo e($tx->title); ?></p>
                                <p class="text-xs text-slate-400"><?php echo e($tx->date->translatedFormat('d M Y')); ?></p>
                            </div>
                        </div>
                        <p class="font-bold text-sm whitespace-nowrap flex-shrink-0 <?php echo e($tx->type === 'income' ? 'text-emerald-600' : 'text-rose-600'); ?>">
                            <?php echo e($tx->type === 'income' ? '+' : '-'); ?> Rp <?php echo e(number_format($tx->amount, 0, ',', '.')); ?>

                        </p>
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium bg-slate-100 text-slate-700 rounded-lg"><?php echo e($tx->category); ?></span>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($tx->is_recurring): ?>
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 text-xs font-medium bg-violet-50 text-violet-700 rounded-lg">
                                    🔁 <?php echo e(\App\Models\Transaction::$recurringFrequencies[$tx->recurring_frequency] ?? ''); ?>

                                </span>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($tx->recurring_parent_id): ?>
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 text-xs font-medium bg-blue-50 text-blue-600 rounded-lg">⚡ Otomatis</span>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                        <div class="flex items-center gap-1 flex-shrink-0">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($tx->is_recurring): ?>
                                <button wire:click="showRecurringInfo(<?php echo e($tx->id); ?>)" class="p-1.5 text-violet-400 hover:text-violet-600 hover:bg-violet-50 rounded-lg transition-colors" title="Info Berulang">🔁</button>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <button wire:click="editTransaction(<?php echo e($tx->id); ?>)" class="p-1.5 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">✏️</button>
                            <button wire:click="confirmDelete(<?php echo e($tx->id); ?>)" class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors">🗑️</button>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="py-16 text-center">
                    <span class="text-4xl block mb-3">💳</span>
                    <p class="text-slate-500 font-medium">Tidak ada transaksi ditemukan</p>
                    <p class="text-sm text-slate-400 mt-1">Coba ubah filter atau tambah transaksi baru</p>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="text-center px-4 py-3.5 font-semibold text-slate-600 w-10">
                            <input type="checkbox" wire:model.live="selectAll"
                                   class="w-4 h-4 rounded accent-primary-500 cursor-pointer">
                        </th>
                        <th class="text-center px-3 py-3.5 font-semibold text-slate-600 w-12">No</th>
                        <th class="text-left px-5 py-3.5 font-semibold text-slate-600">
                            <button wire:click="sortColumn('date')" class="flex items-center gap-1 hover:text-primary-600">
                                Tanggal
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($sortBy === 'date'): ?><span><?php echo e($sortDir === 'asc' ? '↑' : '↓'); ?></span><?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </button>
                        </th>
                        <th class="text-left px-5 py-3.5 font-semibold text-slate-600">Judul</th>
                        <th class="text-left px-5 py-3.5 font-semibold text-slate-600">Kategori</th>
                        <th class="text-left px-5 py-3.5 font-semibold text-slate-600">Metode</th>
                        <th class="text-left px-5 py-3.5 font-semibold text-slate-600">Tipe</th>
                        <th class="text-right px-5 py-3.5 font-semibold text-slate-600">
                            <button wire:click="sortColumn('amount')" class="flex items-center gap-1 ml-auto hover:text-primary-600">
                                Jumlah
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($sortBy === 'amount'): ?><span><?php echo e($sortDir === 'asc' ? '↑' : '↓'); ?></span><?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </button>
                        </th>
                        <th class="text-center px-5 py-3.5 font-semibold text-slate-600">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tx): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-slate-50 transition-colors <?php echo e($tx->is_recurring ? 'bg-violet-50/30' : ''); ?> <?php echo e(in_array((string)$tx->id, $selectedIds) ? 'bg-rose-50/40' : ''); ?>">
                            <td class="px-4 py-3.5 text-center">
                                <input type="checkbox" wire:model.live="selectedIds" value="<?php echo e($tx->id); ?>"
                                       class="w-4 h-4 rounded accent-primary-500 cursor-pointer">
                            </td>
                            <td class="px-3 py-3.5 text-center text-sm text-slate-400 font-medium">
                                <?php echo e($transactions->firstItem() + $loop->index); ?>

                            </td>
                            <td class="px-5 py-3.5 text-slate-500 whitespace-nowrap"><?php echo e($tx->date->translatedFormat('d M Y')); ?></td>
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-2">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($tx->is_recurring): ?>
                                        <span class="text-violet-500 text-base" title="Template berulang">🔁</span>
                                    <?php elseif($tx->recurring_parent_id): ?>
                                        <span class="text-blue-400 text-xs" title="Dibuat otomatis">⚡</span>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    <div>
                                        <p class="font-medium text-slate-800"><?php echo e($tx->title); ?></p>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($tx->notes): ?>
                                            <p class="text-xs text-slate-400 truncate max-w-xs"><?php echo e($tx->notes); ?></p>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($tx->is_recurring && $tx->next_recurring_date): ?>
                                            <p class="text-xs text-violet-500 mt-0.5">
                                                Berikutnya: <?php echo e($tx->next_recurring_date->translatedFormat('d M Y')); ?>

                                            </p>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-3.5">
                                <span class="inline-flex items-center px-2.5 py-1 text-xs font-medium bg-slate-100 text-slate-700 rounded-lg"><?php echo e($tx->category); ?></span>
                            </td>
                            <td class="px-5 py-3.5">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($tx->payment_method): ?>
                                    <?php $pmIcon = \App\Models\PaymentMethod::withoutGlobalScopes()->where('user_id', auth()->id())->where('name', $tx->payment_method)->value('icon') ?? '🔖'; ?>
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-medium bg-blue-50 text-blue-700 rounded-lg">
                                        <?php echo e($pmIcon); ?> <?php echo e($tx->payment_method); ?>

                                    </span>
                                <?php else: ?>
                                    <span class="text-xs text-slate-300">—</span>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </td>
                            <td class="px-5 py-3.5">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($tx->type === 'income'): ?>
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold text-emerald-700 bg-emerald-50 rounded-lg">📥 Pemasukan</span>
                                <?php else: ?>
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold text-rose-700 bg-rose-50 rounded-lg">📤 Pengeluaran</span>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </td>
                            <td class="px-5 py-3.5 text-right font-bold whitespace-nowrap <?php echo e($tx->type === 'income' ? 'text-emerald-600' : 'text-rose-600'); ?>">
                                <?php echo e($tx->type === 'income' ? '+' : '-'); ?> Rp <?php echo e(number_format($tx->amount, 0, ',', '.')); ?>

                            </td>
                            <td class="px-5 py-3.5 text-center">
                                <div class="flex items-center justify-center gap-1">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($tx->is_recurring): ?>
                                        <button wire:click="showRecurringInfo(<?php echo e($tx->id); ?>)"
                                                class="p-1.5 text-violet-400 hover:text-violet-600 hover:bg-violet-50 rounded-lg transition-colors"
                                                title="Info transaksi berulang">🔁</button>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    <button wire:click="editTransaction(<?php echo e($tx->id); ?>)"
                                            class="p-1.5 text-slate-500 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Edit">✏️</button>
                                    <button wire:click="confirmDelete(<?php echo e($tx->id); ?>)"
                                            class="p-1.5 text-slate-500 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors" title="Hapus">🗑️</button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="9" class="py-16 text-center">
                                <span class="text-4xl block mb-3">💳</span>
                                <p class="text-slate-500 font-medium">Tidak ada transaksi ditemukan</p>
                                <p class="text-sm text-slate-400 mt-1">Coba ubah filter atau tambah transaksi baru</p>
                            </td>
                        </tr>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($transactions->hasPages()): ?>
            <div class="px-5 py-4 border-t border-slate-100"><?php echo e($transactions->links()); ?></div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($filteredSummary['count'] > 0): ?>
        <div class="px-5 py-4 border-t border-slate-100 bg-slate-50/60">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <p class="text-xs text-slate-400 font-medium">
                    Ringkasan dari <span class="font-semibold text-slate-600"><?php echo e(number_format($filteredSummary['count'])); ?></span> transaksi
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($search || $filterType || $filterCategory || $filterPaymentMethod || $filterMonth || $filterYear || $filterRecurring): ?>
                        <span class="ml-1 text-primary-500">(hasil filter)</span>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </p>
                <div class="flex flex-wrap items-center gap-3 sm:gap-6">
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 flex-shrink-0"></span>
                        <div>
                            <p class="text-xs text-slate-400 leading-none mb-0.5">Total Pemasukan</p>
                            <p class="text-sm font-bold text-emerald-600">+ Rp <?php echo e(number_format($filteredSummary['income'], 0, ',', '.')); ?></p>
                        </div>
                    </div>
                    <div class="w-px h-8 bg-slate-200 hidden sm:block"></div>
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-rose-400 flex-shrink-0"></span>
                        <div>
                            <p class="text-xs text-slate-400 leading-none mb-0.5">Total Pengeluaran</p>
                            <p class="text-sm font-bold text-rose-600">- Rp <?php echo e(number_format($filteredSummary['expense'], 0, ',', '.')); ?></p>
                        </div>
                    </div>
                    <div class="w-px h-8 bg-slate-200 hidden sm:block"></div>
                    <div class="flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full <?php echo e($filteredSummary['balance'] >= 0 ? 'bg-primary-400' : 'bg-amber-400'); ?> flex-shrink-0"></span>
                        <div>
                            <p class="text-xs text-slate-400 leading-none mb-0.5">Saldo</p>
                            <p class="text-sm font-bold <?php echo e($filteredSummary['balance'] >= 0 ? 'text-primary-600' : 'text-amber-600'); ?>">
                                <?php echo e($filteredSummary['balance'] >= 0 ? '+' : ''); ?> Rp <?php echo e(number_format($filteredSummary['balance'], 0, ',', '.')); ?>

                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    
    
    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showForm): ?>
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg max-h-[90vh] overflow-y-auto">
                <div class="flex items-center justify-between p-6 border-b border-slate-100">
                    <h3 class="text-lg font-bold text-slate-800"><?php echo e($editingId ? 'Edit Transaksi' : 'Tambah Transaksi Baru'); ?></h3>
                    <button wire:click="closeForm" class="p-2 hover:bg-slate-100 rounded-xl text-slate-400">✕</button>
                </div>

                <form wire:submit="saveTransaction" class="p-6 space-y-4" id="transaction-form">

                    
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Tipe Transaksi</label>
                        <div class="flex rounded-xl border border-slate-200 overflow-hidden">
                            <button type="button" wire:click="$set('type', 'income')"
                                    class="flex-1 py-2.5 text-sm font-semibold transition-colors <?php echo e($type === 'income' ? 'bg-emerald-500 text-white' : 'text-slate-600 hover:bg-slate-50'); ?>">
                                📥 Pemasukan
                            </button>
                            <button type="button" wire:click="$set('type', 'expense')"
                                    class="flex-1 py-2.5 text-sm font-semibold transition-colors <?php echo e($type === 'expense' ? 'bg-rose-500 text-white' : 'text-slate-600 hover:bg-slate-50'); ?>">
                                📤 Pengeluaran
                            </button>
                        </div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-xs text-rose-500 mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Judul</label>
                        <input wire:model="title" type="text" placeholder="Contoh: Gaji Bulanan"
                               class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-300 focus:border-primary-400 outline-none <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-rose-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-xs text-rose-500 mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Jumlah (Rp)</label>
                            <input type="hidden" wire:model="amount" data-currency-model>
                            <input type="text" data-currency inputmode="numeric" placeholder="0" autocomplete="off"
                                   class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-300 focus:border-primary-400 outline-none <?php $__errorArgs = ['amount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-rose-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
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
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Tanggal</label>
                            <input wire:model="date" type="date"
                                   class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-300 focus:border-primary-400 outline-none <?php $__errorArgs = ['date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-rose-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-xs text-rose-500 mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Kategori</label>
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
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = \App\Models\Category::where('type', $type)->orderBy('name')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($cat->name); ?>"><?php echo e($cat->icon); ?> <?php echo e($cat->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </select>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['category'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-xs text-rose-500 mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">
                            Metode Pembayaran <span class="text-slate-400 font-normal">(opsional)</span>
                        </label>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = \App\Models\PaymentMethod::ordered(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pm): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <button type="button"
                                        wire:click="$set('payment_method', '<?php echo e($payment_method === $pm->name ? '' : $pm->name); ?>')"
                                        class="flex flex-col items-center gap-1 px-2 py-2.5 text-xs font-medium rounded-xl border transition-all
                                               <?php echo e($payment_method === $pm->name
                                                   ? 'border-primary-400 bg-primary-50 text-primary-700 shadow-sm'
                                                   : 'border-slate-200 text-slate-600 hover:border-primary-300 hover:bg-slate-50'); ?>">
                                    <span class="text-lg leading-none"><?php echo e($pm->icon); ?></span>
                                    <span class="leading-tight text-center"><?php echo e($pm->name); ?></span>
                                </button>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>

                    
                    
                    
                    <div class="border border-slate-200 rounded-xl overflow-hidden">
                        
                        <button type="button" wire:click="$toggle('is_recurring')"
                                class="w-full flex items-center justify-between px-4 py-3 text-sm font-medium transition-colors
                                       <?php echo e($is_recurring ? 'bg-violet-50 text-violet-700' : 'bg-slate-50 text-slate-700 hover:bg-slate-100'); ?>">
                            <span class="flex items-center gap-2">
                                <span class="text-base">🔁</span>
                                Transaksi Berulang
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($is_recurring): ?>
                                    <span class="text-xs font-semibold px-2 py-0.5 bg-violet-100 text-violet-700 rounded-full">Aktif</span>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </span>
                            <span class="text-slate-400 transition-transform <?php echo e($is_recurring ? 'rotate-180' : ''); ?>">▼</span>
                        </button>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($is_recurring): ?>
                            <div class="p-4 space-y-3 border-t border-slate-100 bg-violet-50/30">
                                <div class="grid grid-cols-2 gap-3">
                                    <div>
                                        <label class="block text-xs font-medium text-slate-600 mb-1.5">Frekuensi</label>
                                        <select wire:model.live="recurring_frequency"
                                                class="w-full px-3 py-2 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-violet-300 focus:border-violet-400 outline-none bg-white">
                                            <option value="daily">📅 Setiap Hari</option>
                                            <option value="weekly">📅 Setiap Minggu</option>
                                            <option value="monthly">📅 Setiap Bulan</option>
                                            <option value="yearly">📅 Setiap Tahun</option>
                                        </select>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['recurring_frequency'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-xs text-rose-500 mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-medium text-slate-600 mb-1.5">
                                            Berakhir pada <span class="text-slate-400">(opsional)</span>
                                        </label>
                                        <input wire:model="recurring_ends_at" type="date"
                                               class="w-full px-3 py-2 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-violet-300 outline-none <?php $__errorArgs = ['recurring_ends_at'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-rose-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['recurring_ends_at'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-xs text-rose-500 mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>
                                </div>

                                
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($date && $recurring_frequency): ?>
                                    <?php
                                        $previewDate = \Carbon\Carbon::parse($date);
                                        $previews = [];
                                        for ($i = 0; $i < 3; $i++) {
                                            $previewDate = match($recurring_frequency) {
                                                'daily'   => $previewDate->copy()->addDay(),
                                                'weekly'  => $previewDate->copy()->addWeek(),
                                                'monthly' => $previewDate->copy()->addMonth(),
                                                'yearly'  => $previewDate->copy()->addYear(),
                                            };
                                            $previews[] = $previewDate->translatedFormat('d M Y');
                                        }
                                    ?>
                                    <div class="bg-white rounded-xl p-3 border border-violet-100">
                                        <p class="text-xs font-medium text-slate-500 mb-2">📌 Jadwal berikutnya:</p>
                                        <div class="flex flex-wrap gap-2">
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $previews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $preview): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <span class="px-2.5 py-1 text-xs font-medium bg-violet-100 text-violet-700 rounded-lg"><?php echo e($preview); ?></span>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            <span class="px-2.5 py-1 text-xs text-slate-400">dan seterusnya...</span>
                                        </div>
                                    </div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Catatan (opsional)</label>
                        <textarea wire:model="notes" rows="2" placeholder="Tambahkan catatan..."
                                  class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-300 focus:border-primary-400 outline-none resize-none"></textarea>
                    </div>

                    <div class="flex gap-3 pt-2">
                        <button type="button" wire:click="closeForm"
                                class="flex-1 py-2.5 text-sm font-medium text-slate-700 bg-slate-100 rounded-xl hover:bg-slate-200 transition-colors">
                            Batal
                        </button>
                        <button type="submit"
                                class="flex-1 py-2.5 text-sm font-semibold text-white bg-primary-500 rounded-xl hover:bg-primary-600 transition-colors">
                            <?php echo e($editingId ? 'Simpan Perubahan' : 'Tambah Transaksi'); ?>

                        </button>
                    </div>
                </form>
            </div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    
    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showDeleteModal): ?>
        <?php $deletingTx = $deletingId ? \App\Models\Transaction::find($deletingId) : null; ?>
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm p-6 text-center">
                <div class="w-14 h-14 bg-rose-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <span class="text-2xl">🗑️</span>
                </div>
                <h3 class="text-lg font-bold text-slate-800 mb-2">Hapus Transaksi?</h3>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($deletingTx && ($deletingTx->is_recurring || $deletingTx->recurring_parent_id)): ?>
                    <p class="text-sm text-slate-500 mb-4">Ini adalah transaksi berulang. Pilih yang ingin dihapus:</p>
                    <div class="flex flex-col gap-2 mb-5 text-left">
                        <label class="flex items-center gap-3 p-3 border border-slate-200 rounded-xl cursor-pointer hover:bg-slate-50 transition-colors <?php echo e($deleteScope === 'single' ? 'border-rose-300 bg-rose-50' : ''); ?>">
                            <input type="radio" wire:model="deleteScope" value="single" class="accent-rose-500">
                            <div>
                                <p class="text-sm font-medium text-slate-700">Hanya transaksi ini</p>
                                <p class="text-xs text-slate-400">Jadwal berikutnya tetap berjalan</p>
                            </div>
                        </label>
                        <label class="flex items-center gap-3 p-3 border border-slate-200 rounded-xl cursor-pointer hover:bg-slate-50 transition-colors <?php echo e($deleteScope === 'all' ? 'border-rose-300 bg-rose-50' : ''); ?>">
                            <input type="radio" wire:model="deleteScope" value="all" class="accent-rose-500">
                            <div>
                                <p class="text-sm font-medium text-slate-700">Hapus semua (template + riwayat)</p>
                                <p class="text-xs text-slate-400">Seluruh data berulang akan dihapus permanen</p>
                            </div>
                        </label>
                    </div>
                <?php else: ?>
                    <p class="text-sm text-slate-500 mb-6">Data transaksi ini akan dihapus permanen dan tidak bisa dikembalikan.</p>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <div class="flex gap-3">
                    <button wire:click="$set('showDeleteModal', false)"
                            class="flex-1 py-2.5 text-sm font-medium text-slate-700 bg-slate-100 rounded-xl hover:bg-slate-200">Batal</button>
                    <button wire:click="deleteTransaction"
                            class="flex-1 py-2.5 text-sm font-semibold text-white bg-rose-500 rounded-xl hover:bg-rose-600">Hapus</button>
                </div>
            </div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    
    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showRecurringDetail && $recurringDetail): ?>
        <?php $rd = $recurringDetail; ?>
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm">
                <div class="flex items-center justify-between p-5 border-b border-slate-100">
                    <h3 class="text-base font-bold text-slate-800 flex items-center gap-2">
                        🔁 Info Transaksi Berulang
                    </h3>
                    <button wire:click="$set('showRecurringDetail', false)" class="p-2 hover:bg-slate-100 rounded-xl text-slate-400">✕</button>
                </div>
                <div class="p-5 space-y-3">
                    <div class="bg-violet-50 rounded-xl p-4 space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-500">Judul</span>
                            <span class="font-medium text-slate-800"><?php echo e($rd->title); ?></span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-500">Frekuensi</span>
                            <span class="font-medium text-violet-700"><?php echo e(\App\Models\Transaction::$recurringFrequencies[$rd->recurring_frequency] ?? '-'); ?></span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-500">Mulai dari</span>
                            <span class="font-medium text-slate-800"><?php echo e($rd->date->translatedFormat('d M Y')); ?></span>
                        </div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($rd->next_recurring_date): ?>
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-500">Jadwal berikutnya</span>
                            <span class="font-medium text-violet-700"><?php echo e($rd->next_recurring_date->translatedFormat('d M Y')); ?></span>
                        </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($rd->recurring_ends_at): ?>
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-500">Berakhir pada</span>
                            <span class="font-medium text-slate-800"><?php echo e($rd->recurring_ends_at->translatedFormat('d M Y')); ?></span>
                        </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-500">Total dibuat</span>
                            <span class="font-medium text-slate-800"><?php echo e($rd->recurringChildren()->withoutGlobalScopes()->count()); ?> transaksi</span>
                        </div>
                    </div>

                    <div class="flex gap-2 pt-1">
                        <button wire:click="editTransaction(<?php echo e($rd->id); ?>)"
                                class="flex-1 py-2.5 text-sm font-medium text-blue-700 bg-blue-50 rounded-xl hover:bg-blue-100 transition-colors">
                            ✏️ Edit Template
                        </button>
                        <button wire:click="stopRecurring(<?php echo e($rd->id); ?>)"
                                class="flex-1 py-2.5 text-sm font-medium text-rose-700 bg-rose-50 rounded-xl hover:bg-rose-100 transition-colors"
                                onclick="return confirm('Hentikan transaksi berulang ini? Transaksi yang sudah ada tidak akan terhapus.')">
                            ⏹ Hentikan
                        </button>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    
    
    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showBulkDeleteModal): ?>
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm p-6 text-center">
                <div class="w-14 h-14 bg-rose-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <span class="text-2xl">🗑️</span>
                </div>
                <h3 class="text-lg font-bold text-slate-800 mb-2">Hapus <?php echo e(count($selectedIds)); ?> Transaksi?</h3>
                <p class="text-sm text-slate-500 mb-6">Semua transaksi yang dipilih akan dihapus permanen dan tidak bisa dikembalikan.</p>
                <div class="flex gap-3">
                    <button wire:click="$set('showBulkDeleteModal', false)"
                            class="flex-1 py-2.5 text-sm font-medium text-slate-700 bg-slate-100 rounded-xl hover:bg-slate-200">Batal</button>
                    <button wire:click="bulkDeleteTransactions"
                            class="flex-1 py-2.5 text-sm font-semibold text-white bg-rose-500 rounded-xl hover:bg-rose-600">Hapus Semua</button>
                </div>
            </div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    
    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showImportModal): ?>
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md">

                
                <div class="flex items-center justify-between p-5 border-b border-slate-100">
                    <h3 class="text-base font-bold text-slate-800 flex items-center gap-2">
                        📥 Import Transaksi
                    </h3>
                    <button wire:click="closeImportModal" class="p-2 hover:bg-slate-100 rounded-xl text-slate-400">✕</button>
                </div>

                <div class="p-5 space-y-4">

                    
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($importedCount !== null): ?>
                        <div class="space-y-3">
                            
                            <div class="flex items-center gap-3 p-3 bg-emerald-50 border border-emerald-200 rounded-xl">
                                <span class="text-xl">✅</span>
                                <div>
                                    <p class="text-sm font-semibold text-emerald-700"><?php echo e($importedCount); ?> transaksi berhasil diimport</p>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($skippedCount > 0): ?>
                                        <p class="text-xs text-emerald-600"><?php echo e($skippedCount); ?> baris dilewati</p>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                            </div>

                            
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(count($importErrors) > 0): ?>
                                <div class="bg-rose-50 border border-rose-200 rounded-xl p-3">
                                    <p class="text-xs font-semibold text-rose-700 mb-2">Detail baris yang dilewati:</p>
                                    <ul class="space-y-1">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $importErrors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $err): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <li class="text-xs text-rose-600">• <?php echo e($err); ?></li>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </ul>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($skippedCount > count($importErrors)): ?>
                                        <p class="text-xs text-rose-400 mt-1">...dan <?php echo e($skippedCount - count($importErrors)); ?> baris lainnya.</p>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                            <button wire:click="closeImportModal"
                                    class="w-full py-2.5 text-sm font-semibold text-white bg-primary-500 rounded-xl hover:bg-primary-600 transition-colors">
                                Selesai
                            </button>
                        </div>

                    
                    <?php else: ?>
                        
                        <div class="bg-blue-50 border border-blue-200 rounded-xl p-3 text-xs text-blue-700 space-y-1">
                            <p class="font-semibold">Format kolom yang didukung:</p>
                            <p><span class="font-medium">tipe</span> — income/expense atau pemasukan/pengeluaran</p>
                            <p><span class="font-medium">judul</span> — nama transaksi (wajib)</p>
                            <p><span class="font-medium">jumlah</span> — nominal angka (wajib)</p>
                            <p><span class="font-medium">kategori</span> — nama kategori</p>
                            <p><span class="font-medium">tanggal</span> — format YYYY-MM-DD</p>
                            <p><span class="font-medium">metode_pembayaran</span> — opsional</p>
                            <p><span class="font-medium">catatan</span> — opsional</p>
                        </div>

                        
                        <button wire:click="downloadTemplate"
                                class="w-full flex items-center justify-center gap-2 py-2 text-xs font-medium text-primary-600 border border-primary-200 bg-primary-50 rounded-xl hover:bg-primary-100 transition-colors">
                            ⬇️ Download template CSV
                        </button>

                        
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Pilih file (.xlsx, .xls, .csv)</label>
                            <input type="file" wire:model="importFile" accept=".xlsx,.xls,.csv"
                                   class="w-full text-sm text-slate-600 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100 border border-slate-200 rounded-xl p-1 cursor-pointer">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['importFile'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-xs text-rose-500 mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>

                        
                        <div wire:loading wire:target="importFile" class="text-xs text-slate-400 text-center">Mengunggah file...</div>

                        <div class="flex gap-3 pt-1">
                            <button wire:click="closeImportModal"
                                    class="flex-1 py-2.5 text-sm font-medium text-slate-700 bg-slate-100 rounded-xl hover:bg-slate-200 transition-colors">
                                Batal
                            </button>
                            <button wire:click="importTransactions"
                                    wire:loading.attr="disabled"
                                    wire:target="importTransactions"
                                    class="flex-1 py-2.5 text-sm font-semibold text-white bg-primary-500 rounded-xl hover:bg-primary-600 transition-colors disabled:opacity-60">
                                <span wire:loading.remove wire:target="importTransactions">Import Sekarang</span>
                                <span wire:loading wire:target="importTransactions">Memproses...</span>
                            </button>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

</div><?php /**PATH /Users/izarhairulanam/Downloads/finance-tracker-responsive 7/resources/views/livewire/transactions/index.blade.php ENDPATH**/ ?>