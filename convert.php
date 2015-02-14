<?php
header('Content-Type: text/html; charset=utf-8');

/*
add species name without ssp name??
*/

echo "<pre>";

$xml = simplexml_load_file("data/FI_birds_reports.xml");

$i = 0;
foreach($xml->bird_report as $bird)
{
	foreach ($bird->children() as $value)
	{
	    $name = $value->getName(); 
//		echo $aname . " /// " . $value . "\n";

		$name = trim((string) $name);
		$value = trim((string) $value);

		if (empty($value))
		{
			$value = "null";
		}
		$data[$i][$name] = $value;

	}
	$data[$i]['population_average_size'] = ($data[$i]['population_minimum_size'] + $data[$i]['population_maximum_size']) / 2;
	$data[$i]['population_trend_magnitude_average'] = ($data[$i]['population_trend_magnitude_min'] + $data[$i]['population_trend_magnitude_max']) / 2;
	$data[$i]['population_trend_long_magnitude_average'] = ($data[$i]['population_trend_long_magnitude_min'] + $data[$i]['population_trend_long_magnitude_max']) / 2;
	$data[$i]['range_trend_magnitude_average'] = ($data[$i]['range_trend_magnitude_min'] + $data[$i]['range_trend_magnitude_max']) / 2;
	$data[$i]['range_trend_long_magnitude_average'] = ($data[$i]['range_trend_long_magnitude_min'] + $data[$i]['range_trend_long_magnitude_max']) / 2;
	$data[$i]['spa_population_average'] = ($data[$i]['spa_population_min'] + $data[$i]['spa_population_max']) / 2;

	$i++;
}

print_r ($data);

foreach ($data as $no => $speciesData)
{
	echo $speciesData['speciesname'] . "\n";
}

echo "\n\nend";

/*
$this->routesXMLarray[$DocumentID] = $xml;
foreach ($this->routesXMLarray as $routeXML)
$count = (int) $atomized->MeasurementOrFactAtomised->LowerValue;
*/

?>