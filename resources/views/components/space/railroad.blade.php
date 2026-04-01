@props([
  'name',
  'price'
])

<div class="container">
  <div class="name">{{ $name }}</div>
  <i class="drawing fa fa-subway"></i>
  <div class="price">Price {{ $price }}</div>
</div>