<?php
	
function getBrowser()
{
    $u_agent = $_SERVER['HTTP_USER_AGENT'];
    $bname = 'Unknown';
    $platform = 'Unknown';
    $version= "";
	$ub = "";


    //First get the platform?
    if (preg_match('/linux/i', $u_agent)) {
        $platform = 'linux';
    }
    elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
        $platform = 'mac';
    }
    elseif (preg_match('/windows|win32/i', $u_agent)) {
        $platform = 'windows';
    }
   
   
    // Next get the name of the useragent yes seperately and for good reason
    if(preg_match('/MSIE/i',$u_agent) && !preg_match('/Opera/i',$u_agent))
    {
        $bname = 'Internet Explorer';
        $ub = "MSIE";
    }
    elseif(preg_match('/Firefox/i',$u_agent))
    {
        $bname = 'Mozilla Firefox';
        $ub = "Firefox";
    }
    elseif(preg_match('/Chrome/i',$u_agent))
    {
        $bname = 'Google Chrome';
        $ub = "Chrome";
    }
    elseif(preg_match('/Safari/i',$u_agent))
    {
        $bname = 'Apple Safari';
        $ub = "Safari";
    }
    elseif(preg_match('/Opera/i',$u_agent))
    {
        $bname = 'Opera';
        $ub = "Opera";
    }
    elseif(preg_match('/Netscape/i',$u_agent))
    {
        $bname = 'Netscape';
        $ub = "Netscape";
    }
   
    // finally get the correct version number
    $known = array('Version', $ub, 'other');
    $pattern = '#(?<browser>' . join('|', $known) .
    ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
    if (!preg_match_all($pattern, $u_agent, $matches)) {
        // we have no matching number just continue
    }
   
    // see how many we have
    $i = count($matches['browser']);
    if ($i != 1) {
        //we will have two since we are not using 'other' argument yet
        //see if version is before or after the name
        if (strripos($u_agent,"Version") < strripos($u_agent,$ub)){
            $version= $matches['version'][0];
        }
        else {
            $version= $matches['version'][1];
        }
    }
    else {
        $version= $matches['version'][0];
    }
   
    // check if we have a number
    if ($version==null || $version=="") {$version="?";}
   
    return array(
        'userAgent' => $u_agent,
        'name'      => $bname,
        'version'   => $version,
        'platform'  => $platform,
        'pattern'    => $pattern
    );
}

?>

<?php
if((isset($_POST['action'])) && ($_POST['action'] == 'show_subcategory')) {
	$count = $_POST['count'];
	if($count == 5) {
	echo '<div class="list-subcategory-corner">';
	} else {
	echo '<div class="list-subcategory">';
	}
	echo '<div align="right" style="padding-right:26px;"><a href="#" onclick="close_cat('.$_POST['category_id'].'); return false;" style="color:#db0303;"><strong>Close</strong></a></div><div style="height:356px; overflow:auto; width:218px;">';
	$category_id = $_POST['category_id'];
	 $category_qry = mysql_query('Select * from tpt_module_bandclipartcategory where parent_id ='.$category_id.' order by category_name ASC');
	 while($category_result = mysql_fetch_array($category_qry)) { ?>
	 <div align="left" style="padding-left:30px;"><a href="#" title="<?php echo $category_result['category_name'].'&nbsp;Clipart'; ?>" onclick="list_clipart_ori('<?php echo $category_result['id']; ?>'); return false;"><img src="<?php echo CLIPARTS_URL; ?>/categories/<?php echo $category_result['category_image']; ?>" border="0">&nbsp;<?php echo $category_result['category_name']; ?></a></div>
  <?php } 
  echo '</div></div>';
}

