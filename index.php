<?php
/*
Tooltipeistä undefined pois
h1 ja h2
lajilista uusiksi, suom. nimet mukaan ja engl.
duplikaatit?
CY?
hajautuvatko parimäärät eri alalaljeille? tsekkaa ainakin Larus fuscus
pop ja density-taulukot sivulla
lähteet mukaan
*/

require_once("db/index.php");

if ("population" == $_GET['type'])
{
  $mapJson = json_encode($population);
}
elseif ("density" == $_GET['type'])
{
  $mapJson = json_encode($density);
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
              <input id="submit" type="submit" value="Submit">
            </form>

<!--            <div class="adtest" style="width: 300px; height: 250px;">medium rectangle</div>-->
            <div class="adtest" style="width: 300px; height: 600px;">half page</div>

          </div>

          <div id="map"></div>

        </div>

        <div class="adtest" style="width: 980px; height: 120px;">panorama</div>

      </div>

        <script>

        var data = <?php echo $mapJson; ?>;

        $(function(){
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
                    el.html(el.html()+': '+data[code]);
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

  require_once "species_list.php";  

  foreach ($speciesArray as $key => $value)
  {
    if ($key == $_GET['species'])
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

function typeSelect()
{
  $html = "<select name=\"type\">";
  $speciesArray['population'] = 'Population';
  $speciesArray['density'] = 'Density per 100 km2';

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

?>