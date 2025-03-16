<?php

namespace App\Livewire\Users;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class UsersList extends Component
{
    public $searchByRole;
    use WithPagination;

    public $usersTypes = [
        1 => 'أمين المستودع',
        2 => 'أمين الجامعة',
        3 => 'المدير المالي',
        4 => 'مستخدم',
    ];

    protected $rules = [
        'newUser.name' => 'required|min:3|unique:users,name|regex:/^[a-zA-Z]+\.[a-zA-Z]+$/',
        'newUser.role' => 'required|min:2|unique:users,role',
        'newUser.office_number' => 'required|numeric'
    ];

    protected $messages = [
        'newUser.name.required' => 'اسم المستخدم مطلوب',
        'newUser.name.min' => 'اسم المستخدم يجب أن يكون 3 أحرف على الأقل',
        'newUser.name.unique' => 'اسم المستخدم موجود مسبقاً',
        'newUser.role.unique' => 'يوجد حساب لهذه الجهةة بالفعل',
        'newUser.name.regex' => 'يجب أن يكون اسم المستخدم بالصيغة: اسم.كنية',
        'newUser.role.required' => 'الجهة مطلوبة',
        'newUser.role.min' => 'الجهة يجب أن تكون حرفين على الأقل',
        'newUser.office_number.required' => 'رقم الهاتف الداخلي مطلوب',
        'newUser.office_number.numeric' => 'رقم الهاتف الداخلي يجب أن يكون رقماً'
    ];

    public $newUser = [
        'name' => '',
        'role' => '',
        'office_number' => ''
    ];

    public $isEditModalOpen = false;

    public $editingUser = [
        'id' => '',
        'name' => '',
        'office_number' => ''
    ];

    protected $editRules = [
        'editingUser.name' => 'required|min:3|unique:users,name',
        'editingUser.office_number' => 'required|numeric'
    ];

    public function mount()
    {
        $this->authorize('create', User::class);
    }

    public function updatedSearchByRole()
    {
        $this->resetPage();
    }

    public function editUser($userId)
    {
        $user = User::find($userId);
        $this->editingUser = [
            'id' => $user->id,
            'name' => $user->name,
            'office_number' => $user->office_number
        ];
        $this->isEditModalOpen = true;
    }

    public function updateUser()
    {
        $this->authorize('update', User::class);
        $this->validate($this->editRules);
        $user = User::find($this->editingUser['id']);
        $user->update([
            'name' => $this->editingUser['name'],
            'email' => $this->editingUser['name'] . '@uok.edu.sy',
            'office_number' => $this->editingUser['office_number']
        ]);
        $this->isEditModalOpen = false;
        $this->reset('editingUser');
        $this->dispatch('showMessage', 'عملية ناجحة','تم تعديل المستخدم بنجاح');
    }

    public function closeEditModal()
    {
        $this->isEditModalOpen = false;
        $this->reset('editingUser');
    }

    public function saveNewUser()
    {
        $this->validate();
        $this->authorize('create', User::class);
        User::create([
            'name' => $this->newUser['name'],
            'email' => $this->newUser['name'] . '@uok.edu.sy',
            'password' => env('DEFAULT_PASSWORD'),
            'role' => $this->newUser['role'],
            'office_number' => $this->newUser['office_number'],
            'type' => 4 
        ]);
        $this->reset();
    }

    public function render()
    {

        return view('livewire.users.users-list', ['users' => User::where('role', 'like', '%' . $this->searchByRole . '%')->paginate(10)]);
    }
}
