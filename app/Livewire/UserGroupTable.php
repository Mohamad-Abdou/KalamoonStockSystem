<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\Items_group;
use Illuminate\Contracts\Session\Session;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class UserGroupTable extends Component
{
    use AuthorizesRequests;

    public $users;

    public function mount()
    {
        // تحميل المستخدمين مع المجموعات
        $this->users = User::with('itemsGroups')
            ->where('type', '>', 1) // إزالة المستخدمين المميزين
            ->get()
            ->map(function ($user) {
                $user->group_ids = $user->groups ? $user->groups->pluck('id')->toArray() : [];
                return $user;
            });
    }

    public function toggleGroupAssociation($userId, $groupId)
    {
        $this->authorize('update', Items_group::class);
        $user = User::find($userId);
        $isAssociated = $user->itemsGroups()->wherePivot('items_group_id', $groupId)->exists();
        // فحص حالة العلاقة
        if ($isAssociated) {
            // إزالة العلااقة في حال وجودها
            $user->itemsGroups()->detach($groupId);
        } else {
            // ربط المستخدم مع المجموعة
            $user->itemsGroups()->attach($groupId);
        }

        $this->users = User::with('itemsGroups')
            ->where('type', '>', 1) // إزالة المستخدمين المميزين
            ->get()
            ->map(function ($user) {
                $user->group_ids = $user->groups ? $user->groups->pluck('id')->toArray() : [];
                return $user;
            });
    }

    public function confirmDeleteGroup($groupId)
    {
        $this->authorize('delete', Items_group::class);
        $group = Items_group::find($groupId);
        if ($group->items()->count() > 0) {
            $this->dispatch('showMessage', 'عملية غير ناجحة', 'لا يمكن حذف هذه المجموعة لوجود مواد ضمنها، يرجى إزالة جميع المواد ثم إعادة المحاولة');
            return;
        }   
        $group->delete();

        $this->users = User::with('itemsGroups')
        ->where('type', '>', 1) // Exclude premium users
        ->get()
        ->map(function ($user) {
            $user->group_ids = $user->groups ? $user->groups->pluck('id')->toArray() : [];
            return $user;
        });
    }

    public function render()
    {
        return view('livewire.user-group-table', [
            'groups' => Items_group::all()
        ]);
    }
}
