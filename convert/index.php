<?php
header('Content-Type: text/html; charset=utf-8');

/*
FR and NL data missing
etc.biodiversity@mnhn.fr
helpdesk@eionet.europa.eu
*/

require_once 'convert_fields.php';

$headersPrinted = FALSE;

echo "<pre>";

$files = glob('data/*.{xml}', GLOB_BRACE);
foreach($files as $filename)
{
  parseFile($filename);

  break; // debug
}

function parseFile($filename)
{
	global $fields;

	$xml = simplexml_load_file($filename);

	// Create data
	$i = 0;
	foreach($xml->bird_report as $bird)
	{
		foreach ($bird->children() as $value)
		{
		    $name = $value->getName(); 

		    if (! $fields[$name])
		    {
		    	continue;
		    }

			$name = trim((string) $name);
			$value = trim((string) $value);

			if (isset($_GET['nullify']))
			{
				if (empty($value))
				{
					$value = "null";
				}
			}
			$data[$i][$name] = $value;

		}

		$data[$i]['speciesname_cleaned'] = returnSpeciesName($data[$i]['speciesname']);

		$data[$i]['population_average_size'] = ($data[$i]['population_minimum_size'] + $data[$i]['population_maximum_size']) / 2;
		$data[$i]['population_trend_magnitude_average'] = ($data[$i]['population_trend_magnitude_min'] + $data[$i]['population_trend_magnitude_max']) / 2;
		$data[$i]['population_trend_long_magnitude_average'] = ($data[$i]['population_trend_long_magnitude_min'] + $data[$i]['population_trend_long_magnitude_max']) / 2;
		$data[$i]['range_trend_magnitude_average'] = ($data[$i]['range_trend_magnitude_min'] + $data[$i]['range_trend_magnitude_max']) / 2;
		$data[$i]['range_trend_long_magnitude_average'] = ($data[$i]['range_trend_long_magnitude_min'] + $data[$i]['range_trend_long_magnitude_max']) / 2;
		$data[$i]['spa_population_average'] = ($data[$i]['spa_population_min'] + $data[$i]['spa_population_max']) / 2;

		$i++;
	}

	print_r ($data); // exit(); // debug

//	createInserts($data);
	createCSV($data);

}

function createCSV($data)
{
	global $headersPrinted;

	// Create SQL INSERT
	foreach ($data as $no => $speciesData)
	{
	//	echo $speciesData['speciesname'] . "\n";
		if ($headersPrinted === FALSE)
		{
			foreach ($speciesData as $key => $value)
			{
				echo mysql_escape_string($key);
				echo "\t";
			}
			echo "\n";
			$headersPrinted = TRUE;
		}

		foreach ($speciesData as $key => $value)
		{
			echo mysql_escape_string($value);
			echo "\t";
		}
		echo "\n";
	}

	//echo "\n\nend";
}

function createInserts($data)
{
	// Create SQL INSERT
	foreach ($data as $no => $speciesData)
	{
	//	echo $speciesData['speciesname'] . "\n";

		$keys = "";
		$values = "";
		foreach ($speciesData as $key => $value)
		{
			$keys = $keys . ", " . mysql_escape_string($key);
			$values = $values . ", '" . mysql_escape_string($value) . "'";
		}
		$keys = trim($keys, ", ");
		$values = trim($values, ", ");	
		echo "INSERT INTO eub_birds ($keys) VALUES ($values);\n";
	}

	//echo "\n\nend";
}

function returnSpeciesName($taxon)
{
	$parts = explode(" ", $taxon);
	$species = $parts[0] . " " . $parts[1];
	return $species;
}

/*
$this->routesXMLarray[$DocumentID] = $xml;
foreach ($this->routesXMLarray as $routeXML)
$count = (int) $atomized->MeasurementOrFactAtomised->LowerValue;
*/

?>