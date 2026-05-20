<div class="fixed bottom-5 right-5 z-[100] flex flex-col gap-3 max-w-sm w-full pointer-events-none">
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $notifications ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $n): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div wire:key="<?php echo e($n['id']); ?>"
             x-data="{ visible: false }"
             x-init="requestAnimationFrame(() => visible = true);
                     setTimeout(() => {
                         visible = false;
                         setTimeout(() => $wire.remove('<?php echo e($n['id']); ?>'), 400);
                     }, 4000)"
             x-show="visible"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-x-4"
             x-transition:enter-end="opacity-100 translate-x-0"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="opacity-100 translate-x-0"
             x-transition:leave-end="opacity-0 translate-x-4"
             class="pointer-events-auto flex items-start gap-3 px-4 py-3 rounded-2xl shadow-lg border
                    <?php echo e($n['type'] === 'success' ? 'bg-emerald-50 border-emerald-200 text-emerald-800' : ''); ?>

                    <?php echo e($n['type'] === 'error'   ? 'bg-rose-50 border-rose-200 text-rose-800' : ''); ?>

                    <?php echo e($n['type'] === 'warning' ? 'bg-amber-50 border-amber-200 text-amber-800' : ''); ?>

                    <?php echo e($n['type'] === 'info'    ? 'bg-blue-50 border-blue-200 text-blue-800' : ''); ?>">
            
            <div class="text-sm font-medium">
                <?php echo e($n['message']); ?>

            </div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div><?php /**PATH /Users/izarhairulanam/Downloads/finance-tracker-responsive 7/resources/views/livewire/notification.blade.php ENDPATH**/ ?>