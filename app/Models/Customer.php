<?php
// AGNER — Customer model: copied from _new_auth

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Authenticatable
{
    // [AGNER] Added HasFactory, Notifiable, SoftDeletes
    use HasFactory, Notifiable, SoftDeletes;

    protected $primaryKey = 'customer_id';

    // [CRUZAT] Original fillable
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'phone_number',
        'profile_picture',
        'status',
        'email_verified_at',
        'last_login',
        'role',
    ];

    // [AGNER] Added remember_token
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'email_verified_at' => 'datetime',
            'last_login' => 'datetime',
        ];
    }

    // [CRUZAT]
    public function carts()
    {
        return $this->hasMany(Cart::class, 'customer_id', 'customer_id');
    }

    // [CRUZAT]
    public function addresses(): HasMany
    {
        return $this->hasMany(CustomerAddress::class, 'customer_id');
    }

    // [AGNER]
    public function paymentMethods(): HasMany
    {
        return $this->hasMany(SavedPaymentMethod::class, 'customer_id');
    }

    // [AGNER]
    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class, 'customer_id');
    }

    // [AGNER]
    public function wishlistItems(): HasMany
    {
        return $this->hasMany(WishlistItem::class, 'customer_id');
    }

    // [AGNER]
    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class, 'customer_id');
    }

    // [CRUZAT]
    public function orders()
    {
        return $this->hasMany(Order::class, 'customer_id', 'customer_id');
    }

    // [AGNER]
    public function settings(): HasMany
    {
        return $this->hasMany(UserSetting::class, 'customer_id');
    }

    // [AGNER]
    protected function fullName(): Attribute
    {
        return Attribute::get(fn () => trim($this->first_name.' '.$this->last_name));
    }

    public function getProfilePictureUrlAttribute(): ?string
    {
        if (!$this->profile_picture) return null;
        if (str_starts_with($this->profile_picture, 'http')) return $this->profile_picture;
        return asset($this->profile_picture);
    }
}
