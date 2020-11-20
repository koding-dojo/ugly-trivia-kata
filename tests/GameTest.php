<?php

namespace Tests;

use Trivia\Game;
use PHPUnit\Framework\TestCase;

class GameTest extends TestCase
{
    public function testShouldCreateGame()
    {
        $game = new Game();
        self::assertNotNull($game);
    }
}
