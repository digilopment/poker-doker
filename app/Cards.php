<?php

namespace Dnt3\App\Poker;

class Cards
{

    public $package;
    public $players;
    public $dealedCards;

    public function cards($color)
    {
        return [
            '14' => $color . '-A',
            '2' => $color . '-2',
            '3' => $color . '-3',
            '4' => $color . '-4',
            '5' => $color . '-5',
            '6' => $color . '-6',
            '7' => $color . '-7',
            '8' => $color . '-8',
            '9' => $color . '-9',
            '10' => $color . '-10',
            '11' => $color . '-J',
            '12' => $color . '-Q',
            '13' => $color . '-K'
        ];
    }

    public function setPlayers($players)
    {
        $this->players = count($players);
    }

    public function colors()
    {
        return [
            '1' => '♥',
            '2' => '♦',
            '3' => '♠',
            '4' => '♣',
        ];
    }

    public function setPackage()
    {
        $package = [];
        foreach ($this->colors() as $color) {
            foreach ($this->cards($color) as $card) {
                $package[] = $card;
            }
        }
        $this->package = $package;
    }

    public function mixPackage()
    {
        shuffle($this->package);
    }

    public function dealCards()
    {
        $final = [];

        $c = 0;
        foreach (range(1, $this->players) as $playerId => $player) {
            for ($i = 0; $i <= 1; $i++) {
                $final['players'][$playerId][] = $this->package[$c];
                $c++;
            }
        }

        for ($i = $c; $i < $c + 5; $i++) {
            $final['table'][] = $this->package[$i];
        }
        $this->dealedCards = $final;
    }

}
