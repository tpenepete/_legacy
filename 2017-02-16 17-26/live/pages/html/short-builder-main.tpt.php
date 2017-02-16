<?php

defined('TPT_INIT') or die('access denied');

$types_module = getModule($tpt_vars, "BandType");
$data_module = getModule($tpt_vars, "BandData");


$BID = $builder['id'];

// include custom file
$cfdir = dirname(__FILE__).DS.'short-builder-custom-files';
define('CUSTOM_FILES_DIR',$cfdir);
$builder_descr = '';
if (is_file($cfile=CUSTOM_FILES_DIR.DS.$BID.'.php')) include $cfile;

if(empty($types_module->moduleData['id'][$pgType]['writable'])) {
    if(!$tcont) {
        $preview_title = 'Preview - &quot;Front | Back&quot; Message Style';
    } else {
        $preview_title = 'Preview - &quot;Continuous&quot; Message Style';
    }
} else if(!empty($types_module->moduleData['id'][$pgType]['full_wrap_strip'])) {
    $preview_title = 'Preview - &quot;Writable - Full Wrap Strip&quot; Band';
} else if(!empty($types_module->moduleData['id'][$pgType]['blank'])) {
    $preview_title = 'Preview - &quot;Writable - Basic&quot; Band';
} else {
    $preview_title = 'Preview - &quot;Writable - Basic + Back Message&quot; Band';
}

