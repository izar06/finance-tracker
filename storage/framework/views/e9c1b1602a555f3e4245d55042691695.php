<div>

    
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Tagihan</h2>
            <p class="text-sm text-slate-500">Kelola tagihan rutin & jatuh tempo</p>
        </div>
        <button wire:click="openForm"
                class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold text-white bg-primary-500 rounded-xl hover:bg-primary-600 transition-colors shadow-md shadow-primary-500/25 whitespace-nowrap self-start sm:self-auto">
            + Tambah Tagihan
        </button>
    </div>

    
    <?php $s = $this->summary; ?>
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-5">
        <div class="bg-white rounded-2xl p-3 sm:p-5 border border-slate-100 shadow-sm">
            <p class="text-xs text-slate-500 mb-1">Tagihan Aktif</p>
            <p class="text-lg sm:text-2xl font-bold text-slate-800"><?php echo e($s['total_active']); ?></p>
            <p class="text-xs text-slate-400 mt-0.5">tagihan</p>
        </div>
        <div class="bg-white rounded-2xl p-3 sm:p-5 border border-slate-100 shadow-sm">
            <p class="text-xs text-slate-500 mb-1">Estimasi/Bulan</p>
            <p class="text-sm sm:text-xl font-bold text-slate-800 leading-tight">Rp <?php echo e(number_format($s['monthly_estimate'], 0, ',', '.')); ?></p>
            <p class="text-xs text-slate-400 mt-0.5">~Rp <?php echo e(number_format($s['yearly_estimate'], 0, ',', '.')); ?>/thn</p>
        </div>
        <div class="bg-white rounded-2xl p-3 sm:p-5 border border-<?php echo e($s['overdue_count'] > 0 ? 'rose' : 'slate'); ?>-100 shadow-sm <?php echo e($s['overdue_count'] > 0 ? 'bg-rose-50' : ''); ?>">
            <p class="text-xs <?php echo e($s['overdue_count'] > 0 ? 'text-rose-500' : 'text-slate-500'); ?> mb-1">Belum Dibayar</p>
            <p class="text-lg sm:text-2xl font-bold <?php echo e($s['unpaid_count'] > 0 ? 'text-rose-600' : 'text-emerald-600'); ?>"><?php echo e($s['unpaid_count']); ?></p>
            <p class="text-xs <?php echo e($s['overdue_count'] > 0 ? 'text-rose-400' : 'text-slate-400'); ?> mt-0.5">
                <?php echo e($s['overdue_count'] > 0 ? $s['overdue_count'].' terlambat' : 'semua aman'); ?>

            </p>
        </div>
        <div class="bg-white rounded-2xl p-3 sm:p-5 border border-<?php echo e($s['urgent_count'] > 0 ? 'amber' : 'slate'); ?>-100 shadow-sm <?php echo e($s['urgent_count'] > 0 ? 'bg-amber-50' : ''); ?>">
            <p class="text-xs <?php echo e($s['urgent_count'] > 0 ? 'text-amber-600' : 'text-slate-500'); ?> mb-1">Segera Jatuh Tempo</p>
            <p class="text-lg sm:text-2xl font-bold <?php echo e($s['urgent_count'] > 0 ? 'text-amber-600' : 'text-slate-400'); ?>"><?php echo e($s['urgent_count']); ?></p>
            <p class="text-xs <?php echo e($s['urgent_count'] > 0 ? 'text-amber-400' : 'text-slate-400'); ?> mt-0.5">dalam 7 hari</p>
        </div>
    </div>

    
    <div class="flex flex-wrap gap-2 mb-5">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = ['active' => 'Aktif', 'paused' => 'Dijeda', 'cancelled' => 'Dibatalkan', 'all' => 'Semua']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <button wire:click="setFilter('<?php echo e($val); ?>')"
                    class="px-4 py-2 text-sm font-medium rounded-xl transition-colors whitespace-nowrap
                           <?php echo e($filterStatus === $val
                               ? 'bg-primary-500 text-white shadow-md shadow-primary-500/25'
                               : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50'); ?>">
                <?php echo e($label); ?>

            </button>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($this->bills->count() > 0): ?>
        <div class="space-y-3">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $this->bills; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bill): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $urgency   = $bill->urgency;
                    $isPaid    = $bill->is_paid_this_month;
                    $days      = $bill->days_until_due;
                    $color     = $bill->urgency_color;

                    $borderClass = match(true) {
                        $isPaid                       => 'border-emerald-100 bg-emerald-50/30',
                        $urgency === 'overdue'        => 'border-rose-200 bg-rose-50',
                        $urgency === 'urgent'         => 'border-amber-200 bg-amber-50/50',
                        $bill->status === 'paused'    => 'border-slate-200 bg-slate-50/50 opacity-70',
                        default                       => 'border-slate-100 bg-white',
                    };

                    $badgeClass = match($urgency) {
                        'overdue' => 'bg-rose-100 text-rose-700',
                        'urgent'  => 'bg-amber-100 text-amber-700',
                        'soon'    => 'bg-blue-100 text-blue-700',
                        default   => 'bg-slate-100 text-slate-600',
                    };

                    $dueBadge = match(true) {
                        $isPaid             => ['label' => '✓ Lunas', 'class' => 'bg-emerald-100 text-emerald-700'],
                        $days < 0           => ['label' => abs($days).'h terlambat', 'class' => 'bg-rose-100 text-rose-700'],
                        $days === 0         => ['label' => 'Hari ini!', 'class' => 'bg-rose-100 text-rose-700'],
                        $days <= 3          => ['label' => $days.'h lagi', 'class' => 'bg-amber-100 text-amber-700'],
                        $days <= 7          => ['label' => $days.'h lagi', 'class' => 'bg-blue-100 text-blue-700'],
                        default             => ['label' => 'Tgl '.$bill->due_day, 'class' => 'bg-slate-100 text-slate-600'],
                    };
                ?>

                <div class="rounded-2xl border <?php echo e($borderClass); ?> shadow-sm p-4 sm:p-5 transition-all">
                    <div class="flex items-start justify-between gap-3">

                        
                        <div class="flex items-start gap-3 min-w-0 flex-1">
                            <div class="w-11 h-11 rounded-xl flex items-center justify-center text-xl flex-shrink-0
                                        <?php echo e($isPaid ? 'bg-emerald-100' : ($bill->status === 'paused' ? 'bg-slate-100' : 'bg-primary-50')); ?>">
                                <?php echo e($bill->icon); ?>

                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="flex flex-wrap items-center gap-2 mb-1">
                                    <p class="font-semibold text-slate-800 text-sm sm:text-base truncate"><?php echo e($bill->name); ?></p>
                                    
                                    <span class="text-xs font-semibold px-2 py-0.5 rounded-full <?php echo e($dueBadge['class']); ?>">
                                        <?php echo e($dueBadge['label']); ?>

                                    </span>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bill->status === 'paused'): ?>
                                        <span class="text-xs font-medium bg-slate-200 text-slate-500 px-2 py-0.5 rounded-full">⏸ Dijeda</span>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                                <div class="flex flex-wrap items-center gap-x-3 gap-y-0.5 text-xs text-slate-400">
                                    <span><?php echo e($bill->category); ?></span>
                                    <span>•</span>
                                    <span><?php echo e($bill->frequency_label); ?></span>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bill->payment_method): ?>
                                        <span>•</span>
                                        <span><?php echo e($bill->payment_method); ?></span>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bill->last_paid_at): ?>
                                        <span>•</span>
                                        <span>Dibayar <?php echo e($bill->last_paid_at->translatedFormat('d M Y')); ?></span>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                            </div>
                        </div>

                        
                        <div class="flex flex-col items-end gap-2 flex-shrink-0">
                            <p class="font-bold text-slate-800 text-sm sm:text-base whitespace-nowrap">
                                Rp <?php echo e(number_format($bill->amount, 0, ',', '.')); ?>

                            </p>
                            <div class="flex items-center gap-1">
                                
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bill->status === 'active' && !$isPaid): ?>
                                    <button wire:click="openPayModal(<?php echo e($bill->id); ?>)"
                                            class="inline-flex items-center gap-1 px-2.5 py-1.5 text-xs font-semibold text-white bg-emerald-500 hover:bg-emerald-600 rounded-lg transition-colors">
                                        ✓ Bayar
                                    </button>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bill->status !== 'cancelled'): ?>
                                    <button wire:click="togglePause(<?php echo e($bill->id); ?>)"
                                            title="<?php echo e($bill->status === 'active' ? 'Jeda' : 'Aktifkan'); ?>"
                                            class="p-1.5 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-colors">
                                        <?php echo e($bill->status === 'active' ? '⏸' : '▶'); ?>

                                    </button>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <button wire:click="editBill(<?php echo e($bill->id); ?>)"
                                        class="p-1.5 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">✏️</button>
                                <button wire:click="confirmDelete(<?php echo e($bill->id); ?>)"
                                        class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors">🗑️</button>
                            </div>
                        </div>
                    </div>

                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    <?php else: ?>
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm py-16 text-center">
            <span class="text-5xl block mb-4">🧾</span>
            <p class="font-semibold text-slate-700 mb-1">Belum ada tagihan</p>
            <p class="text-sm text-slate-400 mb-5">Tambah tagihan rutin agar tidak lupa bayar</p>
            <button wire:click="openForm"
                    class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-white bg-primary-500 rounded-xl hover:bg-primary-600 transition-colors">
                + Tambah Tagihan Pertama
            </button>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showForm): ?>
        <div class="fixed inset-0 z-50 flex items-end sm:items-center justify-center p-0 sm:p-4"
             x-data x-on:keydown.escape.window="$wire.closeForm()">
            <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" wire:click="closeForm"></div>
            <div class="relative bg-white w-full sm:max-w-lg sm:rounded-2xl rounded-t-2xl shadow-xl z-10 max-h-[92vh] overflow-y-auto">

                <div class="flex items-center justify-between p-6 border-b border-slate-100">
                    <h3 class="font-bold text-slate-800 text-lg"><?php echo e($editingId ? 'Edit Tagihan' : 'Tambah Tagihan'); ?></h3>
                    <button wire:click="closeForm" class="text-slate-400 hover:text-slate-600 text-xl leading-none">✕</button>
                </div>

                <div class="p-6 space-y-4">

                    
                    <div class="flex gap-3 items-start">
                        <div>
                            <label class="block text-xs font-medium text-slate-500 mb-1.5">Ikon</label>
                            <button type="button" wire:click="$toggle('showIconPicker')"
                                    class="w-12 h-12 rounded-xl border-2 border-slate-200 flex items-center justify-center text-2xl hover:border-primary-300 transition-colors bg-slate-50">
                                <?php echo e($icon); ?>

                            </button>
                        </div>
                        <div class="flex-1">
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Nama Tagihan</label>
                            <input wire:model="name" type="text" placeholder="Contoh: Listrik PLN, Netflix..."
                                   class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-300 focus:border-primary-400 outline-none <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-rose-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-xs text-rose-500 mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>

                    
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showIconPicker): ?>
                        <div class="p-3 border border-slate-200 rounded-xl bg-slate-50 max-h-36 overflow-y-auto">
                            <div class="grid grid-cols-8 gap-1">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = \App\Models\Bill::$icons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ico): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <button type="button" wire:click="selectIcon('<?php echo e($ico); ?>')"
                                            class="w-9 h-9 rounded-lg flex items-center justify-center text-xl hover:bg-white hover:shadow-sm transition-colors
                                                   <?php echo e($icon === $ico ? 'bg-primary-100 ring-2 ring-primary-400' : ''); ?>">
                                        <?php echo e($ico); ?>

                                    </button>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Kategori</label>
                        <select wire:model="category"
                                class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-300 outline-none bg-white <?php $__errorArgs = ['category'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-rose-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <option value="">-- Pilih Kategori --</option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = \App\Models\Bill::$categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat => $ico): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($cat); ?>"><?php echo e($ico); ?> <?php echo e($cat); ?></option>
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

                    
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Nominal</label>
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
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Tgl Jatuh Tempo</label>
                            <select wire:model="due_day"
                                    class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-300 outline-none bg-white <?php $__errorArgs = ['due_day'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-rose-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php for($d = 1; $d <= 31; $d++): ?>
                                    <option value="<?php echo e($d); ?>">Tanggal <?php echo e($d); ?></option>
                                <?php endfor; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </select>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['due_day'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-xs text-rose-500 mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>

                    
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Frekuensi</label>
                            <select wire:model="frequency"
                                    class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-300 outline-none bg-white">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = \App\Models\Bill::$frequencies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($val); ?>"><?php echo e($label); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Status</label>
                            <select wire:model="status"
                                    class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-300 outline-none bg-white">
                                <option value="active">✅ Aktif</option>
                                <option value="paused">⏸ Dijeda</option>
                                <option value="cancelled">❌ Dibatalkan</option>
                            </select>
                        </div>
                    </div>

                    
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">
                            Metode Pembayaran <span class="text-slate-400 font-normal">(opsional)</span>
                        </label>
                        <select wire:model="payment_method"
                                class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-300 outline-none bg-white">
                            <option value="">-- Pilih Metode --</option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $this->paymentMethods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pm): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($pm->name); ?>"><?php echo e($pm->icon); ?> <?php echo e($pm->name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </select>
                    </div>

                    
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">
                            Catatan <span class="text-slate-400 font-normal">(opsional)</span>
                        </label>
                        <textarea wire:model="notes" rows="2"
                                  placeholder="Contoh: ID Pelanggan, nomor rekening..."
                                  class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-300 outline-none resize-none"></textarea>
                    </div>
                </div>

                <div class="flex gap-3 p-6 border-t border-slate-100">
                    <button wire:click="closeForm"
                            class="flex-1 px-4 py-2.5 text-sm font-medium text-slate-600 bg-slate-100 rounded-xl hover:bg-slate-200 transition-colors">
                        Batal
                    </button>
                    <button wire:click="saveBill" wire:loading.attr="disabled"
                            class="flex-1 px-4 py-2.5 text-sm font-semibold text-white bg-primary-500 rounded-xl hover:bg-primary-600 transition-colors disabled:opacity-60">
                        <span wire:loading.remove wire:target="saveBill"><?php echo e($editingId ? 'Simpan' : 'Tambah'); ?></span>
                        <span wire:loading wire:target="saveBill">Menyimpan...</span>
                    </button>
                </div>
            </div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showPayModal && $payingId): ?>
        <?php $payBill = \App\Models\Bill::find($payingId); ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($payBill): ?>
        <div class="fixed inset-0 z-50 flex items-end sm:items-center justify-center p-0 sm:p-4"
             x-data x-on:keydown.escape.window="$wire.set('showPayModal', false)">
            <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" wire:click="$set('showPayModal', false)"></div>
            <div class="relative bg-white w-full sm:max-w-sm sm:rounded-2xl rounded-t-2xl shadow-xl p-6 z-10">

                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-bold text-slate-800 text-lg">Tandai Lunas</h3>
                    <button wire:click="$set('showPayModal', false)" class="text-slate-400 hover:text-slate-600 text-xl">✕</button>
                </div>

                
                <div class="flex items-center gap-3 bg-slate-50 rounded-xl p-3 mb-5">
                    <span class="text-2xl"><?php echo e($payBill->icon); ?></span>
                    <div>
                        <p class="font-semibold text-slate-800 text-sm"><?php echo e($payBill->name); ?></p>
                        <p class="text-sm font-bold text-primary-600">
                            Rp <?php echo e(number_format((float)($payBill->amount ?? 0), 0, ',', '.')); ?>

                        </p>
                    </div>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Tanggal Bayar</label>
                        <input wire:model="payDate" type="date"
                               class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-300 outline-none">
                    </div>

                    
                    <div class="flex items-center justify-between p-3 bg-primary-50 rounded-xl border border-primary-100">
                        <div>
                            <p class="text-sm font-medium text-primary-800">Catat sebagai transaksi</p>
                            <p class="text-xs text-primary-600">Otomatis buat pengeluaran di halaman Transaksi</p>
                        </div>
                        <button type="button" wire:click="$toggle('autoCreateTransaction')"
                                class="relative inline-flex h-6 w-11 items-center rounded-full transition-colors flex-shrink-0
                                       <?php echo e($autoCreateTransaction ? 'bg-primary-500' : 'bg-slate-300'); ?>">
                            <span class="inline-block h-4 w-4 transform rounded-full bg-white shadow-sm transition-transform
                                         <?php echo e($autoCreateTransaction ? 'translate-x-6' : 'translate-x-1'); ?>"></span>
                        </button>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">
                            Catatan <span class="text-slate-400 font-normal">(opsional)</span>
                        </label>
                        <input wire:model="payNotes" type="text" placeholder="Catatan tambahan..."
                               class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-300 outline-none">
                    </div>
                </div>

                <div class="flex gap-3 mt-6">
                    <button wire:click="$set('showPayModal', false)"
                            class="flex-1 px-4 py-2.5 text-sm font-medium text-slate-600 bg-slate-100 rounded-xl hover:bg-slate-200 transition-colors">
                        Batal
                    </button>
                    <button wire:click="markAsPaid" wire:loading.attr="disabled"
                            class="flex-1 px-4 py-2.5 text-sm font-semibold text-white bg-emerald-500 rounded-xl hover:bg-emerald-600 transition-colors disabled:opacity-60">
                        <span wire:loading.remove wire:target="markAsPaid">✓ Tandai Lunas</span>
                        <span wire:loading wire:target="markAsPaid">Menyimpan...</span>
                    </button>
                </div>
            </div>
        </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showDeleteModal): ?>
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" wire:click="$set('showDeleteModal', false)"></div>
            <div class="relative bg-white rounded-2xl shadow-xl p-6 w-full max-w-sm z-10">
                <div class="text-center mb-5">
                    <span class="text-4xl block mb-3">🗑️</span>
                    <h3 class="font-bold text-slate-800 text-lg mb-1">Hapus Tagihan?</h3>
                    <p class="text-sm text-slate-500">Tagihan ini akan dihapus permanen. Transaksi yang sudah dicatat tidak terpengaruh.</p>
                </div>
                <div class="flex gap-3">
                    <button wire:click="$set('showDeleteModal', false)"
                            class="flex-1 px-4 py-2.5 text-sm font-medium text-slate-600 bg-slate-100 rounded-xl hover:bg-slate-200 transition-colors">
                        Batal
                    </button>
                    <button wire:click="deleteBill" wire:loading.attr="disabled"
                            class="flex-1 px-4 py-2.5 text-sm font-semibold text-white bg-rose-500 rounded-xl hover:bg-rose-600 transition-colors">
                        <span wire:loading.remove wire:target="deleteBill">Ya, Hapus</span>
                        <span wire:loading wire:target="deleteBill">Menghapus...</span>
                    </button>
                </div>
            </div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

</div>
<?php /**PATH /Users/izarhairulanam/Downloads/finance-tracker-responsive 7/resources/views/livewire/bills/index.blade.php ENDPATH**/ ?>