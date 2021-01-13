<?php
$rounds = $data;
$dataCard['cardTpl'] = function($cards) {
    foreach ($cards as $card) {
        $symbol = explode('-', $card)[0];
        $number = explode('-', $card)[1];
        if ($symbol == '♣' || $symbol == '♠') {
            $class = 'black';
        } else {
            $class = 'red';
        }
        echo '<div class="card-small">';
        echo '<p class="card-text ' . $class . '">' . $number . '</p>';
        echo '<p class="card-img ' . $class . '">' . $symbol . '</p>';
        echo '</div>';
    }
};
$dataCard['cardTplEmpty'] = function() {
    echo '<div class="card-empty">';
    echo '</div>';
}
?>
<!DOCTYPE HTML>
<html>
    <head>
        <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
        <meta name="viewport" content="width=1200, initial-scale=0.3">
        <style>
            body{
                margin: 0px auto;
                width: 1200px;
                font-family: sans-serif;
            }
            a{
                text-decoration:none;
            }
            .btn{
                display: block;
                font-size: 20px;
                padding: 12px;
                text-align: center;
                background-color: green;
                color: #fff;
                text-transform: uppercase;
                border-radius: 5px;
                width: 200px;
                margin: 0px auto;
                margin-top: 20px;
                margin-bottom: 20px;
                font-family: sans-serif;
            }
            td.status{
                height: 45px;
                color: #000;
                font-weight: bold;
                font-family: sans-serif;
                font-size: 14px;
            }
            td.status .winner{
                color: #fff;
            }
            .board tr.round:nth-child(even){
                background-color: #079b07;
            }
            .board tr.title{
                font-weight: bold;
                text-align: center;
            }
            .board td.roundId{
                font-weight: bold;
                text-align: center;
            }
            .board {
                background-color: green;
                margin: 0 auto;
                border: 2px solid black;
                float:left;
                width:100%;
            }
            .card-small {
                border: .1em solid black;
                border-radius: 10%;
                height:40px;
                width: 28px; /*70% of height*/
                margin-right: 5px;
                float: left;
                background-color: white;
            }

            .card-empty {

                height:40px;
                width: 28px; /*70% of height*/
                margin-right: 5px;
                float: left;
            }

            .card-text {
                margin: 0;
                margin-top: 15%;
                text-align: center;
                font-size: 1em;
                font-weight: bold;
                padding: 0;
            }

            .card-img {
                text-align: center;
                margin: 0;
                font-size: 1em;
            }

            .red {
                color: red;
            }

            .black {
                color: black;
            }

            .btn{

            }
        </style>
        <title>Poker</title>

    </head>
    <body>
        <h2 align="center">Poker Doker tournament</h2>
        <h3>
            &nbsp;Playing on rounds: <?php echo $data[0]['config']['rounds'] ?><br/>
            &nbsp;Number of players:  <?php echo count($data[0]['config']['players']) ?><br/>
            &nbsp;Budget per player: <?php echo $data[0]['config']['budget'] ?>€<br/>
            &nbsp;Blind:  <?php echo $data[0]['config']['small_blind'] ?>€<br/>
            &nbsp;Jackpot:  <?php echo count($data[0]['config']['players']) * $data[0]['config']['budget'] ?>€<br/>
            <br/>&nbsp;Game progress: <a href="#results" style="float:right">Go to results</a>
        </h3>
        <table class="board" cellspacing="0">
            <tr class="title">
                <td>Round</td>
                <td>Cards on hand</td>
                <td>Cards on table</td>
                <td>Highest Figure</td>
                <td>FifthCards</td>
                <td>Figure Name</td>
                <td>Player order<br/> in round</td>
                <td>Gained points</td>
                <td>Budget</td>
