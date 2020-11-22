<?php

namespace Trivia;

class Game
{
    /** @var Player[] */
    private array $players;

    private array $popQuestions;
    private array $scienceQuestions;
    private array $sportsQuestions;
    private array $rockQuestions;

    private int $currentPlayer = 0;
    private bool $isGettingOutOfPenaltyBox;

    public function __construct()
    {
        $this->players = array();

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
        $this->players[] = new Player($playerName, 0);
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

        if ($this->players[$this->currentPlayer]->isInPenaltyBox()) {
            if ($roll % 2 != 0) {
                $this->isGettingOutOfPenaltyBox = true;
                static::echoln($this->players[$this->currentPlayer] . " is getting out of the penalty box");
                $this->movePlace($roll);
            } else {
                static::echoln($this->players[$this->currentPlayer] . " is not getting out of the penalty box");
                $this->isGettingOutOfPenaltyBox = false;
            }
        } else {
            $this->movePlace($roll);
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
        switch ($this->players[$this->currentPlayer]->getPlace() % 4) {
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
        $isWinner = $this->findWinner();
        $this->moveTurn();
        return $isWinner;
    }

    public function wrongAnswer()
    {
        static::echoln("Question was incorrectly answered");
        static::echoln($this->players[$this->currentPlayer] . " was sent to the penalty box");
        $this->players[$this->currentPlayer]->setPenalty(true);

        $this->moveTurn();
        return true;
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
        if ($this->players[$this->currentPlayer]->isInPenaltyBox()) {
            if ($this->isGettingOutOfPenaltyBox) {
                static::echoln("Answer was correct!!!!");
            } else {
                return true;
            }
        } else {
            static::echoln("Answer was corrent!!!!");
        }

        $this->addCoins();
        return $this->players[$this->currentPlayer]->isWinner();
    }

    private function addCoins(): void
    {
        $this->players[$this->currentPlayer]->incrCoins();
        static::echoln($this->players[$this->currentPlayer]
            . " now has "
            . $this->players[$this->currentPlayer]->getCoins()
            . " Gold Coins.");
    }

    private function moveTurn(): void
    {
        $this->currentPlayer++;
        if ($this->currentPlayer == $this->howManyPlayers()) {
            $this->currentPlayer = 0;
        }
    }

    /**
     * @param $roll
     */
    private function movePlace($roll): void
    {
        $this->players[$this->currentPlayer]->promote($roll);
        static::echoln($this->players[$this->currentPlayer]
            . "'s new location is "
            . $this->players[$this->currentPlayer]->getPlace());

        static::echoln("The category is " . $this->currentCategory());
        $this->askQuestion();
    }
}
