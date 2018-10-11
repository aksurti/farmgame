<?php

namespace App;

class Game
{

    /**
     * Max Feed for a Game.
     * @var int
     */
    const MAX_FEED = 50;

    /**
     * @var string
     */
    const FEED = 'Feed';

    /**
     * Define Object involved in game, i.e Farmer, Cow, Bunnie etc
     *
     *  [
     *      'Object' => [
     *          'number' => (int) // number of object involved in the game.
     *          'atLeastOneFeedToBeAlive' => (int) // how long the object can service without a feed.
     *      ]
     *  ]
     *
     * @var array
     */
    const FEED_OBJECT = [
        'Farmer' => [
            'number' => 1,
            'atLeastOneFeedToBeAlive' => 15,
            'atLeastObjectNeedToPlayTheGame' => 1
        ],
        'Cow' => [
            'number' => 2,
            'atLeastOneFeedToBeAlive' => 10,
            'atLeastObjectNeedToPlayTheGame' => 1
        ],
        'Bunnie' => [
            'number' => 4,
            'atLeastOneFeedToBeAlive' => 8,
            'atLeastObjectNeedToPlayTheGame' => 1
        ]
    ];

    /**
     * Setup the new session to start the new game.
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function startNewGame()
    {
        session()->flush();

        session()->put(Game::FEED, []);

        foreach (Game::FEED_OBJECT as $object => $objectVal) {
            for ($i=0;$i<$objectVal['number'];$i++) {
                session()->put("$object.$i", 0); // define alive object 1: alive, 0: dead.
            }
        }

        return redirect('/');
    }



    /**
     * Feed to Random active object.
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function feedToRandom()
    {

        if(!$this->checkIfGameIsOver())
        {

            // feed random from existing alive object..
            session()->push(Game::FEED, $this->pickUpRandom());

            // check is some one dead from alive object ?
            $this->checkForLife();



        }
        return redirect('/');
    }


    /**
     * Check if the game is over.
     *
     * @return bool
     */
    public function checkIfGameIsOver()
    {
        // at least one Farmer and one Cow should be alive to play the game.

        if(collect(session()->get(Game::FEED))->count() >= Game::MAX_FEED) {
            return true;
        } else if (!$this->isItWinningObject()) {
            return true;
        } else {
            return false;
        }

    }



    /**
     * condition:
     *  -   winning: at least a Farmer, a Cow and a bunnie should be alive till the 50 feeds.
     *
     * @return bool
     */
    public function isItWinningObject()
    {
        foreach (Game::FEED_OBJECT as $object => $objectVal)
        {
            if($objectVal['atLeastObjectNeedToPlayTheGame'] > 0) {

                if(collect(session()->get($object))->filter(function($value) {
                        return $value == 0;
                    })->count() < $objectVal['atLeastObjectNeedToPlayTheGame']) {

                    return false;
                }
            }
        }

        return true;
    }



    /**
     * @return static
     */
    public function checkForLife()
    {
        $staticObject = Game::FEED_OBJECT;

        return $totalFeedSoFarForTheObject = collect($this->getTotalFeedSoFar())->mapToGroups(function($item, $key) {
            return [$item => $key];
        })->map(function($value, $key) use ($staticObject) {

            collect($value)->reduce(function($carry, $item) use ($key, $staticObject) {

                if(($item - $carry) > $staticObject[collect(explode('.',$key))->first()]['atLeastOneFeedToBeAlive']) {
                    session()->put($key, $item);
                }
                return  $item;
            });
            return $value;
        });

    }


    /**
     * Return the feeds details
     *
     * @return mixed
     */
    public function getTotalFeedSoFar()
    {
        return session()->get(Game::FEED);
    }

    /**
     * Get the alive object (i.e Farmer, Cow, Bunnie etc)
     * @return array
     */
    public function getAliveObject()
    {
        $alive = [];
        foreach (array_keys(Game::FEED_OBJECT) as $objectName) {
            $alive[] = collect(session()->get($objectName))->map(function($value, $key) use ($objectName){
                return $value == 0 ? "$objectName.$key" : false;
            });
        }

        return $alive;
    }

    /**
     * Pick up a random alive object from the list.
     *
     * @return mixed
     */
    public function pickUpRandom()
    {

        $alive = $this->getAliveObject();

        return collect($alive)->flatten(1)->filter(function($value, $key){
            return $value;
        })->random();

    }
}
