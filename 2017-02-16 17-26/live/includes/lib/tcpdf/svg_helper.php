<?php


/* Image Transparency Class */
 
class Image_Transparency {

	var $source_image;
	var $pct;
	var $new_image_name;
	var $save_to_folder;
	
	function __construct($source_image,$pct=50,$new_image_name='',$save_to_folder=''){
		$pi = pathinfo($source_image);
		$this->source_image = $source_image;
		$this->pct = $pct;
		$this->new_image_name = empty($new_image_name) ? basename($source_image,'.'.$pi['extension']).'_'.$pct.'pc' : $new_image_name;
		$this->save_to_folder = empty($save_to_folder) ? dirname($source_image).'/' : $save_to_folder;
	}
		 
	function make_transparent()
	{
	$info = GetImageSize($this->source_image);
	$width = $info[0];
	$height = $info[1];
	 
	$mime = $info['mime'];
	 
	// What sort of image?
	 
	$type = substr(strrchr($mime, '/'), 1);
	 
	switch ($type)
	{
	case 'jpeg':
		$image_create_func = 'ImageCreateFromJPEG';
		$image_save_func = 'ImageJPEG';
		$new_image_ext = 'jpg';
		break;
	 
	case 'png':
		$image_create_func = 'ImageCreateFromPNG';
		$image_save_func = 'ImagePNG';
		$new_image_ext = 'png';
		break;
	 
	case 'bmp':
		$image_create_func = 'ImageCreateFromBMP';
		$image_save_func = 'ImageBMP';
		$new_image_ext = 'bmp';
		break;
	 
	case 'gif':
		$image_create_func = 'ImageCreateFromGIF';
		$image_save_func = 'ImageGIF';
		$new_image_ext = 'gif';
		break;
	 
	case 'vnd.wap.wbmp':
		$image_create_func = 'ImageCreateFromWBMP';
		$image_save_func = 'ImageWBMP';
		$new_image_ext = 'bmp';
		break;
	 
	case 'xbm':
		$image_create_func = 'ImageCreateFromXBM';
		$image_save_func = 'ImageXBM';
		$new_image_ext = 'xbm';
		break;
	 
	default:
		$image_create_func = 'ImageCreateFromJPEG';
		$image_save_func = 'ImageJPEG';
		$new_image_ext = 'jpg';
	}
	 
	// Source Image
	$image = $image_create_func($this->source_image);
	 
	$new_image = ImageCreateTruecolor($width, $height);
	 
	// Set a White & Transparent Background Color
	$bg = ImageColorAllocateAlpha($new_image, 255, 255, 255, 127); // (PHP 4 >= 4.3.2, PHP 5)
	ImageFill($new_image, 0, 0 , $bg);
	 
	// Copy and merge
	ImageCopyMerge($new_image, $image, 0, 0, 0, 0, $width, $height, $this->pct);
	 
	 
	if($this->save_to_folder)
			{
			   if($this->new_image_name)
			   {
			   $new_name = $this->new_image_name.'.'.$new_image_ext;
			   }
			   else
			   {   
			   $new_name = $this->new_image_name(basename($this->source_image)).'_transparent'.'.'.$new_image_ext;
			   }
	 
			$save_path = $this->save_to_folder.$new_name;
			}
			else
			{
			/* Show the image without saving it to a folder */
			   header("Content-Type: ".$mime);
	 
			   $image_save_func($new_image);
	 
			   $save_path = '';
			}
	 
	// Save image
//	imagesavealpha ($new_image, true);
	 
	$process = $image_save_func($new_image, $save_path) or die("There was a problem in saving the new file.");
	 
	return array('result' => $process, 'new_file_path' => $save_path);
		}
	 
	function new_image_name($filename)
	{
		$string = trim($filename);
		$string = strtolower($string);
		$string = trim(ereg_replace("[^ A-Za-z0-9_]", " ", $string));
		$string = ereg_replace("[ \t\n\r]+", "_", $string);
		$string = str_replace(" ", '_', $string);
		$string = ereg_replace("[ _]+", "_", $string);
	 
		return $string;
	}
 
}



