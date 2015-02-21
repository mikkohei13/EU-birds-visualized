<?php
header('Content-Type: application/json; charset=utf-8');
//header('Content-Type: text/plain; charset=utf-8');
//exit("DEEBEE");

require_once "connection.php";

$db = new Db();

// Quote and escape form submitted values
$species = $db -> quote($_GET['species']);
$species = str_replace("%20", " ", $species);

$typeDirty = $_GET['type'];
$data = Array();

// POPULATION
if ("population" == $typeDirty)
{
	$sql = "SELECT country, population_average_size FROM eub_birds WHERE speciesname_cleaned LIKE $species AND season LIKE 'B'";

	$rows = $db -> select($sql);
	foreach ($rows as $key => $arr)
	{
		$data[$arr['country']] = $arr['population_average_size'];
	}
}
// ALL
else
{
	$sql = "SELECT * FROM eub_birds WHERE speciesname_cleaned LIKE $species AND season LIKE 'B'";
	$rows = $db -> select($sql);
	$data = $rows;
}

//echo $sql;

$json = json_encode($data);
echo $json;

//print_r ($rows);
