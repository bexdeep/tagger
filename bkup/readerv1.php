<?php 
	ini_set('display_errors',1);
	error_reporting(E_ALL); 

	//Using Spreadsheet library
	require 'vendor/autoload.php';
	use PhpOffice\PhpSpreadsheet\Spreadsheet;
	use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
	//------------------------------------------------------------------------------------------------------------------------------

	//Declaring important variables
	$inputFileName = 'W_for_Women.xlsx'; //'W_for_Women.xlsx';	
	$width = '450';
	$src_col = "B";
	$frm_col = "C";
	$lnk_col = "D";
	$cat1_col = "E";
	$cat2_col = "F";
	$debug = 0;
	$writeData = 1;
	$errorMsg = "";
	$getSheetInfo = 0;
	$totalRecords = 0;
	$firstTime = 0;
	$tagCount = 0;
	//End of Important Variable Declaration

	//Reading the data from the Next button
	if (isset($_POST['submit'])) {
	    $cell_id = $_POST['cell_id'];
	    $totalRecords = $_POST['total_rec'];
	    $tagCount = $_POST['total_tags'];
	    $category = $_POST['category'];	    
	    if ($debug == 1) echo $cell_id;
	    $writeData = 1;
  	}elseif (isset($_POST['next'])) {
  		if ($debug == 1) echo "Just accessing last cell: ";
	    $cell_id = $_POST['cell_id'];
	    $totalRecords = $_POST['total_rec'];
	    $tagCount = $_POST['total_tags'];	    
	    $category = $_POST['category'];	    
	    if ($debug == 1) echo $cell_id;
	    $writeData = 0;

  	}elseif (isset($_POST['prev'])) {	    
	    if ($debug == 1) echo "Just accessing last cell: ";
	    $cell_id = $_POST['cell_id'];
	    $totalRecords = $_POST['total_rec'];
	    $tagCount = $_POST['total_tags'];
	    $cell_id = (int)$cell_id - 2;
	    if ($cell_id==0){$cell_id=1; $errorMsg = "You have reached the first row of the records.";}
	    $category = "";	    
	    if ($debug == 1) echo $cell_id;
	    $writeData = 0;

  	}else {
  		$cell_id = 1; 
  		$category = "";
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
		if ($debug == 1) echo "<br>Writing Category"; //debug code
		if ($category== "") {$category="NA";}
		$worksheet->setCellValue($cat1_col.$cell_id, $category); 
		if ($debug == 1) echo "<br>Wrote Data = ".$category."on cell E".$cell_id; //debug code
		$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
		$writer->setPreCalculateFormulas(false);
		$writer->save($inputFileName);
		if ($debug == 1) echo "<br>SUCCESS:".$worksheet->getCell($cat1_col.$cell_id); //debug code		
		$tagCount = countTags($worksheet, $totalRecords, $cat1_col);
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
		$tagCount = countTags($worksheet, $totalRecords, $cat1_col);

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


<!DOCTYPE html>
<html lang="en">
<head>
<style type="text/css">
	body{font-family: Arial;background-color: honeydew;}
	.main{width:800px;margin: auto;background-color: white;border-radius: 8px;padding: 20px;}
	.userControl{margin:10px 0;border-bottom: 1px dashed gainsboro;padding-bottom: 15px;}
	input[type=submit] {
	    padding:5px 15px; 
	    border:0 none;
	    cursor:pointer;
	    -webkit-border-radius: 5px;
	    border-radius: 5px; 
	}

	input[id=submit] {
	    background: crimson;
		color: white;
	}

	input[id=next] {
	    background:gainsboro; 
	}

	input[id=prev] {
	    background:gainsboro; 
	}

	input[type=text] {
	    padding:5px; 
	    border:2px solid #ccc; 
	    -webkit-border-radius: 5px;
	    border-radius: 5px;
	}

	.content{float:left;}
	.infoFrame{
		margin-top: 10px;
		float: left;
		border-radius: 3px;
		background: crimson;
		color: white;
		padding: 20px;
		margin-left: 10px;
		font-size: 12px;
		width:160px;}

	.clear {clear:both;}

	#infoError {margin-top: 15px;color: firebrick;font-style: italic;}
	.info {border-bottom: thin dashed white;padding: 10px 0;}
	.footer {font-size: 10px;text-align: center;}
</style>	

</head>
<body>	
<script src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&amp;version=v2.5" async></script>  

<div class="main">
	<div class="header">
		<h1>Welcome to Category Tagger</h1>
	</div>
	<div class="userControl">
	<form action="" method="post">
	  <input type="hidden" name="cell_id" value="<?php echo $cell_id; ?>"> <!-- Catpures current cell index-->
	  <input type="hidden" name="total_rec" value="<?php echo $totalRecords; ?>"> <!-- Catpures total records-->
	  <input type="hidden" name="total_tags" value="<?php echo $tagCount; ?>"> <!-- Catpures total records-->

	    <label>Choose Trend</label>
	    <input type="text" name="category" value="" list="categoryName1">
		<datalist id="categoryName1">
			<option value="Accessories">Accessories</option>
			<option value="All">All</option>
			<option value="Athleisure">Athleisure</option>
			<option value="Bags">Bags</option>
			<option value="Casuals">Casuals</option>
			<option value="Cosmetics">Cosmetics</option>
			<option value="Denim">Denim</option>
			<option value="Dress">Dress</option>
			<option value="Ethnic">Ethnic</option>
			<option value="Footwear">Footwear</option>
			<option value="Formals">Formals</option>
			<option value="Kidswear">Kidswear</option>
			<option value="Lingeries">Lingeries</option>
			<option value="Store Opening">Store Opening</option>		    
		</datalist>

	    <input type="submit" name="submit" value="Update & Next" id="submit">
	    <input type="submit" name="prev" value="Prev" id="prev">
	    <input type="submit" name="next" value="Next" id="next">

	</form>
	</div>
	<div class="content">
		<?php if ($source == "Facebook") {?>

		    <div class="fb-post" 
		            data-href="<?php echo $msgLink; ?>"
		            data-width="<?php echo $width; ?>">
		            <?php echo "Inside the Facbeook Div"; ?>
		    </div>   

		<?php }else {
			print_r($obj->html);
		} ?>
	</div>
	<div class="infoFrame">
		<div class="info"><span class="lbl">Record No: </span><span class="dataInfo"><?php echo $cell_id -1; ?></span></div>
		<div class="info"><span class="lbl">Total Records: </span><span class="dataInfo"><?php echo $totalRecords; ?></span></div>
		<div class="info" style="font-weight: bold;"><span class="lbl">Tagged Records: </span><span class="dataInfo"><?php echo $tagCount; ?></span></div>
		<div class="info"><span class="lbl">Trend Tag: </span><span class="dataInfo"><?php echo $worksheet->getCell($cat1_col.$cell_id); ?></span></div>

		<div id="infoError"><?php echo $errorMsg; ?></div>
	</div>
	<div class="clear"></div>
	<div class="footer">
		<p>Property of bexdeep dataLab.</p>
	</div>
</div>


</body>
</html>




