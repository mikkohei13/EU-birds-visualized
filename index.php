<?php
header('Content-Type: text/html; charset=utf-8');
/*

MUST
- Tsekkaa helmipöllö: fi vs. se

SHOULD
- duplikaatit?
- suomenkielinen lajilista tietokantaan, join automaattisesti; näin uusien importien mukana mahdollisesti tulevat uudet lajit tulevat valikkoon mukaan heti 

NICE
- tilastosivun taulukkoon kokonaisparimäärä toisella kyselyllä
- select2
- Kreikan data? Ranskan ja Tsekin datan päivitys?

DOC
p - number of pairs
b - number of breeding females
c - number of calling/lekking males
i - number of individuals
m - males

n = null (POISTA NULLIT TIETOKANNASTA ja KONVERTTERISTA)


*/

if (isset($_GET['species']) && isset($_GET['type']))
{
  require_once("include_species.php");
}
else
{
  require_once("include_home.php");
}

//print_r ($speciesData); // debug

?>
<!doctype html>
<html class="no-js" lang="fi">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title><?php echo strip_tags($nameHeading); ?> - levinneisyys ja määrä Euroopassa - biomi.org</title>
        <meta name="description" content="Lajin <?php echo strip_tags($nameHeading); ?> levinneisyys ja runsaus Euroopassa lintudirektiivin raportointidatan perusteella.">
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

        <?php echoMainHTML(); ?>

        <div id="footer">
          <a href="http://bd.eionet.europa.eu/activities/Reporting/Article_12/Reports_2013/Member_State_Deliveries">Datalähde: Eionet - European Topic Centre on Biological Diversity,
          Birds Directive, Article 12, Reports 2013</a>
          <a href="">Mikko Heikkinen/biomi.org</a>
          <a href="https://github.com/mikkohei13/EU-birds-visualized">Source on Github</a>
        </div>

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
