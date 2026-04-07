<?php

use App\Http\Controllers\GameController;
use App\Livewire\CreateGame;
use App\Livewire\InvitePlayers;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
    Route::get('monopoly/create', CreateGame::class)->name('monopoly.create');
    Route::get('monopoly', [GameController::class, 'play'])->name('monopoly');
    Route::get('monopoly/{game}/invite', InvitePlayers::class)->name('monopoly.invite');
    Route::get('monopoly/{game}', [GameController::class, 'show'])->name('monopoly.show');
});

require __DIR__.'/settings.php';
