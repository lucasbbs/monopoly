<?php

test('token tooltip is assigned from the selected token name', function () {
    $script = file_get_contents(resource_path('js/token-position.js'));

    expect($script)
        ->toContain('this.#element.title = token;')
        ->toContain("this.#element.setAttribute('aria-label', token);")
        ->not->toContain('element.title = this.#token;');
});
