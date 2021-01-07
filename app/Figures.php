<?php

namespace Dnt3\App\Poker;

class Figures extends Figure
{

    public function highestCard($rawCards)
    {
        $cards = $this->getCardNumber($rawCards);
        $selectedCards = [$cards[6]];
        $playingCard = [];
        $fifthCard = [];
        $i = 0;

        $rawCards = $this->sortCards($rawCards);
        foreach ($rawCards as $singlCard) {
            $card = (int) $this->getCardNumber([$singlCard])[0];
            if (in_array($card, $selectedCards)) {
                $playingCard[] = $singlCard;
            } else {
                if ($i < 4) {
                    $fifthCard[$card] = $singlCard;
                    $i++;
                }
            }
        }

        krsort($fifthCard);

        $return = [
            'is_figure' => true,
            'cards' => $rawCards,
            'playing_cards' => $playingCard,
            'fifth_card' => $fifthCard,
            'figure' => 'Hight Card',
            'points' => $cards[6],
            'figure_weight' => 0,
        ];
        return $return;
    }

    public function onePair($rawCards)
    {
        $rawCards = $this->sortCards($rawCards);
        $cards = $this->getCardNumber($rawCards);
        $return = ['is_figure' => false];
        $points = 0;
        $selectedCards = [];
        foreach (array_count_values($cards) as $card => $val) {
            if ($val == 2) {
                $points = 2 * $this->factorial($card);
                $selectedCards[] = $card;
            }
        }

        $playingCard = [];
        $fifthCard = [];
        $i = 0;
        foreach ($rawCards as $singlCard) {
            $card = (int) $this->getCardNumber([$singlCard])[0];
            if (in_array($card, $selectedCards)) {
                $playingCard[] = $singlCard;
            } else {
                if ($i < 3) {
                    $fifthCard[$card] = $singlCard;
                    $i++;
                }
            }
        }
        krsort($fifthCard);
        if (in_array(2, array_count_values($cards))) {
            $return = [
                'is_figure' => true,
                'cards' => $cards,
                'playing_cards' => $playingCard,
                'fifth_card' => $fifthCard,
                'figure' => 'One Pair',
                'points' => $points,
                'figure_weight' => 1,
            ];
        }
        return $return;
    }

    public function twoPair($rawCards)
    {
        $rawCards = $this->sortCards($rawCards);
        $cards = $this->getCardNumber($rawCards);
        $return = ['is_figure' => false];
        $selectedCards = [];
        $filter = array_count_values($cards);
        krsort($filter);
        $i = 1;
        $points = 0;
        foreach ($filter as $card => $val) {
            if ($i <= 2) {
                if ($val == 2) {
                    $points += 2 * $this->factorial($card);
                    $selectedCards[] = $card;
                    $i++;
                }
            }
        }



        $playingCard = [];
        $fifthCard = [];
        $j = 0;
        foreach ($rawCards as $singlCard) {
            $card = (int) $this->getCardNumber([$singlCard])[0];
            if (in_array($card, $selectedCards)) {
                $playingCard[] = $singlCard;
            } else {
                if ($j < 1) {
                    $fifthCard[$card] = $singlCard;
                    $j++;
                }
            }
        }
        if ($i > 2) {
            $return = [
                'is_figure' => true,
                'cards' => $cards,
                'playing_cards' => $playingCard,
                'fifth_card' => $fifthCard,
                'figure' => 'Two Pairs',
                'points' => $points,
                'figure_weight' => 2,
            ];
        }
        return $return;
    }

