<?php

namespace Dnt3\App\Poker;

class Figure
{

    public function prepareCard($card)
    {
        $card = str_replace('J', 11, $card);
        $card = str_replace('Q', 12, $card);
        $card = str_replace('K', 13, $card);
        $card = str_replace('A', 14, $card);
        return $card;
    }

    public function factorial($n)
    {
        if ($n <= 1) {
            return 1;
        }
        return $n * $this->factorial($n - 1);
    }

    public function getCardNumber($cards)
    {
        $unsorted = [];
        $final = [];
        foreach ($cards as $card) {
            $card = $this->prepareCard($card);
            $arr = explode('-', $card);
            $unsorted[] = isset($arr[1]) ? $arr[1] : false;
        }
        asort($unsorted);
        foreach ($unsorted as $sorted) {
            $final[] = $sorted;
        }
        return $final;
    }

    function arraySequence($arr)
    {
        $min = $arr[0];
        $max = $arr[count($arr) - 1];
        $comparationArr = range($min, $max);
        $diffed = array_diff($comparationArr, $arr);
        $final = [];
        if (count($diffed) > 0) {
            $reIndex = [];
            foreach ($diffed as $item) {
                $reIndex[] = $item;
            }
            $i = 0;
            foreach ($comparationArr as $item) {
                if (!in_array($item, $diffed)) {
                    $final[$i][] = $item;
                } else {
                    $i++;
                }
            }
        } else {
            $final = $comparationArr;
        }
        return $final;
    }

    public function sortCards($rawCards)
    {
        $prepare = [];
        $final = [];
        foreach ($rawCards as $card) {
            $number = (int) $this->getCardNumber([$card])[0];
            $prepare[] = ['value' => $number, 'card' => $card];
        }

        usort($prepare, function($a, $b) {
            return $b['value'] <=> $a['value'];
        });

        foreach ($prepare as $card) {
            $final[] = $card['card'];
        }

        return $final;
    }

    public function getLongestArray($arr)
    {
        $unsorted = [];
        $final = [];
        foreach ($arr as $child) {
            if (is_array($child)) {
                $unsorted[count($child)] = $child;
            } else {
                $unsorted[0] = $arr;
            }
        }
        krsort($unsorted);
        foreach ($unsorted as $child) {
            $final[] = $child;
        }
        return isset($final[0]) ? $final[0] : [];
    }

    public function getCardColor($cards)
    {
        $final = [];
        foreach ($cards as $card) {
            $arr = explode('-', $card);
            $final[] = isset($arr[0]) ? $arr[0] : false;
        }
        return $final;
    }

    protected function setFrom($data)
    {
        $cards = $data['playing_cards'];
        $final = [];
        foreach ($cards as $card) {
            $symbol = explode('-', $card)[0];
            $number = explode('-', $card)[1];
            $final[] = $symbol;
        }
        $sorted = array_count_values($final);
        arsort($sorted);
        return current($sorted);
    }

    public function bestFigure($cards)
    {
        $royalFlush = $this->royalFlush($cards);
        $straightFlush = $this->straightFlush($cards);
        $poker = $this->poker($cards);
        $fullHouse = $this->fullHouse($cards);
        $theFlush = $this->theFlush($cards);
        $straight = $this->straight($cards);
        $threeOfKind = $this->threeOfKind($cards);
        $twoPair = $this->twoPair($cards);
        $onePair = $this->onePair($cards);
        $highestCard = $this->highestCard($cards);

        if ($royalFlush['is_figure']) {
            return $royalFlush;
        } elseif ($straightFlush['is_figure']) {
            return $straightFlush;
        } elseif ($poker['is_figure']) {
            return $poker;
        } elseif ($fullHouse['is_figure']) {
            return $fullHouse;
        } elseif ($theFlush['is_figure']) {
            return $theFlush;
        } elseif ($straight['is_figure']) {
            return $straight;
        } elseif ($threeOfKind['is_figure']) {
            return $threeOfKind;
        } elseif ($twoPair['is_figure']) {
            return $twoPair;
        } elseif ($onePair['is_figure']) {
            return $onePair;
        } elseif ($highestCard['is_figure']) {
            return $highestCard;
        }
    }

}
