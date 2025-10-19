<x-filament-panels::page>
    {{-- Form for updating team name --}}
    <form wire:submit.prevent="save" class="mb-8">
        {{ $this->form }}

        <div class="mt-6">
            <x-filament::button type="submit">
                Save Team Name
            </x-filament::button>
        </div>
    </form>

    {{-- Section for Team Members --}}
    <x-filament::section>
        <x-slot name="heading">
            Team Members
        </x-slot>

        <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
            <table class="min-w-full divide-y-2 divide-gray-200 bg-white text-sm dark:divide-gray-700 dark:bg-gray-900">
                <thead class="text-left">
                    <tr>
                        <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900 dark:text-white">Name</th>
                        <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900 dark:text-white">Email</th>
                        <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900 dark:text-white">Role</th>
                        <th class="px-4 py-2">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($members as $member)
                        <tr>
                            <td class="whitespace-nowrap px-4 py-2 font-medium text-gray-900 dark:text-white">{{ $member->name }}</td>
                            <td class="whitespace-nowrap px-4 py-2 text-gray-700 dark:text-gray-200">{{ $member->email }}</td>
                            <td class="whitespace-nowrap px-4 py-2 text-gray-700 dark:text-gray-200">
                                {{ $member->pivot->role }}
                                @if ($member->pivot->role === 'member')
                                    <button
                                        wire:click="updateMemberRole({{ $member->id }}, 'owner')"
                                        wire:confirm="Change {{ $member->name }}'s role to Owner?"
                                        class="ml-2 inline-flex items-center gap-2 rounded-lg bg-blue-600 px-3 py-1 text-xs font-medium text-white hover:bg-blue-700"
                                    >
                                        Make Owner
                                    </button>
                                @else
                                    <button
                                        wire:click="updateMemberRole({{ $member->id }}, 'member')"
                                        wire:confirm="Change {{ $member->name }}'s role to Member?"
                                        class="ml-2 inline-flex items-center gap-2 rounded-lg bg-green-600 px-3 py-1 text-xs font-medium text-white hover:bg-green-700"
                                    >
                                        Make Member
                                    </button>
                                @endif
                            </td>
                            <td class="whitespace-nowrap px-4 py-2">
                                <button
                                    wire:click="removeMember({{ $member->id }})"
                                    wire:confirm="Are you sure you want to remove {{ $member->name }} from this team?"
                                    class="inline-flex items-center gap-2 rounded-lg bg-red-600 px-4 py-2 text-xs font-medium text-white hover:bg-red-700"
                                >
                                    Remove
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-gray-500 dark:text-gray-400">
                                No members in this team yet.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-filament::section>
</x-filament-panels::page>
