<?php
// Include team class.
require_once('class/Team.class.php');

// Get instance of team class.
$team = new Team();

// Get stats.
$stats = $team->getStatistic('top');

?>
<!DOCTYPE html>
<html>
<head>
    <title>Team Statistik</title>

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
echo '<strong>Pokemon Go Arenen | Stand: ' . date("d.m.Y H:i") . ' Uhr</strong><br /><br/>';

if (!empty($stats['counter']['arenas'])) {
    echo '<strong>Arenen</strong><br />';
    echo 'Anzahl Arenen gesamt: ' . $stats['counter']['arenas']['all'] . '<br />';
    echo 'Anzahl Arenen blau: ' . $stats['counter']['arenas']['blau'] . '<br />';
    echo 'Anzahl Arenen rot: ' . $stats['counter']['arenas']['rot'] . '<br />';
    echo 'Anzahl Arenen gelb: ' . $stats['counter']['arenas']['gelb'] . '<br />';
}

echo '<br />';

if (!empty($stats['counter']['arenas'])) {
    echo '<strong>Aktive Trainer</strong><br />';
    echo 'Anzahl Spieler gesamt: ' . $stats['counter']['players']['all'] . '<br />';
    echo 'Anzahl Spieler blau: ' . $stats['counter']['players']['blau'] . '<br />';
    echo 'Anzahl Spieler rot: ' . $stats['counter']['players']['rot'] . '<br />';
    echo 'Anzahl Spieler gelb: ' . $stats['counter']['players']['gelb'] . '<br />';
}

echo '<br /><strong>Alle Trainer anzeigen: <a href="all.php">Link</a></strong><br />';

if (!empty($stats['trainers'])) {

    echo '<br /><strong>Top 100 Trainer (Alle Teams)</strong><br />';

    $limit = 1;
    foreach ($stats['trainers'] AS $name => $data) {
        echo $limit . '. <a href="trainer.php?trainer=' . $name . '">' . $name . '</a> [' . $data['level'] . '] (' . $data['counter'] . ' Arenen | ' . $data['team'] . ')<br />';
        if (++$limit > 100) break;
    }

    echo '<br /><strong>Top 20 Trainer (Blau)</strong><br />';

    $limit = 1;
    foreach ($stats['trainers'] AS $name => $data) {
        if ($data['team'] == 'blau') {
            echo $limit . '. <a href="trainer.php?trainer=' . $name . '">' . $name . '</a> [' . $data['level'] . '] (' . $data['counter'] . ' Arenen)<br />';
            if (++$limit > 20) break;
        }
    }

    echo '<br /><strong>Top 20 Trainer (Rot)</strong><br />';

    $limit = 1;
    foreach ($stats['trainers'] AS $name => $data) {
        if ($data['team'] == 'rot') {
            echo $limit . '. <a href="trainer.php?trainer=' . $name . '">' . $name . '</a> [' . $data['level'] . '] (' . $data['counter'] . ' Arenen)<br />';
            if (++$limit > 20) break;
        }
    }

    echo '<br /><strong>Top 20 Trainer (Gelb)</strong><br />';

    $limit = 1;
    foreach ($stats['trainers'] AS $name => $data) {
        if ($data['team'] == 'gelb') {
            echo $limit . '. <a href="trainer.php?trainer=' . $name . '">' . $name . '</a> [' . $data['level'] . '] (' . $data['counter'] . ' Arenen)<br />';
            if (++$limit > 20) break;
        }
    }
} else {
    echo 'Fehler! Keine Trainer Daten gefunden!';
}
?>
</body>
</html>