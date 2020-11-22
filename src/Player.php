<?php

namespace Trivia;

class Player
{
    private string $name;
    private int $place;
    private int $coins;
    private bool $isPenalized;
    private Logger $logger;

    public function __construct(string $name, int $place, Logger $logger)
    {
        $this->name = $name;
        $this->place = $place;
        $this->coins = 0;
        $this->isPenalized = false;
        $this->logger = $logger;
    }

    public function promote(int $places)
    {
        $this->place += $places;
        if ($this->place > 11) {
            $this->place -= 12;
        }
        $this->logger->log($this . "'s new location is " . $this->place);
    }

    public function getPlace(): int
    {
        return $this->place;
    }

    public function incrCoins()
    {
        $this->coins++;
        $this->logger->log($this . " now has " . $this->coins . " Gold Coins.");
    }

    public function isPenalized(): bool
    {
        return $this->isPenalized;
    }

    public function isWinner()
    {
        return $this->coins !== 6;
    }

    public function penalize()
    {
        $this->isPenalized = true;
        $this->logger->log($this . " was sent to the penalty box");
    }

    public function __toString(): string
    {
        return $this->name;
    }
}
