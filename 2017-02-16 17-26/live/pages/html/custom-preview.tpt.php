<?php

defined('TPT_INIT') or die('access denied');

extract($pgconf);

$previewtime = time();

$default_type = DEFAULT_TYPE;
$default_style = DEFAULT_STYLE;

$pgType = (!empty($pgType)?$pgType:DEFAULT_TYPE);
$pgStyle = (!empty($pgStyle)?$pgStyle:DEFAULT_STYLE);
$pgFont = (!empty($pgFont)?$pgFont:DEFAULT_FONT);
$pgFrontRows = (!empty($pgFrontRows)?$pgFrontRows:1);
$pgBackRows = (!empty($pgBackRows)?$pgBackRows:1);
$pgTextCont = (!empty($pgTextCont)?intval($pgTextCont, 10):0);
$pgBandColor = (!empty($pgBandColor)?$pgBandColor:'-1:'.DEFAULT_BAND_COLOR);
$pgMessageColor = (!empty($pgMessageColor)?$pgMessageColor:'-1:'.DEFAULT_MESSAGE_COLOR);
$pgFrontMessage = !empty($pgFrontMessage)?urlencode($pgFrontMessage):'';
$pgFrontMessage2 = !empty($pgFrontMessage2)?urlencode($pgFrontMessage2):'';
$pgBackMessage = !empty($pgBackMessage)?urlencode($pgBackMessage):'';
$pgBackMessage2 = !empty($pgBackMessage2)?urlencode($pgBackMessage2):'';

$pgClipartFrontLeft = !empty($pgClipartFrontLeft)?intval($pgClipartFrontLeft, 10):0;
$pgClipartFrontRight = !empty($pgClipartFrontRight)?intval($pgClipartFrontRight, 10):0;
$pgClipartFrontLeft2 = !empty($pgClipartFrontLeft2)?intval($pgClipartFrontLeft2, 10):0;
$pgClipartFrontRight2 = !empty($pgClipartFrontRight2)?intval($pgClipartFrontRight2, 10):0;
$pgClipartBackLeft = !empty($pgClipartBackLeft)?intval($pgClipartBackLeft, 10):0;
$pgClipartBackRight = !empty($pgClipartBackRight)?intval($pgClipartBackRight, 10):0;
$pgClipartBackLeft2 = !empty($pgClipartBackLeft2)?intval($pgClipartBackLeft2, 10):0;
$pgClipartBackRight2 = !empty($pgClipartBackRight2)?intval($pgClipartBackRight2, 10):0;

$pgEnableJavascript = !empty($pgEnableJavascript)?intval($pgEnableJavascript, 10):0;


$pgDir = getModule($tpt_vars, "BandType")->moduleData['id'][$pgType]['preview_folder'];

$band_sizes = explode(',', getModule($tpt_vars, "BandType")->moduleData['id'][$pgType]['available_sizes_id']);
$initsize = reset($band_sizes);
$band_length = intval(getModule($tpt_vars, "BandSize")->moduleData['id'][$initsize]['milimeters'], 10);
$UEpgBandColor = urlencode($pgBandColor);
$UEpgMessageColor = urlencode($pgMessageColor);

$scale = 4;
$pgWidth = $band_length*$scale;

$pg_fx = $pgWidth;

$pgPaddingTop = 5;
$pgPaddingBottom = 5;


$pg_x = $pg_fx;




$band_width = floatval(getModule($tpt_vars, "BandType")->moduleData['id'][$pgType]['width_mm']);
$pgHeight = round($band_width*$scale);



$x_bg = '100%';
$y_bg = '100%';
$x2_bg = '-100px';
$y2_bg = '-100px';

$pgBandImg = TPT_IMAGES_DIR.DIRECTORY_SEPARATOR.'preview'.DIRECTORY_SEPARATOR.$pgDir.DIRECTORY_SEPARATOR.'plain.png';

if(is_file($pgBandImg)) {
$pgBandImgSize = getimagesize($pgBandImg);
//var_dump($pgBandImgSize);die();

$x_bg = intval($pgBandImgSize[0], 10).'px';
$y_bg = intval($pgBandImgSize[1], 10).'px';
}




