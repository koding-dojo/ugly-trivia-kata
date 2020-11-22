<?php

namespace Trivia;

class Game
{
    private array $players;
    private array $places;
    private array $purses;
    private array $inPenaltyBox;

    private array $popQuestions;
    private array $scienceQuestions;
    private array $sportsQuestions;
    private array $rockQuestions;

    private int $currentPlayer = 0;
    private bool $isGettingOutOfPenaltyBox;

    public function __construct()
    {
        $this->players = array();
        $this->places = array(0);
        $this->purses  = array(0);
        $this->inPenaltyBox  = array(0);

        $this->popQuestions = array();
        $this->scienceQuestions = array();
        $this->sportsQuestions = array();
        $this->rockQuestions = array();

        for ($i = 0; $i < 50; $i++) {
            $this->popQuestions[] = "Pop Question $i" . $i;
            $this->scienceQuestions[] = "Science Question " . $i;
            $this->sportsQuestions[] = "Sports Question " . $i;
            $this->rockQuestions[] = $this->createRockQuestion($i);
        }
    }

    public function createRockQuestion($index)
    {
        return "Rock Question " . $index;
    }

    public function isPlayable()
    {
        return ($this->howManyPlayers() >= 2);
    }

    public function add($playerName)
    {
        $this->players[] = $playerName;
        $this->places[$this->howManyPlayers()] = 0;
        $this->purses[$this->howManyPlayers()] = 0;
        $this->inPenaltyBox[$this->howManyPlayers()] = false;

        static::echoln($playerName . " was added");
        static::echoln("They are player number " . $this->howManyPlayers());
        return true;
    }

    public function howManyPlayers()
    {
        return count($this->players);
    }

    public function roll($roll)
    {
        static::echoln($this->players[$this->currentPlayer] . " is the current player");
        static::echoln("They have rolled a " . $roll);

        if ($this->inPenaltyBox[$this->currentPlayer]) {
            if ($roll % 2 != 0) {
                $this->isGettingOutOfPenaltyBox = true;

                static::echoln($this->players[$this->currentPlayer] . " is getting out of the penalty box");
                $this->places[$this->currentPlayer] = $this->places[$this->currentPlayer] + $roll;
                if ($this->places[$this->currentPlayer] > 11) {
                    $this->places[$this->currentPlayer] = $this->places[$this->currentPlayer] - 12;
                }

                static::echoln($this->players[$this->currentPlayer]
                    . "'s new location is "
                    . $this->places[$this->currentPlayer]);
                static::echoln("The category is " . $this->currentCategory());
                $this->askQuestion();
            } else {
                static::echoln($this->players[$this->currentPlayer] . " is not getting out of the penalty box");
                $this->isGettingOutOfPenaltyBox = false;
            }
        } else {
            $this->places[$this->currentPlayer] = $this->places[$this->currentPlayer] + $roll;
            if ($this->places[$this->currentPlayer] > 11) {
                $this->places[$this->currentPlayer] = $this->places[$this->currentPlayer] - 12;
            }

            static::echoln($this->players[$this->currentPlayer]
                . "'s new location is "
                . $this->places[$this->currentPlayer]);
            static::echoln("The category is " . $this->currentCategory());
            $this->askQuestion();
        }
    }

    public function askQuestion()
    {
        if ($this->currentCategory() == "Pop") {
            static::echoln(array_shift($this->popQuestions));
        }
        if ($this->currentCategory() == "Science") {
            static::echoln(array_shift($this->scienceQuestions));
        }
        if ($this->currentCategory() == "Sports") {
            static::echoln(array_shift($this->sportsQuestions));
        }
        if ($this->currentCategory() == "Rock") {
            static::echoln(array_shift($this->rockQuestions));
        }
    }

    public function currentCategory()
    {
        switch ($this->places[$this->currentPlayer] % 4) {
            case 0:
                return "Pop";
            case 1:
                return "Science";
            case 2:
                return "Sports";
            default:
                return "Rock";
        }
    }

    public function wasCorrectlyAnswered()
    {
        if ($this->inPenaltyBox[$this->currentPlayer]) {
            if ($this->isGettingOutOfPenaltyBox) {
                static::echoln("Answer was correct!!!!");
                return $this->findWinner();
            } else {
                $this->currentPlayer++;
                if ($this->currentPlayer == $this->howManyPlayers()) {
                    $this->currentPlayer = 0;
                }
                return true;
            }
        } else {
            static::echoln("Answer was corrent!!!!");
            return $this->findWinner();
        }
    }

    public function wrongAnswer()
    {
        static::echoln("Question was incorrectly answered");
        static::echoln($this->players[$this->currentPlayer] . " was sent to the penalty box");
        $this->inPenaltyBox[$this->currentPlayer] = true;

        $this->currentPlayer++;
        if ($this->currentPlayer == $this->howManyPlayers()) {
            $this->currentPlayer = 0;
        }
        return true;
    }


    public function didPlayerWin()
    {
        return !($this->purses[$this->currentPlayer] == 6);
    }

    protected static function echoln($string)
    {
        echo $string . "\n";
    }

    /**
     * @return bool
     */
    private function findWinner(): bool
    {
        $this->addCoins();

        $winner = $this->didPlayerWin();
        $this->currentPlayer++;
        if ($this->currentPlayer == $this->howManyPlayers()) {
            $this->currentPlayer = 0;
        }

        return $winner;
    }

    private function addCoins(): void
    {
        $this->purses[$this->currentPlayer]++;
        static::echoln($this->players[$this->currentPlayer]
            . " now has "
            . $this->purses[$this->currentPlayer]
            . " Gold Coins.");
    }
}
