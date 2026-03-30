import TokenPosition from './token-position';

const describeSpace = (space) => {
  if (!space) {
    return 'Board Space';
  }

  if (space.classList.contains('go')) {
    return 'Go';
  }

  if (space.classList.contains('jail')) {
    return 'Jail / Just Visiting';
  }

  if (space.classList.contains('free-parking')) {
    return 'Free Parking';
  }

  if (space.classList.contains('go-to-jail')) {
    return 'Go To Jail';
  }

  const label = [...space.querySelectorAll('.name, .go-word')]
    .map((element) => element.textContent.replace(/\s+/g, ' ').trim())
    .filter(Boolean)
    .join(' ');

  if (label) {
    return label;
  }

  return `Space ${space.dataset.space ?? '?'}`;
};

const initialiseMonopolyBoard = () => {
  const game = document.querySelector('[data-monopoly-game]');
  const board = game?.querySelector('[data-monopoly-board]');
  const tokenPicker = game?.querySelector('[data-token-picker]');
  const spaceInput = game?.querySelector('[data-token-space]');
  const spaceLabel = game?.querySelector('[data-token-space-label]');

  if (!game || !board || !tokenPicker || !spaceInput || !spaceLabel) {
    return;
  }

  tokenPicker.replaceChildren(
    ...TokenPosition.availableTokens.map((token) => {
      const option = document.createElement('option');
      option.value = token;
      option.textContent = token;

      return option;
    }),
  );
  tokenPicker.value = TokenPosition.availableTokens[0] ?? '';

  const tokenPosition = new TokenPosition(tokenPicker.value, Number(spaceInput.value), { board });
  const syncSpace = (value) => {
    const nextPosition = Math.min(39, Math.max(0, Number.parseInt(value, 10) || 0));
    const space = board.querySelector(`[data-space="${nextPosition}"]`);
    const label = `${describeSpace(space)} (${nextPosition})`;

    spaceInput.value = `${nextPosition}`;
    spaceLabel.value = label;
    spaceLabel.textContent = label;
    tokenPosition.moveTo(nextPosition);
  };

  tokenPicker.addEventListener('change', () => {
    tokenPosition.setToken(tokenPicker.value);
  });

  spaceInput.addEventListener('input', () => {
    syncSpace(spaceInput.value);
  });

  game.querySelectorAll('[data-token-move]').forEach((button) => {
    button.addEventListener('click', () => {
      syncSpace(Number(spaceInput.value) + Number(button.dataset.tokenMove));
    });
  });

  window.addEventListener('resize', () => {
    tokenPosition.render();
  });

  syncSpace(spaceInput.value);
};

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initialiseMonopolyBoard);
} else {
  initialiseMonopolyBoard();
}