    public function threeOfKind($rawCards)
    {
        $rawCards = $this->sortCards($rawCards);
        $cards = $this->getCardNumber($rawCards);
        $return = ['is_figure' => false];
        $points = 0;
        $selectedCards = [];
        foreach (array_count_values($cards) as $card => $val) {
            if ($val == 3) {
                $points = 3 * $this->factorial($card);
                $selectedCards[] = $card;
            }
        }

        $playingCard = [];
        $fifthCard = [];
        $j = 0;
        foreach ($rawCards as $singlCard) {
            $card = (int) $this->getCardNumber([$singlCard])[0];
            if (in_array($card, $selectedCards)) {
                $playingCard[] = $singlCard;
            } else {
                if ($j < 2) {
                    $fifthCard[$card] = $singlCard;
                    $j++;
                }
            }
        }

        if (in_array(3, array_count_values($cards))) {
            $return = [
                'is_figure' => true,
                'cards' => $cards,
                'playing_cards' => $playingCard,
                'fifth_card' => $fifthCard,
                'figure' => 'Three Of Kind',
                'points' => $points,
                'figure_weight' => 3,
            ];
        }
        return $return;
    }

    public function straight($rawCards)
    {
        $cards = $this->getCardNumber($rawCards);
        $return = ['is_figure' => false];
        $sequences = $this->arraySequence($cards);
        $longestSequence = $this->getLongestArray($sequences);
        arsort($longestSequence);

        $playingCard = [];
        $rawCardsUnique = [];
        foreach ($rawCards as $singlCard) {
            $card = (int) $this->getCardNumber([$singlCard])[0];
            $rawCardsUnique[$card] = $singlCard;
        }
        krsort($rawCardsUnique);

        $i = $points = 0;
        foreach ($rawCardsUnique as $singlCard) {
            $card = (int) $this->getCardNumber([$singlCard])[0];
            if ($i < 5) {
                if (in_array($card, $longestSequence)) {
                    $playingCard[$card] = $singlCard;
                    $points += $this->factorial($card);
                    $i++;
                }
            }
        }
        krsort($playingCard);
        if (count($longestSequence) >= 5) {
            $return = [
                'is_figure' => true,
                'cards' => $cards,
                'playing_cards' => $playingCard,
                'fifth_card' => [],
                'figure' => 'Straight',
                'points' => $points,
                'figure_weight' => 4,
            ];
        }
        return $return;
    }

    public function theFlush($rawCards)
    {
        $cards = $this->getCardColor($rawCards);
        $return = ['is_figure' => false];

        $final = [];
        foreach ($rawCards as $card) {
            $symbol = explode('-', $card)[0];
            $number = explode('-', $card)[1];
            $final[$symbol][] = $number;
        }
        $sorted = $this->getLongestArray($final);
        arsort($sorted);

        $symbol = 'X';
        foreach (array_count_values($cards) as $k => $v) {
            if ($v == 5 || $v == 6 || $v == 7) {
                $symbol = $k;
            }
        }

        $i = $points = 0;
        $playingCard = [];
        foreach ($sorted as $item) {
            if ($i < 5) {
                $card = $symbol . '-' . $item;
                $playingCard[] = $card;
                $number = (int) $this->getCardNumber([$card])[0];
                $points += $this->factorial($number);
                $i++;
            }
        }

        if (in_array(5, array_count_values($cards)) || in_array(6, array_count_values($cards)) || in_array(7, array_count_values($cards))) {
            $return = [
                'is_figure' => true,
                'cards' => $cards,
                'playing_cards' => $playingCard,
                'fifth_card' => [],
                'figure' => 'Flush',
                'points' => $points,
                'figure_weight' => 5,
            ];
        }
        return $return;
    }

