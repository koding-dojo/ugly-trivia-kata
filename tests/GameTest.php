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
}

class TestableGame extends Game
{
    public static string $output = '';
    protected static function echoln($string)
    {
        self::$output .= $string."\n";
    }
}
