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

class Witch extends Character
{
    public $impact = 2;

    public $energy = 50;
}

class Rules
{
    public $nextCharacterRule = 'next';

    public function __construct($nextCharacterRule) {
        $this->nextCharacterRule = $nextCharacterRule;
    }

    public function changeCharacter($characters, $currentCharacterIndex)
    {
        switch ($this->nextCharacterRule) {

            case 'previous':

                $currentCharacterIndex--;

                if($currentCharacterIndex < 0) {
                    $currentCharacterIndex = count($characters) - 1;
                }

                echo 'Character changed. Current character is: ' . $characters[$currentCharacterIndex]->name . "\n";

            break;

            case 'random':

                $currentCharacterIndex = array_rand($characters);

            break;

            default:

                $currentCharacterIndex++;

                if($currentCharacterIndex >= count($characters)) {
                    $currentCharacterIndex = 0;
                }

                echo 'Character changed. Current character is: ' . $characters[$currentCharacterIndex]->name . "\n";

            break;
        }

        return $currentCharacterIndex;


    }
}


class Game
{
    public $dice;

    public $characters;

    public $currentCharacterIndex = 0;

    public $victimCharacterIndex = 1;

    public $rules = null;

    public function __construct (Dice $dice, array $characters, Rules $rules)
    {
        $this->dice = $dice;
        $this->characters = $characters;
        $this->rules = $rules;
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

        $this->currentCharacterIndex = $this->rules->changeCharacter($this->characters, $this->currentCharacterIndex);

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
        new Witch('Witch 1'),
        new Witch('Witch 2'),
    ],
    new Rules('previous')
);
$game->run();
