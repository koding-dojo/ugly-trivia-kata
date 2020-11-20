<?php

namespace Tests;

use Trivia\Game;
use PHPUnit\Framework\TestCase;

class GameTest extends TestCase
{
    public function testShouldCreateGame()
    {
        $game = new TestableGame();
        self::assertNotNull($game);
    }

    public function testShouldAddPlayers()
    {
        // Arrange
        $game = new TestableGame();

        // Act
        $game->add('gholam');
        $game->add('ghamar');

        // Assert
        self::assertEquals(<<<EOF
            gholam was added
            They are player number 1
            ghamar was added
            They are player number 2
            
            EOF, $game::$output);
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
