<?php

require_once("db/home.php");

$nameHeading = "Pesimälinnusto EU:n jäsenmaissa";

function echoMainHTML()
{
  global $speciesList;

?>

<div id="heading">
  <h1>Pesimälinnusto EU:n jäsenmaissa</h1>
  <p>Tämä sivusto perustuu valtioiden vuonna <a href="http://bd.eionet.europa.eu/activities/Reporting/Article_12/Reports_2013/Member_State_Deliveries">2013 EU:lle raportoimiin lintudirektiivin vaatimiin tietoihin</a>. On huomattava että tiedot ovat paikoin puutteellisia: kaikki valtiot eivät ole toimittaneet tietoa kaikista pesimälajeistaan.</p>
</div>

<div id="content">

  <table id="datatable">
    <tr>
      <th>Nro</th>
      <th>Laji (&amp; alalaji)</th>
      <th>Parimäärä</th>
    </tr>
  <?php

  $i = 0;
  foreach ($speciesList as $name => $pairs)
  {
    $i++;
    echo "<tr>";
    echo "<td>$i</td>";
    echo "<td><a href=\"?species=" . $name . "&type=population\">$name</a></td>";
    echo "<td>" . format_int($pairs) . "</td>";
    echo "</tr>";
  }

  ?>
  </table>
</div>

<?php

}

function format_int($number)
{
  return number_format($number, 0, ",", ".");
}

?>