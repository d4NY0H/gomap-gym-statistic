<?php
if (!empty($_GET) && !empty($_GET['trainer'])) {

    // Include team class.
    require_once('class/Team.class.php');

    // Get instance of team class.
    $team = new Team();

    // Get stats.
    $stats = $team->getStatistic('alphabet');

} else {
    die('Fehler! Kein Trainer angegeben!');
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Trainer Statistik</title>

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

if (!empty($stats['trainers'][$_GET['trainer']])) {

    $trainer = $stats['trainers'][$_GET['trainer']];

    echo '<strong>' . $_GET['trainer'] . ' [' . $trainer['level'] . ' | ' . $trainer['team'] . ']</strong><br />';

    $i = 1;
    foreach ($trainer['arenas'] AS $arena) {
        echo $i . '. ' . $arena['gymName'] . ' <a href="http://maps.google.com/maps?q=' . $arena['latitude'] . ',' . $arena['longitude'] . '" target="_blank">http://maps.google.com/maps?q=' . $arena['latitude'] . ',' . $arena['longitude']. '</a><br />';
        $i++;
    }
}
?>
</body>
</html>