<?php

require_once("db/species.php");

$nameHeading = nameHeading();

$totalHTML = "";

$proTable = proTable(); // generates also totalhtml

function echoMainHTML()
{
  global $nameHeading;
  global $totalHTML;
  global $proTable;
  global $population;
  global $density;

  if ("population" == $_GET['type'])
  {
    $mapJson = @json_encode($population);
    $tableData = $population;
  }
  elseif ("density" == $_GET['type'])
  {
    $mapJson = @json_encode($density);
    $tableData = $density;
  }
  ?>

  <div id="heading">
    <h2><a href="./">Pesimälinnusto EU:n jäsenmaissa</a></h2>
    <h1><?php echo $nameHeading; ?></h1>
    <p>Tämä sivusto perustuu valtioiden vuonna <a href="http://bd.eionet.europa.eu/activities/Reporting/Article_12/Reports_2013/Member_State_Deliveries">2013 EU:lle raportoimiin lintudirektiivin vaatimiin tietoihin</a>. On huomattava että tiedot ovat paikoin puutteellisia: kaikki valtiot eivät ole toimittaneet tietoa kaikista pesimälajeistaan.</p>
  </div>

  <div class="adtest" style="width: 728px; height: 90px;">leaderboard</div>

  <div id="content">

    <div id="tools">
      <h3>Valitse laji</h3>
      <form action="./">
        <?php
        speciesSelect();
        typeSelect();
        ?>
        <input id="submit" type="submit" value="Valitse">
      </form>

      <?php echo $totalHTML; ?>

      <p id="more">
        Lisää tästä lajista (EIONET):<br />
        <a href="http://bd.eionet.europa.eu/article12/summary?period=1&subject=<?php echo $speciesCode; ?>">Tilastoja</a><br />
        <a href="http://discomap.eea.europa.eu/map/Filtermap/?webmap=e95b9a26ab00484ca15caed7213fa57c&zoomto=True&CCode=<?php echo $speciesCode; ?>">Levinneisyyskartta</a>
      </p>

      <div class="adtest" style="width: 300px; height: 600px;">half page</div>

    </div>

    <div id="map"></div>

  </div>

  <?php echo $proTable; ?>

  <div class="adtest" style="width: 980px; height: 120px;">panorama</div>

</div>

<script>
var data = <?php echo $mapJson; ?>;

$(function() {
    $('#map').vectorMap({
        map: 'europe_merc_en',
        backgroundColor: '#a5cbd7', //'#4b96af',
        regionStyle: {
          initial: {
            fill: '#95bbc7'
          }
        },
        series: {
            regions: [{
                values: data,
                scale: ['#e6e696', '#003700'],
//                        scale: ['#ffffff', '#003700'],
                normalizeFunction: 'linear'
            }]
        },
        onRegionTipShow: function(e, el, code) {
            if(typeof data[code] === 'undefined'){
              data[code] = 'ei tietoa / no data';
            };
            el.html(el.html() + ': ' + data[code]);
        }
    });
});
</script>

<?php
}

// -------------------------------------------------------------------------------------

function speciesSelect()
{
  $html = "<select name=\"species\">";

  $speciesArray = file("species.txt");

  foreach ($speciesArray as $key => $names)
  {
    $names = trim($names);
    $parts = explode("\t", $names);

    if ($parts[1] == $_GET['species'])
    {
      $selected = " selected";
    }
    else
    {
      $selected = "";
    }
    $html .= "<option value=\"" . $parts[1] . "\"$selected>" . ucfirst($parts[2]) . " (" . $parts[1] . ")</option>";
  }
  $html .= "</select>";
  
  echo $html;
}

function typeSelect()
{
  $html = "<select name=\"type\">";
  $speciesArray['population'] = 'Pesiviä pareja';
  $speciesArray['density'] = 'Tiheys paria/100 km²';

  foreach ($speciesArray as $key => $value)
  {
    if ($key == $_GET['type'])
    {
      $selected = " selected";
    }
    else
    {
      $selected = "";
    }
    $html .= "<option value=\"$key\"$selected>$value</option>";
  }
  $html .= "</select>";
  
  echo $html;
}

/*
function dataTable()
{
  global $tableData;
  global $fiName;

  arsort($tableData);

  $html = "<table id=\"datatable\">";
  foreach ($tableData as $countryCode => $value)
  {
    $html .= "<tr>";
    $html .= "<td class=\"cou\">" . $fiName[$countryCode] . "</td>";
    $html .= "<td class=\"num\">" . number_format($value, $decimals = 0, $dec_point = ",", $thousands_sep = ".") . "</td>";
    $html .= "</tr>";
  } 

  $html .= "</table>";
  echo $html;
}
*/

