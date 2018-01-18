<!DOCTYPE html>
<html>
<body>

	<?php 
	ini_set('display_errors',1);
	error_reporting(E_ALL); 

	require 'vendor/autoload.php';

	use PhpOffice\PhpSpreadsheet\Spreadsheet;
	use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


	echo "<br>Welcome to sheetread.";

	echo "<br>............................................................<br>";


	$source = $worksheet->getCell('B3');
	$from = $worksheet->getCell('E3');
	$msgLink = $worksheet->getCell('AB3');
	
	echo "<br> Source:".$source;
	echo "<br> From:".$from;
	echo "<br> Message Link:".$msgLink;

?>


<form action="reader.php" method="get">
  <input type="hidden" name="act" value="run">
  <input type="submit" value="Run me now!">
</form>


</body>
</html>