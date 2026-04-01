@props([
  'red' => null,
  'blue' => null,
  'orange' => null
  ])

<div class="container">
  <div class="name">Chance</div>
  <i @class(['drawing fa fa-question', 'text-red-500' => $red, 'text-blue-500' => $blue, 'text-orange-500' => $orange])></i>
</div>