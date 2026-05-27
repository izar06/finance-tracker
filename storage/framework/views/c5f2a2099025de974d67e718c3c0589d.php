<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Transaksi</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 11px; color: #1e293b; }

        .header { background: #22c55e; color: white; padding: 20px 24px; margin-bottom: 20px; }
        .header h1 { font-size: 18px; font-weight: bold; }
        .header p { font-size: 11px; opacity: 0.85; margin-top: 4px; }

        /* Summary pakai table bukan flexbox */
        .summary-table { width: 100%; border-collapse: separate; border-spacing: 6px; margin-bottom: 20px; padding: 0 24px; }
        .summary-cell { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 12px; width: 25%; }
        .label { font-size: 10px; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; }
        .value { font-size: 14px; font-weight: bold; margin-top: 4px; }
        .income { color: #16a34a; }
        .expense { color: #dc2626; }

        /* Main table */
        .main-table { width: 100%; border-collapse: collapse; margin: 0; padding: 0 24px; }
        .main-wrap { margin: 0 24px; }
        .main-table thead { background: #f1f5f9; }
        .main-table th { text-align: left; padding: 8px 10px; font-size: 10px; font-weight: 600; color: #475569; text-transform: uppercase; letter-spacing: 0.4px; border-bottom: 2px solid #e2e8f0; }
        .main-table td { padding: 8px 10px; border-bottom: 1px solid #f1f5f9; }
        .main-table tr:nth-child(even) td { background: #fafafa; }

        .badge { display: inline-block; padding: 2px 8px; border-radius: 20px; font-size: 10px; font-weight: 600; }
        .badge-income  { background: #dcfce7; color: #166534; }
        .badge-expense { background: #fee2e2; color: #991b1b; }

        .footer { margin-top: 20px; padding: 12px 24px; border-top: 1px solid #e2e8f0; text-align: center; color: #94a3b8; font-size: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Laporan Transaksi Keuangan</h1>
        <p>Dicetak pada: <?php echo e(now()->translatedFormat('d F Y, H:i')); ?></p>
    </div>

    
    <table class="summary-table">
        <tr>
            <td class="summary-cell">
                <p class="label">Total Pemasukan</p>
                <p class="value income">Rp <?php echo e(number_format($totalIncome, 0, ',', '.')); ?></p>
            </td>
            <td class="summary-cell">
                <p class="label">Total Pengeluaran</p>
                <p class="value expense">Rp <?php echo e(number_format($totalExpense, 0, ',', '.')); ?></p>
            </td>
            <td class="summary-cell">
                <p class="label">Saldo Bersih</p>
                <p class="value <?php echo e(($totalIncome - $totalExpense) >= 0 ? 'income' : 'expense'); ?>">
                    Rp <?php echo e(number_format(abs($totalIncome - $totalExpense), 0, ',', '.')); ?>

                </p>
            </td>
            <td class="summary-cell">
                <p class="label">Jumlah Transaksi</p>
                <p class="value" style="color:#3b82f6;"><?php echo e($transactions->count()); ?> transaksi</p>
            </td>
        </tr>
    </table>

    
    <div class="main-wrap">
        <table class="main-table">
            <thead>
                <tr>
                    <th style="width:5%">No.</th>
                    <th style="width:11%">Tanggal</th>
                    <th style="width:25%">Judul</th>
                    <th style="width:16%">Kategori</th>
                    <th style="width:15%">Metode</th>
                    <th style="width:10%">Tipe</th>
                    <th style="width:18%; text-align:right;">Jumlah (Rp)</th>
                </tr>
            </thead>
            <tbody>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tx): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td style="color:#94a3b8"><?php echo e($loop->iteration); ?></td>
                        <td><?php echo e($tx->date->format('d/m/Y')); ?></td>
                        <td><?php echo e($tx->title); ?></td>
                        <td><?php echo e($tx->category); ?></td>
                        <td><?php echo e($tx->payment_method ?? '-'); ?></td>
                        <td>
                            <span class="badge <?php echo e($tx->type === 'income' ? 'badge-income' : 'badge-expense'); ?>">
                                <?php echo e($tx->type === 'income' ? 'Pemasukan' : 'Pengeluaran'); ?>

                            </span>
                        </td>
                        <td style="text-align:right; font-weight:bold; color: <?php echo e($tx->type === 'income' ? '#16a34a' : '#dc2626'); ?>">
                            <?php echo e($tx->type === 'income' ? '+' : '-'); ?> <?php echo e(number_format($tx->amount, 0, ',', '.')); ?>

                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="footer">
        Finance Tracker — Laporan dibuat otomatis oleh sistem
    </div>
</body>
</html><?php /**PATH /Users/izarhairulanam/Downloads/finance-tracker-responsive 7/resources/views/exports/transactions-pdf.blade.php ENDPATH**/ ?>