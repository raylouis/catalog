<?php
header("Content-Type: text/comma-seperated-values");
header("Content-Disposition: attachment; filename=" . $model . "s.csv");

foreach ($data as $row) {
    $row2 = array();
    foreach ($row as $column) {
        $row2[] = '"' . $column . '"';
    }
    echo join(';', $row2);
    echo "\n";
}