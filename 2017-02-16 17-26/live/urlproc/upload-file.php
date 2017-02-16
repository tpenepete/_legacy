<?php

$max_size = 5000000; // <-- 5 megabytes

//ini_set('upload_max_filesize',$max_size);

/*
$types = array('image/jpeg', 'image/jpg', 'image/gif', 'image/png');
if (in_array($_FILES['inputname']['type'], $types)) {
// Your file handing script here
} else {
// Error, filetype not supported
	echo '<script>alert("File type is not supported");</script>';
	echo "error";
	die();
}
*/
$contentLength = 0;
if(isset($_SERVER['CONTENT_LENGTH'])){
	$contentLength = (int)$_SERVER['CONTENT_LENGTH'];
}

if ($contentLength > $max_size) {
	echo "toobig";	

} else {
/*if ($_SERVER["REMOTE_ADDR"] == '85.130.27.25')
{
	print('<pre>');
	print_r($_FILES);
	print('</pre>');
	die();
}*/

//	$uploaddir = '';
	$uploadedFileName = '';
	if (isset($_FILES['uploaded']['name'])) {
		$uploadedFileName = basename($_FILES['uploaded']['name']);
	}
	$uploaddir = CUSTOM_CLIPART_PATH;
	$filenam =  preg_replace('/[^A-Za-z0-9\.]/','', $uploadedFileName);
	

	if (file_exists($uploaddir.DIRECTORY_SEPARATOR.$filenam) == true) {
	  $filenam =  preg_replace('/[^A-Za-z0-9\.]/','',$uploadedFileName);
	  $filenam = str_replace('.','-'. uniqid().'.',$filenam);
	} else {
	  $filenam =  preg_replace('/[^A-Za-z0-9\.]/','',$uploadedFileName);
	}
	$file = $uploaddir .DIRECTORY_SEPARATOR. $filenam;
	//$file = $uploaddir . basename($_FILES['uploaded']['name']);

	if (!preg_match('#\.(png|jpe?g|pdf|bmp|gif|eps|svg|tiff?|tga|ico|psd|ai)$#i',$file)) {
	// Error, filetype not supported
		echo "File type is not supported";
		die();
	}
	
	$size = $_FILES['uploaded']['size'];
	
	if ($size>$max_size) {
		echo "File size is too big";	
	} else {
		if (move_uploaded_file($_FILES['uploaded']['tmp_name'], $file)) { 
			echo "success|".$filenam.'|';
			if (!empty($_GET['ieitem'])) {
				echo '
				<script type="text/javascript">
					try{
						parent.upsuccie("'.$filenam.'",'.(int)substr($_GET['ieitem'],1).');
					}catch(e){}
				</script>';
			}
		} else {
			echo "error";
		}
	}

}
//var_dump($_FILES);

