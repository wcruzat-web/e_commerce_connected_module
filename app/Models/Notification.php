<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notification extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'notification_id';

    protected $fillable = [
        'customer_id', 'title', 'message', 'notification_type',
        'icon', 'reference_type', 'reference_id', 'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    protected function defaultIconKey(): Attribute
    {
        return Attribute::get(fn () => match ($this->notification_type) {
            'Order' => 'order',
            'Payment' => 'payment',
            'Promotion' => 'promotion',
            'System' => 'system',
            default => 'system',
        });
    }
}
