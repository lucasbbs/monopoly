<div class="flex gap-1">
    @foreach ($randomSpaces as $index => $space)
        <div wire:key="animated-banner-space-{{ $index }}">
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
