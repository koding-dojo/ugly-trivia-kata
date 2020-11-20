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

    public function testShouldAddPlayers()
    {
        self::assertEquals(<<<EOF
            gholam was added
            They are player number 1
            ghamar was added
            They are player number 2
            
            EOF, $this->game::$output);
    }

    public function testRollWhenPlayerIsNotInPenaltyBox()
    {
        $this->game->roll(1);

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
