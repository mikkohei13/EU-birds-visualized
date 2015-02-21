<?php
header('Content-Type: application/json; charset=utf-8');

require_once "connection.php";

$db = new Db();

// Quote and escape form submitted values
$species = $db -> quote($_GET['species']);
$species = str_replace("%20", " ", $species);

$sql = "SELECT * FROM eub_birds WHERE speciesname_cleaned LIKE $species";
//echo $sql;
$rows = $db -> select($sql);

$json = json_encode($rows);
echo $json;

//print_r ($rows);
