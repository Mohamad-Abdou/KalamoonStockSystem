<?php

namespace App\Livewire\AnnualRequest;

use App\Models\AnnualRequest;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Illuminate\Support\Facades\Hash;

class YearReset extends Component
{
    public $LatResetDate;
    public $showResetModal = false;
    public $password;
    public $password_confirmation;

    protected $rules = [
        'password_confirmation' => 'required|same:password',
    ];

    public function mount()
    {
        $this->LatResetDate = AnnualRequest::getLastYearReset();
    }
    
    public function resetYearButton()
    {
        $this->validate();

        if (!Hash::check($this->password, Auth::user()->password)) {
            $this->addError('password', 'كلمة المرور غير صحيحة');
            $this->reset('password', 'password_confirmation');
            return;
        }
        try {
            AnnualRequest::resetYear();
            $this->dispatch('showMessage', 'تم تدوير السنة وتصفير الأرصدة', 'عملية ناجحة');
        } catch (\Exception $e) {
            $this->dispatch('showMessage', $e->getMessage(), 'عملية غير ناجحة');
        }

        
        $this->LatResetDate = AnnualRequest::getLastYearReset();
        
        $this->reset(['showResetModal', 'password', 'password_confirmation']);


    }
        
    public function render()
    {
        return view('livewire.annual-request.year-reset');
    }
}