if ($pgType == 1) /* Acnapyx : корекция за да е по-голям текста на 1/4" превюто */ 
{
    $pgPaddingTop = 0;
    $pgPaddingBottom = 0;
}


$pgHeightProcessed = $pgHeight - ($pgPaddingTop + $pgPaddingBottom);
$pgHeightProcessedHalf = round($pgHeightProcessed/2);
$pg_y = $pg_yf = $pg_yb = $pgHeightProcessed;
$pg_fy = $pgHeight;

if($pgType == 5) {
    $x_bg = '694px';
    $y_bg = '92px';
    $x2_bg = '-99px';
    $y2_bg = '-99px';
    
    $scale = 3;
    $pgWidth = $band_length*$scale;
    $pg_fx = 695;
    $pgWidth = 660;
    $pg_x = 695;
    
    $pgPaddingTop = 20;
    $pgPaddingBottom = 20;
    $pgHeight = round(98);
    $pgHeightProcessed = $pgHeight - ($pgPaddingTop + $pgPaddingBottom);
    $pgHeightProcessedHalf = round($pgHeightProcessed/2);
    $pg_y = $pg_yf = $pg_yb = $pgHeightProcessed;
    $pg_fy = $pgHeight;
    
}


$pgSeparatorImg = 'none';
$pgFattr = ' class="frontPreview padding-top-'.$pgPaddingTop.' padding-bottom-'.$pgPaddingBottom.' height-'.$pgHeightProcessed.'" style=""';
$pgBattr = ' class="backPreview display-none float-right '.$bdisplay.' padding-top-'.$pgPaddingTop.' padding-bottom-'.$pgPaddingBottom.' height-'.$pgHeightProcessed.'" style="width: 50%;"';
if(!$pgTextCont) {
    $pgFattr = ' class="frontPreview float-left padding-top-'.$pgPaddingTop.' padding-bottom-'.$pgPaddingBottom.' height-'.$pgHeightProcessed.'" style="width: 50%;"';
    $pgBattr = ' class="backPreview float-right '.$bdisplay.' padding-top-'.$pgPaddingTop.' padding-bottom-'.$pgPaddingBottom.' height-'.$pgHeightProcessed.'" style="width: 50%;"';
    $pg_x = round($pg_x/2);
    $pgSeparatorImg = 'url('.TPT_IMAGES_URL.'/preview/separator-1x1.png)';
}

//var_dump($pgFrontRows);
//var_dump($pgBackRows);
//die();

$pgHeightFront = 'height-'.$pgHeightProcessed;
$pgHeightFront2 = 'height-'.$pgHeightProcessedHalf;
$pgClassFront2 = 'display-none';
if($pgFrontRows == 2) {
    $pgHeightFront = $pgHeightFront2;
    $pgClassFront2 = 'display-block';
}

$pgHeightBack = 'height-'.$pgHeightProcessed;
$pgHeightBack2 = 'height-'.$pgHeightProcessedHalf;
$pgClassBack2 = 'display-none';
if($pgBackRows == 2) {
    $pgHeightBack = $pgHeightBack2;
    $pgClassBack2 = 'display-block';
}

//$pgx4 = round($pg_x*2.2);
//$pgy4 = round($pg_y*2.2);
//$pgx4 = round($pg_x*1.6);
//$pgy4 = round($pg_y*1.6);



//$pgBandBG = getModule($tpt_vars, "BandColor")->getBandBGStyle($tpt_vars, $pgBandColor, $pg_fx, $pg_fy);
$pgBandBG = getModule($tpt_vars, 'BandColor')->getBandBGStyle($tpt_vars, $pgBandColor, $pg_fx, $pg_fy);
//var_dump($pgBandBG);die();

$lights_off_color = LIGHTS_OFF_COLOR;
$default_foreground_color = DEFAULT_FOREGROUND_COLOR;
$green_glow_color = GREEN_GLOW_COLOR;
$blue_glow_color = BLUE_GLOW_COLOR;

