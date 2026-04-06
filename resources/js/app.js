import TokenPosition from './token-position';

const BOARD_SPACE_COUNT = 40;

const parseTransitionTime = (value) => {
  const trimmedValue = value.trim();

  if (!trimmedValue) {
    return 0;
  }

  if (trimmedValue.endsWith('ms')) {
    return Number.parseFloat(trimmedValue);
  }

  if (trimmedValue.endsWith('s')) {
    return Number.parseFloat(trimmedValue) * 1000;
  }

  return 0;
};

const getTransitionTimeout = (element) => {
  const styles = window.getComputedStyle(element);
  const durations = styles.transitionDuration.split(',').map(parseTransitionTime);
  const delays = styles.transitionDelay.split(',').map(parseTransitionTime);

  return durations.reduce((longestTransition, duration, index) => {
    const delay = delays[index] ?? delays[0] ?? 0;

    return Math.max(longestTransition, duration + delay);
  }, 0);
};

const resetDiceAnimation = (element) => {
  element.style.transition = 'none';
  element.classList.remove('reRoll');
  void element.offsetWidth;
  element.style.removeProperty('transition');
};

const animateDiceRoll = (element) => new Promise((resolve) => {
  if (!element) {
    resolve();

    return;
  }

  resetDiceAnimation(element);

  let isSettled = false;

  const settleAnimation = () => {
    if (isSettled) {
      return;
    }

    isSettled = true;
    window.clearTimeout(fallbackTimer);
    resetDiceAnimation(element);
    resolve();
  };

  const fallbackTimer = window.setTimeout(settleAnimation, getTransitionTimeout(element) + 100);

  element.addEventListener('transitionend', (event) => {
    if (event.propertyName !== 'transform') {
      return;
    }

    settleAnimation();
  }, { once: true });

  element.classList.add('reRoll');
});

const clampPosition = (value) => {
  const parsedPosition = Number.parseInt(value, 10);

  if (Number.isNaN(parsedPosition)) {
    return 0;
  }

  return Math.min(BOARD_SPACE_COUNT - 1, Math.max(0, parsedPosition));
};

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
  const updateSpaceUi = (position) => {
    const nextPosition = clampPosition(position);
    const space = board.querySelector(`[data-space="${nextPosition}"]`);
    const label = `${describeSpace(space)} (${nextPosition})`;

    spaceInput.value = `${nextPosition}`;
    spaceLabel.value = label;
    spaceLabel.textContent = label;
  };

  const syncSpace = (value) => {
    const nextPosition = clampPosition(value);

    updateSpaceUi(nextPosition);
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

  window.addEventListener('monopoly-dice-rolled', async (event) => {
    const total = Number.parseInt(event.detail?.total, 10);
    const dice = game.querySelectorAll('.dice-inner-container .dice');

    if (Number.isNaN(total) || total <= 0) {
      return;
    }

    await Promise.all([...dice].map(animateDiceRoll));

    await tokenPosition.moveBy(total, {
      onStep: updateSpaceUi,
    });
  });

  syncSpace(spaceInput.value);
};

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initialiseMonopolyBoard);
} else {
  initialiseMonopolyBoard();
}
