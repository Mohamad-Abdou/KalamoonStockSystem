<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\ItemGroup;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class UserGroupTable extends Component
{
    use AuthorizesRequests;

    public $users;

    public function mount()
    {
        // تحميل المستخدمين مع المجموعات
        $this->users = User::withAssociatedGroups();
    }

    public function toggleGroupAssociation($userId, $groupId)
    {
        $this->authorize('update', ItemGroup::class);
        $user = User::find($userId);
        $isAssociated = $user->itemGroups()->wherePivot('item_group_id', $groupId)->exists();
        // فحص حالة العلاقة
        if ($isAssociated) {
            // إزالة العلااقة في حال وجودها
            $user->itemGroups()->detach($groupId);
        } else {
            // ربط المستخدم مع المجموعة
            $user->itemGroups()->attach($groupId);
        }
    }

    public function confirmDeleteGroup($groupId)
    {
        $this->authorize('delete', ItemGroup::class);
        $group = ItemGroup::find($groupId);
        if ($group->items()->count() > 0) {
            $this->dispatch('showMessage', 'عملية غير ناجحة', 'لا يمكن حذف هذه المجموعة لوجود مواد ضمنها، يرجى إزالة جميع المواد ثم إعادة المحاولة');
            return;
        }   
        
        $group->delete();
    }

    public function render()
    {
        $this->users = User::withAssociatedGroups();
        return view('livewire.user-group-table', [
            'groups' => ItemGroup::all()
        ]);
    }
}
