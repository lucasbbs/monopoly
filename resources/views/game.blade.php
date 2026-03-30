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
        <div class="community-chest-deck">
          <h2 class="label">Community Chest</h2>
          <div class="deck"></div>
        </div>
        <h1 class="title">MONOPOLY</h1>
        <div class="chance-deck">
          <h2 class="label">Chance</h2>
          <div class="deck"></div>
        </div>
      </div>

      <div class="space corner go" data-space="0">
        <div class="container">
          <div class="instructions">Collect $200.00 salary as you pass</div>
          <div class="go-word">go</div>
        </div>
        <div class="arrow fa fa-long-arrow-left"></div>
      </div>

      <div class="row horizontal-row bottom-row">
        <div class="space property" data-space="9">
          <div class="container">
            <div class="color-bar light-blue"></div>
            <div class="name">Connecticut Avenue</div>
            <div class="price">PRICE $120</div>
          </div>
        </div>
        <div class="space property" data-space="8">
          <div class="container">
            <div class="color-bar light-blue"></div>
            <div class="name">Vermont Avenue</div>
            <div class="price">Price $100</div>
          </div>
        </div>
        <div class="space chance" data-space="7">
          <div class="container">
            <div class="name">Chance</div>
            <i class="drawing fa fa-question"></i>
          </div>
        </div>
        <div class="space property" data-space="6">
          <div class="container">
            <div class="color-bar light-blue"></div>
            <div class="name">Oriental Avenue</div>
            <div class="price">Price $100</div>
          </div>
        </div>
        <div class="space railroad" data-space="5">
          <div class="container">
            <div class="name">Reading Railroad</div>
            <i class="drawing fa fa-subway"></i>
            <div class="price">Price $200</div>
          </div>
        </div>
        <div class="space fee income-tax" data-space="4">
          <div class="container">
            <div class="name">Income Tax</div>
            <div class="diamond"></div>
            <div class="instructions">Pay 10%<br>or<br>$200</div>
          </div>
        </div>
        <div class="space property" data-space="3">
          <div class="container">
            <div class="color-bar dark-purple"></div>
            <div class="name">Baltic Avenue</div>
            <div class="price">Price $50</div>
          </div>
        </div>
        <div class="space community-chest" data-space="2">
          <div class="container">
            <div class="name">Community Chest</div>
            <i class="drawing fa fa-cube"></i>
            <div class="instructions">Follow instructions on top card</div>
          </div>
        </div>
        <div class="space property" data-space="1">
          <div class="container">
            <div class="color-bar dark-purple"></div>
            <div class="name three-line-name">Mediter-<br>ranean<br>Avenue</div>
            <div class="price">Price $50</div>
          </div>
        </div>
      </div>

      <div class="space corner jail" data-space="10">
        <div class="just">Just</div>
        <div class="drawing">
          <div class="container">
            <div class="name">In</div>
            <div class="window">
              <div class="bar"></div>
              <div class="bar"></div>
              <div class="bar"></div>
              <i class="person fa fa-frown-o"></i>
            </div>
            <div class="name">Jail</div>
          </div>
        </div>
        <div class="visiting">Visiting</div>
      </div>

      <div class="row vertical-row left-row">
        <div class="space property" data-space="19">
          <div class="container">
            <div class="color-bar orange"></div>
            <div class="name">New York Avenue</div>
            <div class="price">Price $200</div>
          </div>
        </div>
        <div class="space property" data-space="18">
          <div class="container">
            <div class="color-bar orange"></div>
            <div class="name">Tennessee Avenue</div>
            <div class="price">Price $180</div>
          </div>
        </div>
        <div class="space community-chest" data-space="17">
          <div class="container">
            <div class="name">Community Chest</div>
            <i class="drawing fa fa-cube"></i>
            <div class="instructions">Follow instructions on top card</div>
          </div>
        </div>
        <div class="space property" data-space="16">
          <div class="container">
            <div class="color-bar orange"></div>
            <div class="name">St. James Avenue</div>
            <div class="price">Price $180</div>
          </div>
        </div>
        <div class="space railroad" data-space="15">
          <div class="container">
            <div class="name long-name">Pennsylvania Railroad</div>
            <i class="drawing fa fa-subway"></i>
            <div class="price">Price $200</div>
          </div>
        </div>
        <div class="space property" data-space="14">
          <div class="container">
            <div class="color-bar purple"></div>
            <div class="name">Virginia Avenue</div>
            <div class="price">Price $160</div>
          </div>
        </div>
        <div class="space property" data-space="13">
          <div class="container">
            <div class="color-bar purple"></div>
            <div class="name">States Avenue</div>
            <div class="price">Price $140</div>
          </div>
        </div>
        <div class="space utility electric-company" data-space="12">
          <div class="container">
            <div class="name">Electric Company</div>
            <i class="drawing fa fa-lightbulb-o"></i>
            <div class="price">Price $150</div>
          </div>
        </div>
        <div class="space property" data-space="11">
          <div class="container">
            <div class="color-bar purple"></div>
            <div class="name">St. Charles Place</div>
            <div class="price">Price $140</div>
          </div>
        </div>
      </div>

      <div class="space corner free-parking" data-space="20">
        <div class="container">
          <div class="name">Free</div>
          <i class="drawing fa fa-car"></i>
          <div class="name">Parking</div>
        </div>
      </div>

      <div class="row horizontal-row top-row">
        <div class="space property" data-space="21">
          <div class="container">
            <div class="color-bar red"></div>
            <div class="name">Kentucky Avenue</div>
            <div class="price">Price $220</div>
          </div>
        </div>
        <div class="space chance" data-space="22">
          <div class="container">
            <div class="name">Chance</div>
            <i class="drawing fa fa-question blue"></i>
          </div>
        </div>
        <div class="space property" data-space="23">
          <div class="container">
            <div class="color-bar red"></div>
            <div class="name">Indiana Avenue</div>
            <div class="price">Price $220</div>
          </div>
        </div>
        <div class="space property" data-space="24">
          <div class="container">
            <div class="color-bar red"></div>
            <div class="name">Illinois Avenue</div>
            <div class="price">Price $200</div>
          </div>
        </div>
        <div class="space railroad" data-space="25">
          <div class="container">
            <div class="name">B & O Railroad</div>
            <i class="drawing fa fa-subway"></i>
            <div class="price">Price $200</div>
          </div>
        </div>
        <div class="space property" data-space="26">
          <div class="container">
            <div class="color-bar yellow"></div>
            <div class="name">Atlantic Avenue</div>
            <div class="price">Price $260</div>
          </div>
        </div>
        <div class="space property" data-space="27">
          <div class="container">
            <div class="color-bar yellow"></div>
            <div class="name">Ventnor Avenue</div>
            <div class="price">Price $260</div>
          </div>
        </div>
        <div class="space utility waterworks" data-space="28">
          <div class="container">
            <div class="name">Waterworks</div>
            <i class="drawing fa fa-tint"></i>
            <div class="price">Price $120</div>
          </div>
        </div>
        <div class="space property" data-space="29">
          <div class="container">
            <div class="color-bar yellow"></div>
            <div class="name">Marvin Gardens</div>
            <div class="price">Price $280</div>
          </div>
        </div>
      </div>

      <div class="space corner go-to-jail" data-space="30">
        <div class="container">
          <div class="name">Go To</div>
          <i class="drawing fa fa-gavel"></i>
          <div class="name">Jail</div>
        </div>
      </div>

      <div class="row vertical-row right-row">
        <div class="space property" data-space="31">
          <div class="container">
            <div class="color-bar green"></div>
            <div class="name">Pacific Avenue</div>
            <div class="price">Price $300</div>
          </div>
        </div>
        <div class="space property" data-space="32">
          <div class="container">
            <div class="color-bar green"></div>
            <div class="name three-line-name">North Carolina Avenue</div>
            <div class="price">Price $300</div>
          </div>
        </div>
        <div class="space community-chest" data-space="33">
          <div class="container">
            <div class="name">Community Chest</div>
            <i class="drawing fa fa-cube"></i>
            <div class="instructions">Follow instructions on top card</div>
          </div>
        </div>
        <div class="space property" data-space="34">
          <div class="container">
            <div class="color-bar green"></div>
            <div class="name long-name">Pennsylvania Avenue</div>
            <div class="price">Price $320</div>
          </div>
        </div>
        <div class="space railroad" data-space="35">
          <div class="container">
            <div class="name">Short Line</div>
            <i class="drawing fa fa-subway"></i>
            <div class="price">Price $200</div>
          </div>
        </div>
        <div class="space chance" data-space="36">
          <div class="container">
            <div class="name">Chance</div>
            <i class="drawing fa fa-question"></i>
          </div>
        </div>
        <div class="space property" data-space="37">
          <div class="container">
            <div class="color-bar dark-blue"></div>
            <div class="name">Park Place</div>
            <div class="price">Price $350</div>
          </div>
        </div>
        <div class="space fee luxury-tax" data-space="38">
          <div class="container">
            <div class="name">Luxury Tax</div>
            <div class="drawing fa fa-diamond"></div>
            <div class="instructions">Pay $75.00</div>
          </div>
        </div>
        <div class="space property" data-space="39">
          <div class="container">
            <div class="color-bar dark-blue"></div>
            <div class="name">Boardwalk</div>
            <div class="price">Price $400</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</x-layouts::app>