function svg_color_sample($color) {
	{{{
	$fill = '';
	
	if (preg_match('%#[a-z0-9]{6}%i',$color)) $fill = $color;
	
	if (preg_match('%^[a-z0-9]{6}$%i',$color)) $fill = '#'.$color;

	$svg_str = '<?xml version="1.0" encoding="UTF-8"?>';
	
	ob_start();
?>
<!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.0//EN" "http://www.w3.org/TR/2001/REC-SVG-20010904/DTD/svg10.dtd">
<svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve" width="11.9286mm" height="14.5732mm" version="1.0" shape-rendering="geometricPrecision" text-rendering="geometricPrecision" image-rendering="optimizeQuality" fill-rule="evenodd" clip-rule="evenodd" viewBox="0 0 333 407" xmlns:xlink="http://www.w3.org/1999/xlink">
 <g id="Layer_x0020_1">
  <metadata id="CorelCorpID_0Corel-Layer"/>
  <path fill="<?php echo $fill; ?>" d="M36 0l297 0 0 407 -297 0c-20,0 -36,-16 -36,-36l0 -335c0,-20 16,-36 36,-36z"/>
 </g>
</svg><?php
	
	$svg_str.= ob_get_clean();
	
	return '@'.$svg_str;
	}}}
}

function svg_band_image($w=200,$h=20,$band_color,$type,&$band_img,$TOPP) {
	
	global $tpt_vars;
	
	$_G['pg_x']=$w*3;
	$_G['pg_y']=$h*3;
	$_G['color']=$band_color;
	$_G['type']=$type;

//	var_dump($type);

	$img_64 = base64_encode($band_img=tpt_PreviewGenerator::generatePreview($tpt_vars, $_G));

	if ($_GET['dump']==4) {
//		echo '<pre>';
//		var_dump($band_color,$type);
//		echo '</pre>';
		header('Content-type: image/png');
		echo base64_decode($img_64);
		die();
	}
	
	$svg_str = '<?xml version="1.0" encoding="UTF-8"?>';
	$bf_i_w = $w;
	
	ob_start(); //###############
	
	if ($TOPP['type']==7) { //keychains
		$w_c = 222.266;
		$h_c = 24;
		$nf = 99.31343525325510874358;
		
		$bx = KCHN_L_SPACE;
		$by = 5.5;
		
		if ($TOPP['text_span']==0) { // F/B message span
			$w = $w/2;
			$h_c = 50;
			$i2_y = KCHN_F_B_SPACE;
		} else if ($TOPP['text_span']==1) {
			$w_c = $w + KCHN_L_SPACE;
		}
		
		?>
<!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.0//EN" "http://www.w3.org/TR/2001/REC-SVG-20010904/DTD/svg10.dtd">
<svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve" width="<?php echo $w_c; ?>mm" height="<?php echo $h_c; ?>mm" version="1.0" shape-rendering="geometricPrecision" text-rendering="geometricPrecision" image-rendering="optimizeQuality" fill-rule="evenodd" clip-rule="evenodd"
viewBox="0 0 <?php echo round($w_c*$nf); ?> <?php echo round($h_c*$nf); ?>"
 xmlns:xlink="http://www.w3.org/1999/xlink">
	
 <defs>

  <clipPath id="id0c">
   <rect x="<?php echo round($bx*$nf); ?>" y="<?php echo round($by*$nf); ?>" 
   rx="100" ry="100" width="<?php echo round($w*$nf); ?>" height="<?php echo round($h*$nf); ?>" />
  </clipPath>
	<?php if ($TOPP['text_span']==0) { ?>
	  <clipPath id="id0c2">
	   <rect x="<?php echo round($bx*$nf); ?>" y="<?php echo round(($by+$i2_y)*$nf); ?>" 
	   rx="100" ry="100" width="<?php echo round($w*$nf); ?>" height="<?php echo round($h*$nf); ?>" />
	  </clipPath>
	<?php } ?>

    <linearGradient id="id0" gradientUnits="userSpaceOnUse" x1="21432.4" y1="600.979" x2="20320.1" y2="1686.15">
     <stop offset="0" stop-opacity="1" stop-color="#96989A"/>
     <stop offset="0.109804" stop-opacity="1" stop-color="#FEFEFE"/>
     <stop offset="0.341176" stop-opacity="1" stop-color="#D2D3D5"/>
     <stop offset="0.6" stop-opacity="1" stop-color="#96989A"/>
     <stop offset="0.85098" stop-opacity="1" stop-color="#FEFEFE"/>
     <stop offset="1" stop-opacity="1" stop-color="#BDBFC1"/>
    </linearGradient>
    <linearGradient id="id1" gradientUnits="userSpaceOnUse" x1="2641.68" y1="602.946" x2="3753.86" y2="1684.18">
     <stop offset="0" stop-opacity="1" stop-color="#96989A"/>
     <stop offset="0.109804" stop-opacity="1" stop-color="#FEFEFE"/>
     <stop offset="0.341176" stop-opacity="1" stop-color="#D2D3D5"/>
     <stop offset="0.6" stop-opacity="1" stop-color="#96989A"/>
     <stop offset="0.85098" stop-opacity="1" stop-color="#FEFEFE"/>
     <stop offset="1" stop-opacity="1" stop-color="#BDBFC1"/>
    </linearGradient>
    <linearGradient id="id2" gradientUnits="userSpaceOnUse" x1="272.215" y1="423.498" x2="1965.83" y2="1863.63">
     <stop offset="0" stop-opacity="1" stop-color="#A9ABAE"/>
     <stop offset="0.188235" stop-opacity="1" stop-color="#FEFEFE"/>
     <stop offset="0.411765" stop-opacity="1" stop-color="#848688"/>
     <stop offset="0.639216" stop-opacity="1" stop-color="#FEFEFE"/>
     <stop offset="1" stop-opacity="1" stop-color="#A9ABAE"/>
    </linearGradient>
 </defs>
 <g id="Camada_x0020_1">
  <metadata id="CorelCorpID_0Corel-Layer"/>
  
  <g clip-path="url(#id0c)">
   <image id="gr.png" transform="matrix(1 0 0 1 <?php echo round($bf_i_w*-50); ?> <?php echo round($h*50); ?>)" 
   x="<?php echo round($bx*$nf+$bf_i_w*50); ?>" y="<?php echo round($by*$nf+$h*-50); ?>" 
   width="<?php echo round($bf_i_w*$nf); ?>" height="<?php echo round($h*$nf); ?>" 
   xlink:href="data:image/png;base64,<?php echo $img_64; ?>"/>
  </g>
	<?php if ($TOPP['text_span']==0) { 
		PR_G::v('bx-w',$bx-$w);
	?>
	  <g clip-path="url(#id0c2)">
	   <image id="gr.png" transform="matrix(1 0 0 1 <?php echo round($bf_i_w*-50); ?> <?php echo round($h*50); ?>)" 
	   x="<?php echo round(($bx-$w)*$nf+$bf_i_w*50); ?>" y="<?php echo round(($by+$i2_y)*$nf+$h*-50); ?>" 
	   width="<?php echo round($bf_i_w*$nf); ?>" height="<?php echo round($h*$nf); ?>" 
	   xlink:href="data:image/png;base64,<?php echo $img_64; ?>"/>
	  </g>
	<?php } ?>

   <rect fill="none" stroke="#999999" stroke-width="8" x="<?php echo round($bx*$nf); ?>" y="<?php echo round($by*$nf); ?>" 
   rx="100" ry="100" width="<?php echo round($w*$nf); ?>" height="<?php echo round($h*$nf); ?>" />
    
  <g id="_404032920">
	<?php if ($TOPP['text_span']==1) { ?>
   <path transform="translate(<?php echo round(($w-222.266+20.4)*$nf); ?>,0)" fill="url(#id0)" stroke="#727376" stroke-width="7.56761" d="M21509 468l-1264 0c-35,0 -63,28 -63,63l0 547c-2,5 -3,11 -3,17 0,29 23,52 51,52 7,0 13,-2 19,-4l1255 0c6,2 12,4 19,4 28,0 51,-23 51,-52 0,-4 -1,-8 -2,-13l0 -551c0,-35 -28,-63 -63,-63zm14 691c28,0 51,23 51,51 0,4 -1,9 -2,13l0 533c0,35 -28,63 -63,63l-1264 0c-35,0 -63,-28 -63,-63l0 -528c-2,-6 -3,-12 -3,-18 0,-28 23,-51 51,-51 6,0 12,1 17,3l1258 0c6,-2 12,-3 18,-3z"/>
    <?php } else if ($TOPP['text_span']==0) { ?>
   <path transform="translate(<?php echo round(($w-$w_c+20.4)*$nf).','.round(($i2_y)*$nf); ?>)" fill="url(#id0)" stroke="#727376" stroke-width="7.56761" d="M21509 468l-1264 0c-35,0 -63,28 -63,63l0 547c-2,5 -3,11 -3,17 0,29 23,52 51,52 7,0 13,-2 19,-4l1255 0c6,2 12,4 19,4 28,0 51,-23 51,-52 0,-4 -1,-8 -2,-13l0 -551c0,-35 -28,-63 -63,-63zm14 691c28,0 51,23 51,51 0,4 -1,9 -2,13l0 533c0,35 -28,63 -63,63l-1264 0c-35,0 -63,-28 -63,-63l0 -528c-2,-6 -3,-12 -3,-18 0,-28 23,-51 51,-51 6,0 12,1 17,3l1258 0c6,-2 12,-3 18,-3z"/>
    <?php } ?>
   <path fill="url(#id1)" stroke="#727376" stroke-width="7.56761" d="M2566 468l1264 0c35,0 63,28 63,63l0 1225c0,35 -28,63 -63,63l-1264 0c-35,0 -63,-28 -63,-63l0 -1225c0,-35 28,-63 63,-63z"/>
   <path fill="url(#id2)" stroke="#727376" stroke-width="7.56761" d="M1208 4c434,0 814,217 1026,544l-147 0c-10,0 -19,0 -28,2l1 0c-194,-245 -503,-403 -852,-403 -586,0 -1061,446 -1061,997 0,550 475,996 1061,996 349,0 658,-158 852,-402l-1 -1c9,2 18,2 28,2l147 0c-212,327 -592,544 -1026,544 -665,0 -1204,-510 -1204,-1139 0,-630 539,-1140 1204,-1140z"/>
    <?php if ($TOPP['text_span']==0) { ?>
     <path transform="scale(-1,1) translate(<?php echo round((-40.4-$w)*$nf).','.round(($i2_y)*$nf); ?>)" fill="url(#id2)" stroke="#727376" stroke-width="7.56761" d="M1208 4c434,0 814,217 1026,544l-147 0c-10,0 -19,0 -28,2l1 0c-194,-245 -503,-403 -852,-403 -586,0 -1061,446 -1061,997 0,550 475,996 1061,996 349,0 658,-158 852,-402l-1 -1c9,2 18,2 28,2l147 0c-212,327 -592,544 -1026,544 -665,0 -1204,-510 -1204,-1139 0,-630 539,-1140 1204,-1140z"/>
    <?php } ?>
  </g>

 </g>
</svg>		
		<?php
		
	//	echo file_get_contents(dfls.'img/'.'keychain_c.svg.tpt');
	} else {
				
		if ($TOPP['type']==5) {
			//slapbands
		//	$h+= 1;
		}
		
		$nf = 95.9;
		$nf = 99.0;
?>
<!DOCTYPE svg PUBLIC "-//W3C//DTD SVG 1.0//EN" "http://www.w3.org/TR/2001/REC-SVG-20010904/DTD/svg10.dtd">
<svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve" width="<?php echo $w; ?>mm" height="<?php echo $h; ?>mm" version="1.0" shape-rendering="geometricPrecision" text-rendering="geometricPrecision" image-rendering="optimizeQuality" fill-rule="evenodd" clip-rule="evenodd"
viewBox="0 0 <?php echo round($w*$nf); ?> <?php echo round($h*$nf); ?>"
 xmlns:xlink="http://www.w3.org/1999/xlink">
 
 <defs>
  <clipPath id="id0">
	<?php if ($TOPP['type']==5) { // slapbands ?>
	  <path fill="#848688" d="M1401 0l18400 0c770,0 1401,557 1401,1238l0 29c0,681 -631,1238 -1401,1238l-18400 0c-771,0 -1401,-557 -1401,-1238l0 -29c0,-681 630,-1238 1401,-1238z"/>
	<?php } else { ?>
   <rect x="0" y="0" rx="100" ry="100" width="<?php echo round($w*$nf); ?>" height="<?php echo round($h*$nf); ?>" />
	<?php } ?>
  </clipPath>

 </defs>

 <g id="Layer_x0020_1">
  <metadata id="CorelCorpID_0Corel-Layer"/>
  <g clip-path="url(#id0)">
   <image id="gr.png" transform="matrix(1 0 0 1 <?php echo round($w*-50); ?> <?php echo round($h*50); ?>)" 
   x="<?php echo round($w*50); ?>" y="<?php echo round($h*-50); ?>" 
   width="<?php echo round($w*$nf); ?>" height="<?php echo round($h*$nf); ?>" 
   xlink:href="data:image/png;base64,<?php echo $img_64; ?>"/>
  </g>
	<?php if ($TOPP['type']==5) { // slapbands ?>
	  <path style="fill-opacity:0;stroke-width:3;stroke:rgb(0,0,0)" d="M1401 0l18400 0c770,0 1401,557 1401,1238l0 29c0,681 -631,1238 -1401,1238l-18400 0c-771,0 -1401,-557 -1401,-1238l0 -29c0,-681 630,-1238 1401,-1238z"/>
	  <path fill="#EEEEEE" style="fill-opacity:0.5;" d="M1216 1023c127,0 229,103 229,229 0,127 -102,230 -229,230 -127,0 -230,-103 -230,-230 0,-126 103,-229 230,-229z"/>
	  <path fill="#EEEEEE" style="fill-opacity:0.5;" d="M19986 1023c127,0 230,103 230,229 0,127 -103,230 -230,230 -127,0 -230,-103 -230,-230 0,-126 103,-229 230,-229z"/>
	<?php } else { ?>
	  <rect style="fill-opacity:0;stroke-width:3;stroke:rgb(0,0,0)" 
	  x="<?php echo round(0.25*$nf); ?>" y="<?php echo round(0.25*$nf); ?>" 
	  rx="100" ry="100" width="<?php echo round(($w-0.5)*$nf); ?>" height="<?php echo round(($h-0.5)*$nf); ?>" />	
	<?php } ?>
 </g>
 
	<?php if ($TOPP['type']==6) { // adj. snapbands ?>
	
   <g transform="scale(3.04) translate(<?php echo (5*32.555).','.(($h-9)/2*32.555); ?>)">
   <circle fill="#D2D3D5" cx="638" cy="147" r="147"/>
   <circle fill="#D2D3D5" cx="1127" cy="147" r="147"/>
   <circle fill="#D2D3D5" cx="147" cy="147" r="147"/>

   <circle fill="#FEFEFE" cx="638" cy="147" r="100"/>
   <circle fill="#FEFEFE" cx="1127" cy="147" r="100"/>
   <circle fill="#FEFEFE" cx="147" cy="147" r="100"/>

   <circle fill="#D2D3D5" cx="638" cy="147" r="75"/>
   <circle fill="#D2D3D5" cx="1127" cy="147" r="75"/>
   <circle fill="#D2D3D5" cx="147" cy="147" r="75"/>
   </g>

   <g transform="scale(3.04) translate(<?php echo (($w-9-5)*32.555).','.(($h-9)/2*32.555); ?>)">
   <circle fill="#FEFEFE" cx="147" cy="147" r="147"/>
   </g>

	<?php } // adj. snapbands ?>
 
 
</svg><?php
	}
	
	$svg_str.= ob_get_clean();
	
	return '@'.$svg_str;
	
}


