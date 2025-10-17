<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AlertContact extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = ["user_id", "name", "type", "value"];
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    /**
     * Permet à Laravel d'envoyer des notifications à cette adresse email.
     */
    public function routeNotificationForMail()
    {
        return $this->value;
    }
    public function monitors(): BelongsToMany
    {
        return $this->belongsToMany(Monitor::class);
    }
}
