<?php

namespace App\Livewire;

use App\Models\Bill;
use Livewire\Component;

class BillReminder extends Component
{
    public bool $open = false;

    public function toggle(): void
    {
        $this->open = !$this->open;
    }

    public function close(): void
    {
        $this->open = false;
    }

    public function getRemindersProperty()
    {
        return Bill::active()
            ->get()
            ->filter(fn($b) => !$b->is_paid_this_month && $b->days_until_due <= 7)
            ->sortBy('days_until_due')
            ->values();
    }

    public function getCountProperty(): int
    {
        return $this->reminders->count();
    }

    public function render()
    {
        $reminders = $this->reminders;
        return view('livewire.bill-reminder', [
            'reminders' => $reminders,
            'count'     => $reminders->count(),
        ]);
    }
}