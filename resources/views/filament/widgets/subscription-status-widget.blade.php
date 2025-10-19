<x-filament::widget>
    <x-filament::card>
        @php
            $status = $this->getTeamSubscriptionStatus();
            $teamName = $this->getTeamName();
            $billingPortalUrl = Auth::user()?->currentTeam?->redirectToBillingPortal(route('filament.admin.pages.dashboard'));
        @endphp

        @if ($status === 'not_subscribed')
            <div class="p-4 text-sm text-yellow-800 rounded-lg bg-yellow-50 dark:bg-gray-800 dark:text-yellow-300" role="alert">
                <span class="font-medium">Attention {{ $teamName }}!</span> Votre équipe n'est pas abonnée. <a href="{{ $billingPortalUrl }}" class="font-semibold underline hover:no-underline">Abonnez-vous maintenant</a> pour accéder à toutes les fonctionnalités.
            </div>
        @elseif ($status === 'on_trial')
            <div class="p-4 text-sm text-blue-800 rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-blue-300" role="alert">
                <span class="font-medium">Bonjour {{ $teamName }}!</span> Votre équipe est en période d'essai. Il vous reste X jours. <a href="{{ $billingPortalUrl }}" class="font-semibold underline hover:no-underline">Gérez votre abonnement</a>.
            </div>
        @elseif ($status === 'cancelled')
            <div class="p-4 text-sm text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-300" role="alert">
                <span class="font-medium">Alerte {{ $teamName }}!</span> Votre abonnement a été annulé. <a href="{{ $billingPortalUrl }}" class="font-semibold underline hover:no-underline">Réactivez-le</a> pour continuer à utiliser nos services.
            </div>
        @endif
    </x-filament::card>
</x-filament::widget>