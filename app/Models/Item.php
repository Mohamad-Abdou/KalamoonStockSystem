<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Guard;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Item extends Model
{
    use HasFactory;

    // لتفعيل الترتيب
    public $sortable = ['name', 'item_group_id'];
    use Sortable;
    protected $fillable = [
        'name',
        'description',
        'unit',
        'item_group_id',
    ];

    // ربط مودل المجموعات
    public function Item_group()
    {
        return $this->belongsTo(ItemGroup::class, 'item_group_id');
    }
}
