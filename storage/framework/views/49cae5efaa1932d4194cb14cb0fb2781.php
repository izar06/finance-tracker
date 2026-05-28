<div class="relative" x-data="{ open: <?php if ((object) ('open') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('open'->value()); ?>')<?php echo e('open'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('open'); ?>')<?php endif; ?> }" @click.outside="$wire.close()">

    
    <button @click="$wire.toggle()"
            class="relative p-2 rounded-xl border border-slate-200 text-slate-500 hover:bg-slate-100 hover:text-slate-700 transition-colors"
            title="Reminder Tagihan">
        
        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/>
        </svg>

        
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($count > 0): ?>
            <span class="absolute -top-1 -right-1 w-4 h-4 flex items-center justify-center text-[10px] font-bold text-white rounded-full
                         <?php echo e($reminders->contains(fn($b) => $b->urgency === 'overdue') ? 'bg-rose-500' :
                            ($reminders->contains(fn($b) => $b->urgency === 'urgent') ? 'bg-amber-500' : 'bg-primary-500')); ?>">
                <?php echo e($count > 9 ? '9+' : $count); ?>

            </span>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </button>

    
    <div x-show="open" x-cloak
         x-transition:enter="transition ease-out duration-150"
         x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
         x-transition:leave="transition ease-in duration-100"
         x-transition:leave-start="opacity-100 scale-100 translate-y-0"
         x-transition:leave-end="opacity-0 scale-95 -translate-y-1"
         class="absolute right-0 top-[calc(100%+10px)] w-80 bg-white border border-slate-200 rounded-2xl shadow-2xl z-50 overflow-hidden">

        
        <div class="flex items-center justify-between px-4 py-3 border-b border-slate-100 bg-slate-50/60">
            <div class="flex items-center gap-2">
                <span class="text-base">🔔</span>
                <p class="text-sm font-semibold text-slate-700">Reminder Tagihan</p>
            </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($count > 0): ?>
                <span class="text-xs font-medium px-2 py-0.5 rounded-full
                             <?php echo e($reminders->contains(fn($b) => $b->urgency === 'overdue') ? 'bg-rose-100 text-rose-700' :
                                ($reminders->contains(fn($b) => $b->urgency === 'urgent') ? 'bg-amber-100 text-amber-700' : 'bg-primary-100 text-primary-700')); ?>">
                    <?php echo e($count); ?> tagihan
                </span>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        
        <div class="max-h-80 overflow-y-auto divide-y divide-slate-50">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $reminders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bill): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php
                    $color = match($bill->urgency) {
                        'overdue' => ['dot' => 'bg-rose-500',  'badge' => 'bg-rose-100 text-rose-700',   'label' => 'Lewat jatuh tempo'],
                        'urgent'  => ['dot' => 'bg-amber-500', 'badge' => 'bg-amber-100 text-amber-700', 'label' => 'Mendesak'],
                        'soon'    => ['dot' => 'bg-blue-500',  'badge' => 'bg-blue-100 text-blue-700',   'label' => 'Segera'],
                        default   => ['dot' => 'bg-slate-400', 'badge' => 'bg-slate-100 text-slate-600', 'label' => 'Normal'],
                    };
                ?>
                <div class="flex items-center gap-3 px-4 py-3 hover:bg-slate-50 transition-colors">
                    
                    <div class="w-9 h-9 rounded-xl flex items-center justify-center text-base flex-shrink-0
                                <?php echo e($bill->urgency === 'overdue' ? 'bg-rose-50' : ($bill->urgency === 'urgent' ? 'bg-amber-50' : 'bg-blue-50')); ?>">
                        <?php echo e($bill->icon); ?>

                    </div>

                    
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-slate-800 truncate"><?php echo e($bill->name); ?></p>
                        <div class="flex items-center gap-1.5 mt-0.5">
                            <span class="w-1.5 h-1.5 rounded-full flex-shrink-0 <?php echo e($color['dot']); ?>"></span>
                            <p class="text-xs text-slate-400">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bill->urgency === 'overdue'): ?>
                                    Terlambat <?php echo e(abs($bill->days_until_due)); ?> hari
                                <?php elseif($bill->days_until_due === 0): ?>
                                    Jatuh tempo hari ini
                                <?php else: ?>
                                    <?php echo e($bill->days_until_due); ?> hari lagi · <?php echo e($bill->next_due_date->translatedFormat('d M')); ?>

                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </p>
                        </div>
                    </div>

                    
                    <div class="text-right flex-shrink-0">
                        <p class="text-sm font-semibold text-slate-700">Rp <?php echo e(number_format($bill->amount, 0, ',', '.')); ?></p>
                        <span class="text-[10px] font-medium px-1.5 py-0.5 rounded-md <?php echo e($color['badge']); ?>">
                            <?php echo e($color['label']); ?>

                        </span>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="py-10 text-center">
                    <span class="text-3xl block mb-2">✅</span>
                    <p class="text-sm font-medium text-slate-600">Semua tagihan aman</p>
                    <p class="text-xs text-slate-400 mt-1">Tidak ada tagihan jatuh tempo dalam 7 hari</p>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        
        <div class="px-4 py-3 border-t border-slate-100 bg-slate-50/60">
            <a href="<?php echo e(route('bills')); ?>" wire:navigate
               @click="$wire.close()"
               class="flex items-center justify-center gap-1.5 text-xs font-medium text-primary-600 hover:text-primary-700 transition-colors">
                Lihat semua tagihan
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                </svg>
            </a>
        </div>
    </div>
</div><?php /**PATH /Users/izarhairulanam/Downloads/finance-tracker-responsive 7/resources/views/livewire/bill-reminder.blade.php ENDPATH**/ ?>