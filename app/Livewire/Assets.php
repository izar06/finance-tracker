<?php

namespace App\Livewire;

use App\Exports\AssetsExport;
use App\Models\Asset;
use Barryvdh\DomPDF\Facade\Pdf;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class Assets extends Component
{
    use WithPagination;

    public bool $showForm = false;
    public bool $showDeleteModal = false;
    public ?int $editingId = null;
    public ?int $deletingId = null;

    public string $filterType = '';
    public string $search = '';
    public string $sortBy = 'current_value';
    public string $sortDir = 'desc';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingFilterType(): void
    {
        $this->resetPage();
    }

    // Form fields
    public string $name = '';
    public string $type = 'property';
    public string $purchase_price = '';
    public string $current_value = '';
    public string $purchase_date = '';
    public string $description = '';

    protected function rules(): array
    {
        return [
            'name'           => 'required|string|max:255',
            'type'           => 'required|in:property,vehicle,investment,cash,other',
            'purchase_price' => ['required', function($a,$v,$f){ $n=str_replace(".","",$v); if(!is_numeric($n)||$n<0)$f("Harga beli harus berupa angka."); }],
            'current_value'  => ['required', function($a,$v,$f){ $n=str_replace(".","",$v); if(!is_numeric($n)||$n<0)$f("Nilai sekarang harus berupa angka."); }],
            'purchase_date'  => 'required|date',
            'description'    => 'nullable|string|max:500',
        ];
    }

    protected array $validationAttributes = [
        'name'           => 'Nama Aset',
        'type'           => 'Tipe Aset',
        'purchase_price' => 'Harga Beli',
        'current_value'  => 'Nilai Sekarang',
        'purchase_date'  => 'Tanggal Beli',
        'description'    => 'Deskripsi',
    ];

    public function openForm(): void
    {
        $this->resetForm();
        $this->showForm = true;
        $this->dispatch('currency:rebind');
    }

    public function editAsset(int $id): void
    {
        $asset = Asset::findOrFail($id);
        $this->editingId = $id;
        $this->name = $asset->name;
        $this->type = $asset->type;
        $this->purchase_price = (string) (int) $asset->purchase_price;
        $this->current_value = (string) (int) $asset->current_value;
        $this->purchase_date = $asset->purchase_date->format('Y-m-d');
        $this->description = $asset->description ?? '';
        $this->showForm = true;
        $this->dispatch('currency:rebind');
    }

    public function saveAsset(): void
    {
        $this->validate();

        $data = [
            'name'           => $this->name,
            'type'           => $this->type,
            'purchase_price' => (float) str_replace(['.', ','], ['', '.'], $this->purchase_price),
            'current_value'  => (float) str_replace(['.', ','], ['', '.'], $this->current_value),
            'purchase_date'  => $this->purchase_date,
            'description'    => $this->description ?: null,
        ];

        if ($this->editingId) {
            Asset::findOrFail($this->editingId)->update($data);
            $this->dispatch('notify', message: 'Aset berhasil diperbarui.', type: 'success');
        } else {
            Asset::create($data);
            $this->dispatch('notify', message: 'Aset berhasil ditambahkan.', type: 'success');
        }

        $this->closeForm();
    }

    public function confirmDelete(int $id): void
    {
        $this->deletingId = $id;
        $this->showDeleteModal = true;
    }

    public function deleteAsset(): void
    {
        Asset::findOrFail($this->deletingId)->delete();
        $this->showDeleteModal = false;
        $this->deletingId = null;
        $this->dispatch('notify', message: 'Aset berhasil dihapus.', type: 'success');
    }

    public function closeForm(): void
    {
        $this->showForm = false;
        $this->resetForm();
    }

    public function sortColumn(string $column): void
    {
        if ($this->sortBy === $column) {
            $this->sortDir = $this->sortDir === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDir = 'desc';
        }
    }

    private function resetForm(): void
    {
        $this->editingId = null;
        $this->name = '';
        $this->type = 'property';
        $this->purchase_price = '';
        $this->current_value = '';
        $this->purchase_date = now()->format('Y-m-d');
        $this->description = '';
        $this->resetValidation();
    }

    public function exportExcel()
    {
        return Excel::download(new AssetsExport, 'aset-' . now()->format('Y-m-d') . '.xlsx');
    }

    public function exportPdf()
    {
        $assets = Asset::all();
        $pdf = Pdf::loadView('exports.assets-pdf', [
            'assets'     => $assets,
            'totalValue' => $assets->sum('current_value'),
        ]);

        return response()->streamDownload(
            fn() => print($pdf->output()),
            'aset-' . now()->format('Y-m-d') . '.pdf'
        );
    }

    public function getTotalValueProperty(): float
    {
        return Asset::sum('current_value');
    }

    public function getValueByTypeProperty(): array
    {
        return Asset::selectRaw('type, SUM(current_value) as total')
            ->groupBy('type')
            ->pluck('total', 'type')
            ->toArray();
    }

    public function render()
    {
        $assets = Asset::query()
            ->when($this->search, fn($q) => $q->where('name', 'like', "%{$this->search}%"))
            ->when($this->filterType, fn($q) => $q->where('type', $this->filterType))
            ->orderBy($this->sortBy, $this->sortDir)
            ->paginate(12);

        return view('livewire.assets.index', [
            'assets' => $assets,
        ])->layout('layouts.app', ['title' => 'Aset']);
    }
}
