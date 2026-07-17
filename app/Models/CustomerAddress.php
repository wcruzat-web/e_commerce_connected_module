<?php
// [CRUZAT] Base CustomerAddress model
// [AGNER]  Added SoftDeletes, HasFactory, fullAddress accessor

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerAddress extends Model
{
    // [AGNER] Added HasFactory, SoftDeletes
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'address_id';

    // [CRUZAT] Original fillable
    protected $fillable = [
        'customer_id', 'address_type', 'recipient_name', 'phone_number',
        'street', 'barangay', 'city', 'province', 'postal_code', 'country', 'is_default',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    // [CRUZAT]
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    // [AGNER]
    protected function fullAddress(): Attribute
    {
        return Attribute::get(function () {
            $parts = array_filter([
                $this->street,
                $this->barangay,
                $this->city,
                $this->province,
                $this->postal_code,
                $this->country,
            ]);

            return implode(', ', $parts);
        });
    }
}
