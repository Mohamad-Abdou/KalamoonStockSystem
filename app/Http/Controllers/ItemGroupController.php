<?php

namespace App\Http\Controllers;

use App\Models\ItemGroup;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ItemGroupController extends Controller
{
    use AuthorizesRequests;
    public function __construct()
    {
        $this->authorizeResource(ItemGroup::class);
    }
    public function index()
    {
        return view('item_groups.index');
    }

    public function store(Request $request)
    {
        //التحقق من المدخلات 
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:' . ItemGroup::class],
        ]);

        // إنشاء المجموعة
        $group = ItemGroup::create([
            'name' => $request->name,
        ]);

        
        return redirect()->back();
    }
}