if(!empty($builder['cl'])) {
    $preview_title = 'Preview - &quot;ID Bracelet&quot;';
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
$save_design_button = '';
if ( (isset($_SERVER['REMOTE_ADDR']) && ($_SERVER['REMOTE_ADDR']=='109.160.0.2188')) || !empty($_GET["savetest"]) || $tpt_vars['user']['userid']==274 ) {

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

$tpt_vars['template_data']['head'][] = '<script defer="defer" type="text/javascript" src="'.$tpt_jsurl.'/jquery.unserializeForm.js"></script>';

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

//$gallery_url = '/builder_gallery.php?bid='.$BID.'&amp;KeepThis=true&amp;TB_iframe=true&amp;height=600&amp;width=778';

$gdescr = preg_replace('#<div class="spacer1">#msU','\\0',$builder_descr,1);

$showbid = (isDev('showbuilderid') && !empty($_GET['showid'])) ? '<div>Builder id is: <b>'.$BID.'</b></div>' : '';

//////////////// micro gallery test ////////////////....
$micro_gallery_html='';
//if ($_SERVER["REMOTE_ADDR"] == '83.222.171.1___' || !empty($_SESSION['ADMIN_TESTER'])) {


//<div class="short_builder_descr bid_$BID" itemprop="description">
//$showbid
//<!-- $builder_descr -->
//$gdescr
//</div>
$data = (isset($data_module->typeStyle[$pgType][$pgStyle])?$data_module->typeStyle[$pgType][$pgStyle]:array('new_preview'=>0));
$preview = '';
//if(empty($tpt_vars['environment']['mobile_template'])) {
	if(!empty($data['new_preview'])) {
		//tpt_dump($pgconf);
		$preview = tpt_PreviewGenerator::previewHTML2($tpt_vars, $pgconf);
	} else {
		$preview = tpt_PreviewGenerator::previewHTML($tpt_vars, $pgconf);
	}
//} else if ($_SERVER["REMOTE_ADDR"] == '89.253.191.44') { /* AIK Edit Start */
//    $preview = tpt_PreviewGenerator::previewHTML($tpt_vars, $pgconf);
//} /* AIK Edit End */
$preview = <<< EOT
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
EOT;

$breadcrumb_url = self_page_URL();//$tpt_rooturl.$_SERVER['REQUEST_URI'];

$tpt_vars['template']['content'] .= <<< EOT
<form id="short_builder_form" autocomplete="off" method="post" action="#" accept-charset="utf-8" class="">
    <div class="text-align-center" itemscope itemtype="http://schema.org/Product">

        <div class="spacer1 clearBoth">
		
			<ul itemscope="" itemtype="http://schema.org/BreadcrumbList" class="article-nav">
				<li itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem" class="first">
					<a itemprop="item" href="$tpt_rooturl">
						 <span itemprop="name" >Home</span>
					 </a>
					 <meta itemprop="position" content="1">
				</li>
				<li itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem" >
						<span itemprop="name" >$builder_breadcrumb</span>
					<meta itemprop="position" content="2">
				</li>           
			</ul> 
		
        </div>

        <div class="short_builder_descr bid_$BID" >$showbid$gdescr</div>

        $preview
        
        $micro_gallery_html
        
        <div class="short_builder_wrapper">
            $builder_html
        </div>
        <div class="text-align-right padding-top-20 padding-right-20">
            $save_design_button

            $builder_addtocart_button
	</div>
        <input type="hidden" name="band_type" id="tpt_pg_type" value="$pgType" />
        <input type="hidden" name="writable_class" id="tpt_pg_class" value="$pgWritableClass" />
        <input type="hidden" name="band_style" id="tpt_pg_style" value="$pgStyle" />
        <input type="hidden" name="band_font" id="tpt_pg_font" value="$pgFont" />

        <input type="hidden" name="band_color" id="tpt_pg_bandcolor" value="$pgBandColor" />
        <input type="hidden" name="message_color" id="tpt_pg_msgcolor" value="$pgMessageColor" />

        <input $fdisable type="hidden" id="tpt_pg_front_lclipart" name="tpt_pg_flclipart" value="$pgClipartFrontLeft" />
        <input id="tpt_pg_front_lclipart_c" name="flclipart_c" class="cust_clip_upload" type="hidden" value="$pgClipartFrontLeft_c" />
        <!--input id="flclipart_c" name="flclipart_c" class="cust_clip_upload" type="hidden" value="" /-->


        <input $fdisable type="hidden" id="tpt_pg_front_rclipart" name="tpt_pg_frclipart" value="$pgClipartFrontRight" />
        <input id="tpt_pg_front_rclipart_c" name="frclipart_c" class="cust_clip_upload" type="hidden" value="$pgClipartFrontRight_c" />
        <!--input id="frclipart_c" name="frclipart_c" class="cust_clip_upload" type="hidden" value="" /-->

        <input $f2disable type="hidden" id="tpt_pg_front2_lclipart" name="tpt_pg_flclipart2" value="$pgClipartFrontLeft2" />
        <input id="tpt_pg_front2_lclipart_c" name="fl2clipart_c" class="cust_clip_upload" type="hidden" value="$pgClipartFrontLeft2_c" />
        <!--input id="fl2clipart_c" name="fl2clipart_c" class="cust_clip_upload" type="hidden" value="" /-->

        <input $f2disable type="hidden" id="tpt_pg_front2_rclipart" name="tpt_pg_frclipart2" value="$pgClipartFrontRight2" />
        <input id="tpt_pg_front2_rclipart_c" name="fr2clipart_c" class="cust_clip_upload" type="hidden" value="$pgClipartFrontRight2_c" />
        <!--input id="fr2clipart_c" name="fr2clipart_c" class="cust_clip_upload" type="hidden" value="" /-->

        <input $bdisable type="hidden" id="tpt_pg_back_lclipart" name="tpt_pg_blclipart" value="$pgClipartBackLeft" />
        <input id="tpt_pg_back_lclipart_c" name="blclipart_c" class="cust_clip_upload" type="hidden" value="$pgClipartBackLeft_c" />
        <!--input id="blclipart_c" name="blclipart_c" class="cust_clip_upload" type="hidden" value="" /-->

        <input $bdisable type="hidden" id="tpt_pg_back_rclipart" name="tpt_pg_brclipart" value="$pgClipartBackRight" />
        <input id="tpt_pg_back_rclipart_c" name="brclipart_c" class="cust_clip_upload" type="hidden" value="$pgClipartBackRight_c" />
        <!--input id="brclipart_c" name="brclipart_c" class="cust_clip_upload" type="hidden" value="" /-->

        <input $b2disable type="hidden" id="tpt_pg_back2_lclipart" name="tpt_pg_blclipart2" value="$pgClipartBackLeft2" />
        <input id="tpt_pg_back2_lclipart_c" name="bl2clipart_c" class="cust_clip_upload" type="hidden" value="$pgClipartBackLeft2_c" />
        <!--input id="bl2clipart_c" name="bl2clipart_c" class="cust_clip_upload" type="hidden" value="" /-->

        <input $b2disable type="hidden" id="tpt_pg_back2_rclipart" name="tpt_pg_brclipart2" value="$pgClipartBackRight2" />
        <input id="tpt_pg_back2_rclipart_c" name="br2clipart_c" class="cust_clip_upload" type="hidden" value="$pgClipartBackRight2_c" />
        <!--input id="br2clipart_c" name="br2clipart_c" class="cust_clip_upload" type="hidden" value="" /-->













        <input type="hidden" name="scproduct" value="$inhouse" />
        <input type="hidden" id="short_builder" name="short_builder" value="$builder_id" />

        <!-- <input type="hidden" name="custom_clipart" id="custom_clipart" value="" /> -->

        <input type="hidden" name="r_front_message" id="rFrontMessage" value="$rFrontMessage" />
        <input type="hidden" name="r_back_message" id="rBackMessage" value="$rBackMessage" />

    </div>
</form>
EOT;



