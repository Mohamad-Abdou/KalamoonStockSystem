<?php

namespace App\Livewire;

use App\Models\Item;
use App\Models\ItemGroup;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Component;
use Livewire\WithPagination;

use function Laravel\Prompts\search;

class ItemTable extends Component
{
    use AuthorizesRequests;
    use WithPagination;

    public $search = '';
    public $groups;
    public $selectedGroup = null;

    // دالة لتبديل حالة المادة
    public function toggleState($id)
    {
        $this->authorize('update', Item::class);
        $item = Item::find($id);
        if ($item) {
            $item->active = !$item->active;
            $item->save();
        }
    }

    // تعيين المجموعة المختارة للتصفية
    public function filterByGroup($groupId)
    {
        $this->selectedGroup = $groupId;
        $this->resetPage(); // إعادة تعيين الصفحة عند الفلترة
    }

    // تحديث المجموعة الخاصة بالمادة
    public function updateItemGroup($itemId, $groupId)
    {
        $this->authorize('update', Item::class);

        $item = Item::find($itemId);
        if ($item) {
            $item->item_group_id = $groupId;
            $item->save();
            session()->flash('message', 'تم تحديث المجموعة بنجاح.');
        }
    }

    public function render()
    {
        $this->authorize('viewAny', Item::class);

        // جلب العناصر مع التصفية والبحث
        $query = Item::with('item_group')
            ->when($this->selectedGroup, function ($query) {
                return $query->where('item_group_id', $this->selectedGroup);
            })
            ->when($this->search, function ($query) {
                return $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('description', 'like', '%' . $this->search . '%');
                });
            });

        $items = $query->paginate(10);

        // جلب جميع المجموعات لعرضها في القائمة المنسدلة
        $groups = ItemGroup::all();

        return view('livewire.item-table', [
            'items' => $items,
            'groups' => $groups,
        ]);
    }
}
