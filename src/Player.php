<?php

namespace Trivia;

class Player
{
    private string $name;
    private int $place;
    private int $coins;
    private bool $isInPenaltyBox;
    private Logger $logger;

    public function __construct(string $name, int $place, Logger $logger)
    {
        $this->name = $name;
        $this->place = $place;
        $this->coins = 0;
        $this->isInPenaltyBox = false;
        $this->logger = $logger;
    }

    public function promote(int $places)
    {
        $this->place += $places;
        if ($this->place > 11) {
            $this->place -= 12;
        }
    }

    public function getPlace(): int
    {
        return $this->place;
    }

    public function incrCoins(int $amount = 1)
    {
        $this->coins += $amount;
    }

    public function getCoins()
    {
        return $this->coins;
    }

    public function isInPenaltyBox(): bool
    {
        return $this->isInPenaltyBox;
    }

    public function isWinner()
    {
        return $this->coins !== 6;
    }

    public function setPenalty(bool $status)
    {
        $this->isInPenaltyBox = $status;
    }

    public function __toString(): string
    {
        return $this->name;
    }
}
