<?php

namespace Dnt3\PokerDoker\App;

use Dnt3\PokerDoker\App\Figures;

class Lap
{

    public $playersCards;

    public function __construct()
    {
        $this->figures = new Figures();
    }

    public function getFirst($foo)
    {
        foreach ($foo as $k => $v) {
            return $k;
        }
    }

    public function players()
    {
        $this->playersCards = [];
        foreach ($this->data['players'] as $playerId => $player) {
            $cards = array_merge($this->data['players'][$playerId], $this->data['table']);
            $this->playersCards[] = $this->figures->bestFigure($cards);
        }
    }

    public function factorial($n)
    {
        if ($n <= 1) {
            return 1;
        }
        return $n * $this->factorial($n - 1);
    }

    public function sumFifthCard($fifthCards)
    {
        $final = 0;
        foreach ($fifthCards as $card => $value) {
            $final += $this->factorial($card);
        }
        return $final;
    }

    public function setWinner()
    {

        foreach ($this->playersCards as $pleyerID => $data) {
            $final[$pleyerID] = [
                'playerID' => $pleyerID,
                'figure_weight' => $data['figure_weight'],
                'playing_cards' => $data['playing_cards'],
                'fifth_card' => $data['fifth_card'],
                'fifth_card_sum' => $this->sumFifthCard($data['fifth_card']),
                'points' => $data['points'],
            ];
        }

        usort($final, function($a, $b) {
            return $b['figure_weight'] <=> $a['figure_weight'];
        });

        $sorted = [];
        foreach ($final as $figure) {
            $sorted[$figure['figure_weight']][$figure['points']][$figure['fifth_card_sum']][] = $figure;
        }
        $finalStand = [];
        $figures = $sorted;
        foreach ($figures as $figure) {
            krsort($figure);
            foreach ($figure as $points) {
                krsort($points);
                foreach ($points as $fifthCard) {
                    krsort($fifthCard);
                    foreach ($fifthCard as $card) {
                        $key = $card['figure_weight'] . '-' . $card['points'] . '-' . $card['fifth_card_sum'];
                        $finalStand[$key][] = $card;
                    }
                }
            }
        }

        $setPoints = [];
        $pointsForStand = 0;
        foreach ($finalStand as $stend) {
            $countForDraw = count($stend);
            if ($countForDraw > 1) {
                $pointsForStand++;
                foreach ($stend as $player) {
                    $player['order'] = $pointsForStand;
                    $setPoints[] = $player;
                }
            } else {
                foreach ($stend as $player) {
                    $pointsForStand++;
                    $player['order'] = $pointsForStand;
                    $setPoints[] = $player;
                }
            }
        }
        $finalStandPlayer = [];
        foreach ($setPoints as $item) {
            $finalStandPlayer[$item['playerID']] = $item;
        }

        $this->finalStand = $setPoints;
        $this->finalStandPlayer = $finalStandPlayer;
        //var_dump($setPoints);
        $this->winner = $setPoints[0]['playerID'];
        //var_dump($finalStand);
    }

    public function init($data)
    {
        $this->data = $data;
        $this->players();
        $this->setWinner();
        $this->playersCards['winner'] = $this->winner;
        $this->playersCards['final_stand'] = $this->finalStandPlayer;
        $this->playersCards['cards'] = $this->data;
        //var_dump($this->playersCards);
    }

}
