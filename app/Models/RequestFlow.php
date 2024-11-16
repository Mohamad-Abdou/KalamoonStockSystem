<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestFlow extends Model
{
    use HasFactory;
    public $timestamps = false;

    public static $requestTypes = [
        0 => 'الطلب السنوي',
        1 => 'الطلب الدوري',
    ];

    // $requestFlow->request_type_text
    public function getRequestTypeTextAttribute()
    {
        return self::$requestTypes[$this->request_type] ?? 'Unknown Type';
    }

}
