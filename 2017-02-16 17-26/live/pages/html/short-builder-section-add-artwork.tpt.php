<?php

defined('TPT_INIT') or die('access denied');

//var_dump('asdasdasasd');die();

//$ajax_call = tpt_ajax::getCall('color.change_color_type');

//$solid_radio = tpt_html::createRadiobutton($tpt_vars, 'color_type'/*name*/, '1'/*control value*/, '1'/*checked value*/, ' onclick="'.$ajax_call.'" id="solid_colors"'/*html attribs*/, ''/*oncheck*/);
//$swirl_radio = tpt_html::createRadiobutton($tpt_vars, 'color_type'/*name*/, '2'/*control value*/, '1'/*checked value*/, ' onclick="'.$ajax_call.'" id="swirl_colors"'/*html attribs*/, ''/*oncheck*/);
//$segmented_radio = tpt_html::createRadiobutton($tpt_vars, 'color_type'/*name*/, '3'/*control value*/, '1'/*checked value*/, ' onclick="'.$ajax_call.'" id="segmented_colors"'/*html attribs*/, ''/*oncheck*/);

//$fl_select_dummy = getModule($tpt_vars, "BandClipart")->Clipart_Select_Dummy($tpt_vars, 0, 'lclipart');
//$fr_select_dummy = getModule($tpt_vars, "BandClipart")->Clipart_Select_Dummy($tpt_vars, 0, 'rclipart');
//$fl2_select_dummy = getModule($tpt_vars, "BandClipart")->Clipart_Select_Dummy($tpt_vars, 0, 'lclipart2');
//$fr2_select_dummy = getModule($tpt_vars, "BandClipart")->Clipart_Select_Dummy($tpt_vars, 0, 'rclipart2');
//$bl_select_dummy = getModule($tpt_vars, "BandClipart")->Clipart_Select_Dummy($tpt_vars, 0, 'blclipart');
//$br_select_dummy = getModule($tpt_vars, "BandClipart")->Clipart_Select_Dummy($tpt_vars, 0, 'brclipart');
//$bl2_select_dummy = getModule($tpt_vars, "BandClipart")->Clipart_Select_Dummy($tpt_vars, 0, 'blclipart2');
//$br2_select_dummy = getModule($tpt_vars, "BandClipart")->Clipart_Select_Dummy($tpt_vars, 0, 'brclipart2');

// master template
/*
$section_add_artwork = <<< EOT
<div id="clipart_container">
    <div id="fclip">
        $fl_select_dummy
        $fr_select_dummy
    </div>
    <div id="fclip2">
        $fl2_select_dummy
        $fr2_select_dummy
    </div>
    <div id="bclip">
        $bl_select_dummy
        $br_select_dummy
    </div>
    <div id="bclip2">
        $bl2_select_dummy
        $br2_select_dummy
    </div>
</div>
EOT;
*/

/* old code
$section_add_artwork = <<< EOT
<div id="clipart_container">
    <div id="fclip">
    </div>
    <div id="fclip2">
    </div>
    <div id="bclip">
    </div>
    <div id="bclip2">
    </div>
</div>
<div class="ccc_wr">
<a class="thickbox view-all-artwork" href="#TB_inline?width=900&amp;height=500&amp;inlineId=_">All Artwork</a>
</div>


EOT;
*/

// new code B-|
/*
$lndisplay = ' display-block';
$lndisable = '';
if($lnum < 2) {
    $lndisplay = ' display-none';
}
*/


$input = $_GET;


$types_module = getModule($tpt_vars, 'BandType');
$clipart_module = getModule($tpt_vars, 'BandClipart');

$bdisplay = 'display-none';
if($tback && !$tcont) {
$bdisplay = 'display-block';
}


$custom_clipart_upload = '<div class="clearBoth" style="margin-top: 10px;padding-left: 3px;">
                    <div style="width: 100%;border-bottom: 1px solid #DE3A3A;"></div>
                    <a class="btn_cca  " id="upload" onclick="$(\'#uploaded\').click();"></a>
                    <span id="status" style="display: block;"></span>
                    <span id="files" style="display: block;"></span>
                    <div style="clear: both;color: #DE3A3A;width: 111px;margin: 0 auto;">(20 MB max size)</div>
            </div>';

$custom_clipart_convert_check = '';
            
if (isDev('convertclipart')) {
    $custom_clipart_convert_check = '<div class="display-none" id="convert_clipart_check_div"><input type="checkbox" name="convert_clipart_check" id="convert_clipart_check"/> Check this box if the image you have uploaded is not a vector format. We will convert it for you ($9.00)</div>';
} else {
    $custom_clipart_convert_check = '';
}