if((isset($_POST['action'])) && ($_POST['action'] == 'show_subcategory1')) {
	$count = $_POST['count'];
	if($count == 5) {
	echo '<div style="padding:5px; border: solid 1px #000; position:absolute; margin-left:-180px; margin-top:5px; background-color:#e2d7c2;">';
	} else {
	echo '<div style="padding:5px; border: solid 1px #000; position:absolute; margin-left:90px; margin-top:5px; background-color:#e2d7c2;">';
	}
	echo '<div class="text-right" style="padding-right:26px;"><a href="#" onclick="close_cat('.$_POST['category_id'].'); return false;" style="color:#db0303;"><strong>Close</strong></a></div><div style="height:356px; overflow:auto; width:218px;">';
	 $category_id = $_POST['category_id'];
	
	 
	 $category_qry = mysql_query('Select * from tpt_module_bandclipartcategory where parent_id ='.$category_id.' order by category_name ASC');
	 while($category_result = mysql_fetch_array($category_qry)) { 
	 
	 ?>
	 <div align="left" style="padding-left:30px;"><a href="#" onclick="list_clipart_ori1('<?php echo $category_result['id']; ?>'); return false;"><img src="<?php echo CLIPARTS_URL; ?>/categories/<?php echo $category_result['category_image']; ?>" border="0">&nbsp;<?php echo $category_result['category_name']; ?></a></div>
  <?php } 
  echo '</div></div>';
}?>

<?php
if((isset($_POST['action'])) && ($_POST['action'] == 'show_clipart')) {
	
	$cat_id = $_POST['cat_id'];
	$category_qry = mysql_query('Select * from tpt_module_bandclipartcategory where id ='.$cat_id.' order by category_name ASC') or die(mysql_error());
	$category_result = mysql_fetch_array($category_qry);
	echo '<div class="float-right"><a href="#" onclick="window.scroll(0,160); return false;">Main category</a></div><h1>'.$category_result['category_name'].'</h1><div class="clear"></div>';
	
	$list_clip_qry = mysql_query("Select * from tpt_module_bandclipart where category = '".$cat_id."' order by name");
	$num = mysql_num_rows($list_clip_qry);
	if($num != 0) {
	while($list_clip_result = mysql_fetch_array($list_clip_qry)) {
		
	
	$chk_folder_qry = mysql_query('Select parent_id from tpt_module_bandclipartcategory where id ='.$cat_id.' order by category_name ASC');
	$chk_folder_result = mysql_fetch_array($chk_folder_qry);
	
	if($chk_folder_result['parent_id'] == 0) {
		
		$folder_qry = mysql_query('Select folder from tpt_module_bandclipartcategory where id ='.$cat_id.' order by category_name ASC');
		$folder_result = mysql_fetch_array($folder_qry);
	    $folder = $folder_result['folder'];
		
	} else {
		
		$folder_qry = mysql_query('Select folder from tpt_module_bandclipartcategory where id ='.$chk_folder_result['parent_id'].' order by category_name ASC');
		$folder_result = mysql_fetch_array($folder_qry);
	    $folder = $folder_result['folder'];
		
	}
	
	$search  = array('-', '_');
    $replace = array(' ', ' ');
// now try it
$ua=getBrowser();
//$yourbrowser= "Your browser: " . $ua['name'] . " " . $ua['version'] . " on " .$ua['platform'] . " reports: <br >" . $ua['userAgent'];
//print_r($yourbrowser);


if ( ($ua['name'] == 'Internet Explorer') && ($ua['version'] < '9.0'))
{
	
?>
    <div class="float-left width-110 height-100 font-size-11">
		<div style="overflow: hidden;width:90px; height:47px; background-image:url(<?php echo TPT_IMAGES_URL; ?>/clipart-bg-1.png); background-position:center; padding-top:4px;">
			<div style="display:none">
				<img src="<?php echo CLIPARTS_URL; ?>/<?php echo $folder?>/1inch/<?php echo $list_clip_result['image']?>">
			1</div>
			<div style="float:none; margin-right:10px; margin-bottom:10px; background-image:url(<?php echo TPT_IMAGES_URL; ?>/clipart-bg-1.png); background-position:center;" onmouseover="Tip('<img src=\'<?php echo CLIPARTS_URL; ?>/<?php echo $folder?>/1inch/<?php echo $list_clip_result['image']?>\'>')" onmouseout="UnTip()">
				<img style="max-width: 90px;max-height: 40px;" src="<?php echo CLIPARTS_URL; ?>/<?php echo $folder?>/regular/<?php echo $list_clip_result['image']?>"></div>
			</div>
			<div class="height-30 width-90 padding-top-2 padding-bottom-2 font-size-10" style="background-color:#C4BBB4; color:#3f3f3f; font-family:arial;">
				<?php echo str_replace($search, $replace, $list_clip_result['name'])?>
			</div>
		</div>

<?php }//if uaname closes
else {
?>
		
	<div class="font-size-11 width-110 height-100 float-left">
		<div style="overflow: hidden;width:90px; height:47px; background-image:url(<?php echo TPT_IMAGES_URL; ?>/clipart-bg-1.png); background-position:center; padding-top:4px;">
			<div style="display:none">
				<img src="<?php echo CLIPARTS_URL; ?>/<?php echo $folder?>/regular/SVG/<?php echo $list_clip_result['svg']?>">
			</div>
			
				<div style="cursor: pointer;float:none; margin-right:10px; margin-bottom:10px; background-image:url(<?php echo TPT_IMAGES_URL; ?>/clipart-bg-1.png); background-position:center;" >
					<img style="max-width: 90px;max-height: 40px;" src="<?php echo CLIPARTS_URL; ?>/<?php echo $folder?>/regular/<?php echo $list_clip_result['image']?>" onmouseover="Tip('<img style=\'padding:0px;margin:0px;border:1px solid black;\' width=\'400\' height=\'400\' src=\'<?php echo CLIPARTS_URL; ?>/<?php echo $folder; ?>/regular/SVG/<?php echo $list_clip_result['svg']; ?>\'>');"  onmouseout="UnTip()" />
				</div>
			
		</div>
		<div class="height-30 width-90 padding-top-2 padding-bottom-2 font-size-10" style="background-color:#C4BBB4; color:#3f3f3f; font-family:arial;">
			<?php echo str_replace($search, $replace, $list_clip_result['name'])?>
		</div>
	</div>
    
	<?php
}//ua name else
} } else { echo '<h3>No cliparts in this category.</h3>'; }
}

