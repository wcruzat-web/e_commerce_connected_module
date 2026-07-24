<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebhookLog extends Model
{
    protected $fillable = [
        'target_module',
        'event',
        'payload',
        'response_status',
        'response_body',
        'success',
    ];

    protected function casts(): array
    {
        return [
            'payload' => 'array',
        ];
    }
}
