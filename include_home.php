<?php

require_once("db/index.php");

$nameHeading = "";
$totalHTML = "";
$proTable = proTable();

nameHeading();

?>

<div id="heading">
  <h1>Pesimälinnusto EU:n jäsenmaissa</h1>
  <p>Tämä sivusto perustuu valtioiden vuonna <a href="http://bd.eionet.europa.eu/activities/Reporting/Article_12/Reports_2013/Member_State_Deliveries">2013 EU:lle raportoimiin lintudirektiivin vaatimiin tietoihin</a>. On huomattava että tiedot ovat paikoin puutteellisia: kaikki valtiot eivät ole toimittaneet tietoa kaikista pesimälajeistaan.</p>
</div>

<?php

speciesList();
$echoMainHTML();

?>