//var_dump($pgType);die();
$fclipdisplay = '';
$bclipdisplay = '';
//if(!empty($types_module->moduleData['id'][$pgType]['writable'])) {
//    $fclipdisplay = 'display-none';
//}

if(!empty($types_module->moduleData['id'][$pgType]['writable'])) {
    if(($types_module->moduleData['id'][$pgType]['writable'] == 1)) {
	$fclipdisplay = 'display-none';
    } else if(($types_module->moduleData['id'][$pgType]['writable'] == 2)) {
        if(($types_module->moduleData['id'][$pgType]['writable_strip_position'] == 2)) {
            $fclipdisplay = 'display-none';
        } else if(($types_module->moduleData['id'][$pgType]['writable_strip_position'] == 1)) {
            $bclipdisplay = 'display-none';
        }
    }
}

$exclpfl = '';
$exclpfr = '';
if(in_array($pgType, array(31,32,33))) {
    $exclpfl = '<input type="checkbox" id="exclpfl" onclick="extend_clipart(this, document.getElementById(\'clpfl2\'));" /> Extend left clipart both lines';
    $exclpfr = '<input type="checkbox" id="exclpfr" onclick="extend_clipart(this, document.getElementById(\'clpfr2\'));" /> Extend right clipart both lines';
}


$input['clipart_front_left'] = !empty($input['clipart_front_left'])?$input['clipart_front_left']:0;
$input['clipart_front_right'] = !empty($input['clipart_front_right'])?$input['clipart_front_right']:0;
$input['clipart_back_left'] = !empty($input['clipart_back_left'])?$input['clipart_back_left']:0;
$input['clipart_back_right'] = !empty($input['clipart_back_right'])?$input['clipart_back_right']:0;
$input['clipart_front_left2'] = !empty($input['clipart_front_left2'])?$input['clipart_front_left2']:0;
$input['clipart_front_right2'] = !empty($input['clipart_front_right2'])?$input['clipart_front_right2']:0;
$input['clipart_back_left2'] = !empty($input['clipart_back_left2'])?$input['clipart_back_left2']:0;
$input['clipart_back_right2'] = !empty($input['clipart_back_right2'])?$input['clipart_back_right2']:0;

$cccls = 'display-none';
$id_clipart_front_left = $input['clipart_front_left'];
$id_clipart_front_right = $input['clipart_front_right'];
$id_clipart_back_left = $input['clipart_back_left'];
$id_clipart_back_right = $input['clipart_back_right'];
$id_clipart_front_left2 = $input['clipart_front_left2'];
$id_clipart_front_right2 = $input['clipart_front_right2'];
$id_clipart_back_left2 = $input['clipart_back_left2'];
$id_clipart_back_right2 = $input['clipart_back_right2'];

$name_clipart_front_left = !empty($input['clipart_front_left'])?$clipart_module->getClipartName($tpt_vars, $input['clipart_front_left']):'Select Front Left Clipart...';
tpt_dump($input, true, 'R');
tpt_dump($name_clipart_back_left, false, 'R');
$name_clipart_front_right = !empty($input['clipart_front_right'])?$clipart_module->getClipartName($tpt_vars, $input['clipart_front_right']):'Select Front Right Clipart...';
$name_clipart_back_left = !empty($input['clipart_back_left'])?$clipart_module->getClipartName($tpt_vars, $input['clipart_back_left']):'Select Back Left Clipart...';
if($_SERVER["REMOTE_ADDR"] == '120.63.37.169'){
    echo 'hehe';die();
    $name_clipart_back_left = (empty($input['clipart_back_left']) && !empty($input['clipart_back_left_c']))?$input['clipart_back_left_c']:'Select Back Left Clipart...';
}


$name_clipart_back_right = !empty($input['clipart_back_right'])?$clipart_module->getClipartName($tpt_vars, $input['clipart_back_right']):'Select Back Right Clipart...';
$name_clipart_front_left2 = !empty($input['clipart_front_left2'])?$clipart_module->getClipartName($tpt_vars, $input['clipart_front_left2']):'Select Front Left Clipart Ln2...';
$name_clipart_front_right2 = !empty($input['clipart_front_right2'])?$clipart_module->getClipartName($tpt_vars, $input['clipart_front_right2']):'Select Front Right Clipart Ln2...';
$name_clipart_back_left2 = !empty($input['clipart_back_left2'])?$clipart_module->getClipartName($tpt_vars, $input['clipart_back_left2']):'Select Back Left Clipart Ln2...';
$name_clipart_back_right2 = !empty($input['clipart_back_right2'])?$clipart_module->getClipartName($tpt_vars, $input['clipart_back_right2']):'Select Back Right Clipart Ln2...';

