<?php

defined('TPT_INIT') or die('access denied');

/*
if($tpt_vars['environment']['isMobileDevice']['ipod'] || 
   $tpt_vars['environment']['isMobileDevice']['ipad'] || 
   $tpt_vars['environment']['isMobileDevice']['iphone'] || 
   $tpt_vars['environment']['isMobileDevice']['webos']) {
// is iStuff
	$tpt_vars['template_data']['template_type'] = 'plain-ios-preview-frame';

} else {
	$tpt_vars['template_data']['template_type'] = 'plain';
}




$stylesheet = <<< EOT
body .outer-wrapper {
    padding-left: 100px;
    width: 1000px;
}

.main-content .content {
    width: 788px;
}


body .main-content .content .con-top {
    background-image: url($tpt_imagesurl/content-top-788.png);
    width: 788px;
}

body .main-content .content .con-middle {
    background-image: url($tpt_imagesurl/content-middle-788.png);
}


body .main-content .content .con-bottom {
    background-image: url($tpt_imagesurl/content-bottom-788.png);
    width: 788px;
}
EOT;



//$url_id = $tpt_vars['environment']['page_rule']['id'];

//$builder = reset($tpt_vars['db']['handler']->getData($tpt_vars, 'tpt_module_builder', '*', '`url_id`='.$url_id));
//$builder_title = $builder['label'];
//$tpt_vars['template']['title'] = $builder_title;
*/



$previewtime = time();

$default_font = DEFAULT_FONT_NAME;
$default_type = DEFAULT_TYPE;
$default_style = DEFAULT_STYLE;
$default_band_color = DEFAULT_BAND_COLOR;
$default_foreground_color = DEFAULT_FOREGROUND_COLOR;
$pgBandBG = "background-color: #$default_band_color;";
$pgType = !empty($_GET['type'])?intval($_GET['type'], 10):DEFAULT_TYPE;
$pgStyle = !empty($builder['style'])?$builder['style']:DEFAULT_STYLE;

//var_dump($pgType);//die();

$lnum = getModule($tpt_vars, "BandType")->moduleData['id'][$pgType]['text_lines_num'];
$tback = getModule($tpt_vars, "BandType")->moduleData['id'][$pgType]['text_back_msg'];
$tcont = getModule($tpt_vars, "BandType")->moduleData['id'][$pgType]['text_continuous_msg'];

$pgDir = getModule($tpt_vars, "BandType")->moduleData['id'][$pgType]['preview_folder'];

$band_sizes = explode(',', getModule($tpt_vars, "BandType")->moduleData['id'][$pgType]['available_sizes_id']);
$initsize = reset($band_sizes);
$band_length = intval(getModule($tpt_vars, "BandSize")->moduleData['id'][$initsize]['milimeters'], 10);
$pgWidth = $band_length*$scale;

$scale = 4;
$pg_fx = $pgWidth;
$pgPaddingTop = 5;
$pgPaddingBottom = 5;
if($pgType == 5) {
    $pgPaddingTop = 10;
    $pgPaddingBottom = 10;
    $pg_fx = 550;
    $scale = 3;
}
if ($pgType == 1) /* Acnapyx : корекция за да е по-голям текста на 1/4" превюто */ 
{
    $pgPaddingTop = 0;
    $pgPaddingBottom = 0;
}

$pg_x = $pg_fx;


$band_width = floatval(getModule($tpt_vars, "BandType")->moduleData['id'][$pgType]['width_mm']);
$pgHeight = $band_width*$scale;
$pgHeightProcessed = $pgHeight - ($pgPaddingTop + $pgPaddingBottom);
$pgHeightProcessedHalf = round($pgHeightProcessed/2);
$pg_y = $pg_yf = $pg_yb = $pgHeightProcessed;
$pg_fy = $pgHeight;

$bdisplay = 'display-block';
if($tcont || !$tback) {
    $bdisplay = 'display-none';
}

if($lnum > 1) {
    $lndisplay = 'display-block';
} else {
    $lndisplay = 'display-none';
}



include(TPT_TEMPLATES_DIR.DIRECTORY_SEPARATOR.'builder-preview.tpt.php');



$tpt_vars['template']['content'] .= <<< EOT

<div class="text-align-center padding-top-10">
	<div class="">
		<!--div class="float-right width-230 text-align-left padding-left-20 padding-top-30 font-size-14" style="font-family: Arial, Helvetica, sans-serif;">
			<div class="amz_green">
				Step1 - Band Type - <span class="amz_red">X</span>
			</div>
			<div class="amz_green">
				Step2 - Msg Style - <span class="amz_red">X</span>
			</div>
			<div class="amz_green">
				Step3 - Color & Qty - <span class="amz_red">X</span>
			</div>
			<div class="amz_green">
				Step4 - Create Message - <span class="amz_red">X</span>
			</div>
		</div-->
		<div class="overflow-hidden">
			<div class="amz_red todayshop-bold font-size-24 text-align-left padding-left-50">See Your Amazing Design</div>
			<div class="padding-top-10">
				$preview
			</div>
		</div>
	</div>
	
</div>
EOT;


?>
