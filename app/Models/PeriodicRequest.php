<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class PeriodicRequest extends Model
{
    use HasFactory;

    protected $guarded = [];


    public function rejectRequest()
    {
        $this->state = 0;
        $this->save();
    }

    public function forwardRequest()
    {
        $current = $this->state;

        $currentFlow = RequestFlow::where('request_type', 1)
            ->where('user_id', $current)
            ->first();
        Log::info('Current flow determined', ['current_flow' => $currentFlow]);
        if (!$currentFlow) {
            $nextFlow = RequestFlow::where('request_type', 1)
                ->orderBy('order')
                ->first();
        } else {
            $nextFlow = RequestFlow::where('request_type', 1)
                ->where('order', '>', $currentFlow->order)
                ->orderBy('order')
                ->first();
        }
        Log::info('Next flow determined', ['next_flow' => $nextFlow]);

        if ($nextFlow && $nextFlow->user_id == $this->user_id) {
            $nextFlow = RequestFlow::where('request_type', 1)
                ->where('order', '>', $nextFlow->order)
                ->orderBy('order')
                ->first();
        }

        if (!$nextFlow) {
            Log::info('Request returned to draft state', ['request_id' => $this->id]);
            $this->update(['state' => 2]);
        } else {
            $this->update([
                'state' => $nextFlow->user_id,
            ]);
        }
    }

    public function getRequestStateTextAttribute()
    {
        if ($this->state === 0) {
            return $this->rejection_reason;
        } elseif ($this->state === -1) return ' منفذ بتاريخ: ' . $this->updated_at;
        else return 'قيد الدراسة';
    }

    //Relations
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
