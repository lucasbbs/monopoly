<div>
    <livewire:animated-banner />
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
            <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />
        </div>
    </div>
    <div class="h-full">

        <div>
            <div class="text-8xl flex items-center w-fit mx-auto">
                <flux:icon.dices class="size-16 text-red-500" variant="mini" /> MONOPOLY
                <flux:icon.dices class="size-16 text-red-500" variant="mini" />
            </div>
            <span class="text-gray-500 flex w-fit m-auto">Create a new game to get started</span>

            <div class="w-1/3 mx-auto card bg-base-100 card-xs shadow-lg mt-4">
                <div class="card-body p-0">
                    <div class="bg-red-500 text-white flex text-3xl w-full card-title rounded-t-xl p-3">
                        <flux:icon.plus class="size-10" variant="mini" /> <span>New Game</span>
                    </div>
                    <div class="space-y-4 p-4 rounded-b-xl">
                        <x-input.text class="w-full" placeholder="Game Name" />
                        <x-select class="w-full">
                            <option value="" disabled selected>Number of Players</option>
                            @foreach(range(2, 12) as $number)
                            <option value="{{ $number }}">{{ $number }}</option>
                            @endforeach
                        </x-select>
                        <x-button type="submit" class="w-full flex justify-center gap-2 text-lg">
                            <flux:icon.dices class="size-6 text-white" variant="mini" />    
                            <span>Create Game</span>
                        </x-button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>