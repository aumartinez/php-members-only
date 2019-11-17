<?php
require_once("inc/dbkey.php");

$conx = mysqli_connect(DBHOST, DBUSER, DBPASS, DBNAME);
if (!$conx) {
  error_log(die("MySQL connection failed: ".mysqli_errno()));
}

$sql = file_get_contents("sql/createtable.sql");
$query = mysqli_query($conx, $sql);

if (!$query) {
  error_log(die("SQL query error: ".mysqli_error($conx)));
}

mysqli_close($conx);

?>
