@props([
    'name',
    'price',
    'color',
])

<div class="container">
    <div class="color-bar {{ $color }}"></div>
    <div class="name flex justify-center">{{ $name }}</div>
    <div class="price">Price {{ $price }}</div>
</div>