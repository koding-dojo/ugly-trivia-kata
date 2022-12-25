<?php

namespace Trivia;

class Game
{
    private array $popQuestions;
    private array $scienceQuestions;
    private array $sportsQuestions;
    private array $rockQuestions;

    private int $turn = 0;
    private bool $isGettingOutOfPenaltyBox;
    private array $players;

    public function __construct()
    {
        $this->players = [];

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

    // ToDo hossein: potential bug.
    public function isPlayable()
    {
        return ($this->playersCount() >= 2);
    }

    public function add($playerName)
    {
        $this->players[] = new Player($playerName);

        self::echoln($playerName . " was added");
        self::echoln("They are player number " . $this->playersCount());
        return true;
    }

    public function playersCount()
    {
        return count($this->players);
    }

    public function roll($roll)
    {
        self::echoln($this->currentPlayer() . " is the current player");
        self::echoln("They have rolled a " . $roll);

        if ($this->currentPlayer()->isPenalized()) {
            if ($roll % 2 != 0) {
                $this->isGettingOutOfPenaltyBox = true;

                self::echoln($this->currentPlayer() . " is getting out of the penalty box");
                $this->advancePlayer($roll);
                $this->askQuestion();
            } else {
                self::echoln($this->currentPlayer() . " is not getting out of the penalty box");
                $this->isGettingOutOfPenaltyBox = false;
            }
        } else {
            $this->advancePlayer($roll);
            $this->askQuestion();
        }
    }

    public function askQuestion()
    {
        self::echoln("The category is " . $this->currentCategory());

        switch ($this->currentCategory()) {
            case "Pop":
                $question = array_shift($this->popQuestions);
                break;
            case "Science":
                $question = array_shift($this->scienceQuestions);
                break;
            case "Sports":
                $question = array_shift($this->sportsQuestions);
                break;
            case "Rock":
                $question = array_shift($this->rockQuestions);
                break;
            default:
                throw new \InvalidArgumentException();
        }

        self::echoln($question);
    }


    public function currentCategory(): string
    {
        switch ($this->currentPlayer()->getPlace() % 4) {
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
        if ($this->currentPlayer()->isPenalized()) {
            if ($this->isGettingOutOfPenaltyBox) {
                self::echoln("Answer was correct!!!!");
                $this->addCoins();
                $winner = $this->currentPlayer()->isWinner();
                $this->moveTurn();
                return $winner;
            } else {
                $this->moveTurn();
                return true;
            }
        } else {
            self::echoln("Answer was corrent!!!!"); // Typo
            $this->addCoins();
            $winner = $this->currentPlayer()->isWinner();
            $this->moveTurn();
            return $winner;
        }
    }

    public function wrongAnswer()
    {
        self::echoln("Question was incorrectly answered");
        self::echoln($this->currentPlayer() . " was sent to the penalty box");
        $this->currentPlayer()->penalize();
        $this->moveTurn();
        return true;
    }

    public static function echoln($string)
    {
        echo $string . "\n";
    }

    /**
     * @param $roll
     */
    private function advancePlayer($roll): void
    {
        $this->currentPlayer()->advance($roll);
        self::echoln($this->currentPlayer()
            . "'s new location is "
            . $this->currentPlayer()->getPlace());
    }

    private function addCoins(): void
    {
        $this->currentPlayer()->addCoin();
        self::echoln($this->currentPlayer()
            . " now has "
            . $this->currentPlayer()->getCoins()
            . " Gold Coins.");
    }

    private function moveTurn(): void
    {
        $this->turn++;
        if ($this->turn == $this->playersCount()) {
            $this->turn = 0;
        }
    }

    private function currentPlayer(): Player
    {
        return $this->players[$this->turn];
    }
}
