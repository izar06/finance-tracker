<?php

namespace App\Livewire;

use Livewire\Attributes\On;
use Livewire\Component;

class Notification extends Component
{
    public array $notifications = [];

    #[On('notify')]
    public function add(string $message, string $type = 'success'): void
    {
        $this->notifications[] = [
            'id'      => uniqid(),
            'message' => $message,
            'type'    => $type,
        ];
    }

    public function remove(string $id): void
    {
        $this->notifications = array_values(array_filter(
            $this->notifications,
            fn($n) => $n['id'] !== $id
        ));
    }

    public function render()
    {
        return view('livewire.notification');
    }
}
