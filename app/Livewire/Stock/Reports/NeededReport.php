<?php

namespace App\Livewire\Stock\Reports;

use App\Models\AnnualRequest;
use App\Models\Item;
use App\Models\Stock;
use Livewire\Component;
use Rap2hpoutre\FastExcel\FastExcel;

class NeededReport extends Component
{
    public $searchByNameAndDetails = '';
    public $lastReset;
    public $filterOption = 'all';
    public $yearState;
    public $semester;
    public $perPage = 50;

    public function mount()
    {
        $this->yearState = AnnualRequest::getYearState();
        $this->lastReset = AnnualRequest::getLastYearReset();
        $this->semester = AnnualRequest::getCurrentSemester();
    }

    public function exportToExcel()
    {
        $lastReset = $this->lastReset;
        $currentSemester = $this->semester;

        $toExcel = Item::query()
            ->when($this->searchByNameAndDetails, function ($query) {
                return $query->where('name', 'like', '%' . $this->searchByNameAndDetails . '%')
                    ->orWhere('description', 'like', '%' . $this->searchByNameAndDetails . '%');
            })
            ->with('Item_group')
            ->get()
            ->map(function ($item) {
                $item->group = $item->Item_group->name;
                $item = Stock::addStockToItem($item, $this->lastReset);
                $item->needed = Stock::NeededStock($item, $this->semester, $this->lastReset);
                $item->totalOut = Stock::totalOut($item, $this->lastReset);
                $item->mainInStock = Stock::mainInStock($item, $this->semester, $this->lastReset);
                $item->extras = $item->mainInStock - $item->needed < 0 ? 0 : $item->mainInStock - $item->needed;
                $item->remainQuantity = $item->needed - $item->mainInStock + $item->extras < 0 ? 0 : $item->needed - $item->mainInStock + $item->extras;
                return $item;
            })
            ->when($this->filterOption === 'stock', function ($collection) {
                return $collection->where('mainInStock', '>', 0);
            })
            ->when($this->filterOption === 'needed', function ($collection) {
                return $collection->where('needed', '>', 0);
            })
            ->sortByDesc('mainInStock')
            ->select(['name', 'description', 'unit', 'inStockAvalible', 'needed', 'totalOut', 'mainInStock', 'extras', 'remainQuantity']);
        $reportName = 'تقرير' . '.xlsx';
        if ($this->filterOption === 'stock')
            $reportName = 'جرد المستودع ' . now()  . '.xlsx';
        elseif ($this->filterOption === 'needed')
            $reportName = 'تقرير الكميات المطلوبة'  . '.xlsx';
        else
            $reportName = 'تقرير مواد'  . '.xlsx';

        
            return (new FastExcel($toExcel))->download($reportName, function ($item) {
            return [
                'اسم المادة' => $item['name'],
                'الوصف' => $item['description'],
                'الوحدة' => $item['unit'],
                'الكمية الموجودة في المستودع' => $item['inStockAvalible'],
                'الكمية المطلوبة' => $item['needed'],
                'الكمية المخرجة' => $item['totalOut'],
                'الكمية المدخلة للمستودع' => $item['mainInStock'],
                'إضافي حر' => $item['extras'],
                'الكمية المتبقية لإتمام الطلب السنوي' => $item['remainQuantity'],

            ];
        });
    }

    public function loadMore()
    {
        $this->perPage += 50;
    }

    public function render()
    {
        $lastReset = $this->lastReset;
        $currentSemester = $this->semester;

        $items = Item::query()
            ->when($this->searchByNameAndDetails, function ($query) {
                return $query->where('name', 'like', '%' . $this->searchByNameAndDetails . '%')
                    ->orWhere('description', 'like', '%' . $this->searchByNameAndDetails . '%');
            })
            ->with('Item_group')
            ->paginate($this->perPage)
            ->map(function ($item) use ($lastReset, $currentSemester) {
                $item->group = $item->Item_group->name;
                $item = Stock::addStockToItem($item, $lastReset);
                $item->needed = Stock::NeededStock($item, $currentSemester, $lastReset);
                $item->firstSemesterNeeded = Stock::getFirstSemesterNeeded($item, $lastReset);
                $item->secondSemesterNeeded = Stock::getSecondSemesterNeeded($item, $lastReset);
                $item->thirdSemesterNeeded = Stock::getThirdSemesterNeeded($item, $lastReset);
                $item->totalOut = Stock::totalOut($item, $lastReset);
                $item->mainInStock = Stock::mainInStock($item, $currentSemester, $lastReset);
                $item->extras = $item->mainInStock - $item->needed < 0 ? 0 : $item->mainInStock - $item->needed;
                $item->remainQuantity = $item->needed - $item->mainInStock + $item->extras < 0 ? 0 : $item->needed - $item->mainInStock + $item->extras;
                return $item;
            })
            ->when($this->filterOption === 'stock', function ($collection) {
                return $collection->where('mainInStock', '>', 0);
            })
            ->when($this->filterOption === 'needed', function ($collection) {
                return $collection->where('needed', '>', 0);
            })
            ->sortByDesc('mainInStock');

        return view('livewire.stock.reports.needed-report', [
            'items' => $items,
        ]);
    }
}
