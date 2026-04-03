<div class="flex gap-1 overflow-x-auto pb-2">
    @foreach ($randomSpaces as $index => $space)
        @php
            $spaceType = str($space['component'])->after('space.')->toString();
            $wrapperClasses = collect([
                'animated-banner__space',
                'space',
                $spaceType,
            ])->filter()->implode(' ');
        @endphp

        <div
            wire:key="animated-banner-space-{{ $index }}"
            class="{{ $wrapperClasses }}"
            style="width: 80px; height: 125px; flex: 0 0 auto;"
        >
            <x-dynamic-component
                :component="$space['component']"
                :name="$space['name'] ?? null"
                :price="$space['price'] ?? null"
                :color="$space['color'] ?? null"
                :icon="$space['icon'] ?? null"
                :instructions="$space['instructions'] ?? null"
                :red="$space['red'] ?? false"
                :blue="$space['blue'] ?? false"
                :orange="$space['orange'] ?? false"
            >
                @isset($space['slot'])
                    {!! $space['slot'] !!}
                @endisset
            </x-dynamic-component>
        </div>
    @endforeach
</div>
