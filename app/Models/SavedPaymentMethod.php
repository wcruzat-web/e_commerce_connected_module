<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class SavedPaymentMethod extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'payment_method_id';

    protected $fillable = [
        'customer_id', 'payment_type', 'provider', 'account_name',
        'masked_account_number', 'expiry_date', 'is_default', 'status',
    ];

    protected $casts = [
        'expiry_date' => 'date',
        'is_default' => 'boolean',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    protected function expiryLabel(): Attribute
    {
        return Attribute::get(fn () => $this->expiry_date?->format('m/y'));
    }
}
