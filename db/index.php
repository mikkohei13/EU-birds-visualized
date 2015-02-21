<?php
header('Content-Type: application/json; charset=utf-8');

require_once "connection.php";

$db = new Db();

// Quote and escape form submitted values
$species = $db -> quote($_GET['species']);
$species = str_replace("%20", " ", $species);

$typeDirty = $_GET['type'];
$data = Array();

if ("population" == $typeDirty)
{
	$sql = "SELECT country, population_average_size FROM eub_birds WHERE speciesname_cleaned LIKE $species";
	$rows = $db -> select($sql);
	foreach ($rows as $key => $arr)
	{
		$data[$arr['country']] = $arr['population_average_size'];
	}
}
else
{
	$sql = "SELECT * FROM eub_birds WHERE speciesname_cleaned LIKE $species";
	$rows = $db -> select($sql);
	$data = $rows;
}

//echo $sql;

$json = json_encode($data);
echo $json;

//print_r ($rows);
