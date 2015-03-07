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
<html class="no-js" lang="">
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

        <h1><?php nameHeading(); ?></h1>

        <div class="adtest" style="width: 728px; height: 90px;">leaderboard</div>

        <h1><?php // echo $rawdata['FI']['common_speciesname'] . " (<em>" . $rawdata['FI']['speciesname_cleaned'] . "</em>)"; ?></h1>
        <h2><?php // echo "AT: " . $rawdata['AT']['common_speciesname'] . ", BG: " . $rawdata['BG']['common_speciesname']; ?></h2>

        <div id="content">

          <div id="tools">
            <form action="./">
              <?php
              speciesSelect();
              typeSelect();
              ?>
              <input id="submit" type="submit" value="Valitse">
            </form>


            <?php dataTable(); ?>

<!--            <div class="adtest" style="width: 300px; height: 250px;">medium rectangle</div>-->
            <div class="adtest" style="width: 300px; height: 600px;">half page</div>

          </div>

          <div id="map"></div>

        </div>

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

<?php

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
    $html .= "<option value=\"" . $parts[1] . "\"$selected>" . $parts[2] . " (" . $parts[1] . ")</option>";
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
  $speciesArray = file("species.txt");

  foreach ($speciesArray as $key => $names)
  {
    $names = trim($names);
    $parts = explode("\t", $names);

    if ($parts[1] == $_GET['species'])
    {
      $nameHeading = ucfirst($parts[2]) . " (<em>" . $parts[1] . "</em>)";
    }
  }
  echo $nameHeading;
}

?>