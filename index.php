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

    // метод выполнения действий, в случае победы в одном броске
    public function point(Gambler $gambler)
    {
        $this->coins++;
        $gambler->coins--;
    }

    //метод банкротства
    public function bankrupt()
    {
        return $this->coins == 0;
    }

    // метод вычисления банка игроков
    public function bank()
    {
        return $this->coins;
    }

    // метод вычисления шансов на победу
    public function odds(Gambler $gambler)
    {
        return round($this->bank() / ($this->bank() + $gambler->bank()), 2) * 100 . '%';
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

    public function flip(): string // возвращаемый тип данных (жесткая типизация omg)
    {
        return rand(0, 1) ? "орел" : "решка";
    }

    public function start()
    {
        echo <<<START
        ////////////////////////////////////////////
            THE GAME BEGINS:
            
            {$this->gambler1->name}'s odds: {$this->gambler1->odds($this->gambler2)}
            {$this->gambler2->name}'s odds: {$this->gambler2->odds($this->gambler1)}\n
        START;

        $this->play();
    }

    // Метод
    public function play()
    {
        // ∞ цикл
        while (true) {

            //Орел - П1 получает монету, П2 теряет
            if ($this->flip() == "орел") {
                $this->gambler1->point($this->gambler2); //т.е. интерпретатор видит это как $game->gambler1...
            } else { //Решка - П2 получает монету, П1 теряет
                $this->gambler2->point($this->gambler1);
            }
            //Как только один из игроков потеряет все монеты - GAME OVER.
            if ($this->gambler1->bankrupt() || $this->gambler2->bankrupt()) {
                return $this->end();
            }

            $this->flips++;
        }
    }
    // победителем становится игрок, с бОльшим кол-вом монет
    public function winner(): Gambler
    {
        return $this->gambler1->bank() > $this->gambler2->bank() ? $this->gambler1 : $this->gambler2;
    }

    public function end()
    {
        // <<<Heredoc - удобный формат для работы с большим кол-вом текста
        echo <<<GAMEOVER
        ////////////////
            GAME OVER:
            
            The Winner is ... {$this->winner()->name}
            flips quantity: {$this->flips}
        ////////////////////////////////////////////    
        GAMEOVER;
    }
}

// объект класса Game, который принимает в себя, как атрибуты - 2 объекта класса Gambler
$game = new Game (
    new Gambler("Jack", 100), // объект класса Gambler
    new Gambler("Dave", 100)//задаем значения свойствам, которые принимает конструктор класса Gambler
);

$game->start();

