<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppConfiguration extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'key', 'value'];

    // You can create a method to retrieve the annual request period
    public static function getAnnualRequestPeriod()
    {
        return [
            'start' => self::where('name', 'AnnualRequestPeriod')->where('key', 'start')->value('value'),
            'end' => self::where('name', 'AnnualRequestPeriod')->where('key', 'end')->value('value'),
        ];
    }
}