$preview = '';

//<div class="amz_green font-size-16 padding-top-10" style="text-align:center;font-family: TODAYSHOP-BOLDITALIC,arial;">Front Preview</div>
$preview .= <<< EOT
<div class="width-$pg_fx position-relative overflow-hidden clearBoth" id="pg_container">
    <div id="pg_separator" class="display-none top-0 right-0 bottom-0 left-0 position-absolute background-position-CC" style="z-index: 3; background-image: $pgSeparatorImg; background-repeat: no-repeat;"></div>
    <div id="pg_band_outline" class="top-0 right-0 bottom-0 left-0 position-absolute" style="z-index: 2; background-image: url($tpt_imagesurl/preview/$pgDir/plain.png); background-repeat: no-repeat;"></div>
    <div id="pg_fg_right" class="top-0 bottom-0 position-absolute width-100" style="left: $x_bg; z-index: 2; background-color: #$default_foreground_color;"></div>
    <div id="pg_fg_bottom" class="right-0 left-0 position-absolute height-100" style="top: $y_bg; z-index: 2; background-color: #$default_foreground_color;"></div>
    <div id="pg_fg_left" class="top-0 bottom-0 position-absolute width-100" style="left: $x2_bg; z-index: 2; background-color: #$default_foreground_color;"></div>
    <div id="pg_fg_top" class="right-0 left-0 position-absolute height-100" style="top: $y2_bg; z-index: 2; background-color: #$default_foreground_color;"></div>
    
    <div class="position-relative background-repeat-repeat clearFix" style="z-index: 1;background-image: url($tpt_imagesurl/clearband.png);">
        <div class="position-relative clearFix" style="z-index: 1;$pgBandBG" id="pg_bg">
        
            <div$pgFattr id="tpt_pg_front_container">
                <div id="tpt_pg_front_parent" class="$pgHeightFront">
                    <img id="tpt_pg_front" style="max-width: 100%; max-height: 100%;" src="$tpt_baseurl/generate-preview?pg_x=$pg_x&pg_y=$pg_y&text=$pgFrontMessage&font=$pgFont&bandType=$pgType&bandStyle=$pgStyle&textColor=$UEpgMessageColor&lclipart=$pgClipartFrontLeft&rclipart=$pgClipartFrontRight&type=plain&timestamp=$previewtime" />
                </div>
                <div id="tpt_pg_front2_parent" class="$pgClassFront2 $pgHeightFront2">
                    <img id="tpt_pg_front2" style="max-width: 100%; max-height: 100%;" src="$tpt_baseurl/generate-preview?pg_x=$pg_x&pg_y=$pg_y&text=$pgFrontMessage2&font=$pgFont&bandType=$pgType&bandStyle=$pgStyle&textColor=$UEpgMessageColor&lclipart=$pgClipartFrontLeft2&rclipart=$pgClipartFrontRight2&type=plain&timestamp=$previewtime" />
                </div>
            </div>
            <div$pgBattr id="tpt_pg_back_container">
                <div id="tpt_pg_back_parent" class="$pgHeightBack">
                    <img id="tpt_pg_back" style="max-width: 100%; max-height: 100%;" src="$tpt_baseurl/generate-preview?pg_x=$pg_x&pg_y=$pg_y&text=$pgBackMessage&font=$pgFont&bandType=$pgType&bandStyle=$pgStyle&textColor=$UEpgMessageColor&lclipart=$pgClipartBackLeft&rclipart=$pgClipartBackRight&type=plain&timestamp=$previewtime" />
                </div>
                <div id="tpt_pg_back2_parent" class="$pgClassBack2 $pgHeightBack2">
                    <img id="tpt_pg_back2" style="max-width: 100%; max-height: 100%;" src="$tpt_baseurl/generate-preview?pg_x=$pg_x&pg_y=$pg_y&text=$pgBackMessage2&font=$pgFont&bandType=$pgType&bandStyle=$pgStyle&textColor=$UEpgMessageColor&lclipart=$pgClipartBackLeft2&rclipart=$pgClipartBackRight2&type=plain&timestamp=$previewtime" />
                </div>
            </div>
        
        </div>
    </div>
