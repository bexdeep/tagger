<?php 
	ini_set('display_errors',1);
	error_reporting(E_ALL); 

	//Using Spreadsheet library
	require 'vendor/autoload.php';
	use PhpOffice\PhpSpreadsheet\Spreadsheet;
	use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
	//------------------------------------------------------------------------------------------------------------------------------
	//Declaring important variables

	$inputFileName = "uploads/".'W_for_Women.xlsx'; //'W_for_Women.xlsx';	
	$width = '450';
	$src_col = "B";
	$frm_col = "C";
	$lnk_col = "D";
	$brand_col = "E";
	$celeb_col = "F";
	$tag1_col = "G";
	$tag2_col = "H";
	$colln_col = "I";
	$type1_col = "J";
	$type2_col = "K";
	$type3_col = "L";

	$debug = 0;
	$writeData = 1;
	$errorMsg = "";
	$errSen = "erNeg";
	$getSheetInfo = 0;
	$totalRecords = 0;
	$firstTime = 0;
	$tagCount = 0;
	//End of Important Variable Declaration

	//PRINTING ALL THE POST VALUES.
	if ($debug == 1) print_r($_POST);


	//Reading the data from the Next button
	if (isset($_POST['submit'])) {
	    $cell_id = $_POST['cell_id'];
	    $totalRecords = $_POST['total_rec'];
	    $tagCount = $_POST['total_tags'];
	    $inputFileName = $_POST['fileName'];
	    $brand = $_POST['brand'];	
	    $celeb = $_POST['celeb'];	
	    $tag1 = $_POST['tag1'];	
	    $tag2 = $_POST['tag2'];	
	    $colln = $_POST['colln'];	
	    $type1 = $_POST['type1'];	
	    $type2 = $_POST['type2'];	
	    $type3 = $_POST['type3'];	

	    if ($debug == 1) echo $cell_id;
	    $writeData = 1;
  	}elseif (isset($_POST['next'])) {
  		if ($debug == 1) echo "Just accessing last cell: ";
	    $cell_id = $_POST['cell_id'];
	    $totalRecords = $_POST['total_rec'];
	    $tagCount = $_POST['total_tags'];	
	    $inputFileName = $_POST['fileName'];
	    if ($debug == 1) echo $cell_id;
	    $writeData = 0;

  	}elseif (isset($_POST['prev'])) {	    
	    if ($debug == 1) echo "Just accessing last cell: ";
	    $cell_id = $_POST['cell_id'];
	    $totalRecords = $_POST['total_rec'];
	    $tagCount = $_POST['total_tags'];
	    $inputFileName = $_POST['fileName'];
	    $cell_id = (int)$cell_id - 2;
	    if ($cell_id==0){$cell_id=1; $errorMsg = "You have reached the first row of the records.";}	    
	    if ($debug == 1) echo $cell_id;
	    $writeData = 0;

  	}elseif (isset($_POST['refresh'])) {
  		$inputFileName = $_POST['fileName'];
  		$cell_id = 1; 
  		$writeData = 0;
  		$getSheetInfo = 1;
  		$firstTime = 1;
  	}elseif (isset($_POST['fileUpload'])) {	    
  		$cell_id = 1; 
  		$writeData = 0;
  		$getSheetInfo = 1;
  		$firstTime = 1;
  		$inputFileName = "uploads/".$_POST['fileName']; //'W_for_Women.xlsx';	
  		$errorMsg = $_POST['fileName']." is uploaded.";
  		if ($debug==1) echo "THE FILE".$inputFileName;
  	}else {
  		$cell_id = 1; 
  		$writeData = 0;
  		$getSheetInfo = 1;
  		$firstTime = 1;  	  			
  	}
  	//End of reading data from the form submit.

	/** Load $inputFileName to a Spreadsheet Object  **/
	$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFileName);
	$worksheet = $spreadsheet->getActiveSheet();
	if ($debug == 1) echo "<br>File Loaded."; //debug code
	if ($debug == 1) echo "<br>............................................................<br>"; //debug code


	//Writing the previously inputted Tags on Excel.	
	if ($writeData == 1){
		if ($debug == 1) echo "<br>Writing type1"; //debug code
		if ($brand== "") {$brand="NA";}
		if ($celeb== "") {$celeb="NA";}
		if ($tag1== "") {$tag1="NA";}
		if ($tag2== "") {$tag2="NA";}
		if ($colln== "") {$colln="NA";}
		if ($type1== "") {$type1="NA";}
		if ($type2== "") {$type2="NA";}
		if ($type3== "") {$type3="NA";}

		$worksheet->setCellValue($brand_col.$cell_id, $brand); 
		$worksheet->setCellValue($celeb_col.$cell_id, $celeb); 
		$worksheet->setCellValue($tag1_col.$cell_id, $tag1); 
		$worksheet->setCellValue($tag2_col.$cell_id, $tag2); 
		$worksheet->setCellValue($colln_col.$cell_id, $colln); 
		$worksheet->setCellValue($type1_col.$cell_id, $type1); 
		$worksheet->setCellValue($type2_col.$cell_id, $type2); 
		$worksheet->setCellValue($type3_col.$cell_id, $type3); 

		if ($debug == 1) echo "<br>Wrote Data = ".$type1."on cell E".$cell_id; //debug code
		$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
		$writer->setPreCalculateFormulas(false);
		$writer->save($inputFileName);
		if ($debug == 1) echo "<br>SUCCESS:".$worksheet->getCell($type1_col.$cell_id); //debug code		
		$tagCount = countTags($worksheet, $totalRecords, $type1_col);
	}
	//End of Writing the file from previous Tags.

	//Moving the pointer to next ROW in the excel	
	if ($firstTime != 1) { 
		if ($cell_id == $totalRecords+1){
			$cell_id -= 1;
			$errorMsg = "You have reached the last row of the records.";
		}
	}

	$cell_id = $cell_id + 1;

	$source = $worksheet->getCell($src_col.$cell_id);
	$from = $worksheet->getCell($frm_col.$cell_id);
	$msgLink = $worksheet->getCell($lnk_col.$cell_id);

	if ($debug == 1) echo "<br>Row : ".$cell_id; //debug code
	if ($debug == 1) echo "<br> Source:".$source; //debug code
	if ($debug == 1) echo "<br> From:".$from; //debug code
	if ($debug == 1) echo "<br> Message Link:".$msgLink;  //debug code
	//End of Reading the value from the new ROW.


	//Getting data from new row to display the content.
	switch ($source) {
	    case "Twitter":
	        if ($debug == 1) echo "<br>Twitter"; //debug code
    		$json = file_get_contents('https://publish.twitter.com/oembed?url='.$msgLink.'&maxwidth='.$width);
    		$obj = json_decode($json);
	        break;
	    case "Instagram":
	        if ($debug == 1) echo "<br>Instagram"; //debug code
    		$json = file_get_contents('https://api.instagram.com/oembed?url='.$msgLink.'&maxwidth='.$width);
    		$obj = json_decode($json);
	        break;
	    case "Facebook":
	        if ($debug == 1) echo "<br>Facebook"; //debug code
	        break;    
	    default:
	        if ($debug == 1) echo "<br>Unrecognised platform."; //debug code
	}
	//End of getting info to display the content.

	//Getting Info about the Sheet
	if ($getSheetInfo == 1){
		$totalRecords = countRecords($worksheet,$debug);
		$tagCount = countTags($worksheet, $totalRecords, $type1_col);

	}
	//End of the task.

	//------------------------------------------------------------------------------------------------------------------------------
	// Begning of all the function declarations
	function countTags ($sheet, $records, $col) {
		$records += 2;
		$tagcount = 0;
		for ($i = 2; $i < $records; $i++) {
			$temp = $sheet->getcell($col.$i);
			if ($temp == "") $tagcount += 1;			
		}
		$tagcount = $records - 2 - $tagcount;
		return $tagcount;
	}

	function countRecords ($sheet, $debug){
		$i = 0;
		$row = 2;	
		if ($debug==1) echo "Calculating Record Number";
		while ($sheet->getCell('A'.$row)!=""){			
			$row += 1;			
			$i+=1;
			if ($debug==1) echo "<br>".$i;
		}
		$totalRecords = $i;
		return $totalRecords;
	}

	// End of all the function declarations
	//------------------------------------------------------------------------------------------------------------------------------
?>