<?php

namespace App\Exports;

use App\Models\Asset;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AssetsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle
{
    public function collection()
    {
        // Global scope on Asset already filters by auth user
        return Asset::orderBy('type')->get();
    }

    public function headings(): array
    {
        return ['ID','Nama','Tipe','Harga Beli (Rp)','Nilai Sekarang (Rp)','Untung/Rugi (Rp)','Tanggal Beli','Deskripsi'];
    }

    public function map($row): array
    {
        return [
            $row->id,
            $row->name,
            Asset::$types[$row->type] ?? $row->type,
            number_format($row->purchase_price, 0, ',', '.'),
            number_format($row->current_value, 0, ',', '.'),
            number_format($row->gain_loss, 0, ',', '.'),
            $row->purchase_date->format('d/m/Y'),
            $row->description ?? '-',
        ];
    }

    public function styles(Worksheet $sheet): array { return [1 => ['font' => ['bold' => true]]]; }
    public function title(): string { return 'Aset'; }
}
