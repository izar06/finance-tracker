<div>

    
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="text-2xl">🛡️</span>
                <h2 class="text-xl font-bold text-slate-800">Manajemen User</h2>
            </div>
            <p class="text-sm text-slate-500">Kelola semua akun pengguna aplikasi</p>
        </div>
        <div class="flex items-center gap-2">
            <span class="text-xs font-semibold text-amber-700 bg-amber-100 px-3 py-1.5 rounded-full border border-amber-200">
                🔐 Mode Superadmin
            </span>
        </div>
    </div>

    
    <?php $stats = $this->stats; ?>
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-6">
        <div class="bg-white rounded-2xl p-4 border border-slate-100 shadow-sm">
            <p class="text-xs text-slate-500 mb-1">Total User</p>
            <p class="text-2xl font-bold text-slate-800"><?php echo e($stats['total']); ?></p>
            <p class="text-xs text-slate-400 mt-0.5">terdaftar</p>
        </div>
        <div class="bg-white rounded-2xl p-4 border border-slate-100 shadow-sm">
            <p class="text-xs text-slate-500 mb-1">Aktif</p>
            <p class="text-2xl font-bold text-emerald-600"><?php echo e($stats['active']); ?></p>
            <p class="text-xs text-slate-400 mt-0.5">user aktif</p>
        </div>
        <div class="bg-white rounded-2xl p-4 border border-slate-100 shadow-sm">
            <p class="text-xs text-slate-500 mb-1">Disuspend</p>
            <p class="text-2xl font-bold text-rose-600"><?php echo e($stats['suspended']); ?></p>
            <p class="text-xs text-slate-400 mt-0.5">user suspend</p>
        </div>
        <div class="bg-white rounded-2xl p-4 border border-amber-100 shadow-sm bg-amber-50">
            <p class="text-xs text-amber-600 mb-1">Superadmin</p>
            <p class="text-2xl font-bold text-amber-700"><?php echo e($stats['superadmin']); ?></p>
            <p class="text-xs text-amber-500 mt-0.5">akun admin</p>
        </div>
    </div>

    
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-4 mb-4">
        <div class="flex flex-col sm:flex-row gap-3">
            <div class="relative flex-1">
                <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm">🔍</span>
                <input wire:model.live.debounce.300ms="search"
                       type="text" placeholder="Cari nama atau email..."
                       class="w-full pl-9 pr-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-300 focus:border-primary-400 outline-none">
            </div>
            <select wire:model.live="filterRole"
                    class="px-4 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary-300 outline-none bg-white">
                <option value="">Semua Role</option>
                <option value="user">User</option>
                <option value="superadmin">Superadmin</option>
            </select>
            <select wire:model.live="filterStatus"
                    class="px-4 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary-300 outline-none bg-white">
                <option value="">Semua Status</option>
                <option value="active">Aktif</option>
                <option value="suspended">Disuspend</option>
            </select>
        </div>
    </div>

    
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden mb-4">

        
        <div class="md:hidden divide-y divide-slate-100">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $this->users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="p-4">
                    <div class="flex items-start justify-between gap-3 mb-3">
                        <div class="flex items-center gap-3 min-w-0">
                            
                            <div class="w-10 h-10 rounded-xl flex items-center justify-center text-sm font-bold flex-shrink-0
                                        <?php echo e($user->role === 'superadmin' ? 'bg-amber-100 text-amber-700' : 'bg-primary-100 text-primary-700'); ?>">
                                <?php echo e(strtoupper(substr($user->name, 0, 2))); ?>

                            </div>
                            <div class="min-w-0">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <p class="font-semibold text-slate-800 text-sm truncate"><?php echo e($user->name); ?></p>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($user->id === auth()->id()): ?>
                                        <span class="text-xs bg-blue-100 text-blue-600 px-1.5 py-0.5 rounded-full">Anda</span>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                                <p class="text-xs text-slate-400 truncate"><?php echo e($user->email); ?></p>
                            </div>
                        </div>
                        <div class="flex items-center gap-1 flex-shrink-0">
                            <button wire:click="showDetail(<?php echo e($user->id); ?>)"
                                    class="p-1.5 text-slate-400 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-colors">👁️</button>
                            <button wire:click="editUser(<?php echo e($user->id); ?>)"
                                    class="p-1.5 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">✏️</button>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($user->id !== auth()->id()): ?>
                                <button wire:click="confirmDelete(<?php echo e($user->id); ?>)"
                                        class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors">🗑️</button>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>
                    <div class="flex flex-wrap items-center gap-2">
                        
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($user->role === 'superadmin'): ?>
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 text-xs font-semibold bg-amber-100 text-amber-700 rounded-full">🛡️ Superadmin</span>
                        <?php else: ?>
                            <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium bg-slate-100 text-slate-600 rounded-full">👤 User</span>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($user->status === 'active'): ?>
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 text-xs font-semibold bg-emerald-100 text-emerald-700 rounded-full">● Aktif</span>
                        <?php else: ?>
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 text-xs font-semibold bg-rose-100 text-rose-700 rounded-full">● Suspend</span>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <span class="text-xs text-slate-400"><?php echo e($user->transactions_count); ?> transaksi</span>
                        <span class="text-xs text-slate-400">Bergabung <?php echo e($user->created_at->diffForHumans()); ?></span>
                    </div>
                    
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($user->id !== auth()->id()): ?>
                        <div class="flex gap-2 mt-3">
                            <button wire:click="toggleStatus(<?php echo e($user->id); ?>)"
                                    class="flex-1 py-1.5 text-xs font-medium rounded-lg border transition-colors
                                           <?php echo e($user->status === 'active' ? 'border-rose-200 text-rose-600 hover:bg-rose-50' : 'border-emerald-200 text-emerald-600 hover:bg-emerald-50'); ?>">
                                <?php echo e($user->status === 'active' ? '⏸ Suspend' : '▶ Aktifkan'); ?>

                            </button>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($user->role === 'user'): ?>
                                <button wire:click="promoteToSuperAdmin(<?php echo e($user->id); ?>)"
                                        class="flex-1 py-1.5 text-xs font-medium rounded-lg border border-amber-200 text-amber-600 hover:bg-amber-50 transition-colors">
                                    ⬆ Jadikan Admin
                                </button>
                            <?php else: ?>
                                <button wire:click="demoteToUser(<?php echo e($user->id); ?>)"
                                        class="flex-1 py-1.5 text-xs font-medium rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50 transition-colors">
                                    ⬇ Jadikan User
                                </button>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="py-16 text-center">
                    <span class="text-4xl block mb-3">👥</span>
                    <p class="text-slate-500 font-medium">Tidak ada user ditemukan</p>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="text-left px-5 py-3.5 font-semibold text-slate-600">User</th>
                        <th class="text-left px-5 py-3.5 font-semibold text-slate-600">Role</th>
                        <th class="text-left px-5 py-3.5 font-semibold text-slate-600">Status</th>
                        <th class="text-left px-5 py-3.5 font-semibold text-slate-600">Transaksi</th>
                        <th class="text-left px-5 py-3.5 font-semibold text-slate-600">Login Terakhir</th>
                        <th class="text-left px-5 py-3.5 font-semibold text-slate-600">Bergabung</th>
                        <th class="text-center px-5 py-3.5 font-semibold text-slate-600">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $this->users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="hover:bg-slate-50 transition-colors">
                            
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl flex items-center justify-center text-xs font-bold flex-shrink-0
                                                <?php echo e($user->role === 'superadmin' ? 'bg-amber-100 text-amber-700' : 'bg-primary-100 text-primary-700'); ?>">
                                        <?php echo e(strtoupper(substr($user->name, 0, 2))); ?>

                                    </div>
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <p class="font-medium text-slate-800"><?php echo e($user->name); ?></p>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($user->id === auth()->id()): ?>
                                                <span class="text-xs bg-blue-100 text-blue-600 px-1.5 py-0.5 rounded-full">Anda</span>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </div>
                                        <p class="text-xs text-slate-400"><?php echo e($user->email); ?></p>
                                    </div>
                                </div>
                            </td>

                            
                            <td class="px-5 py-3.5">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($user->role === 'superadmin'): ?>
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold bg-amber-100 text-amber-700 rounded-lg">🛡️ Superadmin</span>
                                <?php else: ?>
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-medium bg-slate-100 text-slate-600 rounded-lg">👤 User</span>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </td>

                            
                            <td class="px-5 py-3.5">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($user->status === 'active'): ?>
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold bg-emerald-100 text-emerald-700 rounded-lg">● Aktif</span>
                                <?php else: ?>
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold bg-rose-100 text-rose-700 rounded-lg">● Suspended</span>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </td>

                            
                            <td class="px-5 py-3.5 text-slate-600"><?php echo e(number_format($user->transactions_count)); ?></td>

                            
                            <td class="px-5 py-3.5 text-slate-500 text-xs">
                                <?php echo e($user->last_login_at ? $user->last_login_at->diffForHumans() : '—'); ?>

                            </td>

                            
                            <td class="px-5 py-3.5 text-slate-500 text-xs whitespace-nowrap">
                                <?php echo e($user->created_at->translatedFormat('d M Y')); ?>

                            </td>

                            
                            <td class="px-5 py-3.5">
                                <div class="flex items-center justify-center gap-1">
                                    <button wire:click="showDetail(<?php echo e($user->id); ?>)"
                                            title="Detail"
                                            class="p-1.5 text-slate-400 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-colors">👁️</button>
                                    <button wire:click="editUser(<?php echo e($user->id); ?>)"
                                            title="Edit"
                                            class="p-1.5 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">✏️</button>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($user->id !== auth()->id()): ?>
                                        <button wire:click="toggleStatus(<?php echo e($user->id); ?>)"
                                                title="<?php echo e($user->status === 'active' ? 'Suspend' : 'Aktifkan'); ?>"
                                                class="p-1.5 rounded-lg transition-colors
                                                       <?php echo e($user->status === 'active' ? 'text-slate-400 hover:text-amber-600 hover:bg-amber-50' : 'text-slate-400 hover:text-emerald-600 hover:bg-emerald-50'); ?>">
                                            <?php echo e($user->status === 'active' ? '⏸' : '▶'); ?>

                                        </button>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($user->role === 'user'): ?>
                                            <button wire:click="promoteToSuperAdmin(<?php echo e($user->id); ?>)"
                                                    title="Jadikan Superadmin"
                                                    class="p-1.5 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-colors">⬆</button>
                                        <?php else: ?>
                                            <button wire:click="demoteToUser(<?php echo e($user->id); ?>)"
                                                    title="Jadikan User Biasa"
                                                    class="p-1.5 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-lg transition-colors">⬇</button>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        <button wire:click="confirmDelete(<?php echo e($user->id); ?>)"
                                                title="Hapus"
                                                class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors">🗑️</button>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="7" class="py-16 text-center">
                                <span class="text-4xl block mb-3">👥</span>
                                <p class="text-slate-500 font-medium">Tidak ada user ditemukan</p>
                            </td>
                        </tr>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </tbody>
            </table>
        </div>

        
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($this->users->hasPages()): ?>
            <div class="px-5 py-4 border-t border-slate-100">
                <?php echo e($this->users->links()); ?>

            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showDetailModal && $detailUser): ?>
        <div class="fixed inset-0 z-50 flex items-end sm:items-center justify-center p-0 sm:p-4"
             x-data x-on:keydown.escape.window="$wire.closeModals()">
            <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" wire:click="closeModals"></div>
            <div class="relative bg-white w-full sm:max-w-md sm:rounded-2xl rounded-t-2xl shadow-xl p-6 z-10 max-h-[90vh] overflow-y-auto">

                <div class="flex items-center justify-between mb-5">
                    <h3 class="font-bold text-slate-800 text-lg">Detail User</h3>
                    <button wire:click="closeModals" class="text-slate-400 hover:text-slate-600 text-xl">✕</button>
                </div>

                
                <div class="flex items-center gap-4 mb-6 p-4 bg-slate-50 rounded-2xl">
                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-xl font-bold flex-shrink-0
                                <?php echo e($detailUser->role === 'superadmin' ? 'bg-amber-100 text-amber-700' : 'bg-primary-100 text-primary-700'); ?>">
                        <?php echo e(strtoupper(substr($detailUser->name, 0, 2))); ?>

                    </div>
                    <div>
                        <p class="font-bold text-slate-800 text-base"><?php echo e($detailUser->name); ?></p>
                        <p class="text-sm text-slate-500"><?php echo e($detailUser->email); ?></p>
                        <div class="flex gap-2 mt-1.5">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($detailUser->role === 'superadmin'): ?>
                                <span class="text-xs font-semibold bg-amber-100 text-amber-700 px-2 py-0.5 rounded-full">🛡️ Superadmin</span>
                            <?php else: ?>
                                <span class="text-xs font-medium bg-slate-100 text-slate-600 px-2 py-0.5 rounded-full">👤 User</span>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($detailUser->status === 'active'): ?>
                                <span class="text-xs font-semibold bg-emerald-100 text-emerald-700 px-2 py-0.5 rounded-full">● Aktif</span>
                            <?php else: ?>
                                <span class="text-xs font-semibold bg-rose-100 text-rose-700 px-2 py-0.5 rounded-full">● Suspended</span>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>
                </div>

                
                <div class="grid grid-cols-2 gap-3 mb-5">
                    <?php
                        $txCount   = \App\Models\Transaction::withoutGlobalScopes()->where('user_id', $detailUser->id)->count();
                        $txIncome  = (float) \App\Models\Transaction::withoutGlobalScopes()->where('user_id', $detailUser->id)->where('type','income')->sum('amount');
                        $txExpense = (float) \App\Models\Transaction::withoutGlobalScopes()->where('user_id', $detailUser->id)->where('type','expense')->sum('amount');
                        $assetVal  = (float) \App\Models\Asset::withoutGlobalScopes()->where('user_id', $detailUser->id)->sum('current_value');
                        $goalCount = \App\Models\FinancialGoal::withoutGlobalScopes()->where('user_id', $detailUser->id)->count();
                    ?>
                    <div class="bg-emerald-50 rounded-xl p-3">
                        <p class="text-xs text-emerald-600 mb-0.5">Total Pemasukan</p>
                        <p class="font-bold text-emerald-700 text-sm">Rp <?php echo e(number_format($txIncome, 0, ',', '.')); ?></p>
                    </div>
                    <div class="bg-rose-50 rounded-xl p-3">
                        <p class="text-xs text-rose-600 mb-0.5">Total Pengeluaran</p>
                        <p class="font-bold text-rose-700 text-sm">Rp <?php echo e(number_format($txExpense, 0, ',', '.')); ?></p>
                    </div>
                    <div class="bg-blue-50 rounded-xl p-3">
                        <p class="text-xs text-blue-600 mb-0.5">Total Aset</p>
                        <p class="font-bold text-blue-700 text-sm">Rp <?php echo e(number_format($assetVal, 0, ',', '.')); ?></p>
                    </div>
                    <div class="bg-slate-50 rounded-xl p-3">
                        <p class="text-xs text-slate-500 mb-0.5">Transaksi / Tujuan</p>
                        <p class="font-bold text-slate-700 text-sm"><?php echo e($txCount); ?> / <?php echo e($goalCount); ?></p>
                    </div>
                </div>

                
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-slate-500">Bergabung</span>
                        <span class="font-medium text-slate-700"><?php echo e($detailUser->created_at->translatedFormat('d F Y')); ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-500">Login Terakhir</span>
                        <span class="font-medium text-slate-700"><?php echo e($detailUser->last_login_at ? $detailUser->last_login_at->translatedFormat('d F Y, H:i') : '—'); ?></span>
                    </div>
                </div>

                <button wire:click="closeModals"
                        class="w-full mt-5 py-2.5 text-sm font-medium text-slate-600 bg-slate-100 rounded-xl hover:bg-slate-200 transition-colors">
                    Tutup
                </button>
            </div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showEditModal): ?>
        <div class="fixed inset-0 z-50 flex items-end sm:items-center justify-center p-0 sm:p-4"
             x-data x-on:keydown.escape.window="$wire.closeModals()">
            <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" wire:click="closeModals"></div>
            <div class="relative bg-white w-full sm:max-w-md sm:rounded-2xl rounded-t-2xl shadow-xl p-6 z-10 max-h-[90vh] overflow-y-auto">

                <div class="flex items-center justify-between mb-5">
                    <h3 class="font-bold text-slate-800 text-lg">Edit User</h3>
                    <button wire:click="closeModals" class="text-slate-400 hover:text-slate-600 text-xl">✕</button>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Nama</label>
                        <input wire:model="editName" type="text"
                               class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-300 focus:border-primary-400 outline-none <?php $__errorArgs = ['editName'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-rose-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['editName'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-xs text-rose-500 mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">Email</label>
                        <input wire:model="editEmail" type="email"
                               class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-300 focus:border-primary-400 outline-none <?php $__errorArgs = ['editEmail'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-rose-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['editEmail'];
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
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Role</label>
                            <select wire:model="editRole"
                                    class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-300 outline-none bg-white <?php $__errorArgs = ['editRole'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-rose-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                <option value="user">👤 User</option>
                                <option value="superadmin">🛡️ Superadmin</option>
                            </select>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['editRole'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <p class="text-xs text-rose-500 mt-1"><?php echo e($message); ?></p> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1.5">Status</label>
                            <select wire:model="editStatus"
                                    class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-300 outline-none bg-white <?php $__errorArgs = ['editStatus'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-rose-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                <option value="active">● Aktif</option>
                                <option value="suspended">● Suspend</option>
                            </select>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['editStatus'];
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
                        <label class="block text-sm font-medium text-slate-700 mb-1.5">
                            Password Baru
                            <span class="text-slate-400 font-normal">(kosongkan jika tidak diubah)</span>
                        </label>
                        <input wire:model="editPassword" type="password"
                               placeholder="Min. 8 karakter"
                               class="w-full px-4 py-2.5 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-primary-300 focus:border-primary-400 outline-none <?php $__errorArgs = ['editPassword'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-rose-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['editPassword'];
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
                    <button wire:click="closeModals"
                            class="flex-1 px-4 py-2.5 text-sm font-medium text-slate-600 bg-slate-100 rounded-xl hover:bg-slate-200 transition-colors">
                        Batal
                    </button>
                    <button wire:click="saveUser" wire:loading.attr="disabled"
                            class="flex-1 px-4 py-2.5 text-sm font-semibold text-white bg-primary-500 rounded-xl hover:bg-primary-600 transition-colors disabled:opacity-60">
                        <span wire:loading.remove wire:target="saveUser">Simpan Perubahan</span>
                        <span wire:loading wire:target="saveUser">Menyimpan...</span>
                    </button>
                </div>
            </div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($showDeleteModal): ?>
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
            <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" wire:click="closeModals"></div>
            <div class="relative bg-white rounded-2xl shadow-xl p-6 w-full max-w-sm z-10">
                <div class="text-center mb-5">
                    <span class="text-4xl block mb-3">⚠️</span>
                    <h3 class="font-bold text-slate-800 text-lg mb-1">Hapus User?</h3>
                    <p class="text-sm text-slate-500">Semua data user (transaksi, aset, kategori) akan dihapus permanen. Tindakan ini tidak bisa dibatalkan.</p>
                </div>
                <div class="flex gap-3">
                    <button wire:click="closeModals"
                            class="flex-1 px-4 py-2.5 text-sm font-medium text-slate-600 bg-slate-100 rounded-xl hover:bg-slate-200 transition-colors">
                        Batal
                    </button>
                    <button wire:click="deleteUser" wire:loading.attr="disabled"
                            class="flex-1 px-4 py-2.5 text-sm font-semibold text-white bg-rose-500 rounded-xl hover:bg-rose-600 transition-colors">
                        <span wire:loading.remove wire:target="deleteUser">Ya, Hapus</span>
                        <span wire:loading wire:target="deleteUser">Menghapus...</span>
                    </button>
                </div>
            </div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

</div>
<?php /**PATH /Users/izarhairulanam/Downloads/finance-tracker-responsive 7/resources/views/livewire/superadmin/index.blade.php ENDPATH**/ ?>