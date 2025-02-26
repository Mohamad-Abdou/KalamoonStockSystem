<?php

namespace App\Livewire;

use Livewire\Attributes\On;
use Livewire\Component;

class MessageModal extends Component
{
    public $header = '';
    public $message = '';
    public $isOpen = false;

    #[On('showMessage')]
    public function showMessage($message, $header = 'تنبيه')
    {
        $this->header = $header;
        $this->message = $message;
        $this->isOpen = true;
    }

    public function mount($header = 'تنبيه', $message = '')
    {
        $this->header = $header;
        $this->message = $message;

        if (session()->has('message')) {
            $this->message = session('message');
            $this->header = session('header', $header); 
            $this->isOpen = true;
        } elseif (!empty($message)) {
            $this->isOpen = true;
        }
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }

    public function render()
    {
        return view('livewire.message-modal');
    }
}
