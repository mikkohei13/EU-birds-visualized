<?php

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
$landArea['GR'] = 132.0; // changed from EL
$landArea['RO'] = 238.4;
$landArea['GB'] = 248.5; // changed from UK to GB
$landArea['IT'] = 302.1;
$landArea['PL'] = 312.7;
$landArea['FI'] = 338.4;
$landArea['DE'] = 357.2;
$landArea['SE'] = 438.6;
$landArea['ES'] = 506.0;
$landArea['FR'] = 632.8;

// names
$fiName['MT'] = "Malta";
$fiName['LU'] = "Luxemburg";
$fiName['CY'] = "Kypros";
$fiName['SI'] = "Slovenia";
$fiName['BE'] = "Belgia";
$fiName['NL'] = "Alankomaat";
$fiName['DK'] = "Tanska";
$fiName['EE'] = "Viro";
$fiName['SK'] = "Slovakia";
$fiName['LV'] = "Latvia";
$fiName['LT'] = "Liettua";
$fiName['IE'] = "Irlanti";
$fiName['CZ'] = "Tšekki";
$fiName['AT'] = "Itävalta";
$fiName['HR'] = "Kroatia";
$fiName['PT'] = "Portugali";
$fiName['HU'] = "Unkari";
$fiName['BG'] = "Bulgaria";
$fiName['GR'] = "Kreikka";
$fiName['RO'] = "Romania";
$fiName['GB'] = "Iso-Britannia";
$fiName['IT'] = "Italia";
$fiName['PL'] = "Puola";
$fiName['FI'] = "Suomi";
$fiName['DE'] = "Saksa";
$fiName['SE'] = "Ruotsi";
$fiName['ES'] = "Espanja";
$fiName['FR'] = "Ranska";

require_once "connection.php";

$rawdata = Array();

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
	speciescode,

	speciesname_cleaned,
	population_average_size, 

	population_trend,
	population_trend_magnitude_average,
	population_trend_period,

	population_trend_long,
    population_trend_long_magnitude_average,
	population_trend_long_period,

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
    range_sources,

    population_size_unit 
FROM
	eub_birds 
WHERE
	speciesname LIKE $species 
	AND season LIKE 'B'
ORDER BY
	population_average_size DESC
";

$rows = $db -> select($sql);

// Goes through the data and creates derived data
foreach ($rows as $rowNumber => $arr)
{
//	print_r ($arr);

	$speciesCode = $arr['speciescode'];

	// Exceptions: change counry codes used by EU to those used by jVectorMap
	if ("UK" == $arr['country'])
	{
		$arr['country'] = "GB";
	}
	if ("EL" == $arr['country'])
	{
		$arr['country'] = "GR";
	}

	// individuals into pairs
	if ("i" == $arr['population_size_unit'])
	{
		$arr['population_average_size'] = $arr['population_average_size'] / 2;
		$arr['population_minimum_size'] = $arr['population_minimum_size'] / 2;
		$arr['population_maximum_size'] = $arr['population_maximum_size'] / 2;
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

