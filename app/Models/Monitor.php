<?php

namespace App\Models;

use App\Enums\MonitorStatus;
use App\Enums\MonitorType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Monitor extends Model
{
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($monitor) {
            $monitor->uuid = (string) Str::uuid();
            $monitor->uptime_status = MonitorStatus::Down;
        });
    }

    protected $fillable = [
        "user_id",
        "name",
        "type",
        "uptime_status",
        "check_interval_minutes",
        "last_checked_at",
        "url",
        "method",
        "body",
        "headers",
        "port",
        "keyword",
        "keyword_case_sensitive",
        "heartbeat_grace_period_in_minutes",
    ];

    protected $casts = [
        "type" => MonitorType::class,
        "uptime_status" => MonitorStatus::class,
        "last_checked_at" => "datetime",
        "headers" => "json",
        "keyword_case_sensitive" => "boolean",
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function checkLogs(): HasMany
    {
        return $this->hasMany(CheckLog::class);
    }

    public function statusPages(): BelongsToMany
    {
        return $this->belongsToMany(StatusPage::class, "monitor_status_page");
    }
    public function alertContacts(): BelongsToMany
    {
        return $this->belongsToMany(AlertContact::class);
    }
    public function maintenanceWindows(): BelongsToMany
    {
        return $this->belongsToMany(MaintenanceWindow::class);
    }
}
