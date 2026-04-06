import * as THREE from 'three';
import { STLLoader } from 'three/examples/jsm/loaders/STLLoader.js';

const BOARD_SPACE_COUNT = 40;
const RENDER_SIZE = 96;
const STEP_DELAY_MS = 220;
const TOKEN_FILES = Object.freeze({
  Boot: new URL('../tokens/Boot.stl', import.meta.url).href,
  Car: new URL('../tokens/Car.stl', import.meta.url).href,
  Cat: new URL('../tokens/Cat.stl', import.meta.url).href,
  Dog: new URL('../tokens/Dog.stl', import.meta.url).href,
  Duck: new URL('../tokens/Duck.stl', import.meta.url).href,
  Horse: new URL('../tokens/Horse.stl', import.meta.url).href,
  Iron: new URL('../tokens/Iron.stl', import.meta.url).href,
  Penguin: new URL('../tokens/Penguin.stl', import.meta.url).href,
  Ship: new URL('../tokens/Ship.stl', import.meta.url).href,
  TopHat: new URL('../tokens/TopHat.stl', import.meta.url).href,
  TRex: new URL('../tokens/TRex.stl', import.meta.url).href,
  WheelBarrow: new URL('../tokens/WheelBarrow.stl', import.meta.url).href,
});
const TOKEN_COLORS = Object.freeze({
  Boot: 0xb9823d,
  Car: 0xcb4f42,
  Cat: 0x4b5b6e,
  Dog: 0x596a44,
  Duck: 0xd49d1f,
  Horse: 0x7a4d2b,
  Iron: 0x8c9298,
  Penguin: 0x2f455c,
  Ship: 0x466d89,
  TopHat: 0x2d2d2d,
  TRex: 0x5d8f39,
  WheelBarrow: 0x70593f,
});

const normalizePosition = (position) => {
  const parsedPosition = Number.parseInt(position, 10);

  if (Number.isNaN(parsedPosition)) {
    return 0;
  }

  return ((parsedPosition % BOARD_SPACE_COUNT) + BOARD_SPACE_COUNT) % BOARD_SPACE_COUNT;
};

const wait = (duration) => new Promise((resolve) => {
  window.setTimeout(resolve, duration);
});

class TokenPosition {
  static availableTokens = Object.keys(TOKEN_FILES);

  #board = null;
  #layer = null;
  #element = null;
  #fallback = null;
  #renderer = null;
  #scene = null;
  #camera = null;
  #loader = new STLLoader();
  #mesh = null;
  #movementQueue = Promise.resolve();
  #token = null;
  #position = 0;

  constructor(token, position, options = {}) {
    this.#board = options.board ?? document.querySelector('[data-monopoly-board]');
    this.#layer = this.#findOrCreateLayer();
    this.#element = this.#createElement();
    this.#fallback = this.#element.querySelector('[data-token-fallback]');

    this.#setupRenderer();
    this.setToken(token);
    this.moveTo(position);
  }

  get token() {
    return this.#token;
  }

  get position() {
    return this.#position;
  }

  setToken(token) {
    if (!TOKEN_FILES[token]) {
      return this;
    }

    this.#token = token;
    this.#element.title = token;
    this.#element.setAttribute('aria-label', token);
    this.#fallback.textContent = token.slice(0, 2).toUpperCase();

    if (!this.#renderer || !this.#scene) {
      this.#fallback.hidden = false;
      return this;
    }

    this.#loader.load(
      TOKEN_FILES[token],
      (geometry) => {
        geometry.center();

        const mesh = new THREE.Mesh(
          geometry,
          new THREE.MeshStandardMaterial({
            color: TOKEN_COLORS[token] ?? 0x8c9298,
            metalness: 0.7,
            roughness: 0.2,
          }),
        );

        mesh.scale.setScalar(3.5);
        mesh.rotation.x = -Math.PI / 2;
        mesh.rotation.z = Math.PI / 8;

        this.#replaceMesh(mesh);
        this.#fallback.hidden = true;
      },
      undefined,
      () => {
        this.#removeMesh();
        this.#fallback.hidden = false;
      },
    );

