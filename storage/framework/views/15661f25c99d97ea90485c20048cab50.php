<div>

    
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Manajemen Aset</h2>
            <p class="text-sm text-slate-500">Pantau nilai dan pertumbuhan aset Anda</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <button wire:click="exportExcel"
                    class="inline-flex items-center gap-1.5 px-3 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-200 rounded-xl hover:bg-slate-50">
                📊 <span class="hidden sm:inline">Excel</span>
            </button>
            <button wire:click="exportPdf"
                    class="inline-flex items-center gap-1.5 px-3 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-200 rounded-xl hover:bg-slate-50">
                📄 <span class="hidden sm:inline">PDF</span>
            </button>
            <button wire:click="openForm"
                    class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold text-white bg-primary-500 rounded-xl hover:bg-primary-600 shadow-md shadow-primary-500/25 whitespace-nowrap">
                + Tambah Aset
            </button>
        </div>
    </div>

    
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-6">
        <?php
            $totalVal = $this->totalValue;
            $typeData = $this->valueByType;
        ?>
        <div class="bg-gradient-to-br from-primary-500 to-primary-600 rounded-2xl p-5 shadow-lg shadow-primary-500/20">
            <p class="text-white/70 text-xs font-semibold uppercase tracking-wide mb-1">Total Nilai Aset</p>
            <p class="text-white text-lg sm:text-2xl font-bold break-all">Rp <?php echo e(number_format($totalVal, 0, ',', '.')); ?></p>
        </div>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = \App\Models\Asset::$types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($typeData[$key])): ?>
                <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm">
                    <p class="text-slate-400 text-xs font-semibold uppercase tracking-wide mb-1">
                        <?php echo e(\App\Models\Asset::$typeIcons[$key]); ?> <?php echo e($label); ?>

                    </p>
                    <p class="text-slate-800 text-xl font-bold">
                        Rp <?php echo e(number_format($typeData[$key], 0, ',', '.')); ?>

                    </p>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-4 mb-5">
        <div class="flex flex-col sm:flex-row gap-3">
            <div class="flex-1">
                <input wire:model.live.debounce.400ms="search" type="text" placeholder="🔍 Cari aset..."
                       class="w-full px-4 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary-300 outline-none">
            </div>
            <select wire:model.live="filterType"
                    class="px-4 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary-300 outline-none bg-white">
                <option value="">Semua Tipe</option>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = \App\Models\Asset::$types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($key); ?>"><?php echo e(\App\Models\Asset::$typeIcons[$key]); ?> <?php echo e($label); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </select>
        </div>
    </div>

    
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $assets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $asset): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5 hover:shadow-md transition-shadow">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-slate-50 rounded-xl flex items-center justify-center text-2xl">
                            <?php echo e($asset->type_icon); ?>

                        </div>
                        <div>
                            <h4 class="font-bold text-slate-800"><?php echo e($asset->name); ?></h4>
                            <span class="text-xs text-slate-500 bg-slate-100 px-2 py-0.5 rounded-lg">
                                <?php echo e($asset->type_name); ?>

                            </span>
                        </div>
                    </div>
                    <div class="flex items-center gap-1">
                        <button wire:click="editAsset(<?php echo e($asset->id); ?>)"
                                class="p-1.5 text-slate-400 hover:text-blue-500 hover:bg-blue-50 rounded-lg transition-colors">✏️</button>
                        <button wire:click="confirmDelete(<?php echo e($asset->id); ?>)"
                                class="p-1.5 text-slate-400 hover:text-rose-500 hover:bg-rose-50 rounded-lg transition-colors">🗑️</button>
                    </div>
                </div>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($asset->description): ?>
                    <p class="text-xs text-slate-400 mb-4 line-clamp-1"><?php echo e($asset->description); ?></p>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <div class="space-y-2.5 pt-3 border-t border-slate-50">
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-500">Harga Beli</span>
                        <span class="font-medium text-slate-700"><?php echo e($asset->formatted_purchase_price); ?></span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-500">Nilai Sekarang</span>
                        <span class="font-bold text-slate-800"><?php echo e($asset->formatted_current_value); ?></span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-500">Untung/Rugi</span>
                        <div class="flex items-center gap-1">
                            <span class="font-bold <?php echo e($asset->gain_loss >= 0 ? 'text-emerald-600' : 'text-rose-600'); ?>">
                                <?php echo e($asset->gain_loss >= 0 ? '▲' : '▼'); ?>

                                Rp <?php echo e(number_format(abs($asset->gain_loss), 0, ',', '.')); ?>

                            </span>
                            <span class="text-xs <?php echo e($asset->gain_loss >= 0 ? 'text-emerald-500 bg-emerald-50' : 'text-rose-500 bg-rose-50'); ?> px-1.5 py-0.5 rounded-lg">
                                <?php echo e(abs($asset->gain_loss_percentage)); ?>%
                            </span>
                        </div>
                    </div>
                    <div class="flex justify-between text-xs text-slate-400 pt-1">
                        <span>📅 Dibeli: <?php echo e($asset->purchase_date->translatedFormat('d M Y')); ?></span>
                    </div>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="col-span-3 bg-white rounded-2xl border border-slate-100 shadow-sm py-20 text-center">
                <span class="text-5xl block mb-4">🏦</span>
                <p class="text-slate-500 font-medium text-lg mb-2">Belum ada aset tercatat</p>
                <p class="text-sm text-slate-400 mb-5">Tambahkan aset Anda untuk melacak nilai kekayaan</p>
                <button wire:click="openForm"
                        class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-white bg-primary-500 rounded-xl hover:bg-primary-600">
                    + Tambah Aset Pertama
                </button>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($assets->hasPages()): ?>
        <div class="mt-5"><?php echo e($assets->links()); ?></div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showForm): ?>
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg max-h-screen overflow-y-auto">
                <div class="flex items-center justify-between p-6 border-b border-slate-100">
                    <h3 class="text-lg font-bold text-slate-800">
                        <?php echo e($editingId ? 'Edit Aset' : 'Tambah Aset Baru'); ?>

                    </h3>
                    <button wire:click="closeForm" class="p-2 hover:bg-slate-100 rounded-xl text-slate-400">✕</button>
                </div>

                <form wire:submit="saveAsset" class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Nama Aset</label>
                        <input wire:model="name" type="text" placeholder="Contoh: Yamaha NMAX 2022"
                               class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-300 outline-none <?php $__errorArgs = ['name'];
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

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Tipe Aset</label>
                        <select wire:model="type"
                                class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-300 outline-none bg-white <?php $__errorArgs = ['type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-rose-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = \App\Models\Asset::$types; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($key); ?>"><?php echo e(\App\Models\Asset::$typeIcons[$key]); ?> <?php echo e($label); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </select>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['type'];
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
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Harga Beli (Rp)</label>
                            <input type="hidden" wire:model="purchase_price" data-currency-model>
                            <input type="text" data-currency inputmode="numeric" placeholder="0" autocomplete="off"
                                   class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-300 outline-none <?php $__errorArgs = ['purchase_price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-rose-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['purchase_price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-xs text-rose-500 mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Nilai Sekarang (Rp)</label>
                            <input type="hidden" wire:model="current_value" data-currency-model>
                            <input type="text" data-currency inputmode="numeric" placeholder="0" autocomplete="off"
                                   class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-300 outline-none <?php $__errorArgs = ['current_value'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-rose-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['current_value'];
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
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Tanggal Beli</label>
                        <input wire:model="purchase_date" type="date"
                               class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-300 outline-none <?php $__errorArgs = ['purchase_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-rose-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['purchase_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-xs text-rose-500 mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Deskripsi (opsional)</label>
                        <textarea wire:model="description" rows="2" placeholder="Deskripsi aset..."
                                  class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-300 outline-none resize-none"></textarea>
                    </div>

                    <div class="flex gap-3 pt-2">
                        <button type="button" wire:click="closeForm"
                                class="flex-1 py-2.5 text-sm font-medium text-slate-700 bg-slate-100 rounded-xl hover:bg-slate-200">Batal</button>
                        <button type="submit"
                                class="flex-1 py-2.5 text-sm font-semibold text-white bg-primary-500 rounded-xl hover:bg-primary-600">
                            <?php echo e($editingId ? 'Simpan Perubahan' : 'Tambah Aset'); ?>

                        </button>
                    </div>
                </form>
            </div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showDeleteModal): ?>
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm p-6 text-center">
                <div class="w-14 h-14 bg-rose-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <span class="text-2xl">🗑️</span>
                </div>
                <h3 class="text-lg font-bold text-slate-800 mb-2">Hapus Aset?</h3>
                <p class="text-sm text-slate-500 mb-6">Data aset ini akan dihapus permanen.</p>
                <div class="flex gap-3">
                    <button wire:click="$set('showDeleteModal', false)"
                            class="flex-1 py-2.5 text-sm font-medium text-slate-700 bg-slate-100 rounded-xl hover:bg-slate-200">Batal</button>
                    <button wire:click="deleteAsset"
                            class="flex-1 py-2.5 text-sm font-semibold text-white bg-rose-500 rounded-xl hover:bg-rose-600">Hapus</button>
                </div>
            </div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <?php $__env->startPush('scripts'); ?>
    <script>
    document.addEventListener('livewire:update', () => setTimeout(bindCurrencyInputs, 30));
    </script>
    <?php $__env->stopPush(); ?>
</div>
<?php /**PATH /Users/izarhairulanam/Downloads/finance-tracker-responsive 7/resources/views/livewire/assets/index.blade.php ENDPATH**/ ?>