//	$ua = @getBrowser();


if((isset($_POST['action'])) && ($_POST['action'] == 'show_clipart1')) {
	
	$cat_id = $_POST['cat_id'];
	$cp = $_POST['cp'];
	
	$list_clip_qry = mysql_query("Select * from tpt_module_bandclipart where category = '".$cat_id."' order by name");
	$num = mysql_num_rows($list_clip_qry);
	if($num != 0) {
	while($list_clip_result = mysql_fetch_array($list_clip_qry)) {
	
	$chk_folder_qry = mysql_query('Select parent_id from tpt_module_bandclipartcategory where id ='.$cat_id.' order by category_name ASC');
	$chk_folder_result = mysql_fetch_array($chk_folder_qry);
	
	if($chk_folder_result['parent_id'] == 0) {
		
		$folder_qry = mysql_query('Select folder from tpt_module_bandclipartcategory where id ='.$cat_id.' order by category_name ASC');
		$folder_result = mysql_fetch_array($folder_qry);
	    $folder = $folder_result['folder'];
		
	} else {
		
		$folder_qry = mysql_query('Select folder from tpt_module_bandclipartcategory where id ='.$chk_folder_result['parent_id'].' order by category_name ASC');
		$folder_result = mysql_fetch_array($folder_qry);
	    $folder = $folder_result['folder'];
		
	}
	
	$search  = array('-', '_');
    $replace = array(' ', ' ');
		
	if($cp == 'front_left') { $set_clipart = 'set_art_front_left('."'".$folder."/regular/".$list_clip_result['image']."','".$list_clip_result['id']."'".');'; }
	if($cp == 'front_right') { $set_clipart = 'set_art_front_right('."'".$folder."/regular/".$list_clip_result['image']."','".$list_clip_result['id']."'".');'; }
	if($cp == 'back_left') { $set_clipart = 'set_art_back_left('."'".$folder."/regular/".$list_clip_result['image']."','".$list_clip_result['id']."'".');'; }
	if($cp == 'back_right') { $set_clipart = 'set_art_back_right('."'".$folder."/regular/".$list_clip_result['image']."','".$list_clip_result['id']."'".');'; }
	
	
	if($cp == 'front_left_2') { $set_clipart = 'set_art_front_left_2('."'".$folder."/regular/".$list_clip_result['image']."','".$list_clip_result['id']."'".');'; }
	if($cp == 'front_right_2') { $set_clipart = 'set_art_front_right_2('."'".$folder."/regular/".$list_clip_result['image']."','".$list_clip_result['id']."'".');'; }
	if($cp == 'back_left_2') { $set_clipart = 'set_art_back_left_2('."'".$folder."/regular/".$list_clip_result['image']."','".$list_clip_result['id']."'".');'; }
	if($cp == 'back_right_2') { $set_clipart = 'set_art_back_right_2('."'".$folder."/regular/".$list_clip_result['image']."','".$list_clip_result['id']."'".');'; }
	
	
	
	
	
//	$ua = getBrowser();
	// tooltip addition to the builder...
	$thetip = 'onmouseout="UnTip();" ';
	if ( preg_match('#msie#i',$_SERVER['HTTP_USER_AGENT'] )) {
		
	//		 if ($_SERVER['REMOTE_ADDR']=='109.160.0.218') die('sassadasdas'); 

//		$thetip.= 'onmouseover="Tip(\'<div style="position:absolute; z-index:25000;"><img src=\\\'http://www.amazingwristbands.com/clipart/'.$folder.'/1inch/'.$list_clip_result['image'].'\\\' width=\\\'400\\\' height=\\\'400\\\'></div>\');" ';

		$thetip.= 'onmouseover="Tip(\'<img src=\\\''.CLIPARTS_URL.'/'.$folder.'/1inch/'.$list_clip_result['image'].'\\\' >\');" ';

	} else {
		
	//		if ($_SERVER['REMOTE_ADDR']=='109.160.0.218') die('sassadasdas'); 

		$thetip.= 'onmouseover="Tip(\'<img src=\\\''.CLIPARTS_URL.'/'.$folder.'/regular/SVG/'.$list_clip_result['svg'].'\\\' width=\\\'400\\\' height=\\\'400\\\'>\');" ';
	}
	
	 
	 $user_agent = $_SERVER['HTTP_USER_AGENT'];
	 $pos1 = strpos($user_agent, 'Firefox');
	 $pos2 = strpos($user_agent, 'MSIE');
	 $pos3 = strpos($user_agent, 'Chrome');
	 $browser_n = 'others';
	 if ($pos1 !== false) {
		$browser_n = 'firefox';
	 }
	 if($pos2 !== false) {
		$browser_n = 'IE';
	 }
	 if($pos3 !== false) {
		$browser_n = 'chrome';
	 }
	 
	  
	  echo '<div onmouseout="document.getElementById('."'pop_img_".$list_clip_result['id']."'".').style.display='."'none';".'" style="float:left; width:105px; height:98px; color:#603b20; text-transform:capitalize; font-size:11px;" >
	  
	  <div style="display:none; position:absolute; background-color:#fff; border:solid 4px #24b7f5; margin-left:-200px; margin-top:-340px; z-index:25000;" id="pop_img_'.$list_clip_result['id'].'">';
	  
	 if($browser_n == 'IE') {
		 echo '<img src="'.CLIPARTS_URL.'/'.$folder.'/1inch/'.$list_clip_result['image'].'" width="250" height="250">';
	 } else {
		 echo '<img src="'.CLIPARTS_URL.'/'.$folder.'/regular/SVG/'.$list_clip_result['svg'].'" width="300" height="300">';
	 }
	  
	  echo '</div>
	  
	 <a href="#" onclick=" '.$set_clipart.' generate_preview_front();generate_preview_back(); close_lay(); close_clip_listing(); return false;" style="text-decoration:none;">
	 <div style="overflow: hidden;width:90px; height:47px; background-image:url('.TPT_IMAGES_URL.'/clipart-bg-1.png); background-position:center; padding-top:4px;"><img src="'.CLIPARTS_URL.'/'.$folder.'/regular/'.$list_clip_result['image'].'" border="0" '.$thetip.'></div>
	  
	  <div class="height-30 width-90 padding-top-2 padding-bottom-2 font-size-10" style=" background-color:#C4BBB4; color:#3f3f3f; font-family:arial;">'.str_replace($search, $replace, $list_clip_result['name']).'</div></a>
	  
	  </div>';
	} } else { echo '<h3>No cliparts in this category.</h3>'; }
	
//	 if ($_SERVER['REMOTE_ADDR']=='109.160.0.218') var_dump(getBrowser());

}


?>
