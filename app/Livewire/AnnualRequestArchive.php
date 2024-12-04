<?php

namespace App\Livewire;

use App\Models\AnnualRequest;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Component;
use Livewire\WithPagination;

class AnnualRequestArchive extends Component
{
    use WithPagination;
    
    public $search = '';
    public $filterState = 'all';
    public $dateFrom = '';
    public $dateTo = '';
    private function loadAnnualRequests()
    {
        $query = AnnualRequest::query()
            ->with('user'); // Eager load the user relationship

        switch ($this->filterState) {
            case '2':
                $query->where('state', 2);
                break;
            case '-1':
                $query->where('state', -1);
                break;
            case 'study':
                $query->where('state', '!=', 2)->where('state', '!=', -1);
                break;
        }
        if ($this->search) {
            $query->whereHas('user', function ($q) {
                $q->where('role', 'like', '%' . $this->search . '%');
            });
        }
        if ($this->dateFrom) {
            $query->whereDate('created_at', '>=', $this->dateFrom);
        }
        if ($this->dateTo) {
            $query->whereDate('created_at', '<=', $this->dateTo);
        }

        return $query->orderBy('created_at', 'DESC')->paginate(15);
    }

    public function resetFilters()
    {
        $this->filterState = 'all';
        $this->search = '';
        $this->dateFrom = null;
        $this->dateTo = null;
    }

    public function render()
    {
        return view('annual-request.annual-request-archive', [
            'annual_requests' => $this->loadAnnualRequests()
        ]);
    }
}