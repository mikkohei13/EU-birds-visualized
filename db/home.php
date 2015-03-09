<?php

require_once "connection.php";

$db = new Db();

$speciesList = Array();

// Pairs
$sql = "
SELECT speciesname, SUM(population_average_size) AS summa
FROM eub_birds
WHERE season LIKE 'B' AND population_size_unit NOT LIKE 'i'
GROUP BY speciesname
ORDER BY summa DESC
";

$rowsPairs = $db -> select($sql);

// Individuals
$sql = "
SELECT speciesname, SUM(population_average_size) AS summa
FROM eub_birds
WHERE season LIKE 'B' AND population_size_unit LIKE 'i'
GROUP BY speciesname
ORDER BY summa DESC
";

$rowsIndividuals = $db -> select($sql);

//print_r($rowsPairs); exit("DEBUG");

foreach ($rowsPairs as $nro => $arr)
{
	$speciesList[$arr['speciesname']] = $arr['summa'];
}

foreach ($rowsIndividuals as $nro => $arr)
{
	@$speciesList[$arr['speciesname']] = $speciesList[$arr['speciesname']] + ($arr['summa'] / 2);
}

arsort($speciesList);

//print_r($speciesList); exit("DEBUG");