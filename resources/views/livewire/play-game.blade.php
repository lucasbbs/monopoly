<x-layouts::app :title="__('Monopoly')">
  <div class="table" data-monopoly-game>
    <section class="token-toolbar" aria-label="Monopoly token controls">
      <label class="token-toolbar__field" for="token-picker">
        <span>Choose a token</span>
        <select id="token-picker" data-token-picker></select>
      </label>

      <label class="token-toolbar__field token-toolbar__field--range" for="token-space">
        <span>Board space</span>
        <input id="token-space" type="range" min="0" max="39" value="0" data-token-space>
      </label>

      <div class="token-toolbar__actions">
        <button type="button" data-token-move="-1">Previous space</button>
        <output data-token-space-label for="token-space">Go (0)</output>
        <button type="button" data-token-move="1">Next space</button>
      </div>
    </section>

    <div class="board" data-monopoly-board>
      <div class="center">
        <!-- <x-space.deck type="community-chest" />
        <h1 class="title">MONOPOLY</h1>
        <x-space.deck type="chance" /> -->
        <livewire:trade />
        <livewire:dice-tray />
      </div>

      <div class="space corner go" data-space="0">
        <x-space.go />
      </div>

      <div class="row horizontal-row bottom-row">
        <div class="space property" data-space="9">
          <x-space.property name="Connecticut Avenue" price="$120" color="light-blue" />
        </div>
        <div class="space property" data-space="8">
          <x-space.property name="Vermont Avenue" price="$100" color="light-blue" />
        </div>
        <div class="space chance" data-space="7">
          <x-space.chance red />
        </div>
        <div class="space property" data-space="6">
          <x-space.property name="Oriental Avenue" price="$100" color="light-blue" />
        </div>
        <div class="space railroad" data-space="5">
          <x-space.railroad name="Reading Railroad" price="$200" />
        </div>
        <div class="space fee income-tax" data-space="4">
          <x-space.fee name="Income Tax" instructions="Pay 10%<br>or<br>$200">
            <div class="diamond"></div>
          </x-space.fee>
        </div>
        <div class="space property" data-space="3">
          <x-space.property name="Baltic Avenue" price="$50" color="dark-purple" />
        </div>
        <div class="space community-chest" data-space="2">
          <x-space.community-chest />
        </div>
        <div class="space property" data-space="1">
          <x-space.property name="Mediterranean Avenue" price="$50" color="dark-purple" />
        </div>
      </div>

      <div class="space corner jail" data-space="10">
        <x-space.jail />
      </div>

      <div class="row vertical-row left-row">
        <div class="space property" data-space="19">
          <x-space.property name="New York Avenue" price="$200" color="orange" />
        </div>
        <div class="space property" data-space="18">
          <x-space.property name="Tennessee Avenue" price="$180" color="orange" />
        </div>
        <div class="space community-chest" data-space="17">
          <div class="container">
            <div class="name">Community Chest</div>
            <i class="drawing fa fa-cube"></i>
            <div class="instructions">Follow instructions on top card</div>
          </div>
        </div>
        <div class="space property" data-space="16">
          <x-space.property name="St. James Avenue" price="$180" color="orange" />
        </div>
        <div class="space railroad" data-space="15">
          <x-space.railroad name="Pennsylvania Railroad" price="$200" />
        </div>
        <div class="space property" data-space="14">
          <x-space.property name="Virginia Avenue" price="$160" color="purple" />
        </div>
        <div class="space property" data-space="13">
          <x-space.property name="States Avenue" price="$140" color="purple" />
        </div>
        <div class="space utility electric-company" data-space="12">
          <x-space.utility name="Electric Company" icon="fa-lightbulb-o" price="$150" />
        </div>
        <div class="space property" data-space="11">
          <x-space.property name="St. Charles Place" price="$140" color="purple" />
        </div>
      </div>

      <div class="space corner free-parking" data-space="20">
        <x-space.free-parking />
      </div>

      <div class="row horizontal-row top-row">
        <div class="space property" data-space="21">
          <x-space.property name="Kentucky Avenue" price="$220" color="red" />
        </div>
        <div class="space chance" data-space="22">
          <x-space.chance blue />
        </div>
        <div class="space property" data-space="23">
          <x-space.property name="Indiana Avenue" price="$220" color="red" />
        </div>
        <div class="space property" data-space="24">
          <x-space.property name="Illinois Avenue" price="$200" color="red" />
        </div>
        <div class="space railroad" data-space="25">
          <x-space.railroad name="B & O Railroad" price="$200" />
        </div>
        <div class="space property" data-space="26">
          <x-space.property name="Atlantic Avenue" price="$260" color="yellow" />
        </div>
        <div class="space property" data-space="27">
          <x-space.property name="Ventnor Avenue" price="$260" color="yellow" />
        </div>
        <div class="space utility waterworks" data-space="28">
          <x-space.utility name="Waterworks" icon="fa-tint" price="$120" />
        </div>
        <div class="space property" data-space="29">
          <x-space.property name="Marvin Gardens" price="$280" color="yellow" />
        </div>
      </div>

      <div class="space corner go-to-jail" data-space="30">
        <x-space.go-to-jail />
      </div>
      <div class="row vertical-row right-row">
        <div class="space property" data-space="31">
          <x-space.property name="Pacific Avenue" price="$300" color="green" />
        </div>
        <div class="space property" data-space="32">
          <x-space.property name="North Carolina Avenue" price="$300" color="green" />
        </div>
        <div class="space community-chest" data-space="33">
          <x-space.community-chest />
        </div>
        <div class="space property" data-space="34">
          <x-space.property name="Pennsylvania Avenue" price="$320" color="green" />
        </div>
        <div class="space railroad" data-space="35">
          <x-space.railroad name="Short Line" price="$200" />
        </div>
        <div class="space chance" data-space="36">
          <x-space.chance orange />
        </div>
        <div class="space property" data-space="37">
          <x-space.property name="Park Place" price="$350" color="dark-blue" />
        </div>
        <div class="space fee luxury-tax" data-space="38">
          <x-space.fee name="Luxury Tax" instructions="Pay $75.00">
            <i class="drawing fa fa-diamond"></i>
          </x-space.fee>
        </div>
        <div class="space property" data-space="39">
          <x-space.property name="Boardwalk" price="$400" color="dark-blue" />
        </div>
      </div>
    </div>
  </div>
</x-layouts::app>
