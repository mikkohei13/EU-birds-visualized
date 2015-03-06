<?php
header('Content-Type: text/html; charset=utf-8');
//header('Content-Type: text/plain; charset=utf-8');
//exit("DEEBEE");


// Land areas * 1000 km2
// Source: http://europa.eu/about-eu/facts-figures/living/index_en.htm
$landArea['MT'] = 0.3;
$landArea['LU'] = 2.6;
$landArea['CY'] = 9.3;
$landArea['SI'] = 20.3;
$landArea['BE'] = 30.5;
$landArea['NL'] = 41.5;
$landArea['DK'] = 42.9;
$landArea['EE'] = 45.2;
$landArea['SK'] = 49.0;
$landArea['LV'] = 64.6;
$landArea['LT'] = 65.3;
$landArea['IE'] = 69.8;
$landArea['CZ'] = 78.9;
$landArea['AT'] = 83.9;
$landArea['HR'] = 87.7;
$landArea['PT'] = 92.2;
$landArea['HU'] = 93.0;
$landArea['BG'] = 110.9;
$landArea['EL'] = 132.0;
$landArea['RO'] = 238.4;
$landArea['GB'] = 248.5; // changed from UK to GB
$landArea['IT'] = 302.1;
$landArea['PL'] = 312.7;
$landArea['FI'] = 338.4;
$landArea['DE'] = 357.2;
$landArea['SE'] = 438.6;
$landArea['ES'] = 506.0;
$landArea['FR'] = 632.8;


require_once "connection.php";

$db = new Db();

// Quote and escape form submitted values
$species = $db -> quote($_GET['species']);
$species = str_replace("%20", " ", $species);

$mapdata = Array();

// Raw data from database
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
    spa_population_average,

    population_date,
    population_minimum_size,
    population_maximum_size,
    population_type_of_estimate,
    population_sources,

    range_surface_area,
    range_period,
    range_sources
FROM
	eub_birds 
WHERE
	speciesname LIKE $species 
AND season LIKE 'B'
";

$rows = $db -> select($sql);

// Goes through the data and creates derived data
foreach ($rows as $rowNumber => $arr)
{
//	print_r ($arr);

	// Exceptions
	if ("UK" == $arr['country'])
	{
		$arr['country'] = "GB";
	}

	// Population
	if ($arr['population_average_size'] > 0)
	{
		$population[$arr['country']] = $arr['population_average_size'];
	}

	// Density
	$densityPer100km2 = $arr['population_average_size'] / ($landArea[$arr['country']] * 10);
	$density[$arr['country']] = round($densityPer100km2, 1);

	// Raw data
	$rawdata[$arr['country']] = $arr;
}

// Add zeroes to those EU countries, which are missing
/*
foreach ($landArea as $country => $area)
{
	if (! isset($population[$country]))
	{
		$population[$country] = 0;
	}
	if (! isset($density[$country]))
	{
		$density[$country] = 0;
	}
}
*/


//print_r ($population);
