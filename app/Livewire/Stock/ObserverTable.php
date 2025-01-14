<?php

namespace App\Livewire\Stock;

use App\Models\AnnualRequest;
use App\Models\AppConfiguration;
use App\Models\Stock;
use Livewire\Attributes\Reactive;
use Livewire\Component;
use Livewire\WithPagination;

class ObserverTable extends Component
{
    use WithPagination;

    public $searchDetails = '';
    public $searchItem = '';
    public $searchDep = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $paginate = true;
    public $filters = [
        'this-year' => true,
        'date_from' => '',
        'date_to' => ''
    ];

    public function sortBy($field)
    {
        $this->sortDirection = $this->sortField === $field
            ? $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc'
            : 'asc';

        $this->sortField = $field;
    }

    public function toggleThisYear() {
        $this->filters['this-year'] = !$this->filters['this-year'];
    }

    public function togglePagination()
    {
        $this->paginate = !$this->paginate;
    }

    public function render()
    {
        $query = Stock::query()
            ->with(['item', 'user'])
            ->when($this->searchDetails, fn($q) => $q->where('details', 'like', '%' . $this->searchDetails . '%'))
            ->when($this->searchItem, fn($q) => $q->whereHas('item', function($query) {
                $query->where('name', 'like', '%' . $this->searchItem . '%');
            }))
            ->when($this->searchDep, fn($q) => $q->whereHas('user', function($query) {
                $query->where('role', 'like', '%' . $this->searchDep . '%');
            }))
            ->when($this->filters['this-year'], fn($q) => $q->where('created_at', '>=', AnnualRequest::getLastYearReset()))
            ->when($this->filters['date_from'], fn($q) => $q->where('created_at', '>=', $this->filters['date_from']))
            ->when($this->filters['date_to'], fn($q) => $q->where('created_at', '<=', $this->filters['date_to']))
            ->orderBy($this->sortField, $this->sortDirection);
    
        return view('livewire.stock.observer-table', [
            'stocks' => $this->paginate ? $query->paginate(20) : $query->get()
        ]);
    }
}
