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
    public $sortable = ['name', 'item_groups_id'];
    use Sortable;
    protected $fillable = [
        'name',
        'descripton',
        'items_groups_id',
    ];

    // ربط مودل المجموعات
    public function Items_group()
    {
        return $this->belongsTo(items_group::class, 'items_groups_id');
    }
    
}
