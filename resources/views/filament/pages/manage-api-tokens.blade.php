<x-filament-panels::page>
    <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
        <table class="min-w-full divide-y-2 divide-gray-200 bg-white text-sm dark:divide-gray-700 dark:bg-gray-900">
            <thead class="text-left">
                <tr>
                    <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900 dark:text-white">Token Name</th>
                    <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900 dark:text-white">Created At</th>
                    <th class="px-4 py-2"></th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse ($tokens as $token)
                    <tr>
                        <td class="whitespace-nowrap px-4 py-2 font-medium text-gray-900 dark:text-white">{{ $token->name }}</td>
                        <td class="whitespace-nowrap px-4 py-2 text-gray-700 dark:text-gray-200">{{ $token->created_at->toFormattedDateString() }}</td>
                        <td class="whitespace-nowrap px-4 py-2">
                            <button 
                                wire:click="deleteToken({{ $token->id }})"
                                wire:confirm="Are you sure you want to delete this token? This action cannot be undone."
                                class="inline-flex items-center gap-2 rounded-lg bg-red-600 px-4 py-2 text-xs font-medium text-white hover:bg-red-700"
                            >
                                Delete
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center py-4 text-gray-500 dark:text-gray-400">
                            No API tokens found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</x-filament-panels::page>