<!--<td>Fifth Card</td>-->
            </tr>
            <?php
            $counterWinner = [];
            $counterBudget = [];
            foreach ($data[1]['cards']['players'] as $playerId => $player) {
                $counterWinner[$playerId] = 0;
                $counterBudget[$playerId] = 0;
            }
            $budgetAfterRounds = 0;
            foreach ($rounds as $roundId => $round) {
                if ($roundId > 0) {
                    $figures = $data[$roundId];
                    $players = $data[$roundId]['cards']['players'];
                    $table = $data[$roundId]['cards']['table'];
                    ?>
                    <tr class="round">
                        <!-- PLAYERS -->
                        <td class="roundId">
                            <table>
                                <tr>
                                    <?php echo $roundId; ?>
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <td>
                            <table>
                                <?php foreach ($players as $playerId => $player) { ?>
                                    <tr>
                                        <td class="empty"><?php echo $data[$roundId]['config']['players'][$playerId]['name'] ?></td>
                                        <td class="player">
                                            <?php $dataCard['cardTpl']($player); ?>
                                        </td>
                                    </tr>
                                <?php } ?>

                            </table>
                        </td>
                        <!-- Table -->
                        <td>
                            <table>
                                <tr>
                                    <td>
                                        <?php $dataCard['cardTpl']($table); ?>
                                    </td>
                                </tr>

                            </table>
                        </td>
                        <td>
                            <table>
                                <?php foreach ($players as $playerId => $player) { ?>
                                    <tr>
                                        <td class="player">
                                            <?php $dataCard['cardTpl']($figures[$playerId]['playing_cards']); ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </table>
                        </td>


                        <td>
                            <table>
                                <?php foreach ($players as $playerId => $player) { ?>
                                    <tr>
                                        <td class="player">
                                            <?php
                                            if (count($figures[$playerId]['fifth_card']) == 0) {
                                                $dataCard['cardTplEmpty']();
                                            } else {
                                                $dataCard['cardTpl']($figures[$playerId]['fifth_card']);
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </table>
                        </td>

                        <td>
                            <table>
                                <?php foreach ($players as $playerId => $player) { ?>
                                    <tr>

                                        <td class="status">
                                            <?php echo $figures[$playerId]['figure']; ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </table>
                        </td>

                        <td>
                            <table>
                                <?php
                                foreach ($players as $playerId => $player) {
                                    ?>
                                    <tr>
                                        <td class="status">
                                            <?php echo $figures['final_stand'][$playerId]['order']; ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </table>
                        </td>

                        <td>
                            <table>
                                <?php
                                foreach ($players as $playerId => $player) {
                                    ?>
                                    <tr>
                                        <td class="status">
                                            <?php echo ($figures['final_stand'][$playerId]['order'] == 1) ? '<span class="winner">winner</span>' : '<span class="loose">loose</span>'; ?>
                                            <br/><?php
                                            if ($figures['final_stand'][$playerId]['order'] == 1) {
                                                $counterWinner[$playerId]++;
                                            }
                                            if ($figures['config']['players'][$playerId]['budget'] == 1200) {
                                                if (!$budgetAfterRounds) {
                                                    $budgetAfterRounds = $roundId;
                                                }
                                            }
                                            echo $counterWinner[$playerId];
                                            ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </table>
                        </td>

                        <td>
                            <table>
                                <?php
                                foreach ($players as $playerId => $player) {
                                    ?>
                                    <tr>
                                        <td class="status">
                                            <?php print($figures['config']['players'][$playerId]['budget']); ?>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </table>
                        </td>
                    </tr>
                <?php } ?>
            <?php } ?>
            <tr id="results">
                <td colspan="6" align="center">
                    <h3>Overall standing on POINTS (status after <?php echo $data[0]['config']['rounds'] ?> rounds)</h3>
                </td>
            </tr>
            <?php
            $finalOrder = [];
            foreach ($players as $playerId => $player) {
                $finalOrder[$playerId] = $counterWinner[$playerId];
            }
            arsort($finalOrder);
            $i = 0;
            foreach ($finalOrder as $playerId => $points) {
                $i++;
                if ($i == 1) {
                    $subfix = 'st';
                } elseif ($i == 2) {
                    $subfix = 'nd';
                } elseif ($i == 3) {
                    $subfix = 'rd';
                } else {
                    $subfix = 'th';
                }
                ?>
                <tr>
                    <td></td>
                    <td>
                        <h3><b><?php echo $i ?></b><?php echo $subfix ?> place</h3>
                    </td>
                    <td>
                        <h3><?php echo $data[$roundId]['config']['players'][$playerId]['name'] ?></h3>
                    </td>
                    <td>
                    </td>
                    <td>
                    </td>
                    <td>
                        <h3><?php echo $points; ?> points</h3>
                    </td>
                </tr>
            <?php } ?>


            <tr>
                <td colspan="6" align="center">
                    <h3>Overall standing on BUDGET (status after <?php echo $data[0]['config']['rounds'] ?> rounds)</h3>
                    <h4><?php echo ($budgetAfterRounds > 0) ? 'Final winner was defined after ' . $budgetAfterRounds . 'rounds' : 'no final winner defined' ?></h4>
                </td>
            </tr>
            <?php
            $finalOrder = [];
            foreach ($players as $playerId => $player) {
                $finalOrder[$playerId] = $data[$roundId]['config']['players'][$playerId]['budget'];
            }
            arsort($finalOrder);
            $i = 0;
            foreach ($finalOrder as $playerId => $points) {
                $i++;
                if ($i == 1) {
                    $subfix = 'st';
                } elseif ($i == 2) {
                    $subfix = 'nd';
                } elseif ($i == 3) {
                    $subfix = 'rd';
                } else {
                    $subfix = 'th';
                }
                ?>
                <tr>
                    <td></td>
                    <td>
                        <h3><b><?php echo $i ?></b><?php echo $subfix ?> place</h3>
                    </td>
                    <td>
                        <h3><?php echo $data[$roundId]['config']['players'][$playerId]['name'] ?></h3>
                    </td>
                    <td>
                    </td>
                    <td>
                    </td>
                    <td>
                        <h3><?php echo $data[$roundId]['config']['players'][$playerId]['budget'] ?> €</h3>
                    </td>
                </tr>
            <?php } ?>
        </table>
        <div style="clear:both"></div>
        <a href="">
            <span class="btn">Play again</span>
        </a>
    </body>
</html>