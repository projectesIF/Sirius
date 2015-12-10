<?php

//require_once 'config.php'
include_once('configdb.php');


$database =$siriusdb['dbname'];

$sql = "SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES
        WHERE TABLE_SCHEMA = '{$database}'
        AND ENGINE = 'MyISAM'";

$rs = mysql_query($sql);

while($row = mysql_fetch_array($rs)) {
    $tbl = $row[0];
    $sql = "ALTER TABLE `$tbl` ENGINE=INNODB";
    mysql_query($sql);
}
?>