$value_clipart_front_left = !empty($input['clipart_front_left'])?$input['clipart_front_left']:'';
$value_clipart_front_right = !empty($input['clipart_front_right'])?$input['clipart_front_right']:'';
$value_clipart_back_left = !empty($input['clipart_back_left'])?$input['clipart_back_left']:'';
$value_clipart_back_right = !empty($input['clipart_back_right'])?$input['clipart_back_right']:'';
$value_clipart_front_left2 = !empty($input['clipart_front_left2'])?$input['clipart_front_left2']:'';
$value_clipart_front_right2 = !empty($input['clipart_front_right2'])?$input['clipart_front_right2']:'';
$value_clipart_back_left2 = !empty($input['clipart_back_left2'])?$input['clipart_back_left2']:'';
$value_clipart_back_right2 = !empty($input['clipart_back_right2'])?$input['clipart_back_right2']:'';
if(
	!empty($input['clipart_front_left']) ||
	!empty($input['clipart_front_right']) ||
	!empty($input['clipart_back_left']) ||
	!empty($input['clipart_back_right']) ||
	!empty($input['clipart_front_left2']) ||
	!empty($input['clipart_front_right2']) ||
	!empty($input['clipart_back_left2']) ||
	!empty($input['clipart_back_right2'])
) {
	$cccls = '';
}


$section_add_artwork = <<< EOT

