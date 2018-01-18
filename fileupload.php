
<?php
$errorMsg = "";
$target_dir = "uploads/";
$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$xlsFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
// Check if image file is a actual image or fake image
if(isset($_POST["submit"])) {
    if ($xlsFileType == "xlsx") {$check =1;} else {$check = 0;}
    if($check == 1) {
        $errorMsg = "<br>File is an excel file.";
        $uploadOk = 1;
    } else {
        $errorMsg = "<br>File is not an excel.";
        $uploadOk = 0;
    }

	// Check if file already exists
    if ($_FILES["fileToUpload"]["name"] == "")
    {
	    $errorMsg = "No File choosen.";
	    $uploadOk = 0;
    }elseif (file_exists($target_file)) {
	    $errorMsg = "File already exists.";
	    $uploadOk = 0;
	}
	// Check file size
	if ($_FILES["fileToUpload"]["size"] > 500000) {
	    $errorMsg = "Your file is too large.";
	    $uploadOk = 0;
	}
	if ($uploadOk == 0) {
	    $errorMsg = "Sorry, your file was not uploaded."."<br>".$errorMsg;
	// if everything is ok, try to upload file
	} else {
	    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
	        $errorMsg = "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
	    } else {
	        $errorMsg = "Sorry, there was an error uploading your file.";
	    }
	}    	

}
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

<div class="main midwidth">
	<div class="header">
		<h1>Upload Files</h1> <p id="demo"></p>
		<img src="img/excel.png">		
	</div>	
	<div class="content">
		<div class="userForm">
			<form action="" method="post" enctype="multipart/form-data">
			    <input type="file" name="fileToUpload" id="fileToUpload">
			    <input type="submit" name="submit" value="Upload File" id="fileupload">
			    <div class="clear"></div>
			</form>
		</div>
		<?php if ($errorMsg != "") {?>				
			<div class="infoFrame">										
				<div class="info"><span class="lbl">File Name: </span><span class="dataInfo"><?php echo $_FILES["fileToUpload"]["name"]; ?></span></div>
				<div class="info"><span class="lbl">File Size: </span><span class="dataInfo"><?php echo $_FILES["fileToUpload"]["size"]; ?>B</span></div>
				<div class="clear"></div>
				<div id="infoError">
					<div class="indicator"></div>
					<div class="error_span"><?php echo $errorMsg; ?></div>
					<div class="clear"></div>				
				</div>
			</div>
		<?php } ?>
	</div>	

	<div class="clear"></div>
	<div class="menubar">
		<a href="index.php"><< Back to Tagger</a>
	</div>		
	<div class="footer">
		<p>Property of bexdeep dataLab.</p>
	</div>

	
</div>


</body>
</html>




