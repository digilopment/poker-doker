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
};

$counterWinner = [];
foreach ($data[1]['cards']['players'] as $playerId => $player) {
    $counterWinner[$playerId] = 0;
}

//GAME
foreach ($rounds as $roundId => $round) {
    if ($roundId > 0) {
        $figures = $data[$roundId];
        $players = $data[$roundId]['cards']['players'];

        foreach ($players as $playerId => $player) {
            if ($figures['final_stand'][$playerId]['order'] == 1) {
                $counterWinner[$playerId]++;
            }
        }
    }
}
$finalOrder = [];
foreach ($players as $playerId => $player) {
    $finalOrder[$playerId] = $counterWinner[$playerId];
}
arsort($finalOrder);
?>
&nbsp;Playing on <?php echo $data[0]['config']['rounds'] ?> rounds
<table>
    <?php
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
            <td>
                <b><?php echo $i ?></b><?php echo $subfix ?> place
            </td>
            <td>
                <?php echo $data[$roundId]['config']['players'][$playerId]['name'] ?>
            </td>
            <td>
            </td>
            <td>
            </td>
            <td>
                <?php echo $points; ?> points
            </td>
        </tr>
    <?php } ?>
</table>
