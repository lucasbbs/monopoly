<div class="dice-container">
    <div class="dice-inner-container">
        <x-dice :value="$value1" id="dice-1" />
        <x-dice :value="$value2" id="dice-2" />
    </div>
    <button type="button" id="roll-all-dice" wire:click="roll">Roll Dices</button>
</div>
