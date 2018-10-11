<?php

namespace Tests\Feature;

use App\Game;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GameTest extends TestCase
{
    /**
     * Check For the Total Feed Number..
     */
    public function testTotalFeedSoFar()
    {
        $feed = [
            0 => "Cow.0",
            1 => "Bunnie.2",
            2 => "Cow.1",
            3 => "Bunnie.2",
            4 => "Cow.0",
            5 => "Cow.1",
            6 => "Bunnie.3",
            7 => "Bunnie.3",
            8 => "Farmer.0",
            9 => "Cow.0",
            10 => "Bunnie.0",
            11 => "Farmer.0",
            12 => "Bunnie.1",
            13 => "Farmer.0",
            14 => "Cow.1",
            15 => "Bunnie.3",
            16 => "Bunnie.3",
            17 => "Farmer.0",
            18 => "Cow.1",
            19 => "Bunnie.3",
            20 => "Bunnie.3",
            21 => "Bunnie.2",
            22 => "Farmer.0",
            23 => "Cow.1",
            24 => "Bunnie.3",
            25 => "Cow.0",
            26 => "Farmer.0",
            27 => "Bunnie.3",
            28 => "Bunnie.3",
            29 => "Cow.1",
            30 => "Cow.1",
            31 => "Bunnie.3",
            32 => "Cow.1",
            33 => "Bunnie.3",
            34 => "Farmer.0",
            35 => "Cow.1",
            36 => "Bunnie.3",
            37 => "Bunnie.3",
            38 => "Farmer.0",
            39 => "Bunnie.3",
            40 => "Cow.1",
            41 => "Bunnie.3",
            42 => "Farmer.0",
            43 => "Cow.1",
            44 => "Bunnie.3",
            45 => "Bunnie.3",
            46 => "Cow.1",
            47 => "Farmer.0",
            48 => "Cow.1",
            49 => "Cow.1"
        ];
        $game = new Game();
        $game->startNewGame();
        session()->put(Game::FEED, $feed);
        $this->assertEquals(50, collect($game->getTotalFeedSoFar())->count());
    }

    /**
     * Test to Check if the game is over ?
     */
    public function testToCheckIfGameIsOver()
    {
        $feed = [
            0 => "Cow.0",
            1 => "Bunnie.2",
            2 => "Cow.1",
            3 => "Bunnie.2",
            4 => "Cow.0",
            5 => "Cow.1",
            6 => "Bunnie.3",
            7 => "Bunnie.3",
            8 => "Farmer.0",
            9 => "Cow.0",
            10 => "Bunnie.0",
            11 => "Farmer.0",
            12 => "Bunnie.1",
            13 => "Farmer.0",
            14 => "Cow.1",
            15 => "Bunnie.3",
            16 => "Bunnie.3",
            17 => "Farmer.0",
            18 => "Cow.1",
            19 => "Bunnie.3",
            20 => "Bunnie.3",
            21 => "Bunnie.2",
            22 => "Farmer.0",
            23 => "Cow.1",
            24 => "Bunnie.3",
            25 => "Cow.0",
            26 => "Farmer.0",
            27 => "Bunnie.3",
            28 => "Bunnie.3",
            29 => "Cow.1",
            30 => "Cow.1",
            31 => "Bunnie.3",
            32 => "Cow.1",
            33 => "Bunnie.3",
            34 => "Farmer.0",
            35 => "Cow.1",
            36 => "Bunnie.3",
            37 => "Bunnie.3",
            38 => "Farmer.0",
            39 => "Bunnie.3",
            40 => "Cow.1",
            41 => "Bunnie.3",
            42 => "Farmer.0",
            43 => "Cow.1",
            44 => "Bunnie.3",
            45 => "Bunnie.3",
            46 => "Cow.1",
            47 => "Farmer.0",
            48 => "Cow.1",
            49 => "Cow.1"
        ];

        $farmer = [
            0 => 1 // farmer is dead here..
        ];

        $cow = [
            0 => 25,
            1 => 0, // still alive
        ];

        $bunnies = [
            0 => 10,
            1 => 12,
            2 => 21,
            3 => 0 // still alive
        ];

        $game = new Game();
        $game->startNewGame();
        session()->put(Game::FEED, $feed);
        session()->put('Farmer', $farmer);
        session()->put('Cow', $cow);
        session()->put('Bunnie', $bunnies);
        $this->assertEquals(true, $game->checkIfGameIsOver()); // game is over as farmer dead..
    }

    /**
     * Test to find out all the alive object to feed..
     */
    public function testForAvalilableLiveObject()
    {
        $farmer = [
            0 => 1
        ];

        $cow = [
            0 => 25,
            1 => 32,
        ];

        $bunnies = [
            0 => 10,
            1 => 12,
            2 => 21,
            3 => 0 // Bunnie 3 is only object alive..
        ];

        $game = new Game();
        $game->startNewGame();
        session()->put('Farmer', $farmer);
        session()->put('Cow', $cow);
        session()->put('Bunnie', $bunnies);
        $this->assertEquals('Bunnie.3', $game->pickUpRandom());

    }
}
