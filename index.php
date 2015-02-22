<?php
/*
TODO:
- data security w/ soeciesDirty
- UTF-8 database connection? (BG names etc.)

*/

$speciesDirty = str_replace(" ", "%20", $_GET['species']);

$baseURLtemp = explode("?", $_SERVER["REQUEST_URI"]);
$baseURL = "http://" . $_SERVER["SERVER_NAME"] . $baseURLtemp[0];

$json = file_get_contents($baseURL . "db/?species=" . $speciesDirty);
$data = json_decode($json, TRUE);

$rawData = $data['rawdata'];
$mapJson = json_encode($data['mapdata']);

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
        <h1><?php echo $rawData['FI']['common_speciesname'] . " (<em>" . $rawData['FI']['speciesname'] . "</em>)"; ?></h1>
        <h2><?php echo "AT: " . $rawData['AT']['common_speciesname'] . ", BG: " . $rawData['BG']['common_speciesname']; ?></h2>

        <div id="content">
          <form action="./">
            <?php speciesSelect(); ?>
            <input id="submit" type="submit" value="Submit">
          </form>

          <div id="map"></div>
        </div>
      </div>

        <script>

        var data = <?php echo $mapJson; ?>;

        $(function(){
            $('#map').vectorMap({
                map: 'europe_merc_en',
                backgroundColor: '#a5cbd7', //'#4b96af',
                series: {
                    regions: [{
                        values: data,
                        scale: ['#e6e696', '#003700'],
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
  $speciesArray['Parus major'] = 'Parus major';
  $speciesArray['Parus caeruleus'] = 'Parus caeruleus';
  $speciesArray['Parus cristatus'] = 'Parus cristatus';
  $speciesArray['Parus ater'] = 'Parus ater';
  $speciesArray['Cygnus cygnus'] = 'Cygnus cygnus';

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

?>