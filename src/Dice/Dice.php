<?php

namespace Vibe\Dice;

/**
* Dice class.
*/
class Dice
{
    public $rolls = [];
    public $scorePlayerOne = 0;
    public $scorePlayerTwo = 0;
    public $turn = 1;

    public function roll()
    {
        $value = rand(1, 6);
        array_push($this->rolls, $value);
    }

    public function returnList()
    {
        return $this->rolls;
    }

    public function checkTurn()
    {
        if ($this->turn % 2 == 0) {
            return $player = 2;
        } else {
            return $player = 1;
        }
    }

    public function checkRolls()
    {
        if (in_array(1, $this->rolls)) {
            $text = "You rolled 1, better luck next time.";
            $this->turn = $this->turn + 1;
            $this->resetGame();
            return $text;
        }
    }

    public function getTotal()
    {
        if (!empty($this->rolls)) {
            if ($this->turn % 2 == 0) {
                $this->scorePlayerTwo = $this->scorePlayerTwo + array_sum($this->rolls);
            } else {
                $this->scorePlayerOne = $this->scorePlayerOne + array_sum($this->rolls);
            }
            $this->turn = $this->turn + 1;
            $this->resetGame();
        }
    }

    public function resetGame()
    {
        $this->rolls = array();
    }

    public function checkWinner()
    {
        if ($this->scorePlayerOne >= 100) {
            return "Player One Won!";
        } else if ($this->scorePlayerTwo >= 100) {
            return "Player Two Won!";
        }
    }
}
