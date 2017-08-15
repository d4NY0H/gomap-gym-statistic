<?php
// Include team class.
require_once('class/Team.class.php');

// Get instance of team class.
$team = new Team();

// Get stats.
$stats = $team->getStatistic('alphabet');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Alle Trainer</title>

    <meta http-equiv="Content-Language" content="de">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 15px;
            line-height: 22px;
            margin: 10px;
        }
    </style>
</head>
<body><?php

if (!empty($stats['trainers'])) {
    echo '<strong>Alle Trainer</strong><br />';

    $counter = 1;
    foreach ($stats['trainers'] AS $name => $data) {
        echo $counter . '. <a href="trainer.php?trainer=' . $name . '">' . $name . '</a> [' . $data['level'] . '] (' . $data['counter'] . ' Arenen | ' . $data['team'] . ')<br />';
        $counter++;
    }
}
?>
</body>
</html>