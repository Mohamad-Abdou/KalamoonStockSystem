<?php

namespace App\Http\Controllers;

use App\Models\Items_group;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ItemsGroupController extends Controller
{
    use AuthorizesRequests;
    public function __construct()
    {
        $this->authorizeResource(Items_group::class);
    }
    public function index()
    {
        return view('items_groups.index');
    }

    public function store(Request $request)
    {
        //التحقق من المدخلات 
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:' . Items_group::class],
        ]);

        // إنشاء المجموعة
        $group = Items_group::create([
            'name' => $request->name,
        ]);
        
        return redirect()->back();
    }
}