    public function fullHouse($rawCards)
    {
        $cards = $this->getCardNumber($rawCards);
        $return = ['is_figure' => false];
        $points = 0;
        $selectedCards = [];
        $filter = array_count_values($cards);
        arsort($filter);
        $sorted = [];
        foreach ($filter as $k => $v) {
            if (isset($sorted[$v])) {
                if ($sorted[$v][0] < $k) {
                    $sorted[$v][0] = $k;
                }
            } else {
                $sorted[$v][] = $k;
            }
        }

        $filter = [];
        foreach ($sorted as $k => $v) {
            $filter[$k] = $v[0];
        }
        foreach ($filter as $val => $card) {
            if ($val == 3) {
                $points += 3 * $this->factorial($card);
                $selectedCards[] = $card;
            }
            if ($val == 2) {
                $points += 2 * $this->factorial($card);
                $selectedCards[] = $card;
            }
            if ($val == 4) {
                $points += 3 * $this->factorial($card);
                $selectedCards[] = $card;
            }
        }

        $playingCard = [];
        foreach ($rawCards as $singlCard) {
            $card = (int) $this->getCardNumber([$singlCard])[0];
            if (in_array($card, $selectedCards)) {
                $playingCard[] = $singlCard;
            }
        }
        if (
                (in_array(3, array_count_values($cards)) && in_array(2, array_count_values($cards))) ||
                (in_array(4, array_count_values($cards)) && in_array(2, array_count_values($cards))) ||
                (in_array(3, array_count_values($cards)) && in_array(4, array_count_values($cards)))
        ) {
            $return = [
                'is_figure' => true,
                'cards' => $cards,
                'playing_cards' => $playingCard,
                'fifth_card' => [],
                'figure' => 'Full House',
                'points' => $points,
                'figure_weight' => 6,
            ];
        }
        return $return;
    }

    public function poker($rawCards)
    {
        $rawCards = $this->sortCards($rawCards);
        $cards = $this->getCardNumber($rawCards);
        $return = ['is_figure' => false];
        $selectedCards = [];
        foreach (array_count_values($cards) as $card => $val) {
            if ($val == 4) {
                $points = 4 * $this->factorial($card);
                $selectedCards[] = $card;
            }
        }

        $playingCard = [];
        $fifthCards = [];
        $j = 0;
        foreach ($rawCards as $singlCard) {
            $card = (int) $this->getCardNumber([$singlCard])[0];
            if (in_array($card, $selectedCards)) {
                $playingCard[] = $singlCard;
            } else {
                if ($j < 1) {
                    $fifthCards[$card] = $singlCard;
                    $j++;
                }
            }
        }

        if (in_array(4, array_count_values($cards))) {
            $return = [
                'is_figure' => true,
                'cards' => $cards,
                'playing_cards' => $playingCard,
                'fifth_card' => $fifthCards,
                'figure' => 'Poker',
                'points' => $points,
                'figure_weight' => 7,
            ];
        }
        return $return;
    }

    public function straightFlush($cards)
    {
        $return = ['is_figure' => false];
        $flush = $this->theFlush($cards);
        $straight = $this->straight($cards);
        if (isset($straight['playing_cards']) && isset($flush['playing_cards'])) {
            if (in_array(5, array_count_values($this->getCardColor($straight['playing_cards'])))) {
                $return = [
                    'is_figure' => true,
                    'cards' => $cards,
                    'playing_cards' => $straight['playing_cards'],
                    'fifth_card' => [],
                    'figure' => 'Straight Flush',
                    'points' => $flush['points'],
                    'figure_weight' => 8,
                ];
            }
        }
        return $return;
    }

    public function royalFlush($cards)
    {
        $return = ['is_figure' => false];
        $straightFlush = $this->straightFlush($cards);
        if (isset($straightFlush['playing_cards'])) {
            if (current($straightFlush['playing_cards']) == '♥-A' || current($straightFlush['playing_cards']) == '♦-A' || current($straightFlush['playing_cards']) == '♠-A' || current($straightFlush['playing_cards']) == '♣-A') {
                $return = [
                    'is_figure' => true,
                    'cards' => $cards,
                    'playing_cards' => $straightFlush['playing_cards'],
                    'fifth_card' => [],
                    'figure' => 'Royal Flush',
                    'points' => $straightFlush['points'],
                    'figure_weight' => 9,
                ];
            }
        }
        return $return;
    }

}
