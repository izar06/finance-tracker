<div>

    
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Tujuan Keuangan</h2>
            <p class="text-sm text-slate-500">Pantau progres tabungan Anda</p>
        </div>
        <button wire:click="openForm"
                class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold text-white bg-primary-500 rounded-xl hover:bg-primary-600 transition-colors shadow-md shadow-primary-500/25">
            <span>+</span> Tambah Tujuan
        </button>
    </div>

    
    <div class="flex flex-wrap gap-2 mb-6">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = ['active' => '🎯 Aktif', 'completed' => '✅ Selesai', 'cancelled' => '❌ Dibatalkan', '' => '📋 Semua']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $val => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <button wire:click="$set('filterStatus', '<?php echo e($val); ?>')" wire:key="filter-<?php echo e($val ?: 'all'); ?>"
                    class="px-3 py-2 text-sm font-medium rounded-xl transition-colors whitespace-nowrap
                           <?php echo e($filterStatus === $val ? 'bg-primary-500 text-white shadow-md shadow-primary-500/25' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50'); ?>">
                <?php echo e($label); ?>

            </button>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $goals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $goal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-5 hover:shadow-md transition-shadow">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 bg-slate-50 rounded-xl flex items-center justify-center text-2xl">
                            <?php echo e($goal->icon); ?>

                        </div>
                        <div>
                            <h4 class="font-bold text-slate-800 leading-tight"><?php echo e($goal->name); ?></h4>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($goal->deadline): ?>
                                <p class="text-xs text-slate-400 mt-0.5">
                                    📅 <?php echo e($goal->deadline->translatedFormat('d M Y')); ?>

                                </p>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>

                    <div class="flex items-center gap-1">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($goal->status === 'active'): ?>
                            <button wire:click="openAddSaving(<?php echo e($goal->id); ?>)"
                                    class="p-1.5 text-emerald-500 hover:bg-emerald-50 rounded-lg transition-colors" title="Tambah Tabungan">
                                💰
                            </button>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <button wire:click="editGoal(<?php echo e($goal->id); ?>)"
                                class="p-1.5 text-slate-400 hover:text-blue-500 hover:bg-blue-50 rounded-lg transition-colors" title="Edit">
                            ✏️
                        </button>
                        <button wire:click="confirmDelete(<?php echo e($goal->id); ?>)"
                                class="p-1.5 text-slate-400 hover:text-rose-500 hover:bg-rose-50 rounded-lg transition-colors" title="Hapus">
                            🗑️
                        </button>
                    </div>
                </div>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($goal->description): ?>
                    <p class="text-xs text-slate-500 mb-4 line-clamp-2"><?php echo e($goal->description); ?></p>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                
                <div class="mb-3">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-semibold text-slate-700">
                            Rp <?php echo e(number_format($goal->current_amount, 0, ',', '.')); ?>

                        </span>
                        <span class="text-sm font-bold
                                     <?php echo e($goal->progress_percentage >= 100 ? 'text-emerald-600' : 'text-primary-600'); ?>">
                            <?php echo e($goal->progress_percentage); ?>%
                        </span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-2.5">
                        <div class="h-2.5 rounded-full transition-all duration-500
                                    <?php echo e($goal->progress_percentage >= 100 ? 'bg-emerald-500' : 'bg-gradient-to-r from-primary-400 to-primary-600'); ?>"
                             style="width: <?php echo e(min(100, $goal->progress_percentage)); ?>%"></div>
                    </div>
                </div>

                <div class="flex justify-between items-center pt-3 border-t border-slate-50">
                    <div>
                        <p class="text-xs text-slate-400">Target</p>
                        <p class="text-sm font-bold text-slate-700">Rp <?php echo e(number_format($goal->target_amount, 0, ',', '.')); ?></p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-slate-400">Sisa</p>
                        <p class="text-sm font-bold text-slate-700">
                            Rp <?php echo e(number_format($goal->remaining_amount, 0, ',', '.')); ?>

                        </p>
                    </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($goal->status === 'completed'): ?>
                        <span class="text-xs font-semibold text-emerald-700 bg-emerald-50 px-3 py-1.5 rounded-xl">✅ Selesai</span>
                    <?php elseif($goal->status === 'cancelled'): ?>
                        <span class="text-xs font-semibold text-slate-500 bg-slate-100 px-3 py-1.5 rounded-xl">❌ Dibatalkan</span>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div class="col-span-3 bg-white rounded-2xl border border-slate-100 shadow-sm py-20 text-center">
                <span class="text-5xl block mb-4">🎯</span>
                <p class="text-slate-500 font-medium text-lg mb-2">Belum ada tujuan keuangan</p>
                <p class="text-sm text-slate-400 mb-5">Mulai tetapkan tujuan untuk memotivasi tabungan Anda</p>
                <button wire:click="openForm"
                        class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-white bg-primary-500 rounded-xl hover:bg-primary-600">
                    + Buat Tujuan Pertama
                </button>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($goals->hasPages()): ?>
        <div class="mt-5"><?php echo e($goals->links()); ?></div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showForm): ?>
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg max-h-screen overflow-y-auto">
                <div class="flex items-center justify-between p-6 border-b border-slate-100">
                    <h3 class="text-lg font-bold text-slate-800">
                        <?php echo e($editingId ? 'Edit Tujuan' : 'Tujuan Keuangan Baru'); ?>

                    </h3>
                    <button wire:click="closeForm" class="p-2 hover:bg-slate-100 rounded-xl text-slate-400">✕</button>
                </div>

                <form wire:submit="saveGoal" class="p-6 space-y-4">
                    
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Ikon</label>
                        <div class="flex flex-wrap gap-2">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = \App\Models\FinancialGoal::$icons; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ic): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <button type="button" wire:click="$set('icon', '<?php echo e($ic); ?>')"
                                        class="w-10 h-10 text-xl rounded-xl border-2 transition-all
                                               <?php echo e($icon === $ic ? 'border-primary-500 bg-primary-50' : 'border-slate-200 hover:border-slate-300'); ?>">
                                    <?php echo e($ic); ?>

                                </button>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Nama Tujuan</label>
                        <input wire:model="name" type="text" placeholder="Contoh: DP Rumah"
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

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Target (Rp)</label>
                            <input type="hidden" wire:model="target_amount" data-currency-model>
                            <input type="text" data-currency inputmode="numeric" placeholder="0" autocomplete="off"
                                   class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-300 outline-none <?php $__errorArgs = ['target_amount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-rose-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['target_amount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-xs text-rose-500 mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Dana Awal (Rp)</label>
                            <input type="hidden" wire:model="current_amount" data-currency-model>
                            <input type="text" data-currency inputmode="numeric" placeholder="0" autocomplete="off"
                                   class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-300 outline-none">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Tenggat Waktu (opsional)</label>
                        <input wire:model="deadline" type="date"
                               class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-300 outline-none <?php $__errorArgs = ['deadline'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-rose-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['deadline'];
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
                        <textarea wire:model="description" rows="2" placeholder="Deskripsi tujuan..."
                                  class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-300 outline-none resize-none"></textarea>
                    </div>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($editingId): ?>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Status</label>
                            <select wire:model="status"
                                    class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-300 outline-none bg-white">
                                <option value="active">Aktif</option>
                                <option value="completed">Selesai</option>
                                <option value="cancelled">Dibatalkan</option>
                            </select>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <div class="flex gap-3 pt-2">
                        <button type="button" wire:click="closeForm"
                                class="flex-1 py-2.5 text-sm font-medium text-slate-700 bg-slate-100 rounded-xl hover:bg-slate-200">Batal</button>
                        <button type="submit"
                                class="flex-1 py-2.5 text-sm font-semibold text-white bg-primary-500 rounded-xl hover:bg-primary-600">
                            <?php echo e($editingId ? 'Simpan Perubahan' : 'Buat Tujuan'); ?>

                        </button>
                    </div>
                </form>
            </div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showAddSavingModal): ?>
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm p-6">
                <div class="flex items-center justify-between mb-5">
                    <h3 class="text-lg font-bold text-slate-800">Tambah Tabungan</h3>
                    <button wire:click="$set('showAddSavingModal', false)" class="p-2 hover:bg-slate-100 rounded-xl text-slate-400">✕</button>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Jumlah Tabungan (Rp)</label>
                    <input type="hidden" wire:model="savingAmount" data-currency-model>
                    <input type="text" data-currency inputmode="numeric" placeholder="Masukkan jumlah..." autocomplete="off"
                           class="w-full px-4 py-3 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-300 outline-none text-lg font-bold <?php $__errorArgs = ['savingAmount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-rose-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['savingAmount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-xs text-rose-500 mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <div class="flex gap-3">
                    <button wire:click="$set('showAddSavingModal', false)"
                            class="flex-1 py-2.5 text-sm font-medium text-slate-700 bg-slate-100 rounded-xl hover:bg-slate-200">Batal</button>
                    <button wire:click="addSaving"
                            class="flex-1 py-2.5 text-sm font-semibold text-white bg-emerald-500 rounded-xl hover:bg-emerald-600">
                        💰 Tambahkan
                    </button>
                </div>
            </div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showDeleteModal): ?>
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50">
            <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm p-6 text-center">
                <div class="w-14 h-14 bg-rose-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <span class="text-2xl">🗑️</span>
                </div>
                <h3 class="text-lg font-bold text-slate-800 mb-2">Hapus Tujuan?</h3>
                <p class="text-sm text-slate-500 mb-6">Data tujuan keuangan ini akan dihapus permanen.</p>
                <div class="flex gap-3">
                    <button wire:click="$set('showDeleteModal', false)"
                            class="flex-1 py-2.5 text-sm font-medium text-slate-700 bg-slate-100 rounded-xl hover:bg-slate-200">Batal</button>
                    <button wire:click="deleteGoal"
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
<?php /**PATH /Users/izarhairulanam/Downloads/finance-tracker-responsive 7/resources/views/livewire/goals/index.blade.php ENDPATH**/ ?>