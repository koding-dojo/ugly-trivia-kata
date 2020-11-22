<?php

namespace Tests;

use Trivia\Game;
use PHPUnit\Framework\TestCase;

class GameTest extends TestCase
{
    private TestableGame $game;

    public function setUp(): void
    {
        $this->game = new TestableGame();
        $this->game->add('gholam');
        $this->game->add('ghamar');
    }

    public function tearDown(): void
    {
        parent::tearDown();
        TestableGame::$output = '';
    }

    public function testShouldAddPlayers()
    {
        self::assertEquals(<<<EOF
            gholam was added
            They are player number 1
            ghamar was added
            They are player number 2
            
            EOF, $this->game::$output);
    }

    public function testBugIsPlayableWithOnePlayer()
    {
        $game = new TestableGame();
        $game->add('gholam');
        $game->roll(1);
        $game->wrongAnswer();
        $game->roll(1);
        $game->wasCorrectlyAnswered();
        $game->roll(1);
        $game->wrongAnswer();
        $game->roll(2);
        $game->wasCorrectlyAnswered();

        self::assertEquals(<<<EOF
            gholam was added
            They are player number 1
            ghamar was added
            They are player number 2
            gholam was added
            They are player number 1
            gholam is the current player
            They have rolled a 1
            gholam's new location is 1
            The category is Science
            Science Question 0
            Question was incorrectly answered
            gholam was sent to the penalty box
            gholam is the current player
            They have rolled a 1
            gholam is getting out of the penalty box
            gholam's new location is 2
            The category is Sports
            Sports Question 0
            Answer was correct!!!!
            gholam now has 1 Gold Coins.
            gholam is the current player
            They have rolled a 1
            gholam is getting out of the penalty box
            gholam's new location is 3
            The category is Rock
            Rock Question 0
            Question was incorrectly answered
            gholam was sent to the penalty box
            gholam is the current player
            They have rolled a 2
            gholam is not getting out of the penalty box
            
            EOF, $game::$output);
    }

    public function testRollWhenPlayerIsNotInPenaltyBox()
    {
        $this->game->roll(1);
        $this->game->wasCorrectlyAnswered();

        self::assertEquals(<<<EOF
            gholam was added
            They are player number 1
            ghamar was added
            They are player number 2
            gholam is the current player
            They have rolled a 1
            gholam's new location is 1
            The category is Science
            Science Question 0
            Answer was corrent!!!!
            gholam now has 1 Gold Coins.
            
            EOF, $this->game::$output);
    }

    public function testRollOddWhenPlayerIsInPenaltyBox()
    {
        $this->game->roll(1);    // first player
        $this->game->wrongAnswer();
        $this->game->roll(1);    // 2nd player
        $this->game->wrongAnswer();
        $this->game->roll(1);    // first player
        $this->game->wasCorrectlyAnswered();

        self::assertEquals(<<<EOF
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
            They have rolled a 1
            ghamar's new location is 1
            The category is Science
            Science Question 1
            Question was incorrectly answered
            ghamar was sent to the penalty box
            gholam is the current player
            They have rolled a 1
            gholam is getting out of the penalty box
            gholam's new location is 2
            The category is Sports
            Sports Question 0
            Answer was correct!!!!
            gholam now has 1 Gold Coins.

            EOF, $this->game::$output);
    }

    public function testRollEvenWhenPlayerIsInPenaltyBox()
    {
        $this->game->roll(1);   // first player
        $this->game->wrongAnswer();
        $this->game->roll(1);   // 2nd player
        $this->game->wrongAnswer();
        $this->game->roll(2);   // first player again
        $this->game->wasCorrectlyAnswered();

        self::assertEquals(<<<EOF
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
            They have rolled a 1
            ghamar's new location is 1
            The category is Science
            Science Question 1
            Question was incorrectly answered
            ghamar was sent to the penalty box
            gholam is the current player
            They have rolled a 2
            gholam is not getting out of the penalty box

            EOF, $this->game::$output);
    }
}

class TestableGame extends Game
{
    public static string $output = '';
    protected static function echoln($string)
    {
        self::$output .= $string."\n";
    }
}