<div class="$cccls" id="clipart_container">
    <div id="tpt_pg_front_clipart_wrap" class="$fclipdisplay">
        <div id="tpt_pg_front_clipart">
            <div id="clpfl">
                <div style="font-family: Arial;" class="amz_brown font-size-14 font-weight-bold">Front Left Clipart:: </div>
                <span class="clip_select_trigger" id="front_left_trigger" title="Select Front Left Clipart..." onclick="try{click_artwrk_sel(this,'tpt_pg_front_lclipart_ctr');}catch(e){};">$name_clipart_front_left</span>
                <input class="fl" type="hidden" name="flclipart" title="Select Front Left Clipart..." id="tpt_pg_front_lclipart_ctr" value="$value_clipart_front_left" />
                <a id="remove_clipart_tpt_pg_front_lclipart_ctr" href="javascript:;" class="remove_clipart display-none" onclick="$('#tpt_pg_front_lclipart_ctr').val(''); addClass(document.getElementById('remove_clipart_tpt_pg_front_lclipart_ctr'), 'display-none');">[X]</a>
                <br />
                $exclpfl
            </div>
            <div id="clpfr">
                <div style="font-family: Arial;" class="amz_brown font-size-14 font-weight-bold">Front Right Clipart: </div>
                <span class="clip_select_trigger" id="front_right_trigger" title="Select Front Right Clipart..." onclick="try{click_artwrk_sel(this,'tpt_pg_front_rclipart_ctr');}catch(e){};">$name_clipart_front_right</span>
                <input class="fr" type="hidden" name="frclipart" title="Select Front Right Clipart..." id="tpt_pg_front_rclipart_ctr" value="$value_clipart_front_right" />
                <a id="remove_clipart_tpt_pg_front_rclipart_ctr" href="javascript:;" class="remove_clipart display-none" onclick="$('#tpt_pg_front_rclipart_ctr').val(''); addClass(document.getElementById('remove_clipart_tpt_pg_front_rclipart_ctr'), 'display-none');">[X]</a>
                <br />
                $exclpfr
            </div>
        </div>
        <div id="tpt_pg_front2_clipart" class="display-none">
            <div id="clpfl2">
                <div style="font-family: Arial;" class="amz_brown font-size-14 font-weight-bold">Front Left Clipart Ln2: </div>
                <span class="clip_select_trigger" id="front_left_trigger2" title="Select Front Left Clipart Ln2..." onclick="try{click_artwrk_sel(this,'tpt_pg_front2_lclipart_ctr');}catch(e){};">$name_clipart_front_left2</span>
                <input class="fl2" type="hidden" name="flclipart2" title="Select Front Left Clipart Ln2..." id="tpt_pg_front2_lclipart_ctr" value="$value_clipart_front_left2" />
                <a id="remove_clipart_tpt_pg_front2_lclipart_ctr" href="javascript:;" class="remove_clipart display-none" onclick="$('#tpt_pg_front2_lclipart_ctr').val(''); addClass(document.getElementById('remove_clipart_tpt_pg_front2_lclipart_ctr'), 'display-none');">[X]</a>
            </div>
            <div id="clpfr2">
                <div style="font-family: Arial;" class="amz_brown font-size-14 font-weight-bold">Front Right Clipart Ln2: </div>
                <span class="clip_select_trigger" id="front_right_trigger2" title="Select Front Right Clipart Ln2..." onclick="try{click_artwrk_sel(this,'tpt_pg_front2_rclipart_ctr');}catch(e){};">$name_clipart_front_right2</span>
                <input class="fr2" type="hidden" name="frclipart2" title="Select Front Right Clipart Ln2..." id="tpt_pg_front2_rclipart_ctr" value="$value_clipart_front_right2" />
                <a id="remove_clipart_tpt_pg_front2_rclipart_ctr" href="javascript:;" class="remove_clipart display-none" onclick="$('#tpt_pg_front2_rclipart_ctr').val(''); addClass(document.getElementById('remove_clipart_tpt_pg_front2_rclipart_ctr'), 'display-none');">[X]</a>
            </div>
        </div>
    </div>
    <div id="tpt_pg_back_clipart_wrap" class="$bdisplay $bclipdisplay">
        <div id="tpt_pg_back_clipart">
            <div id="clpbl">
                <div style="font-family: Arial;" class="amz_brown font-size-14 font-weight-bold">Back Left Clipart: </div>
                <span class="clip_select_trigger" id="back_left_trigger" title="Select Back Left Clipart..." onclick="try{click_artwrk_sel(this,'tpt_pg_back_lclipart_ctr');}catch(e){};">$name_clipart_back_left</span>
                <input class="bl" type="hidden" name="blclipart" title="Select Back Left Clipart..." id="tpt_pg_back_lclipart_ctr" value="$value_clipart_back_left" />
                <a id="remove_clipart_tpt_pg_back_lclipart_ctr" href="javascript:;" class="remove_clipart display-none" onclick="$('#tpt_pg_back_lclipart_ctr').val(''); addClass(document.getElementById('remove_clipart_tpt_pg_back_lclipart_ctr'), 'display-none');">[X]</a>
            </div>
            <div id="clpbr">
                <div style="font-family: Arial;" class="amz_brown font-size-14 font-weight-bold">Back Right Clipart: </div>
                <span class="clip_select_trigger" id="back_right_trigger" title="Select Back Right Clipart..." onclick="try{click_artwrk_sel(this,'tpt_pg_back_rclipart_ctr');}catch(e){};">$name_clipart_back_right</span>
                <input class="br" type="hidden" name="brclipart" title="Select Back Right Clipart..." id="tpt_pg_back_rclipart_ctr" value="$value_clipart_back_right" />
                <a id="remove_clipart_tpt_pg_back_rclipart_ctr" href="javascript:;" class="remove_clipart display-none" onclick="$('#tpt_pg_back_rclipart_ctr').val(''); addClass(document.getElementById('remove_clipart_tpt_pg_back_rclipart_ctr'), 'display-none');">[X]</a>
            </div>
        </div>
        <div id="tpt_pg_back2_clipart" class="display-none">
            <div id="clpbl2">
                <div style="font-family: Arial;" class="amz_brown font-size-14 font-weight-bold">Back Left Clipart Ln2: </div>
                <span class="clip_select_trigger" id="back_left_trigger2" title="Select Back Left Clipart Ln2..." onclick="try{click_artwrk_sel(this,'tpt_pg_back2_lclipart_ctr');}catch(e){};">$name_clipart_back_left2</span>
                <input class="bl2" type="hidden" name="blclipart2" title="Select Back Left Clipart Ln2..." id="tpt_pg_back2_lclipart_ctr" value="$value_clipart_back_left2" />
                <a id="remove_clipart_tpt_pg_back2_lclipart_ctr" href="javascript:;" class="remove_clipart display-none" onclick="$('#tpt_pg_back2_lclipart_ctr').val(''); addClass(document.getElementById('remove_clipart_tpt_pg_back2_lclipart_ctr'), 'display-none');">[X]</a>
            </div>
            <div id="clpbr2">
                <div style="font-family: Arial;" class="amz_brown font-size-14 font-weight-bold">Back Right Clipart Ln2: </div>
                <span class="clip_select_trigger" id="back_right_trigger2" title="Select Back Right Clipart Ln2..." onclick="try{click_artwrk_sel(this,'tpt_pg_back2_rclipart_ctr');}catch(e){};">$name_clipart_back_right2</span>
                <input class="br2" type="hidden" name="brclipart2" title="Select Back Right Clipart Ln2..." id="tpt_pg_back2_rclipart_ctr" value="$value_clipart_back_right2" />
                <a id="remove_clipart_tpt_pg_back2_rclipart_ctr" href="javascript:;" class="remove_clipart display-none" onclick="$('#tpt_pg_back2_rclipart_ctr').val(''); addClass(document.getElementById('remove_clipart_tpt_pg_back2_rclipart_ctr'), 'display-none');">[X]</a>
            </div>
        </div>
    </div>
    $custom_clipart_upload
    $custom_clipart_convert_check
</div>



<div class="ccc_wr">
<a class="thickbox view-all-artwork" href="#TB_inline?width=900&amp;height=550">All Artwork</a>
</div>


EOT;

