<?php

namespace App\Livewire\AnnualRequest;

use App\Helpers\adLDAP;
use App\Models\AnnualRequest;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Illuminate\Support\Facades\Hash;

class YearReset extends Component
{
    public $LatResetDate;
    public $YearState;
    public $showResetModal = false;
    public $showStartYearModal = false;
    public $password;
    public $password_confirmation;
    public $yearState;

    protected $rules = [
        'password_confirmation' => 'required|same:password',
    ];

    public function mount()
    {
        $this->yearState = AnnualRequest::getYearState();
        $this->YearState = AnnualRequest::getYearState();
        $this->LatResetDate = AnnualRequest::getLastYearReset();
    }

    public function YearStartModal()
    {
        $this->showStartYearModal = true;
    }

    public function ResetModal()
    {
        $this->showResetModal = true;
    }

    public function startYear()
    {
        
        $this->validate();

        $adldap = new adLDAP();
        if (!$adldap->authenticate(Auth::user()->name, $this->password)) {
            $this->addError('password', 'كلمة المرور غير صحيحة');
            $this->reset('password', 'password_confirmation');
            return;
        }

        try {
            AnnualRequest::startYear();
            $this->dispatch('showMessage', 'تم بنجاح تفعيل السنة الجديدة', 'عملية ناجحة');
        } catch (\Exception $e) {
            $users = json_decode($e->getMessage());
            $userNames = collect($users)->pluck('role')->implode(', ');
            $this->dispatch('showMessage', "الجهات التالية ليس لديها طلب سنوي فعال: " . $userNames, 'عملية غير ناجحة');
        } finally {
            $this->reset(['showStartYearModal', 'password', 'password_confirmation']);
        }
    }

    public function resetYear()
    {
        $this->validate();

        $adldap = new adLDAP();
        if (!$adldap->authenticate(Auth::user()->name, $this->password)) {
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
