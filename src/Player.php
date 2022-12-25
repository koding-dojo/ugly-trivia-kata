<?php

namespace Trivia;

class Player
{
    private string $name;
    private int $coins;
    private int $place;
    private bool $isPenalized;

    public function __construct(string $playerName)
    {
        $this->name = $playerName;
        $this->coins = 0;
        $this->place = 0;
        $this->isPenalized = false;
    }

    public function __toString()
    {
        return $this->name;
    }

    public function advance(int $roll)
    {
        $this->place += $roll;
        if ($this->place >= 12) { // max 6 players + max die number is 6
            $this->place -= 12;
        }
    }

    public function getPlace()
    {
        return $this->place;
    }

    public function addCoin()
    {
        $this->coins++;
    }

    public function getCoins()
    {
        return $this->coins;
    }

    public function isWinner()
    {
        return $this->coins >= 6;
    }

    public function penalize()
    {
        $this->isPenalized = true;
    }

    public function isPenalized()
    {
        return $this->isPenalized;
    }
}
