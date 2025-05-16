<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TemporaryRequest extends Model
{
    protected $fillable = [
        'user_id',
        'item_id',
        'quantity',
        'reason',
        'state',
    ];

    public static $stateText = [
        1 => 'قيد المراجعة',
        2 => 'منفذ',
        -1 => 'مرفوض',
    ];
    
    public function getStateTextAttribute()
    {
        return self::$stateText[$this->state] ?? 'Unknown State';
    }
    
    public function user()
    {
        return $this->belongsTo(user::class);
    }



    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
