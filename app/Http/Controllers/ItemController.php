<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\Item;
use App\Models\ItemGroup;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as RoutingController;



class ItemController extends RoutingController
{
    use AuthorizesRequests;
    public function __construct()
    {
        $this->authorizeResource(Item::class);
    }

    public function index()
    {
        $groups = ItemGroup::all();
        return view('items.index', ['groups' => $groups]);
    }

    public function store(Request $request)
    {
        //التحقق من المدخلات 
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'unit' => ['string', 'max:100'],
            'item_group_id' => ['required'],
        ]);

        $exists = Item::where('name', $validatedData['name'])
            ->where('description', $validatedData['description'])
            ->exists();

        if ($exists) {
            session()->flash('message', 'يوجد مادة بنفس الاسم والوصف');
            return redirect()->back()->withInput();
        }
        // إنشاء المادة
        Item::create([
            'name' => $validatedData['name'],
            'description' => $validatedData['description'],
            'unit' => $validatedData['unit'],
            'item_group_id' => $validatedData['item_group_id'],
        ]);

        return redirect()->back();
    }
}
