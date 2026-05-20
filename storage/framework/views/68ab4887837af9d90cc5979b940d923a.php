<div>
    
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <div>
            <h2 class="text-xl font-bold text-slate-800">Laporan Keuangan</h2>
            <p class="text-sm text-slate-500">Ringkasan & analisis keuangan tahunan</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <select wire:model.live="year"
                    class="px-4 py-2.5 text-sm border border-slate-200 rounded-xl focus:ring-2 focus:ring-primary-300 outline-none bg-white font-medium">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $this->availableYears; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $y): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($y); ?>"><?php echo e($y); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </select>
            <button wire:click="exportExcel"
                    wire:loading.attr="disabled"
                    class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-slate-700 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition-colors">
                <span>📊</span>
                <span wire:loading.remove wire:target="exportExcel">Excel</span>
                <span wire:loading wire:target="exportExcel">...</span>
            </button>
            <button id="btn-export-pdf"
                    onclick="captureChartsAndExportPdf()"
                    class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-slate-700 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition-colors">
                <span>📄</span>
                <span id="btn-pdf-label">PDF</span>
            </button>
        </div>
    </div>

    
    <?php $s = $this->yearlySummary; ?>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm">
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide mb-2">Total Pemasukan <?php echo e($year); ?></p>
            <p class="text-base sm:text-xl font-bold text-emerald-600 break-all">Rp <?php echo e(number_format($s['income'], 0, ',', '.')); ?></p>
            <p class="text-xs text-slate-400 mt-1">Rata-rata Rp <?php echo e(number_format($s['avg_monthly_income'], 0, ',', '.')); ?>/bln</p>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm">
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide mb-2">Total Pengeluaran <?php echo e($year); ?></p>
            <p class="text-base sm:text-xl font-bold text-rose-600 break-all">Rp <?php echo e(number_format($s['expense'], 0, ',', '.')); ?></p>
            <p class="text-xs text-slate-400 mt-1">Rata-rata Rp <?php echo e(number_format($s['avg_monthly_expense'], 0, ',', '.')); ?>/bln</p>
        </div>
        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm">
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide mb-2">Saldo Bersih <?php echo e($year); ?></p>
            <p class="text-base sm:text-xl font-bold break-all <?php echo e($s['balance'] >= 0 ? 'text-blue-600' : 'text-rose-600'); ?>">
                Rp <?php echo e(number_format(abs($s['balance']), 0, ',', '.')); ?>

            </p>
            <p class="text-xs <?php echo e($s['savings_rate'] >= 20 ? 'text-emerald-500' : 'text-amber-500'); ?> mt-1 font-medium">
                Rasio Tabungan: <?php echo e($s['savings_rate']); ?>%
            </p>
        </div>
        <div class="bg-gradient-to-br from-primary-500 to-primary-600 rounded-2xl p-5 shadow-lg shadow-primary-500/20">
            <p class="text-xs font-semibold text-white/70 uppercase tracking-wide mb-2">Total Aset Saat Ini</p>
            <p class="text-base sm:text-xl font-bold text-white break-all">Rp <?php echo e(number_format($s['total_assets'], 0, ',', '.')); ?></p>
            <p class="text-xs text-white/60 mt-1"><?php echo e($s['active_goals']); ?> tujuan aktif</p>
        </div>
    </div>

    
    <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm mb-6">
        <h3 class="font-bold text-slate-800 mb-1">Perbandingan Bulanan <?php echo e($year); ?></h3>
        <p class="text-xs text-slate-400 mb-5">Pemasukan vs Pengeluaran setiap bulan</p>
        <div class="relative w-full" style="height:220px">
            <canvas id="monthlyBarChart"
                    data-chart='<?php echo json_encode($this->chartData, 15, 512) ?>'></canvas>
        </div>
    </div>

    
    <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm mb-6">
        <h3 class="font-bold text-slate-800 mb-1">Saldo Bersih per Bulan</h3>
        <p class="text-xs text-slate-400 mb-5">Surplus atau defisit setiap bulan di <?php echo e($year); ?></p>
        <div class="relative w-full" style="height:180px">
            <canvas id="balanceLineChart"
                    data-chart='<?php echo json_encode($this->chartData, 15, 512) ?>'></canvas>
        </div>
    </div>

    
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">

        
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 bg-emerald-50">
                <h3 class="font-bold text-emerald-800">📥 Pemasukan per Kategori</h3>
                <p class="text-xs text-emerald-600 mt-0.5">Tahun <?php echo e($year); ?></p>
            </div>
            <div class="divide-y divide-slate-50">
                <?php $categories = $this->categoryBreakdown; ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $categories['income']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="flex items-center gap-4 px-6 py-3">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-slate-800"><?php echo e($cat['category']); ?></p>
                            <p class="text-xs text-slate-400"><?php echo e($cat['count']); ?> transaksi</p>
                        </div>
                        <p class="text-sm font-bold text-emerald-600 whitespace-nowrap">
                            Rp <?php echo e(number_format($cat['total'], 0, ',', '.')); ?>

                        </p>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="py-10 text-center text-slate-400 text-sm">Tidak ada data pemasukan</div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>

        
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 bg-rose-50">
                <h3 class="font-bold text-rose-800">📤 Pengeluaran per Kategori</h3>
                <p class="text-xs text-rose-600 mt-0.5">Tahun <?php echo e($year); ?></p>
            </div>
            <div class="divide-y divide-slate-50">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $categories['expense']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        $pct = $s['expense'] > 0 ? round(($cat['total'] / $s['expense']) * 100, 1) : 0;
                    ?>
                    <div class="px-6 py-3">
                        <div class="flex items-center gap-4 mb-1.5">
                            <div class="flex-1">
                                <p class="text-sm font-medium text-slate-800"><?php echo e($cat['category']); ?></p>
                                <p class="text-xs text-slate-400"><?php echo e($cat['count']); ?> transaksi · <?php echo e($pct); ?>% dari total</p>
                            </div>
                            <p class="text-sm font-bold text-rose-600 whitespace-nowrap">
                                Rp <?php echo e(number_format($cat['total'], 0, ',', '.')); ?>

                            </p>
                        </div>
                        <div class="w-full bg-slate-100 rounded-full h-1.5">
                            <div class="h-1.5 rounded-full bg-rose-400" style="width: <?php echo e($pct); ?>%"></div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="py-10 text-center text-slate-400 text-sm">Tidak ada data pengeluaran</div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
    </div>

    
    <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100">
            <h3 class="font-bold text-slate-800">Rincian Bulanan <?php echo e($year); ?></h3>
        </div>
        <div class="overflow-x-auto -webkit-overflow-scrolling-touch">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 border-b border-slate-100">
                    <tr>
                        <th class="text-left px-3 sm:px-6 py-3 font-semibold text-slate-600 whitespace-nowrap">Bulan</th>
                        <th class="text-right px-3 sm:px-6 py-3 font-semibold text-slate-600 whitespace-nowrap">Pemasukan</th>
                        <th class="text-right px-3 sm:px-6 py-3 font-semibold text-slate-600 whitespace-nowrap">Pengeluaran</th>
                        <th class="text-right px-3 sm:px-6 py-3 font-semibold text-slate-600 whitespace-nowrap">Saldo</th>
                        <th class="text-right px-3 sm:px-6 py-3 font-semibold text-slate-600 whitespace-nowrap">Tabungan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $this->monthlyBreakdown; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $savingRate = $row['income'] > 0
                                ? round((($row['income'] - $row['expense']) / $row['income']) * 100, 1)
                                : 0;
                        ?>
                        <tr class="<?php echo e($row['income'] == 0 && $row['expense'] == 0 ? 'opacity-40' : 'hover:bg-slate-50'); ?> transition-colors">
                            <td class="px-6 py-3 font-medium text-slate-700"><?php echo e($row['month']); ?></td>
                            <td class="px-6 py-3 text-right text-emerald-600 font-semibold">
                                <?php echo e($row['income'] > 0 ? 'Rp '.number_format($row['income'], 0, ',', '.') : '—'); ?>

                            </td>
                            <td class="px-6 py-3 text-right text-rose-600 font-semibold">
                                <?php echo e($row['expense'] > 0 ? 'Rp '.number_format($row['expense'], 0, ',', '.') : '—'); ?>

                            </td>
                            <td class="px-6 py-3 text-right font-bold
                                       <?php echo e($row['balance'] > 0 ? 'text-blue-600' : ($row['balance'] < 0 ? 'text-rose-600' : 'text-slate-400')); ?>">
                                <?php echo e($row['income'] > 0 || $row['expense'] > 0
                                    ? ($row['balance'] >= 0 ? '+' : '').' Rp '.number_format($row['balance'], 0, ',', '.')
                                    : '—'); ?>

                            </td>
                            <td class="px-6 py-3 text-right">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($row['income'] > 0): ?>
                                    <span class="inline-flex items-center px-2 py-0.5 text-xs font-semibold rounded-lg
                                                 <?php echo e($savingRate >= 20 ? 'bg-emerald-50 text-emerald-700' : ($savingRate >= 0 ? 'bg-amber-50 text-amber-700' : 'bg-rose-50 text-rose-700')); ?>">
                                        <?php echo e($savingRate); ?>%
                                    </span>
                                <?php else: ?>
                                    <span class="text-slate-300">—</span>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </tbody>
                <tfoot class="bg-slate-50 border-t-2 border-slate-200">
                    <tr>
                        <td class="px-6 py-3 font-bold text-slate-700">TOTAL <?php echo e($year); ?></td>
                        <td class="px-6 py-3 text-right font-bold text-emerald-700">
                            Rp <?php echo e(number_format($s['income'], 0, ',', '.')); ?>

                        </td>
                        <td class="px-6 py-3 text-right font-bold text-rose-700">
                            Rp <?php echo e(number_format($s['expense'], 0, ',', '.')); ?>

                        </td>
                        <td class="px-6 py-3 text-right font-bold <?php echo e($s['balance'] >= 0 ? 'text-blue-700' : 'text-rose-700'); ?>">
                            <?php echo e($s['balance'] >= 0 ? '+' : ''); ?> Rp <?php echo e(number_format($s['balance'], 0, ',', '.')); ?>

                        </td>
                        <td class="px-6 py-3 text-right">
                            <span class="inline-flex items-center px-2 py-0.5 text-xs font-bold rounded-lg
                                         <?php echo e($s['savings_rate'] >= 20 ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800'); ?>">
                                <?php echo e($s['savings_rate']); ?>%
                            </span>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
// ── Chart capture untuk export PDF ──────────────────────────────────────────
function captureChartsAndExportPdf() {
    const btn   = document.getElementById('btn-export-pdf');
    const label = document.getElementById('btn-pdf-label');

    label.textContent = 'Memproses...';
    btn.disabled = true;

    const barEl  = document.getElementById('monthlyBarChart');
    const lineEl = document.getElementById('balanceLineChart');

    const barImage  = barEl  ? barEl.toDataURL('image/png')  : '';
    const lineImage = lineEl ? lineEl.toDataURL('image/png') : '';

    // Kirim ke Livewire, tunggu event 'charts-received', lalu trigger download
    Livewire.dispatch('receiveChartImages', { bar: barImage, line: lineImage });

    // Dengarkan event dari server bahwa chart sudah diterima → trigger export
    document.addEventListener('charts-received', function handler() {
        document.removeEventListener('charts-received', handler);
        Livewire.dispatch('exportPdf');

        setTimeout(() => {
            label.textContent = 'PDF';
            btn.disabled = false;
        }, 2000);
    }, { once: true });

    // Fallback timeout
    setTimeout(() => {
        label.textContent = 'PDF';
        btn.disabled = false;
    }, 10000);
}

// ── Inisialisasi chart ───────────────────────────────────────────────────────
document.addEventListener('DOMContentLoaded', function () {
    initReportsCharts();

    Livewire.hook('commit', ({ component, commit, respond, succeed, fail }) => {
        succeed(({ snapshot, effect }) => {
            window.setTimeout(() => initReportsCharts(), 50);
        });
    });

    function initReportsCharts() {
        const barEl  = document.getElementById('monthlyBarChart');
        const lineEl = document.getElementById('balanceLineChart');
        if (!barEl) return;

        const d = JSON.parse(barEl.getAttribute('data-chart') || '{}');
        if (!d.months) return;

        const colors = {
            income:  '#22c55e', expense: '#f43f5e', balance: '#3b82f6',
            incomeAlpha:  'rgba(34,197,94,0.15)',
            expenseAlpha: 'rgba(244,63,94,0.15)',
            balanceAlpha: 'rgba(59,130,246,0.12)',
        };
        const tooltipDefaults = {
            backgroundColor: '#1e293b',
            callbacks: { label: ctx => ' Rp ' + ctx.raw.toLocaleString('id-ID') }
        };

        // Bar Chart
        const existingBar = Chart.getChart(barEl);
        if (existingBar) existingBar.destroy();
        new Chart(barEl, {
            type: 'bar',
            data: {
                labels: d.months,
                datasets: [
                    { label: 'Pemasukan', data: d.incomes, backgroundColor: colors.incomeAlpha, borderColor: colors.income, borderWidth: 2, borderRadius: 6, borderSkipped: false },
                    { label: 'Pengeluaran', data: d.expenses, backgroundColor: colors.expenseAlpha, borderColor: colors.expense, borderWidth: 2, borderRadius: 6, borderSkipped: false },
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: { mode: 'index', intersect: false },
                plugins: { legend: { labels: { font: { family: 'Plus Jakarta Sans', size: 11 }, usePointStyle: true, boxWidth: 8 } }, tooltip: tooltipDefaults },
                scales: {
                    y: { beginAtZero: true, ticks: { callback: v => 'Rp '+(v/1e6).toFixed(0)+'jt', font: { size: 10 }, maxTicksLimit: 5 }, grid: { color: 'rgba(0,0,0,0.04)' } },
                    x: { ticks: { font: { size: 9 }, maxRotation: 45, autoSkip: true, maxTicksLimit: 6 }, grid: { display: false } }
                }
            }
        });

        // Line Chart
        if (lineEl) {
            const existingLine = Chart.getChart(lineEl);
            if (existingLine) existingLine.destroy();
            new Chart(lineEl, {
                type: 'line',
                data: {
                    labels: d.months,
                    datasets: [{
                        label: 'Saldo Bersih', data: d.balances,
                        borderColor: colors.balance, backgroundColor: colors.balanceAlpha,
                        fill: true, tension: 0.4,
                        pointBackgroundColor: d.balances.map(v => v >= 0 ? colors.income : colors.expense),
                        pointBorderColor: '#fff', pointBorderWidth: 2, pointRadius: 5, pointHoverRadius: 7,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false }, tooltip: tooltipDefaults },
                    scales: {
                        y: { ticks: { callback: v => 'Rp '+(v/1e6).toFixed(0)+'jt', font: { size: 10 }, maxTicksLimit: 5 }, grid: { color: 'rgba(0,0,0,0.04)' } },
                        x: { ticks: { font: { size: 9 }, maxRotation: 45, autoSkip: true, maxTicksLimit: 6 }, grid: { display: false } }
                    }
                }
            });
        }
    }
});
</script>
<?php $__env->stopPush(); ?>
<?php /**PATH /Users/izarhairulanam/Downloads/finance-tracker-responsive 7/resources/views/livewire/reports/index.blade.php ENDPATH**/ ?>