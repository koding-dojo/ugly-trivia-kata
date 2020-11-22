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
        $this->game->addPlayer('gholam');
        $this->game->addPlayer('ghamar');
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
        $game->addPlayer('gholam');
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

    public function testSixPlayersWithTwelveRolls()
    {
        $this->game->addPlayer('ghanbar');
        $this->game->addPlayer('ghodrat');
        $this->game->addPlayer('gheysar');
        $this->game->addPlayer('ghesmat');
        for ($i = 1; $i < 12; $i++) {
            $this->game->roll($i);
            if ($i % 2 == 0) {
                $this->game->wasCorrectlyAnswered();
            } else {
                $this->game->wrongAnswer();
            }
        }

        self::assertEquals(<<<EOF
            gholam was added
            They are player number 1
            ghamar was added
            They are player number 2
            ghanbar was added
            They are player number 3
            ghodrat was added
            They are player number 4
            gheysar was added
            They are player number 5
            ghesmat was added
            They are player number 6
            gholam is the current player
            They have rolled a 1
            gholam's new location is 1
            The category is Science
            Science Question 0
            Question was incorrectly answered
            gholam was sent to the penalty box
            ghamar is the current player
            They have rolled a 2
            ghamar's new location is 2
            The category is Sports
            Sports Question 0
            Answer was corrent!!!!
            ghamar now has 1 Gold Coins.
            ghanbar is the current player
            They have rolled a 3
            ghanbar's new location is 3
            The category is Rock
            Rock Question 0
            Question was incorrectly answered
            ghanbar was sent to the penalty box
            ghodrat is the current player
            They have rolled a 4
            ghodrat's new location is 4
            The category is Pop
            Pop Question 00
            Answer was corrent!!!!
            ghodrat now has 1 Gold Coins.
            gheysar is the current player
            They have rolled a 5
            gheysar's new location is 5
            The category is Science
            Science Question 1
            Question was incorrectly answered
            gheysar was sent to the penalty box
            ghesmat is the current player
            They have rolled a 6
            ghesmat's new location is 6
            The category is Sports
            Sports Question 1
            Answer was corrent!!!!
            ghesmat now has 1 Gold Coins.
            gholam is the current player
            They have rolled a 7
            gholam is getting out of the penalty box
            gholam's new location is 8
            The category is Pop
            Pop Question 11
            Question was incorrectly answered
            gholam was sent to the penalty box
            ghamar is the current player
            They have rolled a 8
            ghamar's new location is 10
            The category is Sports
            Sports Question 2
            Answer was corrent!!!!
            ghamar now has 2 Gold Coins.
            ghanbar is the current player
            They have rolled a 9
            ghanbar is getting out of the penalty box
            ghanbar's new location is 0
            The category is Pop
            Pop Question 22
            Question was incorrectly answered
            ghanbar was sent to the penalty box
            ghodrat is the current player
            They have rolled a 10
            ghodrat's new location is 2
            The category is Sports
            Sports Question 3
            Answer was corrent!!!!
            ghodrat now has 2 Gold Coins.
            gheysar is the current player
            They have rolled a 11
            gheysar is getting out of the penalty box
            gheysar's new location is 4
            The category is Pop
            Pop Question 33
            Question was incorrectly answered
            gheysar was sent to the penalty box
            
            EOF, TestableGame::$output);
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
