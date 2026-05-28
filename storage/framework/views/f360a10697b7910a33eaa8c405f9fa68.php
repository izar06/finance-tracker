<div>

    
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Kategori</h2>
            <p class="text-sm text-slate-500">Kelola kategori pemasukan dan pengeluaran</p>
        </div>
        <button wire:click="openForm"
                class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold text-white bg-primary-500 rounded-xl hover:bg-primary-600 transition-colors shadow-md shadow-primary-500/25 whitespace-nowrap self-start sm:self-auto">
            + Tambah Kategori
        </button>
    </div>

    
    <div class="flex gap-2 mb-5">
        <button wire:click="setTab('expense')"
                class="px-4 py-2 text-sm font-medium rounded-xl transition-colors whitespace-nowrap
                       <?php echo e($activeTab === 'expense' ? 'bg-primary-500 text-white shadow-md shadow-primary-500/25' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50'); ?>">
            📤 Pengeluaran (<?php echo e($this->expenseCategories->count()); ?>)
        </button>
        <button wire:click="setTab('income')"
                class="px-4 py-2 text-sm font-medium rounded-xl transition-colors whitespace-nowrap
                       <?php echo e($activeTab === 'income' ? 'bg-primary-500 text-white shadow-md shadow-primary-500/25' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50'); ?>">
            📥 Pemasukan (<?php echo e($this->incomeCategories->count()); ?>)
        </button>
    </div>

    
    <?php
        $cats = $activeTab === 'expense' ? $this->expenseCategories : $this->incomeCategories;
    ?>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($cats->count() > 0): ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 mb-4">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $cats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-4 flex items-center gap-4">
                    
                    <div class="w-11 h-11 rounded-xl flex items-center justify-center text-xl flex-shrink-0
                                <?php echo e($activeTab === 'expense' ? 'bg-rose-50' : 'bg-emerald-50'); ?>">
                        <?php echo e($cat->icon); ?>

                    </div>

                    
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-slate-800 text-sm truncate"><?php echo e($cat->name); ?></p>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($cat->is_default): ?>
                            <span class="text-xs text-slate-400">Kategori bawaan</span>
                        <?php else: ?>
                            <span class="text-xs text-primary-500">Kategori kustom</span>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    
                    <div class="flex items-center gap-1 flex-shrink-0">
                        <button wire:click="editCategory(<?php echo e($cat->id); ?>)"
                                class="p-1.5 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
                                title="Edit">✏️</button>
                        <button wire:click="confirmDelete(<?php echo e($cat->id); ?>)"
                                class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors"
                                title="Hapus">🗑️</button>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            
            <button wire:click="openForm('<?php echo e($activeTab); ?>')"
                    class="bg-slate-50 border-2 border-dashed border-slate-200 rounded-2xl p-4 flex items-center justify-center gap-2 text-slate-400 hover:border-primary-300 hover:text-primary-500 hover:bg-primary-50 transition-colors min-h-[72px]">
                <span class="text-lg">+</span>
                <span class="text-sm font-medium">Tambah Kategori</span>
            </button>
        </div>
    <?php else: ?>
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm py-16 text-center mb-4">
            <span class="text-5xl block mb-4">🏷️</span>
            <p class="font-semibold text-slate-700 mb-1">Belum ada kategori</p>
            <p class="text-sm text-slate-400 mb-5">Tambah kategori untuk mulai mencatat transaksi</p>
            <button wire:click="openForm('<?php echo e($activeTab); ?>')"
                    class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-white bg-primary-500 rounded-xl hover:bg-primary-600 transition-colors">
                + Tambah Pertama
            </button>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    <div class="bg-blue-50 border border-blue-100 rounded-2xl p-4 flex items-start gap-3">
        <span class="text-lg flex-shrink-0 mt-0.5">ℹ️</span>
        <div class="text-sm text-blue-700">
            <p class="font-medium mb-0.5">Tentang Kategori Bawaan</p>
            <p class="text-blue-600 text-xs">Kategori bawaan bisa diedit nama dan ikonnya, tapi pastikan tidak mengubah kategori yang sudah dipakai di banyak transaksi karena data lama tidak ikut berubah.</p>
        </div>
    </div>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showForm): ?>
        <div class="fixed inset-0 z-50 flex items-end sm:items-center justify-center p-0 sm:p-4"
             x-data x-on:keydown.escape.window="$wire.closeForm()">
            <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" wire:click="closeForm"></div>
            <div class="relative bg-white w-full sm:max-w-sm sm:rounded-2xl rounded-t-2xl shadow-xl p-6 z-10">

                <div class="flex items-center justify-between mb-5">
                    <h3 class="font-bold text-slate-800 text-lg">
                        <?php echo e($editingId ? 'Edit Kategori' : 'Tambah Kategori'); ?>

                    </h3>
                    <button wire:click="closeForm" class="text-slate-400 hover:text-slate-600 text-xl leading-none">✕</button>
                </div>

                <div class="space-y-4">

                    
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$editingId): ?>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Tipe Kategori</label>
                            <div class="grid grid-cols-2 gap-2">
                                <button type="button" wire:click="$set('type', 'expense')"
                                        class="py-2.5 text-sm font-medium rounded-xl border transition-colors
                                               <?php echo e($type === 'expense' ? 'bg-rose-50 border-rose-300 text-rose-700' : 'bg-white border-slate-200 text-slate-600 hover:bg-slate-50'); ?>">
                                    📤 Pengeluaran
                                </button>
                                <button type="button" wire:click="$set('type', 'income')"
                                        class="py-2.5 text-sm font-medium rounded-xl border transition-colors
                                               <?php echo e($type === 'income' ? 'bg-emerald-50 border-emerald-300 text-emerald-700' : 'bg-white border-slate-200 text-slate-600 hover:bg-slate-50'); ?>">
                                    📥 Pemasukan
                                </button>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="bg-slate-50 rounded-xl px-4 py-2.5 text-sm text-slate-600">
                            Tipe: <span class="font-medium"><?php echo e($type === 'expense' ? '📤 Pengeluaran' : '📥 Pemasukan'); ?></span>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Ikon</label>
                        <div class="flex items-center gap-3">
                            <button type="button" wire:click="$toggle('showIconPicker')"
                                    class="w-12 h-12 rounded-xl border-2 border-slate-200 flex items-center justify-center text-2xl hover:border-primary-300 transition-colors flex-shrink-0">
                                <?php echo e($icon); ?>

                            </button>
                            <p class="text-xs text-slate-400">Klik untuk ganti ikon</p>
                        </div>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showIconPicker): ?>
                            <div class="mt-3 p-3 border border-slate-200 rounded-xl bg-slate-50 max-h-40 overflow-y-auto">
                                <div class="grid grid-cols-8 gap-1">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $iconOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ico): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <button type="button" wire:click="selectIcon('<?php echo e($ico); ?>')"
                                                class="w-9 h-9 rounded-lg flex items-center justify-center text-xl hover:bg-white hover:shadow-sm transition-colors
                                                       <?php echo e($icon === $ico ? 'bg-primary-100 ring-2 ring-primary-400' : ''); ?>">
                                            <?php echo e($ico); ?>

                                        </button>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Nama Kategori</label>
                        <input wire:model="name" type="text"
                               placeholder="Contoh: Olahraga, Pet Care, dll."
                               maxlength="50"
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

                <div class="flex gap-3 mt-6">
                    <button wire:click="closeForm"
                            class="flex-1 px-4 py-2.5 text-sm font-medium text-slate-600 bg-slate-100 rounded-xl hover:bg-slate-200 transition-colors">
                        Batal
                    </button>
                    <button wire:click="saveCategory" wire:loading.attr="disabled"
                            class="flex-1 px-4 py-2.5 text-sm font-semibold text-white bg-primary-500 rounded-xl hover:bg-primary-600 transition-colors disabled:opacity-60">
                        <span wire:loading.remove wire:target="saveCategory"><?php echo e($editingId ? 'Simpan' : 'Tambah'); ?></span>
                        <span wire:loading wire:target="saveCategory">Menyimpan...</span>
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
                    <h3 class="font-bold text-slate-800 text-lg mb-1">Hapus Kategori?</h3>
                    <p class="text-sm text-slate-500">Kategori yang masih dipakai di transaksi tidak dapat dihapus.</p>
                </div>
                <div class="flex gap-3">
                    <button wire:click="$set('showDeleteModal', false)"
                            class="flex-1 px-4 py-2.5 text-sm font-medium text-slate-600 bg-slate-100 rounded-xl hover:bg-slate-200 transition-colors">
                        Batal
                    </button>
                    <button wire:click="deleteCategory" wire:loading.attr="disabled"
                            class="flex-1 px-4 py-2.5 text-sm font-semibold text-white bg-rose-500 rounded-xl hover:bg-rose-600 transition-colors">
                        <span wire:loading.remove wire:target="deleteCategory">Ya, Hapus</span>
                        <span wire:loading wire:target="deleteCategory">Menghapus...</span>
                    </button>
                </div>
            </div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

</div>
<?php /**PATH /Users/izarhairulanam/Downloads/finance-tracker-responsive 7/resources/views/livewire/categories/index.blade.php ENDPATH**/ ?>