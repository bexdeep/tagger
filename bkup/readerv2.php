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
	    if ($debug == 1) echo $cell_id;
	    $writeData = 0;

  	}elseif (isset($_POST['prev'])) {	    
	    if ($debug == 1) echo "Just accessing last cell: ";
	    $cell_id = $_POST['cell_id'];
	    $totalRecords = $_POST['total_rec'];
	    $tagCount = $_POST['total_tags'];
	    $cell_id = (int)$cell_id - 2;
	    if ($cell_id==0){$cell_id=1; $errorMsg = "You have reached the first row of the records.";}	    
	    if ($debug == 1) echo $cell_id;
	    $writeData = 0;

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


<!DOCTYPE html>
<html lang="en">
<head>
<link rel="stylesheet" href="css/main.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="js/control.js"></script> 

</head>
<body>	
<script src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&amp;version=v2.5" async></script>  

<div class="main">
	<div class="header">
		<h1>Welcome to Tagger</h1> <p id="demo"></p>
	</div>
	<div class="userControl">
		<form action="" method="post" style="float:left;">
			<input type="hidden" name="cell_id" value="<?php echo $cell_id; ?>"> <!-- Catpures current cell index-->
			<input type="hidden" name="total_rec" value="<?php echo $totalRecords; ?>"> <!-- Catpures total records-->
		  	<input type="hidden" name="total_tags" value="<?php echo $tagCount; ?>"> <!-- Catpures total records-->
		    <input type="submit" name="prev" value="Prev" id="prev">
		    <input type="submit" name="next" value="Next" id="next">
		</form>
		<div class="navi">
			<?php echo $cell_id -1; ?> Out of <?php echo $totalRecords; ?> Records.
			Tagged Records: <?php echo $tagCount; ?>
		</div>
		<div class="clear"></div>
	</div>
	
	<?php if ($source == "Twitter") {?>
	<div class="content">
	<?php }else { ?>
	<div class="content pushcontent">
	<?php }	?>	

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
	<div class="lefthand">
	<div class="userForm">
		<form action="" method="post">
		  <input type="hidden" name="cell_id" value="<?php echo $cell_id; ?>"> <!-- Catpures current cell index-->
		  <input type="hidden" name="total_rec" value="<?php echo $totalRecords; ?>"> <!-- Catpures total records-->
		  <input type="hidden" name="total_tags" value="<?php echo $tagCount; ?>"> <!-- Catpures total records-->

		    <br><label>Brand</label>
		    <input type="text" name="brand" value="" list="">
		    <br><br><label>Celebrity</label>
		    <input type="text" name="celeb" value="" list="">
		    <br><br><label>Tag 1</label>
		    <input type="text" name="tag1" value="" list="">
		    <br><br><label>Tag 2</label>
		    <input type="text" name="tag2" value="" list="">
		    <br><br><label>Collection</label>
		    <input type="text" name="colln" value="" list="">
		    <br><br><label>Type 1</label>		    		    		    		    
		    <input type="text" name="type1" value="" list="type1">
		    <br><br><label>Type 2</label>
		    <input type="text" name="type2" value="" list="type2">
		    <br><br><label>Type 3</label>
		    <input type="text" name="type3" value="" list="">
			<datalist id="type1">
				<option value="Accessories">Accessories</option>
				<option value="Athleisure">Athleisure</option>
				<option value="Bags">Bags</option>
				<option value="Casuals">Casuals</option>
				<option value="Cosmetics">Cosmetics</option>
				<option value="Denim">Denim</option>
				<option value="Dress">Dress</option>
				<option value="Ethnic">Ethnic</option>
				<option value="Formals">Formals</option>
				<option value="Kids Wear">Kids Wear</option>
				<option value="Lingeries">Lingeries</option>
				<option value="Footwears">Footwears</option>	    
			</datalist>
			<datalist id="type2">
				<option value="Sunglasses">Sunglasses</option>
				<option value="Watches">Watches</option>
				<option value="Earrings">Earrings</option>
				<option value="Headphones">Headphones</option>
				<option value="Jewelley">Jewelley</option>
				<option value="Necklace">Necklace</option>
				<option value="Fitness band">Fitness band</option>
				<option value="Jacket">Jacket</option>
				<option value="Joggers">Joggers</option>
				<option value="Shorts">Shorts</option>
				<option value="Sweatshirt">Sweatshirt</option>
				<option value="Top">Top</option>
				<option value="T-Shirt">T-Shirt</option>
				<option value="Vest">Vest</option>
				<option value="Yoga wear">Yoga wear</option>
				<option value="Trackpant">Trackpant</option>
				<option value="Bagpack">Bagpack</option>
				<option value="Handbag">Handbag</option>
				<option value="Clutch">Clutch</option>
				<option value="Sling Bag">Sling Bag</option>
				<option value="All">All</option>
				<option value="Blouse">Blouse</option>
				<option value="Chinos">Chinos</option>
				<option value="Cold Shoulder Shirt">Cold Shoulder Shirt</option>
				<option value="Cold Shoulder Top">Cold Shoulder Top</option>
				<option value="Crop Top">Crop Top</option>
				<option value="Jacket">Jacket</option>
				<option value="Shirts">Shirts</option>
				<option value="Skirt">Skirt</option>
				<option value="T-Shirt">T-Shirt</option>
				<option value="Top">Top</option>
				<option value="Tube Top">Tube Top</option>
				<option value="Tunic">Tunic</option>
				<option value="Culottes">Culottes</option>
				<option value="Jumpsuit">Jumpsuit</option>
				<option value="Long Jacket">Long Jacket</option>
				<option value="Off Shoulder Top">Off Shoulder Top</option>
				<option value="Sheer Top">Sheer Top</option>
				<option value="Shirt">Shirt</option>
				<option value="Shorts">Shorts</option>
				<option value="Shrug">Shrug</option>
				<option value="Sweater">Sweater</option>
				<option value="Sweatshirt">Sweatshirt</option>
				<option value="Trousers">Trousers</option>
				<option value="Coat">Coat</option>
				<option value="lower">lower</option>
				<option value="Long Coat">Long Coat</option>
				<option value="Scarf">Scarf</option>
				<option value="Shawls">Shawls</option>
				<option value="Hoodie">Hoodie</option>
				<option value="Dungarees">Dungarees</option>
				<option value="Cargo">Cargo</option>
				<option value="Eyeliner">Eyeliner</option>
				<option value="Lipstick">Lipstick</option>
				<option value="Nail Paint">Nail Paint</option>
				<option value="Foundation">Foundation</option>
				<option value="Highlighters">Highlighters</option>
				<option value="Eye Shadow">Eye Shadow</option>
				<option value="Face Pack">Face Pack</option>
				<option value="Fancy Dress">Fancy Dress</option>
				<option value="High Slit Dress">High Slit Dress</option>
				<option value="Maxi Dress">Maxi Dress</option>
				<option value="Off Shoulder">Off Shoulder</option>
				<option value="Short Dress">Short Dress</option>
				<option value="Wrap Dress">Wrap Dress</option>
				<option value="Sleeveless Dress">Sleeveless Dress</option>
				<option value="Jacket">Jacket</option>
				<option value="Jeans">Jeans</option>
				<option value="Culottes">Culottes</option>
				<option value="Kurtis">Kurtis</option>
				<option value="Palazzo Suits">Palazzo Suits</option>
				<option value="Palazzo Pant">Palazzo Pant</option>
				<option value="Sarees">Sarees</option>
				<option value="Shirt">Shirt</option>
				<option value="Suit">Suit</option>
				<option value="Pant">Pant</option>
				<option value="Bra">Bra</option>
				<option value="Vest">Vest</option>
				<option value="Shorts">Shorts</option>
				<option value="Pantie">Pantie</option>
				<option value="Swim Suit">Swim Suit</option>
				<option value="Fairytale Dress">Fairytale Dress</option>
				<option value="T-Shirt">T-Shirt</option>
				<option value="Jacket">Jacket</option>
				<option value="Boots">Boots</option>
				<option value="High Heels">High Heels</option>
				<option value="Sport Shoes">Sport Shoes</option>
				<option value="Slides">Slides</option>
				<option value="Casual Shoes">Casual Shoes</option>
				<option value="Stilettos">Stilettos</option>
				<option value="Heels">Heels</option>				
			</datalist>				

			<br><br>
		    <input type="submit" name="submit" value="Update & Next" id="submit">
		    <div class="clear"></div>
		</form>
	</div>

	<div class="infoFrame">					
		
	<?php 

		if($worksheet->getCell($brand_col.$cell_id) == "") {$p1 = 1;}else{$p1 = 0;};
		if($worksheet->getCell($celeb_col.$cell_id) == "") {$p2 = 1;}else{$p2 = 0;};
		if($worksheet->getCell($tag1_col.$cell_id) == "") {$p3 = 1;}else{$p3 = 0;};
		if($worksheet->getCell($tag2_col.$cell_id) == "") {$p4 = 1;}else{$p4 = 0;};
		if($worksheet->getCell($colln_col.$cell_id) == "") {$p5 = 1;}else{$p5 = 0;};
		if($worksheet->getCell($type1_col.$cell_id) == "") {$p6 = 1;}else{$p6 = 0;};
		if($worksheet->getCell($type2_col.$cell_id) == "") {$p7 = 1;}else{$p7 = 0;};
		if($worksheet->getCell($type3_col.$cell_id) == "") {$p8 = 1;}else{$p8 = 0;};
		
		if($p1 && $p2 && $p3 && $p4 && $p5 && $p6 && $p7 && $p8) {$p9 = 1;}else{$p9 = 0;}

		if ($p9 == 1) {
			$errorMsg = "UNTAGGED";
		}else {
	?>		

		<div class="info"><span class="lbl">Brand: </span><span class="dataInfo"><?php echo $worksheet->getCell($brand_col.$cell_id); ?></span></div>
		<div class="info"><span class="lbl">Celebrity: </span><span class="dataInfo"><?php echo $worksheet->getCell($celeb_col.$cell_id); ?></span></div>
		<div class="info"><span class="lbl">Tag 1: </span><span class="dataInfo"><?php echo $worksheet->getCell($tag1_col.$cell_id); ?></span></div>
		<div class="info"><span class="lbl">Tag 2: </span><span class="dataInfo"><?php echo $worksheet->getCell($tag2_col.$cell_id); ?></span></div>
		<div class="info"><span class="lbl">Collection: </span><span class="dataInfo"><?php echo $worksheet->getCell($colln_col.$cell_id); ?></span></div>
		<div class="info"><span class="lbl">Type 1: </span><span class="dataInfo"><?php echo $worksheet->getCell($type1_col.$cell_id); ?></span></div>
		<div class="info"><span class="lbl">Type 2: </span><span class="dataInfo"><?php echo $worksheet->getCell($type2_col.$cell_id); ?></span></div>
		<div class="info"><span class="lbl">Type 3: </span><span class="dataInfo"><?php echo $worksheet->getCell($type3_col.$cell_id); ?></span></div>
		<div class="clear"></div>
	<?php } ?>	
	
		<?php if ($errorMsg != "") {?>
		<div id="infoError"><div class="indicator"></div><span class="error_span"><?php echo $errorMsg; ?></span><div class="clear"></div></div>
		<?php } ?>
	</div>
	</div>

	<div class="clear"></div>
	<div class="menubar">
		File Name : <b><?php echo $inputFileName; ?></b> | Press: <b>'j'</b> for Previous, <b>'k'</b> for Next
	</div>
	<div class="footer">
		<p>Property of bexdeep dataLab.</p>
	</div>
</div>


</body>
</html>




