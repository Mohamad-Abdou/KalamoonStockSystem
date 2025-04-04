<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AnnualRequestItem extends Model
{
    protected $table = 'annual_request_item';

    public function annualRequest()
    {
        return $this->belongsTo(AnnualRequest::class);
    }
}
