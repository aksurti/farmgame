<?php

namespace App\Http\Controllers;

use App\Game;

class GameController extends Controller
{
    protected $game;

    public function __construct(Game $game)
    {

        $this->game = $game;

        if(!session()->has('Farmer') || !session()->has('Cow') || !session()->has('Bunnie')) {
            $this->game->startNewGame();
        }
    }

    /**
     * set up Farm Game initial view
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {


        $staticObject = Game::FEED_OBJECT;
        $checkForLife = $this->game->checkForLife()->toArray();
        $aliveObject = [];
        $checkIfTheGameOver = $this->game->checkIfGameIsOver();
        $totalFeedSoFar = collect($this->game->getTotalFeedSoFar())->count();
        $isItWinningObject = $this->game->isItWinningObject();
        return view('index', compact('staticObject', 'checkForLife', 'aliveObject', 'totalFeedSoFar', 'checkIfTheGameOver', 'isItWinningObject' ));
    }

    /**
     * Start a new game with the new session data..
     */
    public function startNewGame()
    {
        return $this->game->startNewGame();
    }

    /**
     * Feed to Random Alive Object.
     */
    public function feedToRandom()
    {
        return $this->game->feedToRandom();
    }

    public function session()
    {
        dd(session()->all());
    }


}
