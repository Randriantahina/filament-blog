<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;

class SubscriptionStatusWidget extends Widget
{
    protected static string $view = "filament.widgets.subscription-status-widget";

    protected int|string|array $columnSpan = "full";

    public function getTeamSubscriptionStatus(): ?string
    {
        $team = Auth::user()?->currentTeam;

        if (!$team) {
            return null;
        }

        if ($team->subscribed("default")) {
            return "subscribed";
        }

        if ($team->onTrial()) {
            return "on_trial";
        }

        if ($team->cancelled()) {
            return "cancelled";
        }

        dd($status); // Debugging line
        return 'not_subscribed';
    }

    public function getTeamName(): ?string
    {
        return Auth::user()?->currentTeam?->name;
    }
}
