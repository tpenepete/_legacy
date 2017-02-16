<?php

function hex2rgb($hex) {
   $hex = str_replace("#", "", $hex);
   if(strlen($hex) == 3) {
      $r = hexdec(substr($hex,0,1).substr($hex,0,1));
      $g = hexdec(substr($hex,1,1).substr($hex,1,1));
      $b = hexdec(substr($hex,2,1).substr($hex,2,1));
   } else {
      $r = hexdec(substr($hex,0,2));
      $g = hexdec(substr($hex,2,2));
      $b = hexdec(substr($hex,4,2));
   }
   $rgb = array($r, $g, $b);
   //return implode(",", $rgb); // returns the rgb values separated by commas
   return $rgb; // returns an array with the rgb values
}

if (isset($_POST['action']) && ($_POST['action']=='get_all_pms')) {
	echo '<div id="color_palet">';
	$rrr = mysql_query('Select * from colors_data ORDER BY name ASC');
	while($r = mysql_fetch_array($rrr)) {
		$rgb = hex2rgb($r['hex']);
		if ( ($rgb[0]+$rgb[1]+$rgb[2])/3 > 190 ) {
			$color_value='#000';
		} else {
			$color_value='#fff';
		}
		
		echo '<a class="pantone_sample pantones '.(!empty($r['popular'])?'pop':'').' id_'.$r['id'].'" id="pantone_color_id_'.$r['id'].'" onclick="custom_color_select(\'pantone_color_id_'.$r['id'].'\','.$r['id'].');" href="javascript:void(0);" style="color:'.$color_value.';background-color:#'.$r['hex'].'">'.(preg_match('#[a-z]{2,}#i',$r['nickname'])?$r['nickname']:$r['name']).'</a>';
	}
	echo '</div>';
	
	echo '<div class="stndr display-none">';
//	echo getModule($tpt_vars, 'BandColor')->Standard_Color_Samples($tpt_vars);	
	echo getModule($tpt_vars, 'BandColor')->Standard_Color_Samples($tpt_vars);	
	echo '</div>';
	
}

if (isset($_POST['action']) && ($_POST['action']=='get_all_fonts')) {
	?><div id='font_palet'><?php
	echo getModule($tpt_vars, 'BandFont')->BandFont_Panel($tpt_vars);
	?></div><?php
}

if (isset($_POST['action']) && ($_POST['action']=='get_artwork_panel')) {
	?><div class="artwork_jwr">
	<div class="heading">Categories:</div>
	<?php
	echo getModule($tpt_vars, 'BandClipartCategory')->BandClipartCategory_Panel_SB($tpt_vars);
	?></div>
	<div class="cat_items_wr">
	</div>
	<script type="text/javascript">
		function clipartSecondaryOverOut(el,c) {
			if (c) {
				cmovr = 1;
			} else {
			//	alert('mmmmooo0');
				cmovr = 0;
				cmovrt = setTimeout(function(){
			//		alert('mmmmooottt');
					if (cmovr==0) {
				//		alert('mmmmooor');
						$(".clipartSecondary > a.hoverCB.dbi").removeClass("dbi");
					}
				},1000);
			}	
			
		} 
		
	//	$('.clipartSecondary').attr('onmouseout','clipartSecondaryOverOut(this,0);');
	//	$('.clipartSecondary').attr('onmouseover','clipartSecondaryOverOut(this,1);');
	
		$('.clipartSecondary').hover(
			function(){clipartSecondaryOverOut(this,1);},
			function(){clipartSecondaryOverOut(this,0);}
		);
	</script>
	<?php
}

if (isset($_POST['action']) && ($_POST['action']=='list_artwork_items')) {
	
	?><div class="artwork_items_wr"><?php
	echo getModule($tpt_vars, 'BandClipart')->BandClipart_Panel_SB($tpt_vars, (int)$_POST['cat']);
	?></div><?php
}

