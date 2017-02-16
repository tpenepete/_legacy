<?php

defined('TPT_INIT') or die('access denied');


$BID = $builder['id'];



if(!$tcont) {
    $preview_title = 'Preview - &quot;Front | Back&quot; Message Style';
} else {
    $preview_title = 'Preview - &quot;Continuous&quot; Message Style';
}

if($pgType == 8) {
    $preview_title = 'Preview - &quot;Ring&quot;';
}

$fdisable = '';

$f2disable = 'disabled="disabled"';
if($pgFrontRows > 1) {
$f2disable = '';
}

$bdisable = '';
if(!$tback || $tcont) {
$bdisable = 'disabled="disabled"';
}

$b2disable = 'disabled="disabled"';
if($pgBackRows > 1) {
$b2disable = '';
}

//var_dump($_GET["savetest"]);

if ( $_SERVER['REMOTE_ADDR']=='109.160.0.2188' || !empty($_GET["savetest"]) || $tpt_vars['user']['userid']==274 ) {

//	var_dump($tpt_vars['user']['userid']);
	
//	var_dump($tpt_vars['user']['isLogged'],$tpt_vars['user']['usertype']);
//	die();
	
//	if ($tpt_vars['user']['isLogged'] && $tpt_vars['user']['data']['usertype']==1) {
	if ($tpt_vars['user']['isLogged']) {
		ob_start();
//		echo '<pre>'; var_dump($tpt_vars['user']['data']['usertype']); echo '</pre>';
		?>
		<input type="button" value="" onclick="if (1||validate_short_builder()) save_band_design();" class="add_to_cart save_design">
		<div class="save_band_design_loader"></div>
		<?php
		$save_design_button = ob_get_clean();
	}
}

$tpt_vars['template_data']['head'][] = '<script type="text/javascript" src="'.$tpt_baseurl.'/js/jquery.unserializeForm.js"></script>';

if (!empty($_GET['load_design'])) {
	$dr = mysql_query('select * from `tpt_user_productdesigns` where `id`='.(int)$_GET['load_design']);
	$d = mysql_fetch_assoc($dr);
	
	if (empty($d['user_id']) || $d['user_id']!=$tpt_vars['user']['userid']) die('Invalid Design Id.');

	$ldata = unserialize($d['design_data']);
	$ldata_ = $ldata;
	
	$cliparray=array();
	
	foreach ($ldata_ as $k=>$v) {
		if (preg_match('#^qty_#',$k)) unset($ldata[$k]);
		if (preg_match('#(?<!enable_)clipart#',$k) && $v!='') $cliparray[$k] = (int)$v;
	}
	
	$clip_names = array();
	
	if (!empty($cliparray)) {
		$cldata = mysql_query('select * from `tpt_module_bandclipart` where `id` in ('.implode(',',$cliparray).')');
		while ($r=mysql_fetch_assoc($cldata)) $clip_names[$r['id']]=$r['name'];
	}
	
	$tpt_vars['template_data']['head'][] = '<script type="text/javascript">
		load_design_data='.json_encode($ldata).';
		'.(!empty($clip_names)?'load_clipart_names='.json_encode($clip_names):'').'
	</script>';
}

$tpt_vars['template']['content'] .= <<< EOT
<form id="short_builder_form" autocomplete="off" method="post" action="#" accept-charset="utf-8" class="">
    <div class="text-align-center">
        
        <div class="spacer1 clearBoth">
            <ul class="article-nav">
               <li class="first"><a href="$tpt_baseurl" title="Wristbands Home">Wristbands Home</a></li>
               <li title="$builder_breadcrumb">$builder_breadcrumb</li>
            </ul>
        </div>
        
        <div class="short_builder_descr bid_$BID">
            $builder_descr
        </div>
                
        <div id="pg_lights" class="no-glow">
            <div class="preview_title">
                $preview_title
            </div>
    
            
            <div class="display-inline-block">
                $preview
            </div>
            <br />
            <br />
        </div>
        <div class="short_builder_wrapper">
            $builder_html
        </div>
        <div class="text-align-right padding-top-20 padding-right-20">
			$save_design_button
			
			$builder_addtocart_button
		</div>
        <input type="hidden" name="band_type" id="tpt_pg_type" value="$pgType" />
        <input type="hidden" name="band_style" id="tpt_pg_style" value="$pgStyle" />
        <input type="hidden" name="band_font" id="tpt_pg_font" value="$pgFont" />
        
        <input type="hidden" name="band_color" id="tpt_pg_bandcolor" value="$pgBandColor" />
        <input type="hidden" name="message_color" id="tpt_pg_msgcolor" value="$pgMessageColor" />
        
        <input $fdisable type="hidden" id="tpt_pg_front_lclipart" name="tpt_pg_flclipart" value="$pgClipartFrontLeft" />
        <input $fdisable type="hidden" id="tpt_pg_front_rclipart" name="tpt_pg_frclipart" value="$pgClipartFrontRight" />
        <input $f2disable type="hidden" id="tpt_pg_front2_lclipart" name="tpt_pg_flclipart2" value="$pgClipartFrontLeft2" />
        <input $f2disable type="hidden" id="tpt_pg_front2_rclipart" name="tpt_pg_frclipart2" value="$pgClipartFrontRight2" />
        
        <input $bdisable type="hidden" id="tpt_pg_back_lclipart" name="tpt_pg_blclipart" value="$pgClipartBackLeft" />
        <input $bdisable type="hidden" id="tpt_pg_back_rclipart" name="tpt_pg_brclipart" value="$pgClipartBackRight" />
        <input $b2disable type="hidden" id="tpt_pg_back2_lclipart" name="tpt_pg_blclipart2" value="$pgClipartBackLeft2" />
        <input $b2disable type="hidden" id="tpt_pg_back2_rclipart" name="tpt_pg_brclipart2" value="$pgClipartBackRight2" />
        
        <input type="hidden" name="scproduct" value="$inhouse" />
        <input type="hidden" id="short_builder" name="short_builder" value="$builder_id" />
        
    <!--    <input type="hidden" name="custom_clipart" id="custom_clipart" value="" /> -->
    </div>
</form>
EOT;



?>
