<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class TransactionsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithTitle
{
    private $query;

    public function __construct($query)
    {
        $this->query = $query;
    }

    public function collection(): Collection
    {
        return $this->query->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Tipe',
            'Judul',
            'Jumlah (Rp)',
            'Kategori',
            'Tanggal',
            'Catatan',
        ];
    }

    public function map($row): array
    {
        return [
            $row->id,
            $row->type === 'income' ? 'Pemasukan' : 'Pengeluaran',
            $row->title,
            number_format($row->amount, 0, ',', '.'),
            $row->category,
            $row->date->format('d/m/Y'),
            $row->notes ?? '-',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }

    public function title(): string
    {
        return 'Transaksi';
    }
}
