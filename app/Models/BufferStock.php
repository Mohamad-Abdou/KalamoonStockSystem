<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BufferStock extends Model
{
    protected $table = 'buffer_stock';
    protected $fillable = ['item_id', 'quantity'];
    
    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id')->with('Item_group');
    }
}
