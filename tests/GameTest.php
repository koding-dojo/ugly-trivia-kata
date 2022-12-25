<?php

namespace Tests;

use Trivia\Game;
use PHPUnit\Framework\TestCase;

class GameTest extends TestCase
{
    /**
     * @var Game
     */
    private Game $game;

    public function setUp(): void
    {
        parent::setUp();
        $this->game = new Game();
        $this->game->add('gholam');
        $this->game->add('ghamar');
    }

    public function testIsPlayableWithTwoPlayers()
    {
        self::assertTrue($this->game->isPlayable());
    }

    public function testPlayGame()
    {
        $this->game->roll(1);   // 1st
        $this->game->wrongAnswer();
        $this->game->roll(6);
        $this->game->wasCorrectlyAnswered();

        $this->game->roll(3);   // 2nd
        $this->game->wasCorrectlyAnswered();
        $this->game->roll(6);
        $this->game->wrongAnswer();

        $this->game->roll(5);   // 3rd
        $this->game->wasCorrectlyAnswered();
        $this->game->roll(2);
        $this->game->wasCorrectlyAnswered();

        $this->game->roll(1);   // 4th
        $this->game->wrongAnswer();
        $this->game->roll(3);
        $this->game->wasCorrectlyAnswered();

        self::expectOutputString(<<<EOF
            gholam was added
            They are player number 1
            ghamar was added
            They are player number 2
            gholam is the current player
            They have rolled a 1
            gholam's new location is 1
            The category is Science
            Science Question 0
            Question was incorrectly answered
            gholam was sent to the penalty box
            ghamar is the current player
            They have rolled a 6
            ghamar's new location is 6
            The category is Sports
            Sports Question 0
            Answer was corrent!!!!
            ghamar now has 1 Gold Coins.
            gholam is the current player
            They have rolled a 3
            gholam is getting out of the penalty box
            gholam's new location is 4
            The category is Pop
            Pop Question 00
            Answer was correct!!!!
            gholam now has 1 Gold Coins.
            ghamar is the current player
            They have rolled a 6
            ghamar's new location is 0
            The category is Pop
            Pop Question 11
            Question was incorrectly answered
            ghamar was sent to the penalty box
            gholam is the current player
            They have rolled a 5
            gholam is getting out of the penalty box
            gholam's new location is 9
            The category is Science
            Science Question 1
            Answer was correct!!!!
            gholam now has 2 Gold Coins.
            ghamar is the current player
            They have rolled a 2
            ghamar is not getting out of the penalty box
            gholam is the current player
            They have rolled a 1
            gholam is getting out of the penalty box
            gholam's new location is 10
            The category is Sports
            Sports Question 1
            Question was incorrectly answered
            gholam was sent to the penalty box
            ghamar is the current player
            They have rolled a 3
            ghamar is getting out of the penalty box
            ghamar's new location is 3
            The category is Rock
            Rock Question 0
            Answer was correct!!!!
            ghamar now has 2 Gold Coins.
            
            EOF);
    }
}
