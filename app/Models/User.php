<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'role',
        'email',
        'password',
        'office_number'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function itemGroups(): BelongsToMany
    {
        return $this->belongsToMany(ItemGroup::class, 'item_group_user');
    }


    public function getIsAdminAttribute(): bool
    {
        return $this->type == 0;
    }

    public function getActiveRequest()
    {
        return $this->annualRequests()->where('state', 2)->first() ?? null;
    }
    public static $usersTypes = [
        0 => 'مدير النظام',
        1 => 'أمين المستودع',
        2 => 'أمين الجامعة',
        3 => 'المدير المالي',
        4 => 'مستخدم',
    ];

    // $requestFlow->request_type_text
    public function getUserTypeTextAttribute()
    {
        return self::$usersTypes[$this->type] ?? 'Unknown Type';
    }

    public static function withAssociatedGroups()
    {
        return self::with('itemGroups')
            ->where('type', '>', 1) // إزالة مدير النظام وأمين المستودع
            ->get()
            ->map(function ($user) {
                $user->group_ids = $user->groups ? $user->groups->pluck('id')->toArray() : [];
                return $user;
            });
    }

    public function getIsPartOfTheAnnualFlowAttribute()
    {
        return RequestFlow::where('request_type', 0)->where('user_id', $this->id)->exists();
    }
    
    public function getIsPartOfThePeriodicFlowAttribute()
    {
        return RequestFlow::where('request_type', 1)->where('user_id', $this->id)->exists();
    }
    
    
    // Relations
    public function annualRequests(): HasMany
    {
        return $this->hasMany(AnnualRequest::class)->orderBy('created_at', 'desc');
    }

    public function periodicRequests(): HasMany
    {
        return $this->hasMany(PeriodicRequest::class)->orderBy('created_at', 'desc');
    }

    public function items()
    {
        return Item::whereIn('item_group_id', $this->itemGroups->pluck('id'))->where('active', 1)->get();
    }
}
