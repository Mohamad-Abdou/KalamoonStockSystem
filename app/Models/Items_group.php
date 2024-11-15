<?php


namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Items_group extends Model
{
    public $timestamps = false;
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
    ];

    // ربط المواد بالمجموعات
    public function items()
    {
        return $this->hasMany(Item::class, 'items_groups_id');
    }

    // ربط المستخدمين بالمجموعات
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'items_group_user');
    }
}
