<?php

namespace App\Livewire\AnnualRequest;

use App\Models\AnnualRequest;
use Livewire\Component;

class UpdateSemester extends Component
{
    public $currentSemester;

    public function mount()
    {
        $this->currentSemester = AnnualRequest::getCurrentSemester();
    }

    public function updateSemester()
    {
        try{
            AnnualRequest::NextSemester();
            $this->dispatch('showMessage', "تم بدء الفصل الجديد بنجاح", 'عملية ناجحة');
            $this->dispatch('refresh');

        }
        catch(\Exception $e){
            $this->dispatch('showMessage', "لا يوجد فصل تالي", 'عملية غير ناجحة');
        }
    }

    public function render()
    {
        return view('livewire.annual-request.update-semester');
    }
}