</div>
EOT;


if($pgEnableJavascript) {


$script = <<< EOT
var preview_bgs = ['pg_bg'];
var preview_ids = [];

//var pgx4 = $pgx4;
//var pgy4 = $pgy4;
var pg_x = $pg_x;
var pg_y = $pg_y;
var pg_yp = $pgHeightProcessed;
var pg_yf = $pg_yf;
var pg_yb = $pg_yb;
var pg_fx = $pg_fx;
var pg_fy = $pg_fy;
var pg_ffy = $pg_y;
var pg_tpad = $pgPaddingTop;
var pg_bpad = $pgPaddingBottom;

var pg_default_fg_color = '$default_foreground_color';
var lights_off_color = '$lights_off_color';
var green_glow_color = '$green_glow_color';
var blue_glow_color = '$blue_glow_color';
var pg_defaulttype = $default_type;
var pg_defaultstyle = $default_style;

var front_rows = $pgFrontRows;
var back_rows = $pgBackRows;


var all_preview_ids = ['tpt_pg_front', 'tpt_pg_front2', 'tpt_pg_back', 'tpt_pg_back2'];

if(front_rows == 1) {
    if(back_rows == 1) {
        preview_ids = ['tpt_pg_front', 'tpt_pg_back'];
    } else {
        preview_ids = ['tpt_pg_front', 'tpt_pg_back', 'tpt_pg_back2'];
    }
} else {
    if(back_rows == 1) {
        preview_ids = ['tpt_pg_front', 'tpt_pg_front2', 'tpt_pg_back'];
    } else {
        preview_ids = ['tpt_pg_front', 'tpt_pg_front2', 'tpt_pg_back', 'tpt_pg_back2'];
    }

}
EOT;

$tpt_vars['template_data']['head'][] = <<< EOT
<script type="text/javascript" src="$tpt_baseurl/js/preview-generator.js"></script>
<script type="text/javascript">
$script
</script>
EOT;

} else {
    $dec = 'var ';
$tpt_vars['environment']['ajax_result']['exec_script'] = <<< EOT
var preview_bgs = ['pg_bg'];
var preview_ids = [];

//$dec pgx4 = $pgx4;
//$dec pgy4 = $pgy4;
$dec pg_x = $pg_x;
$dec pg_y = $pg_y;
$dec pg_yp = $pgHeightProcessed;
$dec pg_yf = $pg_yf;
$dec pg_yb = $pg_yb;
$dec pg_fx = $pg_fx;
$dec pg_fy = $pg_fy;
$dec pg_ffy = $pg_y;
$dec pg_tpad = $pgPaddingTop;
$dec pg_bpad = $pgPaddingBottom;

$dec pg_default_fg_color = '$default_foreground_color';
$dec lights_off_color = '$lights_off_color';
$dec green_glow_color = '$green_glow_color';
$dec blue_glow_color = '$blue_glow_color';
$dec pg_defaulttype = $default_type;
$dec pg_defaultstyle = $default_style;

$dec front_rows = $pgFrontRows;
$dec back_rows = $pgBackRows;

$dec all_preview_ids = ['tpt_pg_front', 'tpt_pg_front2', 'tpt_pg_back', 'tpt_pg_back2'];

if(front_rows == 1) {
    if(back_rows == 1) {
        preview_ids = ['tpt_pg_front', 'tpt_pg_back'];
    } else {
        preview_ids = ['tpt_pg_front', 'tpt_pg_back', 'tpt_pg_back2'];
    }
} else {
    if(back_rows == 1) {
        preview_ids = ['tpt_pg_front', 'tpt_pg_front2', 'tpt_pg_back'];
    } else {
        preview_ids = ['tpt_pg_front', 'tpt_pg_front2', 'tpt_pg_back', 'tpt_pg_back2'];
    }

}

init_client_val();
tb_init('a.thickbox, area.thickbox, input.thickbox');
EOT;
}
?>
