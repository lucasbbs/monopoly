@php
    $color = null;
    function getBorderColor($colorGroup) : string {
        switch ($colorGroup) {
            case 'dark-purple':
                $color = 'border-purple-800';
                break;

            case 'light-blue':
                $color = 'border-blue-300';
                break;

            case 'purple':
                $color = 'border-purple-600';
                break;

            case 'orange':
                $color = 'border-orange-400';
                break;

            case 'red':
                $color = 'border-red-600';
                break;

            case 'yellow':
                $color = 'border-yellow-400';
                break;

            case 'green':
                $color = 'border-green-600';
                break;

            case 'light-gray':
                $color = 'border-gray-500';
                break;

            case 'dark-blue':
                $color = 'border-blue-900';
                break;

            case 'white':
                $color = 'border-gray-100';
                break;
        }
        return $color;
    }
@endphp

<div class="flex items-start gap-8">
    <div class="flex-none">
        @foreach ($properties as $colorGroup => $propertyGroup)
            <div class="mb-4 w-fit">
                <div class="grid grid-cols-4 gap-4">
                    @foreach ($propertyGroup as $property)
                        <div class="border {{ getBorderColor($colorGroup) }} p-4 rounded hover:bg-gray-100 cursor-pointer">
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>

    <div class="flex-none">
        <h1 class="text-2xl font-bold">Trade</h1>
        <span>Mouse over a card to see details about it</span>
    </div>

    <div class="flex-none">
        @foreach ($properties as $colorGroup => $propertyGroup)
            <div class="mb-4 w-fit">
                <div class="grid grid-cols-4 gap-4">
                    @foreach ($propertyGroup as $property)
                        <div class="border {{ getBorderColor($colorGroup) }} p-4 rounded hover:bg-gray-100 cursor-pointer">
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</div>