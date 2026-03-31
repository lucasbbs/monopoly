<?php

namespace App\Http\Controllers;

use App\Models\Game;
use Illuminate\Http\Request;

class GameController extends Controller
{
    
    public function index()
    {
        $games = Game::all();
        return view('games.index', ['games' => $games]);
    }

    public function create()
    {
        return view('create-game');
    }

    public function show(Game $game)
    {
        return view('game', ['game' => $game]);
    }
}
