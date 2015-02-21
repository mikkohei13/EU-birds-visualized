<pre>
<?php
require_once "connection.php";

$db = new Db();

// Quote and escape form submitted values
//$name = $db -> quote($_POST['username']);
//$email = $db -> quote($_POST['email']);

$rows = $db -> select("SELECT * FROM `eub_birds` LIMIT 1");

print_r ($rows);

echo "\nEND";