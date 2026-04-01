@props([
  'name',
  'instructions'
  ])

<div class="container">
  <div class="name">{{ $name }}</div>
  {{ $slot }}
  <div class="instructions">{!! $instructions !!}</div>
</div>