if (isset($_POST['action']) && ($_POST['action']=='save_band_design')) {
	
//	var_dump($_POST);
	
	parse_str($_POST['form-data'],$form_data);

//	var_dump($form_data);
	$form_data_ = $form_data;
	$i = 0;
	foreach ($form_data_ as $k=>$v) {
		if ($i==1 && preg_match('#^qty_#',$k)) {
			$form_data[$k]='';
		}
		if ($i==0 && preg_match('#^qty_#',$k)) {
			$form_data[$k]=500;
			$i=1;
		}
	}
	
//	var_dump($form_data);
	$form_data['cci']=@$_POST['cci'];
	
	$ir = mysql_query(
		'INSERT INTO `tpt_user_productdesigns` (user_id,timestamp,design_data,builder_id)
		VALUES ('.(int)$tpt_vars['user']['userid'].','
				 .time().',"'
				 .mysql_real_escape_string(serialize($form_data)).'","'
				 .mysql_real_escape_string((int)$_POST['builder_id']).'")'
	);
	
	if ($ir) {
		?><script type="text/javascript">
			alert('Your Design Was Saved.');
		</script><?php
	}	
}

if (isset($_POST['action']) && ($_POST['action']=='del_band_design')) {
	
	$uid = $tpt_vars['db']['handler']->getData($tpt_vars, 'tpt_user_productdesigns', 'user_id', 'id='.(int)$_POST['design_id']);
	$uid = $uid[0]['user_id'];
	
	if ($uid!=$tpt_vars['user']['userid']) die('Invalid id');
	
	$ir = mysql_query(
		'delete from `tpt_user_productdesigns` where id='.(int)$_POST['design_id']
	);
	
	if ($ir) {
		?><script type="text/javascript">
		//	alert('Your Design Was Deleted.');
			location.reload();
		</script><?php
	}	
}

if (0 && $_GET['action']=='uc') {
?><pre><?php
	$rrr = mysql_query(
'SELECT *
FROM `temp_band_color`
WHERE `color_type` LIKE "segmented"
and `color_image` not like "%new_segmented%"
limit 0,1
');

//	while($r = mysql_fetch_assoc($rrr)) {
//		var_dump($r);
//	}
		
	$r = mysql_fetch_assoc($rrr);
	
		
	$hvdir = TPT_RESOURCE_DIR.'/preview-bands/new_segmented';

	if ($handle = opendir($hvdir)) {
	    $fls=array();
	    while (false !== ($entry = readdir($handle))) {
			
			$k = strtolower(preg_replace(
					array('#(_hov)?\.(jpg|png|gif)$#i','#[^a-z0-9]#i')
				,'',$entry));
				
			$v = $entry;
			
			$fls[$k] = $v;
		}
		
		$le = array();
		foreach (array_keys($fls) as $k) {

			$kkk = strtolower(preg_replace(
					array('#(_hov)?\.(jpg|png|gif)$#i','#[^a-z0-9]#i')
				,'',$r['color_image']));			
			
			
			$le[$k] = levenshtein($kkk,$k);
			
		}
		
		asort($le);
		
		$kkkk = array_keys($le);
		
		var_dump($r);
		var_dump($le[$kkkk[0]]);
		var_dump($fls[$kkkk[0]]);
		
	    closedir($handle);
	    
	    if (!empty($_GET['o']) || $le[$kkkk[0]]<2) {

	$rrr = mysql_query(
'update `temp_band_color`
set `color_image`="'.mysql_real_escape_string('new_segmented/'.$fls[$kkkk[0]]).'"
where `color_id` = "'.$r['color_id'].'"
');
		var_dump('!!!!UPDATED '.$r['color_name']);
		var_dump('!!!!with the image: '.'new_segmented/'.$fls[$kkkk[0]]);
		
		} else {
			
			var_dump('oooooppappapa');
		}
	}
?></pre><?php
	
}

