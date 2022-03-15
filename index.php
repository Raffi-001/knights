<?php

class Dice
{
    public $faces = [1, 2, 3, 4, 5, 6];

    public function roll()
    {
        $value = array_rand($this->faces);

        echo 'Dice rolled and value is: ' . $value . "\n";
        return $value;
    }
}

class Character
{
    public $impact = 1;

    public $energy = 100;

    public $name = '';

    public function __construct ($name)
    {
        $this->name = $name;
    }

    public function reduceEnergy($enemyImpact)
    {
        $this->energy = $this->energy - $enemyImpact;
    }
}

class Knight extends Character
{

}

class Game
{
    public $dice;

    public $characters;

    public $currentCharacterIndex = 0;

    public $victimCharacterIndex = 1;

    public function __construct (Dice $dice, array $characters)
    {
        $this->dice = $dice;
        $this->characters = $characters;
    }

    public function run ()
    {
        echo "Start the game \n";

        while(count($this->characters) > 1) {
            $this->changeCharacter();
            $this->changeVictimCharacter();

            $diceValue = $this->dice->roll();

            $this->attack($diceValue);

            // array_shift($this->characters);
        }

        echo "Winner is" . $this->characters[0]->name;
    }

    public function attack($diceValue)
    {
        $character = $this->characters[$this->currentCharacterIndex];
        $victim = $this->characters[$this->victimCharacterIndex];


        $victim->reduceEnergy($diceValue * $character->impact);


        if($victim->energy <= 0) {
            unset($this->characters[$this->victimCharacterIndex]);
            $this->characters = array_values($this->characters);
        }
    }

    public function changeCharacter ()
    {
        $this->currentCharacterIndex++;

        if($this->currentCharacterIndex >= count($this->characters)) {
            $this->currentCharacterIndex = 0;
        }

        echo 'Character changed. Current character is: ' . $this->characters[$this->currentCharacterIndex]->name . "\n";
    }

    public function changeVictimCharacter()
    {
        $this->victimCharacterIndex = $this->currentCharacterIndex + 1;

        if($this->victimCharacterIndex >= count($this->characters)) {
            $this->victimCharacterIndex = 0;
        }

        echo 'Victim is: ' . $this->characters[$this->victimCharacterIndex]->name . "\n";
    }
}




/**
 * Client code
 */

$game = new Game(
    new Dice,
    [
        new Knight('Knight 1'),
        new Knight('Knight 2'),
        new Knight('Knight 3'),
        new Knight('Knight 4'),
        new Knight('Knight 5'),
        new Knight('Knight 6'),
        new Knight('Knight 7'),
    ]
);
$game->run();
