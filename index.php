<?php

namespace Index;

use Dnt3\App\Poker\Game;

(new class
{

    public function __construct()
    {
        include 'app/Cards.php';
        include 'app/Figure.php';
        include 'app/Figures.php';
        include 'app/Lap.php';
        include 'app/Game.php';
    }

    public function setParam($param)
    {
        $return = rand(25, 45);
        if (isset($_GET[$param])) {
            $return = $_GET[$param];
        }
        return $return;
    }

    public function config()
    {
        $this->config = [
            'players' => [
                ['name' => 'Tomas'],
                ['name' => 'Tmax'],
                ['name' => 'Lubo'],
                ['name' => 'Botor'],
                ['name' => 'Partak'],
                ['name' => 'PohodaDoma'],
            ],
            'rounds' => $this->setParam('rounds'),
            'small_blind' => 10,
            'budget' => 250,
            'game_mod' => $this->setParam('game_mod')
        ];
    }

    public function play()
    {
        $this->game = new Game($this->config);
        $this->game->run();
        if ($this->config['rounds'] > 1000) {
            $this->game->addTemplate('onlyFinal', $this->game->finalData);
        } else {
            $this->game->addTemplate('default', $this->game->finalData);
        }
        $this->game->render();
    }

    protected function test()
    {
        $j[0] = $j[1] = $j[2] = $j[3] = $j[4] = $j[5] = $j[6] = $j[7] = $j[8] = $j[9] = 0;
        for ($i = 1; $i <= $this->config['rounds']; $i++) {
            $this->game = new Game($this->config);
            $this->game->run();
            foreach ($this->game->playersCards['cards']['players'] as $pleyerId => $player) {
                foreach (range(0, 9) as $figure) {
                    if ($this->game->playersCards[0]['figure_weight'] == $figure) {
                        $j[$figure]++;
                    }
                }
            }
        }
        var_dump($j);
    }

    protected function game()
    {
        $this->config();
        $this->play();
    }

    public function main()
    {
        $this->config();
        match ((int) $this->config['game_mod']) {
            1 => $this->game(),
            2 => $this->test(),
            default => $this->game(),
        };
    }
})->main();
