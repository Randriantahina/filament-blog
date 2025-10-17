<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class MaintenanceWindow extends Model
{
    use HasFactory;

    protected $fillable = ["name", "description", "starts_at", "ends_at"];

    protected $casts = [
        "starts_at" => "datetime",
        "ends_at" => "datetime",
    ];

    public function monitors(): BelongsToMany
    {
        return $this->belongsToMany(Monitor::class);
    }
}
