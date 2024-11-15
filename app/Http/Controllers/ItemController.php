<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\Item;
use App\Models\Items_group;
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
    /**
     * عرض صفحة إدارة المواد
     */
    public function index()
    {
        $groups = Items_group::all();
        return view('items.index', ['groups' => $groups]);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //التحقق من المدخلات 
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'descripton' => ['nullable', 'string', 'max:1000'],
            'items_groups_id' => ['required'],
        ]);
        // إنشاء المادة
        $item = Item::create([
            'name' => $validatedData['name'],
            'descripton' => $validatedData['descripton'],
            'items_groups_id' => $validatedData['items_groups_id'],
        ]);
        
        return redirect()->route('items.index');
    }
}
