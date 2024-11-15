<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\Item;
use App\Models\ItemGroup;
use Illuminate\Support\Facades\Gate;
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
            'item_group_id' => ['required'],
        ]);
        // إنشاء المادة
        $item = Item::create([
            'name' => $validatedData['name'],
            'description' => $validatedData['description'],
            'item_group_id' => $validatedData['item_group_id'],
        ]);

        return redirect()->route('items.index');
    }
}
