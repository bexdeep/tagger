<?php require 'tagger.php';?>

<!DOCTYPE html>
<html lang="en">
<head>
<link rel="stylesheet" href="css/main.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="js/control.js"></script> 

</head>
<body>	
<script src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&amp;version=v2.5" async></script>  

<div class="main largewidth">
	<div class="header">
		<h1>Welcome to Tagger</h1> <p id="demo"></p>
		<img src="img/excel.png">
	</div>
	<div class="clear"></div>
	<div class="userControl">
		<form action="" method="post" style="float:left;">
			<input type="hidden" name="cell_id" value="<?php echo $cell_id; ?>"> <!-- Catpures current cell index-->
			<input type="hidden" name="total_rec" value="<?php echo $totalRecords; ?>"> <!-- Catpures total records-->
		  	<input type="hidden" name="total_tags" value="<?php echo $tagCount; ?>"> <!-- Catpures total records-->
		  	<input type="hidden" name="fileName" value="<?php echo $inputFileName; ?>"> <!-- Catpures total records-->
		    <input type="submit" name="prev" value="Prev" id="prev">
		    <input type="submit" name="next" value="Next" id="next">
		    <input type="submit" name="refresh" value="Refresh" id="refresh">
		</form>
			<a href="fileupload.php"><div class="button postBt">Upload</div></a>
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
	<div class="righthand">
	<div class="userForm">
		<form action="" method="post">
		  <input type="hidden" name="cell_id" value="<?php echo $cell_id; ?>"> <!-- Catpures current cell index-->
		  <input type="hidden" name="total_rec" value="<?php echo $totalRecords; ?>"> <!-- Catpures total records-->
		  <input type="hidden" name="total_tags" value="<?php echo $tagCount; ?>"> <!-- Catpures total records-->
		  <input type="hidden" name="fileName" value="<?php echo $inputFileName; ?>"> <!-- Catpures total records-->

		    <br><label>Brand</label>
		    <input type="text" name="brand" value="<?php echo $worksheet->getCell($brand_col.$cell_id); ?>" list="">
		    <br><br><label>Celebrity</label>
		    <input type="text" name="celeb" value="<?php echo $worksheet->getCell($celeb_col.$cell_id); ?>" list="">
		    <br><br><label>Tag 1</label>
		    <input type="text" name="tag1" value="<?php echo $worksheet->getCell($tag1_col.$cell_id); ?>" list="">
		    <br><br><label>Tag 2</label>
		    <input type="text" name="tag2" value="<?php echo $worksheet->getCell($tag2_col.$cell_id); ?>" list="">
		    <br><br><label>Collection</label>
		    <input type="text" name="colln" value="<?php echo $worksheet->getCell($colln_col.$cell_id); ?>" list="">
		    <br><br><label>Type 1</label>		    		    		    		    
		    <input type="text" name="type1" value="<?php echo $worksheet->getCell($type1_col.$cell_id); ?>" list="type1">
		    <br><br><label>Type 2</label>
		    <input type="text" name="type2" value="<?php echo $worksheet->getCell($type2_col.$cell_id); ?>" list="type2">
		    <br><br><label>Type 3</label>
		    <input type="text" name="type3" value="<?php echo $worksheet->getCell($type3_col.$cell_id); ?>" list="">
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

	<!-- <div class="infoFrame"> -->							
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
			$errorMsg = "TAGGED"."<br>".$errorMsg; 
			$errSen = "erPos";
		}
	
		if ($errorMsg != "") {?>
		<div id="infoError" class="<?php echo $errSen; ?> marginleft"><div class="indicator"></div><span class="error_span"><?php echo $errorMsg; ?></span><div class="clear"></div></div>
		<?php } ?>
	<!-- </div> -->
	</div>	
	<div class="clear"></div>
	<div class="menubar">
		File Name : <a href="<?php echo $inputFileName; ?>"><b><?php echo $inputFileName; ?></b></a> | Press: <b>'j'</b> Previous, <b>'k'</b> Next, <b>'r'</b> Refresh, <b>'f'</b> Load File
	</div>
	<div class="footer">
		<p>Property of bexdeep dataLab.</p>
	</div>	
</div>

<!--Listing all the files in the directory -->
<div id="fileList">
	<div class="image">
		<img src="img/excel.png">
	</div>
	<div class="fileListRight">
	<div class="flist_t">Data Files</div>
	<?php
		$dir = "uploads/";
		if (is_dir($dir)){
		  if ($dh = opendir($dir)){
		    while (($file = readdir($dh)) !== false){
		    	if ($file == "." || $file == ".." || $file == ".DS_Store"){
		    		//Do Nothing
		    	}else{

	?>			
			
			<div class="flist"><?php echo $file; ?></div>			

	<?php
				}
		    }
		    closedir($dh);
		  }
		}
	?>
	</div>
	<div class="clear"></div>
	<div class="fileclose">Press 'f' to close this window.</div>
</div>

</body>
</html>




