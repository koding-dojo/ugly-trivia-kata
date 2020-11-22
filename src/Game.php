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

    private Logger $logger;

    public function __construct(Logger $logger = null)
    {
        if (empty($logger)) {
            $this->logger = new EchoLogger();
        } else {
            $this->logger = $logger;
        }
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
        return ($this->playersCount() >= 2);
    }

    public function addPlayer($playerName)
    {
        $this->players[] = new Player($playerName, 0, $this->logger);
        $this->logger->log($playerName . " was added");
        $this->logger->log("They are player number " . $this->playersCount());
        return true;
    }

    public function playersCount()
    {
        return count($this->players);
    }

    public function roll($roll)
    {
        $this->logger->log($this->players[$this->currentPlayer] . " is the current player");
        $this->logger->log("They have rolled a " . $roll);

        if ($this->players[$this->currentPlayer]->isPenalized()) {
            if ($roll % 2 != 0) {
                $this->isGettingOutOfPenaltyBox = true;
                $this->logger->log($this->players[$this->currentPlayer] . " is getting out of the penalty box");
                $this->movePlace($roll);
            } else {
                $this->logger->log($this->players[$this->currentPlayer] . " is not getting out of the penalty box");
                $this->isGettingOutOfPenaltyBox = false;
            }
        } else {
            $this->movePlace($roll);
        }
    }

    public function askQuestion()
    {
        if ($this->currentCategory() == "Pop") {
            $this->logger->log(array_shift($this->popQuestions));
        }
        if ($this->currentCategory() == "Science") {
            $this->logger->log(array_shift($this->scienceQuestions));
        }
        if ($this->currentCategory() == "Sports") {
            $this->logger->log(array_shift($this->sportsQuestions));
        }
        if ($this->currentCategory() == "Rock") {
            $this->logger->log(array_shift($this->rockQuestions));
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
        $this->logger->log("Question was incorrectly answered");
        $this->players[$this->currentPlayer]->penalize();
        $this->moveTurn();
        return true;
    }

    /**
     * @return bool
     */
    private function findWinner(): bool
    {
        if ($this->players[$this->currentPlayer]->isPenalized()) {
            if ($this->isGettingOutOfPenaltyBox) {
                $this->logger->log("Answer was correct!!!!");
            } else {
                return true;
            }
        } else {
            $this->logger->log("Answer was corrent!!!!");
        }

        $this->addCoins();
        return $this->players[$this->currentPlayer]->isWinner();
    }

    private function addCoins(): void
    {
        $this->players[$this->currentPlayer]->incrCoins();
    }

    private function moveTurn(): void
    {
        $this->currentPlayer++;
        if ($this->currentPlayer == $this->playersCount()) {
            $this->currentPlayer = 0;
        }
    }

    /**
     * @param $roll
     */
    private function movePlace($roll): void
    {
        $this->players[$this->currentPlayer]->promote($roll);
        $this->logger->log("The category is " . $this->currentCategory());
        $this->askQuestion();
    }
}
