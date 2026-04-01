@props([
  'name',
  'icon',
  'price'
])

<div class="container">
  <div class="name">{{ $name }}</div>
  <i class="drawing fa {{ $icon }}"></i>
  <div class="price">Price {{ $price }}</div>
</div>