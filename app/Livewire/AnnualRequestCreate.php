<?php
// @cody: why the error here?

namespace App\Livewire;

use App\Models\AnnualRequest;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

use function Pest\Laravel\get;

class AnnualRequestCreate extends Component
{
    use WithPagination;
    public $itemsToRequest;
    public $selectedItems = [];
    public function mount()
    {
        $user = Auth::user();
        // اختيار المواد التي يمكن للمستخدم طلبها فقط
        $this->itemsToRequest = $user->items();
    }

    public function addItem($newItemId)
    {
        if (!$newItemId) {
            return;
        }

        $item = $this->itemsToRequest->find($newItemId);

        if (!$item || array_key_exists($item->id, $this->selectedItems)) {
            return;
        }


        $this->selectedItems[$item->id] = [
            'name' => $item->name,
            'description' => $item->description ?? '',
            'quantity' => 1,
        ];
    }

    public function removeItem($itemId)
    {
        unset($this->selectedItems[$itemId]);
    }

    public function saveRequest()
    {
        $request = AnnualRequest::create([
            'user_id' => Auth::user()->id,
        ]);

        foreach ($this->selectedItems as $itemId => $details) {
            $request->items()->attach($itemId, ['quantity' => $details['quantity']]);
        }
        session()->flash('message', 'تم حفظ الطلب بنجاح');
        redirect(route('annual-request.index'));
    }

    public function render()
    {
        return view('annual-request.create-livewire');
    }
}