    return this;
  }

  moveTo(position) {
    this.#position = normalizePosition(position);

    return this.render();
  }

  async moveBy(steps, options = {}) {
    const parsedSteps = Number.parseInt(steps, 10);

    if (Number.isNaN(parsedSteps) || parsedSteps === 0) {
      return this;
    }

    this.#movementQueue = this.#movementQueue.then(() => this.#walkSteps(parsedSteps, options.onStep));

    await this.#movementQueue;

    return this;
  }

  render() {
    if (!this.#board || !this.#layer || !this.#element) {
      return null;
    }

    const targetSpace = this.#board.querySelector(`[data-space="${this.#position}"]`);

    if (!targetSpace) {
      return null;
    }

    if (!this.#element.isConnected) {
      this.#layer.append(this.#element);
    }

    const boardBounds = this.#board.getBoundingClientRect();
    const spaceBounds = targetSpace.getBoundingClientRect();

    this.#element.style.left = `${spaceBounds.left - boardBounds.left + (spaceBounds.width / 2)}px`;
    this.#element.style.top = `${spaceBounds.top - boardBounds.top + (spaceBounds.height / 2)}px`;

    return this.#element;
  }

  #findOrCreateLayer() {
    if (!this.#board) {
      return null;
    }

    const existingLayer = this.#board.querySelector('[data-token-layer]');

    if (existingLayer) {
      return existingLayer;
    }

    const layer = document.createElement('div');
    layer.className = 'board-token-layer';
    layer.dataset.tokenLayer = 'true';
    this.#board.append(layer);

    return layer;
  }

  #createElement() {
    const element = document.createElement('div');
    element.title = '';
    element.className = 'board-token';

    const fallback = document.createElement('span');
    fallback.className = 'board-token__fallback';
    fallback.dataset.tokenFallback = 'true';
    fallback.hidden = true;

    element.append(fallback);

    return element;
  }

  async #walkSteps(steps, onStep = null) {
    const direction = steps >= 0 ? 1 : -1;
    const totalSteps = Math.abs(steps);

    this.#element?.classList.add('board-token--rolling');

    for (let currentStep = 0; currentStep < totalSteps; currentStep += 1) {
      this.moveTo(this.#position + direction);
      onStep?.(this.#position);
      this.#animateHop();

      await wait(STEP_DELAY_MS);
    }

    this.#element?.classList.remove('board-token--hop');
    this.#element?.classList.remove('board-token--rolling');
  }

  #animateHop() {
    if (!this.#element) {
      return;
    }

    this.#element.classList.remove('board-token--hop');
    void this.#element.offsetWidth;
    this.#element.classList.add('board-token--hop');
  }

  #setupRenderer() {
    try {
      this.#renderer = new THREE.WebGLRenderer({ alpha: true, antialias: true });
    } catch {
      this.#fallback.hidden = false;

      return;
    }

    this.#renderer.setPixelRatio(Math.min(window.devicePixelRatio || 1, 2));
    this.#renderer.setSize(RENDER_SIZE, RENDER_SIZE, false);
    this.#renderer.outputColorSpace = THREE.SRGBColorSpace;
    this.#renderer.domElement.classList.add('board-token__canvas');
    this.#element.append(this.#renderer.domElement);

    this.#scene = new THREE.Scene();
    this.#camera = new THREE.PerspectiveCamera(35, 1, 0.1, 100);
    this.#camera.position.set(0, 0.2, 3.5);

    const ambientLight = new THREE.AmbientLight(0xffffff, 1.7);
    const keyLight = new THREE.DirectionalLight(0xffffff, 2.4);
    keyLight.position.set(2, 3, 4);
    const fillLight = new THREE.DirectionalLight(0xffffff, 1.2);
    fillLight.position.set(-3, -2, 2);

    this.#scene.add(ambientLight, keyLight, fillLight);
    this.#animate();
  }

  #replaceMesh(mesh) {
    this.#removeMesh();
    this.#mesh = mesh;
    this.#scene?.add(mesh);
  }

  #removeMesh() {
    if (!this.#mesh) {
      return;
    }

    this.#scene?.remove(this.#mesh);
    this.#mesh.geometry.dispose();
    this.#mesh.material.dispose();
    this.#mesh = null;
  }

  #animate() {
    this.#renderer?.render(this.#scene, this.#camera);
    requestAnimationFrame(() => this.#animate());
  }
}

export default TokenPosition;
