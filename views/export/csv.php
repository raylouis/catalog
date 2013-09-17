<?php
if (!defined('IN_CMS')) { exit(); }

/**
 * Catalog
 * 
 * The catalog plugin adds a catalog or webshop to Wolf CMS.
 * 
 * @package     Plugins
 * @subpackage  catalog
 * 
 * @author      Nic Wortel <nic.wortel@nth-root.nl>
 * @copyright   Nic Wortel, 2012
 * @version     0.1.5
 */

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