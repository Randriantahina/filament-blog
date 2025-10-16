<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CheckLog extends Model
{
    protected $fillable = [
        'monitor_id',
        'status_code',
        'response_time_ms',
        'response_body_snippet',
        'error_message',
        'is_up',
    ];

    protected $casts = [
        'is_up' => 'boolean',
    ];

    public function monitor(): BelongsTo
    {
        return $this->belongsTo(Monitor::class);
    }
}
