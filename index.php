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

        <div id="map" style="width: 778px; height: 900px; border: 4px solid #165878;"></div>
        <script>

        var data = {
          "HU": 0,
          "NL": 128,
          "FI": 64,
          "DE": 32,
          "ES": 16,
          "SE": 8,
          "DK": 4,
          "EE": 2,
          "LV": 1,
          "LT": 160,
          "PL": 150,
          "MT": 140,
          "RO": 130,
          "BE": 120,
          "DE": 110,
          "GB": 100,
          "BE": 90,
          "IT": 80,
          "GR": 70,
          "AT": 60,
          "SK": 50,
          "CZ": 40,
          "IE": 30,
          "FR": 20,
          "PT": 10,
        };
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
