<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Aset</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 11px; color: #1e293b; }
        .header { background: #22c55e; color: white; padding: 20px 24px; margin-bottom: 20px; }
        .header h1 { font-size: 18px; font-weight: bold; }
        .header p { font-size: 11px; opacity: 0.85; margin-top: 4px; }
        .total-card { background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px; padding: 16px 24px; margin: 0 24px 20px; }
        .total-card .label { font-size: 11px; color: #15803d; }
        .total-card .value { font-size: 22px; font-weight: bold; color: #14532d; margin-top: 4px; }
        table { width: 100%; border-collapse: collapse; margin: 0 24px; width: calc(100% - 48px); }
        thead { background: #f1f5f9; }
        th { text-align: left; padding: 8px 10px; font-size: 10px; font-weight: 600; color: #475569; text-transform: uppercase; letter-spacing: 0.4px; border-bottom: 2px solid #e2e8f0; }
        td { padding: 8px 10px; border-bottom: 1px solid #f1f5f9; vertical-align: top; }
        tr:nth-child(even) td { background: #fafafa; }
        .gain { color: #16a34a; font-weight: bold; }
        .loss { color: #dc2626; font-weight: bold; }
        .footer { margin-top: 20px; padding: 12px 24px; border-top: 1px solid #e2e8f0; text-align: center; color: #94a3b8; font-size: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>🏦 Laporan Daftar Aset</h1>
        <p>Dicetak pada: {{ now()->translatedFormat('d F Y, H:i') }}</p>
    </div>

    <div class="total-card">
        <p class="label">Total Nilai Aset Keseluruhan</p>
        <p class="value">Rp {{ number_format($totalValue, 0, ',', '.') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Nama Aset</th>
                <th>Tipe</th>
                <th style="text-align:right;">Harga Beli</th>
                <th style="text-align:right;">Nilai Sekarang</th>
                <th style="text-align:right;">Untung/Rugi</th>
                <th>Tgl Beli</th>
            </tr>
        </thead>
        <tbody>
            @foreach($assets as $asset)
                <tr>
                    <td>
                        <strong>{{ $asset->name }}</strong>
                        @if($asset->description)
                            <br><small style="color:#94a3b8;">{{ $asset->description }}</small>
                        @endif
                    </td>
                    <td>{{ \App\Models\Asset::$types[$asset->type] ?? $asset->type }}</td>
                    <td style="text-align:right;">{{ number_format($asset->purchase_price, 0, ',', '.') }}</td>
                    <td style="text-align:right; font-weight:bold;">{{ number_format($asset->current_value, 0, ',', '.') }}</td>
                    <td style="text-align:right;" class="{{ $asset->gain_loss >= 0 ? 'gain' : 'loss' }}">
                        {{ $asset->gain_loss >= 0 ? '+' : '' }}{{ number_format($asset->gain_loss, 0, ',', '.') }}
                        <br><small>({{ $asset->gain_loss_percentage }}%)</small>
                    </td>
                    <td>{{ $asset->purchase_date->format('d/m/Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Finance Tracker — Laporan dibuat otomatis oleh sistem
    </div>
</body>
</html>