function nameHeading()
{ 
  $speciesArray = file("species.txt");

  foreach ($speciesArray as $key => $names)
  {
    $names = trim($names);
    $parts = explode("\t", $names);

    if ($parts[1] == $_GET['species'])
    {
      return ucfirst($parts[2]) . " (<em>" . $parts[1] . "</em>)";
    }
  }
}


function proTable()
{
  global $rawdata;
  global $density;
  global $fiName;
  global $totalHTML;
  $totalAmount = 0;
  $FIamount = 0;

//  arsort($rawdata);

  $html = "<table id=\"datatable\">";
  $html .= "<tr>";
  $html .= "<th rowspan=\"2\">Maa</th>";
  $html .= "<th colspan=\"5\">Pesiviä pareja</th>";
  $html .= "<th colspan=\"3\">Lyhyen ajan trendi</th>";
  $html .= "<th colspan=\"3\">Pitkän ajan trendi</th>";
  $html .= "<th rowspan=\"2\">Lähde</th>";
  $html .= "</tr>";
  $html .= "<tr>";
  $html .= "<th>min</th>";
  $html .= "<th>ka.</th>";
  $html .= "<th>max</th>";
  $html .= "<th>tiheys/100km<sup>2</sup></th>";
  $html .= "<th>ajalla</th>";

  $html .= "<th>trendi</th>";
  $html .= "<th>suuruus</th>";
  $html .= "<th>ajalla</th>";

  $html .= "<th>trendi</th>";
  $html .= "<th>suuruus</th>";
  $html .= "<th>ajalla</th>";

  $html .= "</tr>";

  foreach ($rawdata as $countryCode => $arr)
  {
    if ("FI" == $countryCode)
    {
      $html .= "<tr class=\"suomi\">";
      $FIamount = $arr['population_average_size'];
    }
    else
    {
      $html .= "<tr>";
    }
    $html .= "<td>" . $fiName[$countryCode] . "</td>";
    $html .= "<td class=\"num\">" . format_int($arr['population_minimum_size']) . "</td>";
    $html .= "<td class=\"num\">" . format_int($arr['population_average_size']) . "</td>";
    $html .= "<td class=\"num\">" . format_int($arr['population_maximum_size']) . "</td>";
    $html .= "<td class=\"num\">" . number_format($density[$countryCode], 1, ",", ".") . "</td>";
    $html .= "<td>" . $arr['population_date'] . "</td>";

    $html .= "<td class=\"" . trendClass($arr['population_trend']) . "\">" . trim($arr['population_trend'], "nu") . "</td>";
    $html .= "<td class=\"num\">" . $arr['population_trend_magnitude_average'] . "</td>";
    $html .= "<td>" . $arr['population_trend_period'] . "</td>";

    $html .= "<td class=\"" . trendClass($arr['population_trend_long']) . "\">" . trim($arr['population_trend_long'], "nu") . "</td>";
    $html .= "<td class=\"num\">" . $arr['population_trend_long_magnitude_average'] . "</td>";
    $html .= "<td>" . $arr['population_trend_long_period'] . "</td>";

    $html .= "<td>" . $arr['population_sources'] . "</td>";

//    $html .= "<td class=\"num\">" . number_format($value, $decimals = 0, $dec_point = ",", $thousands_sep = ".") . "</td>";
    $html .= "</tr>";

    $totalAmount = $totalAmount + $arr['population_average_size'];
  } 

  $html .= "</table>";
  $html .= "<p>+ = lisääntyvä, - = vähentyvä, 0 = stabiili, F = vaihteleva, x = tuntematon</p>";

  $totalHTML .= "<p>Yhteensä " . format_int($totalAmount) . " paria";
  if ($totalAmount > 0)
  {
    $totalHTML .= ", joista Suomessa " . number_format(($FIamount / $totalAmount * 100), 2, ",", ".") . " %";
  }
  $totalHTML .= "</p>";

  return $html;
}

function format_int($number)
{
  return number_format($number, 0, ",", ".");
}

/*
function speciesCode()
{
  global $rawdata;
  print_r ($rawdata);

  $first = current(reset($rawdata));
  echo "///"; print_r($first);
  exit();

  $code = $first['speciescode'];
  echo $code;
}
*/

function trendClass($trend)
{
  if ("+" == $trend)
  {
    $class = "pos";
  }
  elseif ("-" == $trend)
  {
    $class = "neg";
  }
  elseif ("F" == $trend)
  {
    $class = "fluct";
  }
  else
  {
    $class = "";
  }
  return $class;
}



function speciesList()
{
  $html = "<ul>";

  $speciesArray = file("species.txt");

  foreach ($speciesArray as $key => $names)
  {
    $names = trim($names);
    $parts = explode("\t", $names);

    $html .= "<li><a href=\"?species=" . $parts[1] . "&type=population\">" . ucfirst($parts[2]) . " (" . $parts[1] . ")</a></li>";
  }
  $html .= "</ul>";
  
  echo $html;
}


?>