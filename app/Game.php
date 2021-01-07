<?php

namespace Dnt3\App\Poker;

use Dnt3\App\Poker\Cards;
use Dnt3\App\Poker\Lap;

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
        $data = $this->lap->playersCards;
        $this->playersCards = $data;
    }

    public function run()
    {
        for ($i = 0; $i <= $this->config['rounds']; $i++) {
            $this->lap($i);
        }
    }

}
