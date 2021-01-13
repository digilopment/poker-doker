<?php

namespace Dnt3\PokerDoker\App;

use Dnt3\PokerDoker\App\Cards;
use Dnt3\PokerDoker\App\Lap;

class Game
{

    public $playersCards;
    public $finalData = [];

    public function __construct($config)
    {
        $this->cards = new Cards();
        $this->lap = new Lap();
        $this->config = $config;
    }

    public function minify($html)
    {

        $search = array(
            '/\>[^\S ]+/s', // strip whitespaces after tags, except space
            '/[^\S ]+\</s', // strip whitespaces before tags, except space
            '/(\s)+/s', // shorten multiple whitespace sequences
            '/<!--(.|\s)*?-->/' // Remove HTML comments
        );
        $replace = array(
            '>',
            '<',
            '\\1',
            ''
        );
        return preg_replace($search, $replace, $html);
    }

    public function players()
    {
        return $this->config['players'];
    }

    public function addTemplate($tpl, $data)
    {
        ob_start();
        include 'templates/' . $tpl . '.php';
        $html = ob_get_clean();
        $this->html = $this->minify($html);
    }

    public function render()
    {
        print($this->html);
    }

    protected function setBudget($playerId, $playerData, $smallBlind, $budget)
    {

        $finalBudget = [];
        foreach ($budget as $k => $v) {
            if ($v > 0) {
                $finalBudget[$k] = $v;
            } else {
                $finalBudget[$k] = 0;
            }
        }

        $deposit = [];
        foreach ($finalBudget as $k => $v) {
            if ($v - $smallBlind >= 0) {
                $deposit[$k] = $smallBlind;
            } else {
                $testDeposit = $v - $smallBlind;
                if ($testDeposit <= 0 && $v > 0) {
                    $deposit[$k] = $v;
                } else {
                    $deposit[$k] = 0;
                }
            }
        }

        $firstPlayers = [];
        foreach ($playerData['final_stand'] as $player) {
            if ($finalBudget[$player['playerID']] > 0) {
                $firstPlayers[$player['order']][$player['playerID']] = $player['playerID'];
            }
        }

        $firstPlayers = current($firstPlayers);
        if (is_array($firstPlayers)) {
            $countFirstPlayers = count($firstPlayers);
            $depositToWin = array_sum($deposit);
            if (in_array($playerId, $firstPlayers) && $budget[$playerId] > 0) {
                $newBudget = ($depositToWin / $countFirstPlayers) - $deposit[$playerId] + $finalBudget[$playerId];
                return $newBudget;
            } else {
                $newBudget = $finalBudget[$playerId] - $deposit[$playerId];
                return $newBudget;
            }
        } else {
            $newBudget = $finalBudget[$playerId];
            return $newBudget;
        }
    }

    public function lap($i)
    {
        $players = $this->players();
        $this->cards->setPlayers($players);
        $this->cards->setPackage();
        $this->cards->mixPackage();
        $this->cards->dealCards();
        $pcards = $this->cards->dealedCards;
        //$pcards['players'][0] = ['♣-K', '♦-7'];
        //$pcards['players'][1] = ['♦-K', '♦-8'];
        //$pcards['players'][2] = ['♣-K', '♦-K'];
        //$pcards['players'][3] = ['♣-8', '♦-9'];
        //$pcards['players'][4] = ['♣-A', '♦-A'];
        //$pcards['table'] = ['♥-Q', '♥-Q', '♦-K', '♥-3', '♥-4'];
        $this->playersCards['config'] = $this->config;
        $this->lap->init($pcards);
        $this->finalData[$i] = $this->playersCards;
        if ($i >= 1) {
            $budget = [];
            if (isset($this->finalData[$i - 1]['config']['players'][0]['budget'])) {
                foreach ($this->finalData[$i - 1]['config']['players'] as $playerId => $playerData) {
                    $budget[$playerId] = $this->finalData[$i - 1]['config']['players'][$playerId]['budget'];
                }
            } else {
                foreach ($this->finalData[$i]['config']['players'] as $playerId => $playerData) {
                    $budget[$playerId] = $this->config['budget'];
                }
            }
            
            foreach ($this->finalData[$i]['config']['players'] as $playerId => $playerData) {
                $this->finalData[$i]['config']['players'][$playerId] = $playerData;
                $this->finalData[$i]['config']['players'][$playerId]['budget'] = $this->setBudget($playerId, $this->finalData[$i], $this->config['small_blind'], $budget);
            }
        }
        $data = $this->lap->playersCards;
        $this->playersCards = $data;
    }

    public function run()
    {
        for ($i = 0; $i <= $this->config['rounds']; $i++) {
            $this->lap($i);
        }
        //var_dump($this->finalData[1]['config']['players']);
    }

}
