<?php

class Gambler {
    // свойства:
    public $name;
    public $coins;

    public function __construct($name, $coins)
    {
        $this->name = $name; // $this это локальная переменная, которая отсылает к текущему объекту
        $this->coins = $coins;
    }
}

class Game {
    // Свойства класса Game:
    protected $gambler1;
    protected $gambler2;
    protected $flips = 1;

    // Метод-конструктор - автоматически вызывается при создании нового объекта
    public function __construct(Gambler $gambler1, Gambler $gambler2)
    {
        $this->gambler1 = $gambler1;
        $this->gambler2 = $gambler2;
    }

    // Метод
    public function start()
    {
        // ∞ цикл
        while (true) {
            $flip = rand(0, 1) ? "орел" : "решка";

            //Орел - П1 получает монету, П2 теряет
            if ($flip == "орел") {
                $this->gambler1->coins++; //т.е. интерпретатор видит это как $game->...
                $this->gambler2->coins--;
            } else { //Решка - П2 получает монету, П1 теряет
                $this->gambler1->coins--;
                $this->gambler2->coins++;
            }
            //Как только один из игроков потеряет все монеты - GAME OVER.
            if ($this->gambler1->coins == 0 || $this->gambler2->coins == 0) {
                return $this->end();
            }

            $this->flips++;
        }
    }
    // победителем становится игрок, с бОльшим кол-вом монет
    public function winner()
    {
        if ($this->gambler1->coins > $this->gambler2->coins) {
            return $this->gambler1;
        }else{
            return $this->gambler2;
        }
    }

    public function end()
    {
        // <<<Heredoc - удобный формат для работы с большим кол-вом текста
        echo <<<GAMEOVER
        ////////////////////////
            GAME OVER
            
            The Winner is ... {$this->winner()->name}
            
            flips quantity: {$this->flips}
        ////////////////////////    
        GAMEOVER;
    }
}

// объект
$game = new Game (
    new Gambler("Jack", 100), //задаем значения свойствам, которые принимает конструктор класса Gambler
    new Gambler("Dave", 100)
);

$game->start();

