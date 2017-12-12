<?php
include("checkdocument.php");

$dlugosc = strlen($response);
if(($poz = strpos($response, '_rev')) !== false) {
	$od = $poz + 7;
} else if(($poz = strpos($response, 'rev')) !== false) {
	$od = $poz + 6;
}
$temp = substr($response, $od);
if(($do = strpos($temp, '"')) !== false) {}
$revision = substr($temp, 0, $do);

/*TEST
echo $response."<br>";
echo $dlugosc."<br>";
echo $poz."<br>";
echo $od."<br>";
echo $temp."<br>";
echo $do."<br>";
echo $revision."<br>";
*/
?>