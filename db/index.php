<?php
header('Content-Type: application/json; charset=utf-8');
//header('Content-Type: text/plain; charset=utf-8');
//exit("DEEBEE");

require_once "connection.php";

$db = new Db();

// Quote and escape form submitted values
$species = $db -> quote($_GET['species']);
$species = str_replace("%20", " ", $species);

$mapdata = Array();


$sql = "
SELECT
	country, 
	speciesname,
	common_speciesname,
	speciesname_cleaned,
	population_average_size, 
	population_trend_magnitude_average,
    population_trend_long_magnitude_average,
    range_trend_magnitude_average,
    range_trend_long_magnitude_average,
    spa_population_average
FROM
	eub_birds 
WHERE
	speciesname_cleaned LIKE $species 
AND season LIKE 'B'
";

$rows = $db -> select($sql);

foreach ($rows as $rowNumber => $arr)
{
	$mapdata[$arr['country']] = $arr['population_average_size'];
	$rawdata[$arr['country']] = $arr;
}

//echo $sql; // debug

$data['rawdata'] = $rawdata;
$data['mapdata'] = $mapdata;

$json = json_encode($data);
echo $json;

//print_r ($rows);
