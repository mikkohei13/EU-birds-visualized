<?php
/*

h1 ja h2
duplikaatit?
lähteet mukaan
select2
etusivun virheilmot pois
Kreikan data? Ranskan ja Tsekin datan päivitys?

suomenkielinen lajilista tietokantaan, join automaattisesti; näin uusien importien mukana mahdollisesti tulevat uudet lajit tulevat valikkoon mukaan heti 
*/

require_once("db/index.php");

$nameHeading = "";
nameHeading();

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

//print_r ($speciesData); // debug

?>
<!doctype html>
<html class="no-js" lang="fi">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="apple-touch-icon" href="apple-touch-icon.png">

        <link rel="stylesheet" href="css/vendor/normalize.min.css">
        <link rel="stylesheet" href="css/vendor/jquery-jvectormap-2.0.1.css">
        <link rel="stylesheet" href="css/main.css">

        <script src="js/vendor/modernizr-2.8.3.min.js"></script>

        <script src="js/vendor/jquery-1.11.2.min.js"></script>
        <script src="js/vendor/jquery-jvectormap-2.0.1.min.js"></script>
        <script src="js/vendor/jquery-jvectormap-europe-merc-en.js"></script>
        <script src="js/main.js"></script>
    </head>
    <body>

      <div id="main">

        <a href="/" id="homelink"><span>biomi.org</span></a>

        <div id="heading">
          <h2>Pesimälinnusto EU:n jäsenmaissa</h2>
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

            <p id="more">
              Lisää tästä lajista (EIONET):<br />
              <a href="http://bd.eionet.europa.eu/article12/summary?period=1&subject=<?php speciesCode(); ?>">Tilastoja</a><br />
              <a href="http://discomap.eea.europa.eu/map/Filtermap/?webmap=e95b9a26ab00484ca15caed7213fa57c&zoomto=True&CCode=<?php speciesCode(); ?>">Levinneisyyskartta</a>
            </p>

            <?php // dataTable(); ?>

<!--            <div class="adtest" style="width: 300px; height: 250px;">medium rectangle</div>-->
            <div class="adtest" style="width: 300px; height: 600px;">half page</div>

          </div>

          <div id="map"></div>

        </div>

        <?php proTable(); ?>

        <div class="adtest" style="width: 980px; height: 120px;">panorama</div>

      </div>

      <div id="footer">
        <a href="http://bd.eionet.europa.eu/activities/Reporting/Article_12/Reports_2013/Member_State_Deliveries">Datalähde: Eionet - European Topic Centre on Biological Diversity,
        Birds Directive, Article 12, Reports 2013</a>
        <a href="">Mikko Heikkinen/biomi.org</a>
        <a href="https://github.com/mikkohei13/EU-birds-visualized">Source on Github</a>
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

        <!-- Google Analytics: change UA-XXXXX-X to be your site's ID. -->
        <script>
            (function(b,o,i,l,e,r){b.GoogleAnalyticsObject=l;b[l]||(b[l]=
            function(){(b[l].q=b[l].q||[]).push(arguments)});b[l].l=+new Date;
            e=o.createElement(i);r=o.getElementsByTagName(i)[0];
            e.src='//www.google-analytics.com/analytics.js';
            r.parentNode.insertBefore(e,r)}(window,document,'script','ga'));
            ga('create','UA-XXXXX-X','auto');ga('send','pageview');
        </script>
    </body>
</html>
<link href='http://fonts.googleapis.com/css?family=Istok+Web:400,700,400italic' rel='stylesheet' type='text/css'>

<?php

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

function nameHeading()
{ 
  global $nameHeading;

  $speciesArray = file("species.txt");

  foreach ($speciesArray as $key => $names)
  {
    $names = trim($names);
    $parts = explode("\t", $names);

    if ($parts[1] == $_GET['species'])
    {
      $nameHeading = ucfirst($parts[2]) . " (<em>" . $parts[1] . "</em>)";
      return;
    }
  }
}


function proTable()
{
  global $rawdata;
  global $density;
  global $fiName;

//  arsort($rawdata);

  $html = "<table id=\"datatable\">";
  $html .= "<tr>";
  $html .= "<th rowspan=\"2\">Maa</th>";
  $html .= "<th colspan=\"5\">Pesiviä pareja</th>";
  $html .= "<th colspan=\"3\">Pitkän ajan trendi</th>";
  $html .= "<th colspan=\"3\">Lyhyen ajan trendi</th>";
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
  } 

  $html .= "</table>";
  $html .= "<p>+ = lisääntyvä, - = vähentyvä, 0 = stabiili, F = vaihteleva, x = tuntematon</p>";

  echo $html;
}

function format_int($number)
{
  return number_format($number, 0, ",", ".");
}


function speciesCode()
{
  global $rawdata;
  $first = current($rawdata);
  $code = $first['speciescode'];
  echo $code;
}

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
    $class = "flu";
  }
  else
  {
    $class = "";
  }
  return $class;
}

?>