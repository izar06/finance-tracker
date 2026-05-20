<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Keuangan {{ $year }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 11px; color: #1e293b; }

        /* ── Header ── */
        .header { background: #3b82f6; color: white; padding: 20px 24px; margin-bottom: 20px; }
        .header h1 { font-size: 20px; font-weight: bold; }
        .header p { font-size: 11px; opacity: 0.85; margin-top: 3px; }
        .header .meta { margin-top: 8px; font-size: 10px; opacity: 0.7; }

        /* ── Summary Cards ── */
        .section { margin: 0 24px 20px; }
        .section-title { font-size: 13px; font-weight: bold; color: #1e293b; margin-bottom: 10px;
                         padding-bottom: 6px; border-bottom: 2px solid #e2e8f0; }

        .summary-table { width: 100%; border-collapse: separate; border-spacing: 6px; margin-bottom: 20px; padding: 0 24px; }
        .summary-cell { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 12px; vertical-align: top; }
        .s-label { font-size: 9px; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px; font-weight: bold; }
        .s-value { font-size: 13px; font-weight: bold; margin-top: 4px; }
        .s-sub { font-size: 9px; color: #94a3b8; margin-top: 3px; }
        .income { color: #16a34a; }
        .expense { color: #dc2626; }
        .balance-pos { color: #2563eb; }
        .balance-neg { color: #dc2626; }
        .asset-card { background: #3b82f6; border: none; }
        .asset-card .s-label { color: rgba(255,255,255,0.7); }
        .asset-card .s-value { color: white; }
        .asset-card .s-sub { color: rgba(255,255,255,0.6); }

        /* ── Chart image ── */
        .chart-box { background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px;
                     padding: 14px; margin-bottom: 14px; }
        .chart-title { font-size: 12px; font-weight: bold; color: #1e293b; margin-bottom: 4px; }
        .chart-sub { font-size: 10px; color: #94a3b8; margin-bottom: 10px; }
        .chart-img { width: 100%; height: auto; }
        .no-chart { text-align: center; padding: 30px; color: #94a3b8; font-size: 10px;
                    background: #f1f5f9; border-radius: 6px; }

        /* ── Tables ── */
        .data-table { width: 100%; border-collapse: collapse; }
        .data-table thead tr { background: #f1f5f9; }
        .data-table th { text-align: left; padding: 7px 10px; font-size: 10px; font-weight: 600;
                         color: #475569; text-transform: uppercase; letter-spacing: 0.3px;
                         border-bottom: 2px solid #e2e8f0; }
        .data-table th.right { text-align: right; }
        .data-table td { padding: 7px 10px; border-bottom: 1px solid #f1f5f9; vertical-align: middle; }
        .data-table td.right { text-align: right; }
        .data-table tr:nth-child(even) td { background: #fafafa; }
        .data-table tfoot td { font-weight: bold; background: #eff6ff; border-top: 2px solid #3b82f6; }

        /* ── Category ── */
        .cat-grid { width: 100%; border-collapse: separate; border-spacing: 8px; }
        .cat-cell { vertical-align: top; width: 50%; }
        .cat-header { padding: 8px 12px; border-radius: 6px 6px 0 0; font-weight: bold; font-size: 11px; color: white; }
        .cat-header.income-h { background: #16a34a; }
        .cat-header.expense-h { background: #dc2626; }
        .cat-row { padding: 6px 12px; border-bottom: 1px solid #f1f5f9; font-size: 10px; }
        .cat-row:nth-child(even) { background: #fafafa; }
        .cat-name { font-weight: 500; color: #1e293b; }
        .cat-count { color: #94a3b8; font-size: 9px; }
        .cat-amount { font-weight: bold; float: right; }
        .cat-amount.income { color: #16a34a; }
        .cat-amount.expense { color: #dc2626; }

        /* ── Progress bar ── */
        .prog-bg { background: #f1f5f9; border-radius: 4px; height: 4px; margin-top: 3px; }
        .prog-fill { background: #f87171; border-radius: 4px; height: 4px; }

        /* ── Badge ── */
        .badge { display: inline-block; padding: 2px 7px; border-radius: 12px; font-size: 9px; font-weight: bold; }
        .badge-green { background: #dcfce7; color: #16a34a; }
        .badge-amber { background: #fef9c3; color: #d97706; }
        .badge-red   { background: #fee2e2; color: #dc2626; }

        /* ── Footer ── */
        .footer { text-align: center; color: #94a3b8; font-size: 9px; margin-top: 20px;
                  padding-top: 10px; border-top: 1px solid #e2e8f0; }

        .page-break { page-break-after: always; }
    </style>
</head>
<body>

{{-- ══ HEADER ══ --}}
<div class="header">
    <h1>Laporan Keuangan {{ $year }}</h1>
    <p>Finance Tracker — Ringkasan & analisis keuangan tahunan</p>
    <div class="meta">Dicetak pada {{ now()->translatedFormat('d F Y, H:i') }} WIB</div>
</div>

{{-- ══ SUMMARY CARDS ══ --}}
<table class="summary-table">
    <tr>
        <td class="summary-cell">
            <div class="s-label">Total Pemasukan {{ $year }}</div>
            <div class="s-value income">Rp {{ number_format($summary['income'], 0, ',', '.') }}</div>
            <div class="s-sub">Rata-rata Rp {{ number_format($summary['avg_monthly_income'], 0, ',', '.') }}/bln</div>
        </td>
        <td class="summary-cell">
            <div class="s-label">Total Pengeluaran {{ $year }}</div>
            <div class="s-value expense">Rp {{ number_format($summary['expense'], 0, ',', '.') }}</div>
            <div class="s-sub">Rata-rata Rp {{ number_format($summary['avg_monthly_expense'], 0, ',', '.') }}/bln</div>
        </td>
        <td class="summary-cell">
            <div class="s-label">Saldo Bersih {{ $year }}</div>
            <div class="s-value {{ $summary['balance'] >= 0 ? 'balance-pos' : 'balance-neg' }}">
                Rp {{ number_format(abs($summary['balance']), 0, ',', '.') }}
            </div>
            <div class="s-sub">Rasio Tabungan: {{ $summary['savings_rate'] }}%</div>
        </td>
        <td class="summary-cell asset-card">
            <div class="s-label">Total Aset Saat Ini</div>
            <div class="s-value">Rp {{ number_format($summary['total_assets'], 0, ',', '.') }}</div>
            <div class="s-sub">{{ $summary['active_goals'] }} tujuan aktif</div>
        </td>
    </tr>
</table>

{{-- ══ CHARTS ══ --}}
<div class="section">
    <div class="section-title">📊 Grafik Keuangan</div>

    {{-- Bar Chart --}}
    <div class="chart-box">
        <div class="chart-title">Perbandingan Bulanan {{ $year }}</div>
        <div class="chart-sub">Pemasukan vs Pengeluaran setiap bulan</div>
        @if(!empty($chartImages['bar']))
            <img class="chart-img" src="{{ $chartImages['bar'] }}" alt="Bar Chart">
        @else
            <div class="no-chart">📊 Grafik tidak tersedia</div>
        @endif
    </div>

    {{-- Line Chart --}}
    <div class="chart-box">
        <div class="chart-title">Saldo Bersih per Bulan</div>
        <div class="chart-sub">Surplus atau defisit setiap bulan di {{ $year }}</div>
        @if(!empty($chartImages['line']))
            <img class="chart-img" src="{{ $chartImages['line'] }}" alt="Line Chart">
        @else
            <div class="no-chart">📈 Grafik tidak tersedia</div>
        @endif
    </div>
</div>

<div class="page-break"></div>

{{-- ══ MONTHLY TABLE ══ --}}
<div class="header" style="background:#1e40af;">
    <h1>Rincian Bulanan {{ $year }}</h1>
</div>
<div class="section">
    <table class="data-table">
        <thead>
            <tr>
                <th>Bulan</th>
                <th class="right">Pemasukan</th>
                <th class="right">Pengeluaran</th>
                <th class="right">Saldo Bersih</th>
                <th class="right">Rasio Tabungan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($monthlyData as $row)
                @php
                    $savingRate = $row['income'] > 0
                        ? round((($row['income'] - $row['expense']) / $row['income']) * 100, 1)
                        : 0;
                    $hasData = $row['income'] > 0 || $row['expense'] > 0;
                @endphp
                <tr style="{{ !$hasData ? 'opacity: 0.4' : '' }}">
                    <td>{{ $row['month'] }}</td>
                    <td class="right income">{{ $hasData && $row['income'] > 0 ? 'Rp '.number_format($row['income'], 0, ',', '.') : '—' }}</td>
                    <td class="right expense">{{ $hasData && $row['expense'] > 0 ? 'Rp '.number_format($row['expense'], 0, ',', '.') : '—' }}</td>
                    <td class="right {{ $row['balance'] >= 0 ? 'balance-pos' : 'balance-neg' }}">
                        {{ $hasData ? ($row['balance'] >= 0 ? '+' : '').  'Rp '.number_format($row['balance'], 0, ',', '.') : '—' }}
                    </td>
                    <td class="right">
                        @if($hasData && $row['income'] > 0)
                            <span class="badge {{ $savingRate >= 20 ? 'badge-green' : ($savingRate >= 0 ? 'badge-amber' : 'badge-red') }}">
                                {{ $savingRate }}%
                            </span>
                        @else
                            —
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td>TOTAL {{ $year }}</td>
                <td class="right income">Rp {{ number_format($summary['income'], 0, ',', '.') }}</td>
                <td class="right expense">Rp {{ number_format($summary['expense'], 0, ',', '.') }}</td>
                <td class="right {{ $summary['balance'] >= 0 ? 'balance-pos' : 'balance-neg' }}">
                    {{ $summary['balance'] >= 0 ? '+' : '' }} Rp {{ number_format($summary['balance'], 0, ',', '.') }}
                </td>
                <td class="right">
                    <span class="badge {{ $summary['savings_rate'] >= 20 ? 'badge-green' : 'badge-amber' }}">
                        {{ $summary['savings_rate'] }}%
                    </span>
                </td>
            </tr>
        </tfoot>
    </table>
</div>

<div class="page-break"></div>

{{-- ══ CATEGORY BREAKDOWN ══ --}}
<div class="header" style="background:#059669;">
    <h1>Breakdown per Kategori {{ $year }}</h1>
</div>
<div class="section">
    <table class="cat-grid">
        <tr>
            {{-- Income Categories --}}
            <td class="cat-cell">
                <div class="cat-header income-h">📥 Pemasukan per Kategori</div>
                @forelse($categories['income'] as $cat)
                    <div class="cat-row">
                        <span class="cat-amount income">Rp {{ number_format($cat['total'], 0, ',', '.') }}</span>
                        <span class="cat-name">{{ $cat['category'] }}</span><br>
                        <span class="cat-count">{{ $cat['count'] }} transaksi</span>
                    </div>
                @empty
                    <div class="cat-row" style="color:#94a3b8;">Tidak ada data</div>
                @endforelse
            </td>

            {{-- Expense Categories --}}
            <td class="cat-cell">
                <div class="cat-header expense-h">📤 Pengeluaran per Kategori</div>
                @forelse($categories['expense'] as $cat)
                    @php $pct = $summary['expense'] > 0 ? round(($cat['total'] / $summary['expense']) * 100, 1) : 0; @endphp
                    <div class="cat-row">
                        <span class="cat-amount expense">Rp {{ number_format($cat['total'], 0, ',', '.') }}</span>
                        <span class="cat-name">{{ $cat['category'] }}</span><br>
                        <span class="cat-count">{{ $cat['count'] }} transaksi · {{ $pct }}%</span>
                        <div class="prog-bg"><div class="prog-fill" style="width:{{ $pct }}%"></div></div>
                    </div>
                @empty
                    <div class="cat-row" style="color:#94a3b8;">Tidak ada data</div>
                @endforelse
            </td>
        </tr>
    </table>
</div>

<div class="footer">
    Finance Tracker &copy; {{ now()->year }} — Laporan ini dibuat otomatis oleh sistem
</div>

</body>
</html>
