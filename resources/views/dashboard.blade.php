<x-layouts::app :title="__('Dashboard')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl" x-data="{
        setupEchoConnections() {
            Echo.private('monopoly')
                .listen('GameLifecycleEvent', (event) => {
                    // TODO: Include lifecycle event for joining a game
                });
        }
    }">
        <div class="grid auto-rows-min gap-4 md:grid-cols-3">
            <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
            </div>
            <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
            </div>
            <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
            </div>
        </div>
        <table class="rounded-xl border border-neutral-200">
            <thead>
                <tr>
                    <th class="text-start w-fit"># of players</th>
                    <th class="text-start grow">Name</th>
                    <th class="text-start grow">Status</th>
                    <th class="text-start grow">Actions</th>
                </tr>
            </thead>
            @foreach($games as $game)
            <tr class="border-b border-neutral-200 p-4 last:border-0 dark:border-neutral-700">
                <td class="flex h-10 w-10 items-center justify-center rounded-full bg-gray-200 text-sm font-medium text-gray-600 dark:bg-neutral-700 dark:text-neutral-300">
                    {{ $game->players()->count() }}
                </td>
                <td class="text-sm font-medium text-gray-900 dark:text-white">
                    {{ $game->name }}
                </td>
                <td class="text-sm text-gray-500 dark:text-gray-400">
                    {{ $game->status }}
                </td>
                <td class="text-sm text-gray-500 dark:text-gray-400 flex gap-2">
                    <x-button.link href="{{ route('monopoly.show', $game) }}">
                        {{ __('View') }}
                    </x-button.link>
                    <form action="{{ route('monopoly.join', $game) }}" method="post">
                        @csrf
                        <x-button type="submit" method="post" class="text-gray-900 dark:text-white">
                            {{ __('Join') }}
                        </x-button>
                    </form>
                </td>
            </tr>
            @endforeach
        </table>
    </div>
</x-layouts::app>