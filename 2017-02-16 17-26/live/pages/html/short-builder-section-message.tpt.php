<?php

defined('TPT_INIT') or die('access denied');

//$solid_radio = tpt_html::createRadiobutton($tpt_vars, 'color_type'/*name*/, '1'/*control value*/, '1'/*checked value*/, ' id="solid_colors"'/*html attribs*/, ''/*oncheck*/);
//$swirl_radio = tpt_html::createRadiobutton($tpt_vars, 'color_type'/*name*/, '2'/*control value*/, '1'/*checked value*/, ' id="swirl_colors"'/*html attribs*/, ''/*oncheck*/);
//$segmented_radio = tpt_html::createRadiobutton($tpt_vars, 'color_type'/*name*/, '3'/*control value*/, '1'/*checked value*/, ' id="segmented_colors"'/*html attribs*/, ''/*oncheck*/);

/*
// master template
$color_type_control = <<< EOT
<label class="color-black" for="solid_colors">Solid</label>$solid_radio
<label class="color-black" for="swirl_colors">Swirl</label>$swirl_radio
<label class="color-black" for="segmented_colors">Segmented</label>$segmented_radio
EOT;
*/



$types_module = getModule($tpt_vars, 'BandType');
$msg_module = getModule($tpt_vars, 'BandMessage');

$tpt_res_url = RESOURCE_URL;

$section_message = '';



$mspan_disabled = '';
$mspan1_checked = '';
$mspan2_checked = '';
if(!$tback) {
$mspan_disabled = 'disabled="disabled"';
}

$bdisplay = 'display-none';
$bdisabled = 'disabled="disabled"';

if($tback && !$tcont) {
$mspan2_checked = 'checked="checked"';
$bdisplay = 'display-block';
$bdisabled = '';
} 
else {
$mspan1_checked = 'checked="checked"';
}

$hideclass = '';
if(!$tback) {
    $hideclass = ' display-none';
}

$fl2display = 'display-none';
$fl2undisplay = '';
/*
if(!empty($pgFrontMessage2)) {
	$fl2display = '';
	$fl2undisplay = 'display-none';
	$bdisabled = '';
}
*/
$bl2display = 'display-none';
$bl2undisplay = '';
/*
if(!empty($pgBackMessage2)) {
	$bl2display = '';
	$bl2undisplay = 'display-none';
	$bdisabled = '';
	$mspan2_checked = 'checked="checked"';
	$bdisplay = 'display-block';
}
*/
$lndisplay = ' display-block';
if($lnum < 2) {
    $lndisplay = ' display-none';
}

/*tpt_dump($tcont);
tpt_dump($tback);
tpt_dump($mspan2_checked);
tpt_dump($mspan_disabled);
tpt_dump($im_mspan_disabled);
tpt_dump($im_mspan1_checked);
tpt_dump($bdisabled);*/
//tpt_dump($UDpgFrontMessage, true);
//tpt_dump($data_module->typeStyle[$pgType][$pgStyle]['id']);
//tpt_dump($data_module->typeStyle[$pgType][$pgStyle]['blank'], true);
//tpt_dump($data_module->typeStyle[$pgType][$pgStyle]['writable'], true);
//tpt_dump($data_module->typeStyle[$pgType][$pgStyle]['writable_strip_position'], true);
$msgDisplayClass = '';
$fmDisplayClass = '';
$bmDisplayClass = '';
$fmWritableLabel = '';
$bmWritableLabel = '';
$lntogglefuncparams = 'this';
if(!empty($data_module->typeStyle[$pgType][$pgStyle]['writable'])) {
    $lntogglefuncparams = 'this, true';
    if(($data_module->typeStyle[$pgType][$pgStyle]['writable'] == 1)) {
        $fmDisplayClass = 'display-none';
        $fmWritableLabel = '<div class="amz_brown font-size-16 font-weight-bold">Writable Strip (only available in White)</div>';
    } else if(($data_module->typeStyle[$pgType][$pgStyle]['writable'] == 2)) {
        if(($data_module->typeStyle[$pgType][$pgStyle]['writable_strip_position'] == 2)) {
            $fmDisplayClass = 'display-none';
            $fmWritableLabel = '<div class="amz_brown font-size-16 font-weight-bold">Writable Strip (only available in White)</div>';
        } else if(($data_module->typeStyle[$pgType][$pgStyle]['writable_strip_position'] == 1)) {
            $bmDisplayClass = 'display-none';
            $bmWritableLabel = '<div class="amz_brown font-size-16 font-weight-bold">Writable Strip (only available in White)</div>';
        } else if(($data_module->typeStyle[$pgType][$pgStyle]['writable_strip_position'] == 0)){
            $fmWritableLabel = '<div class="amz_brown font-size-16 font-weight-bold">Writable Strip (only available in White)</div>';
        }
    }
}


if(!empty($data_module->typeStyle[$pgType][$pgStyle]['blank'])) {
    //die();
    $msgDisplayClass = 'display-none';
    $fmDisplayClass = 'display-none';
    $bmDisplayClass = 'display-none';

    if(!empty($data_module->typeStyle[$pgType][$pgStyle]['writable'])) {
        $fmWritableLabel = '<div class="amz_brown font-size-16 font-weight-bold">Writable Strip (only available in White)</div>';
    }
}

$fl2ext = '';
if(in_array($pgType, array(31,32,33))) {
/*
$fl2ext = <<< EOT
<div id="fl2extcontainer" class="display-none">
<div class="padding-left-12 background-position-LC background-repeat-no-repeat" style="">
    <div class="padding-right-60 background-position-RC background-repeat-no-repeat" style=" cursor: pointer;" onclick="tpt_pg_generate_prevew_short('tpt_pg_front2');" title="Update Front Ln2 Preview">
        <div class="background-repeat-repeat-x" style="">
            <input disabled="disabled" oninput="clearTimeout(front2ext_tmt);front2ext_tmt = setTimeout(function(){tpt_pg_generate_prevew_short('tpt_pg_front2');}, 500);" onfocus="removeClass(this.parentNode.parentNode.parentNode.parentNode, 'invalid_field');activate_text_field(this);tpt_pg_generate_prevew_short('tpt_pg_front2');" oncontextmenu="return false" autocomplete="off" readonly="readonly" id="tpt_pg_front2_message" class="plain-input-field height-26 line-height-26 padding-left-2 padding-right-2 padding-top-2 padding-bottom-2 font-size-14" type="text" name="tpt_pg_FrontMessage2" value="$UDpgFrontMessage2" title="Front Message Line 2" style="width: 100%; border: 1px solid #CCCCCC; border-radius: 8px;  background-color: #FFF;" />
        </div>
    </div>
</div>
</div>
EOT;
*/

	$fl2ext = <<< EOT
<div id="fl2extcontainer" class="display-none">
	<input disabled="disabled" oninput="clearTimeout(front2ext_tmt);front2ext_tmt = setTimeout(function(){tpt_pg_generate_prevew_short('tpt_pg_front2');}, 500);" onfocus="removeClass(this.parentNode.parentNode.parentNode.parentNode, 'invalid_field');activate_text_field(this);tpt_pg_generate_prevew_short('tpt_pg_front2');" oncontextmenu="return false" autocomplete="off" readonly="readonly" id="tpt_pg_front2_message" class="plain-input-field height-26 line-height-26 padding-left-2 padding-right-2 padding-top-2 padding-bottom-2 font-size-14" type="text" name="tpt_pg_FrontMessage2" value="$UDpgFrontMessage2" title="Front Message Line 2" style="width: 100%; border: 1px solid #CCCCCC; border-radius: 8px;  background-color: #FFF;" />
</div>
EOT;
}

$contdisable = ' ';
$fbdisable = ' ';
$contspandisplay = ' display-inline';
if((!empty($builder['inhouse']) && ($pgType != 5)) || (($pgStyle == 7) && ($pgType != 5)) || !empty($builder['writable']) || !empty($data_module->typeStyle[$pgType][$pgStyle]['blank'])) {
    $contspandisplay = ' display-none';
    $contdisable = ' disabled="disabled"';
    $fbdisable = ' disabled="disabled"';
}
$frontbackspandisplay = $contspandisplay;
$section_message .= <<< EOT
<div class="$hideclass">
<span class="$contspandisplay">
EOT;
if($pgType != 5) {
$section_message .= <<< EOT
<label for="msg_span_1" class="amz_brown font-size-14 font-weight-bold display-inline-block line-height-16" style="font-family: Arial, Helvetica, sans-serif;">
    <span>Continuous Style</span>
    <!--span class="amz_red font-size-10">(50 min order qty)</span-->
</label>
EOT;
} else {
$section_message .= <<< EOT
<label for="msg_span_1" class="amz_brown font-size-14 font-weight-bold display-inline-block line-height-16" style="font-family: Arial, Helvetica, sans-serif;">
    <span>Continuous Style</span>
</label>
EOT;
}

//for="swirl_colors" removed attribute of the label
$section_message .= <<< EOT
<input type="radio" onclick="change_text_span(this);" name="message_span" value="1" id="msg_span_1" $mspan_disabled $mspan1_checked />
&nbsp;&nbsp;&nbsp;
</span>
<span class="$frontbackspandisplay">
<label for="msg_span_2" class="amz_brown font-size-14 font-weight-bold" style="font-family: Arial, Helvetica, sans-serif;">Front/Back Style</label>
<input type="radio" onclick="change_text_span(this);" name="message_span" value="2" id="msg_span_2" $mspan_disabled $mspan2_checked />
</span>
</div>
EOT;

if(false && isDev()) {
    $section_message .= $msg_module->BandMessage_Control($tpt_vars, 'f', $pgconf);
} else {
/*
$section_message .= <<< EOT
<div class="clear"></div>

<div id="tpt_pg_front_input_container" class="">
<div class="padding-left-12 background-position-LC background-repeat-no-repeat" style="">
    <div style="font-family: Arial;height: 20px;padding-top: 10px;" class="amz_brown font-size-14 font-weight-bold">Front Message: </div>
    $fmWritableLabel
    <div class="$fmDisplayClass padding-right-60 background-position-RC background-repeat-no-repeat" style=" cursor: pointer;" title="Update Front Message Preview">
        <div class="background-repeat-repeat-x" style="">
            <input oninput="update_message(this);" onfocus="removeClass(this.parentNode.parentNode.parentNode.parentNode, 'invalid_field');activate_text_field(this);tpt_pg_generate_prevew_short('tpt_pg_front');" onclick="tpt_pg_generate_prevew_short('tpt_pg_front');" oncontextmenu="return false" autocomplete="off" id="tpt_pg_front_message" class="plain-input-field height-26 line-height-26 padding-left-2 padding-right-2 padding-top-2 padding-bottom-2 font-size-14" type="text" name="tpt_pg_FrontMessage" value="$UDpgFrontMessage" title="Front Message" style="width: 100%; background-color: #FFF;" />
        </div>
    </div>
</div>
EOT;
*/

	$section_message .= <<< EOT
<div class="clear"></div>

<div id="tpt_pg_front_input_container" class=" $msgDisplayClass">
<div class="padding-left-12 background-position-LC background-repeat-no-repeat">
    <div style="font-family: Arial;height: 20px;padding-top: 10px;" class="amz_brown font-size-14 font-weight-bold">Front Message: </div>
    $fmWritableLabel
    <div class="$fmDisplayClass padding-right-60 background-position-RC background-repeat-no-repeat" style="cursor: pointer;" title="Update Front Message Preview">
        <div class="background-repeat-repeat-x" >
            <input oninput="update_message(this);" onfocus="removeClass(this.parentNode.parentNode.parentNode.parentNode, 'invalid_field');activate_text_field(this);tpt_pg_generate_prevew_short('tpt_pg_front');" onclick="tpt_pg_generate_prevew_short('tpt_pg_front');" oncontextmenu="return false" autocomplete="off" id="tpt_pg_front_message" class="plain-input-field height-26 line-height-26 padding-left-2 padding-right-2 padding-top-2 padding-bottom-2 font-size-14" type="text" name="tpt_pg_FrontMessage" value="$UDpgFrontMessage" title="Front Message" style="width: 100%; border: 1px solid #CCCCCC; border-radius: 8px; background-color: #FFF;" />
        </div>
    </div>
</div>
EOT;

//if ($pgType != 1) //2- Custom 1/4" Thin Wristband Builder
//{
if(!empty($UDpgFrontMessage2)){
    $fl2display = '';
    $bdisabled = '';
    $fl2undisplay = 'display-none';
}
$section_message .= <<< EOT
<div class="font-size-14 clearFix $lndisplay $fmDisplayClass">
    <div class="float-left $fl2undisplay" id="fl2add">
        <a href="javascript:void(0);" onclick="add_text_line($lntogglefuncparams); return false;" class="plain-link">[Add 2nd line of text]</a>
    </div>
    <div class="float-right $fl2display" id="fl2remove">
        <a href="javascript:void(0);" onclick="remove_text_line($lntogglefuncparams); return false;" class="amz_red plain-link">[X]</a>
    </div>
</div>
EOT;

//}
/*
$section_message .= <<< EOT
<div id="fl2container" class="$fl2display">
<div class="padding-left-12 background-position-LC background-repeat-no-repeat" style="">
    <div class="padding-right-60 background-position-RC background-repeat-no-repeat" style=" cursor: pointer;" title="Update Front Message Line 2 Preview">
        <div class="background-repeat-repeat-x" style="">
            <input $bdisabled oninput="update_message(this);" onfocus="removeClass(this.parentNode.parentNode.parentNode.parentNode, 'invalid_field');activate_text_field(this);tpt_pg_generate_prevew_short('tpt_pg_front2');" onclick="tpt_pg_generate_prevew_short('tpt_pg_front2');" oncontextmenu="return false" autocomplete="off" id="tpt_pg_front2_message" class="plain-input-field height-26 line-height-26 padding-left-2 padding-right-2 padding-top-2 padding-bottom-2 font-size-14" type="text" name="tpt_pg_FrontMessage2" value="$UDpgFrontMessage2" title="Front Message Line 2" style="width: 100%; background-color: #FFF;" />
        </div>
    </div>
</div>
</div>
$fl2ext
EOT;
*/

	$section_message .= <<< EOT
<div id="fl2container" class="$fl2display">
<div class="padding-left-12 background-position-LC background-repeat-no-repeat">
    <div class="padding-right-60 background-position-RC background-repeat-no-repeat" style="cursor: pointer;" title="Update Front Message Line 2 Preview">
        <div class="background-repeat-repeat-x">
            <input $bdisabled oninput="update_message(this);" onfocus="removeClass(this.parentNode.parentNode.parentNode.parentNode, 'invalid_field');activate_text_field(this);tpt_pg_generate_prevew_short('tpt_pg_front2');" onclick="tpt_pg_generate_prevew_short('tpt_pg_front2');" oncontextmenu="return false" autocomplete="off" id="tpt_pg_front2_message" class="plain-input-field height-26 line-height-26 padding-left-2 padding-right-2 padding-top-2 padding-bottom-2 font-size-14" type="text" name="tpt_pg_FrontMessage2" value="$UDpgFrontMessage2" title="Front Message Line 2" style="width: 100%; border: 1px solid #CCCCCC; border-radius: 8px; background-color: #FFF;" />
        </div>
    </div>
</div>
</div>
$fl2ext
EOT;

$section_message .= <<< EOT
</div>
EOT;
}



if(false && isDev()) {
    $section_message .= $msg_module->BandMessage_Control($tpt_vars, 'b', $pgconf);
} else {
/*
$section_message .= <<< EOT
<div id="tpt_pg_back_input_container" class="$bdisplay $bmdisplaycls">
<div class="height-10"></div>

<div class="padding-left-12 background-position-LC background-repeat-no-repeat" style="">
    <div class="silver-line-separator clear-both"></div>
    <div style="font-family: Arial;height: 20px;padding-top: 10px;" class="amz_brown font-size-14 font-weight-bold">Back Message: </div>
    $bmWritableLabel
    <div class="$bmDisplayClass padding-right-60 background-position-RC background-repeat-no-repeat" style=" cursor: pointer;" title="Update Back Message Preview">
        <div class="background-repeat-repeat-x" style="">
            <input $bdisabled oninput="update_message(this);" onfocus="removeClass(this.parentNode.parentNode.parentNode.parentNode, 'invalid_field');activate_text_field(this);tpt_pg_generate_prevew_short('tpt_pg_back');" onclick="tpt_pg_generate_prevew_short('tpt_pg_back');" oncontextmenu="return false" autocomplete="off" id="tpt_pg_back_message" class="plain-input-field height-26 line-height-26 padding-left-2 padding-right-2 padding-top-2 padding-bottom-2 font-size-14" type="text" name="tpt_pg_BackMessage" value="$UDpgBackMessage" title="Back Message" style="width: 100%; background-color: #FFF;" />
        </div>
    </div>
</div>
EOT;
*/

	$section_message .= <<< EOT
<div id="tpt_pg_back_input_container" class="$bdisplay $bmdisplaycls $msgDisplayClass">
<div class="height-10"></div>

<div class="padding-left-12 background-position-LC background-repeat-no-repeat" >
    <div class="silver-line-separator clear-both"></div>
    <div style="font-family: Arial;height: 20px;padding-top: 10px;" class="amz_brown font-size-14 font-weight-bold">Back Message: </div>
    $bmWritableLabel
    <div class="$bmDisplayClass padding-right-60 background-position-RC background-repeat-no-repeat" style="cursor: pointer;" title="Update Back Message Preview">
        <div class="background-repeat-repeat-x" >
            <input $bdisabled oninput="update_message(this);" onfocus="removeClass(this.parentNode.parentNode.parentNode.parentNode, 'invalid_field');activate_text_field(this);tpt_pg_generate_prevew_short('tpt_pg_back');" onclick="tpt_pg_generate_prevew_short('tpt_pg_back');" oncontextmenu="return false" autocomplete="off" id="tpt_pg_back_message" class="plain-input-field height-26 line-height-26 padding-left-2 padding-right-2 padding-top-2 padding-bottom-2 font-size-14" type="text" name="tpt_pg_BackMessage" value="$UDpgBackMessage" title="Back Message" style="width: 100%; border: 1px solid #CCCCCC; border-radius: 8px; background-color: #FFF;" />
        </div>
    </div>
</div>
EOT;

//if ($pgType != 1) //2- Custom 1/4" Thin Wristband Builder
//{
if(!empty($UDpgBackMessage2)){
    $bl2display = '';
    $bdisabled = '';
    $bl2undisplay = 'display-none';
}
$section_message .= <<< EOT
<div class="font-size-14 clearFix $lndisplay $bmDisplayClass">
    <div class="float-left $bl2undisplay" id="bl2add">
        <a href="javascript:void(0);" onclick="add_text_line($lntogglefuncparams); return false;" class="plain-link">[Add 2nd line of text]</a>
    </div>
    <div class="float-right $bl2display" id="bl2remove">
        <a href="javascript:void(0);" onclick="remove_text_line($lntogglefuncparams); return false;" class="amz_red plain-link">[X]</a>
    </div>
</div>
EOT;

//}
/*
$section_message .= <<< EOT
<div id="bl2container" class="$bl2display">
<div class="padding-left-12 background-position-LC background-repeat-no-repeat" style="">
    <div class="padding-right-60 background-position-RC background-repeat-no-repeat" style=" cursor: pointer;" title="Update Back Message Line 2 Preview">
        <div class="background-repeat-repeat-x" style="">
            <input $bdisabled oninput="update_message(this);" onfocus="removeClass(this.parentNode.parentNode.parentNode.parentNode, 'invalid_field');activate_text_field(this);tpt_pg_generate_prevew_short('tpt_pg_back2');" onclick="tpt_pg_generate_prevew_short('tpt_pg_back2');" oncontextmenu="return false" autocomplete="off" id="tpt_pg_back2_message" class="plain-input-field height-26 line-height-26 padding-left-2 padding-right-2 padding-top-2 padding-bottom-2 font-size-14" type="text" name="tpt_pg_BackMessage2" value="$UDpgBackMessage2" title="Back Message Line 2" style="width: 100%; background-color: #FFF;" />
        </div>
    </div>
</div>
</div>
EOT;
*/

	$section_message .= <<< EOT
<div id="bl2container" class="$bl2display">
<div class="padding-left-12 background-position-LC background-repeat-no-repeat" >
    <div class="padding-right-60 background-position-RC background-repeat-no-repeat" style="cursor: pointer;" title="Update Back Message Line 2 Preview">
        <div class="background-repeat-repeat-x" >
            <input $bdisabled oninput="update_message(this);" onfocus="removeClass(this.parentNode.parentNode.parentNode.parentNode, 'invalid_field');activate_text_field(this);tpt_pg_generate_prevew_short('tpt_pg_back2');" onclick="tpt_pg_generate_prevew_short('tpt_pg_back2');" oncontextmenu="return false" autocomplete="off" id="tpt_pg_back2_message" class="plain-input-field height-26 line-height-26 padding-left-2 padding-right-2 padding-top-2 padding-bottom-2 font-size-14" type="text" name="tpt_pg_BackMessage2" value="$UDpgBackMessage2" title="Back Message Line 2" style="width: 100%; border: 1px solid #CCCCCC; border-radius: 8px; background-color: #FFF;" />
        </div>
    </div>
</div>
</div>
EOT;

$section_message .= <<< EOT
</div>
EOT;
}










//////////////////////// INSIDE MESSAGE SECTION




if(isDev('imessage')) {
    //$imbox = '';
    $imbox = tpt_html::createCheckbox($tpt_vars, 'enable_im'/*name*/, '1'/*control value*/, !empty($_POST['enable_im']), ' onclick="toggle_inside_message(this);" id="enable_im_cb"'/*html attribs*/, ''/*oncheck*/);
    //$addim = '';
    $addim = <<< EOT
    <div style="color: #669669;" class="float-left font-size-plus2 todayshop-bold padding-top-5 padding-bottom-10">
    Add Inside Message?
    </div>
EOT;
    
    
$section_message .= <<< EOT
<div class="padding-top-10 height-1 padding-bottom-10" style="margin: 0px -20px 0px -20px;">
<div class="height-1" style="background: #E2DEDA none;"></div>
</div>

<div class="clearFix">
    $addim
    
    <div class="padding-left-40 float-left amz_brown font-size-14 font-weight-bold padding-top-5 padding-bottom-10" style="font-family: Arial, Helvetica, sans-serif;">
    Yes&nbsp;$imbox
    </div>
    
</div>
EOT;

$section_message .= <<< EOT
<div id="inside_message_container" class="display-none">
EOT;
    
    
    $section_message .= $msg_module->BandMessage_Control($tpt_vars, 'if', $pgconf);
    $section_message .= <<< EOT
    <div class="silver-line-separator clear-both"></div>
EOT;
    $section_message .= $msg_module->BandMessage_Control($tpt_vars, 'ib', $pgconf);
$section_message .= <<< EOT
</div>
EOT;




} else if(true) {
} else {

$imbox = tpt_html::createCheckbox($tpt_vars, 'enable_im', '1', !empty($_POST['enable_im']), ' onclick="toggle_inside_message(this);" id="enable_im_cb"', '');




$section_message .= <<< EOT
<div class="$im_hideclass">
<span class="$im_contspandisplay">
EOT;
if($pgType != 5) {
$section_message .= <<< EOT
<label for="im_msg_span_1" class="amz_brown font-size-14 font-weight-bold display-inline-block line-height-16" style="font-family: Arial, Helvetica, sans-serif;">
    <span>Continuous Style</span>
    <!--span class="amz_red font-size-10">(50 min order qty)</span-->
</label>
EOT;
} else {
$section_message .= <<< EOT
<label for="im_msg_span_1" class="amz_brown font-size-14 font-weight-bold display-inline-block line-height-16" style="font-family: Arial, Helvetica, sans-serif;">
    <span>Continuous Style</span>
</label>
EOT;
}

//removed duplicate attr. for="swirl_colors" on label
$section_message .= <<< EOT
<input type="radio" onclick="change_text_span_im(this);" name="message_span" value="1" id="im_msg_span_1" $im_mspan_disabled $im_mspan1_checked />
&nbsp;&nbsp;&nbsp;
</span>
<span class="$frontbackspandisplay">
<label for="im_msg_span_2" class="amz_brown font-size-14 font-weight-bold" style="font-family: Arial, Helvetica, sans-serif;">Front/Back Style</label>
<input type="radio" onclick="change_text_span_im(this);" name="message_span" value="2" id="im_msg_span_2" $im_mspan_disabled $im_mspan2_checked />
</span>
</div>
EOT;

/*
$section_message .= <<< EOT
<div class="clear"></div>

<div id="im_tpt_pg_front_input_container" class="">
<div class="padding-left-12 background-position-LC background-repeat-no-repeat" style="">
    <div style="font-family: Arial;height: 20px;padding-top: 10px;" class="amz_brown font-size-14 font-weight-bold">Inside Front Message: </div>
    $im_fmWritableLabel
    <div class="$im_fmDisplayClass padding-right-60 background-position-RC background-repeat-no-repeat" style=" cursor: pointer;" onclick="tpt_pg_generate_prevew_short('im_tpt_pg_front');" title="Update Inside Front Message Preview">
        <div class="background-repeat-repeat-x" style="">
            <input oninput="clearTimeout(front_tmt);front_tmt = setTimeout(function(){tpt_pg_generate_prevew_short('im_tpt_pg_front');}, 500);" onfocus="removeClass(this.parentNode.parentNode.parentNode.parentNode, 'invalid_field');activate_text_field(this);tpt_pg_generate_prevew_short('im_tpt_pg_front');" oncontextmenu="return false" autocomplete="off" readonly="readonly" id="im_tpt_pg_front_message" class="plain-input-field height-26 line-height-26 padding-left-2 padding-right-2 padding-top-2 padding-bottom-2 font-size-14" type="text" name="im_tpt_pg_FrontMessage" value="$im_UDpgFrontMessage" title="Inside Front Message" style="width: 100%; border: 1px solid #CCCCCC; border-radius: 8px; background-color: #FFF;" />
        </div>
    </div>
</div>
EOT;
*/

	$section_message .= <<< EOT
<div class="clear"></div>

<div id="im_tpt_pg_front_input_container" class="">
<div class="padding-left-12 background-position-LC background-repeat-no-repeat" >
    <div style="font-family: Arial;height: 20px;padding-top: 10px;" class="amz_brown font-size-14 font-weight-bold">Inside Front Message: </div>
    $im_fmWritableLabel
    <div class="$im_fmDisplayClass padding-right-60 background-position-RC background-repeat-no-repeat" style="cursor: pointer;" onclick="tpt_pg_generate_prevew_short('im_tpt_pg_front');" title="Update Inside Front Message Preview">
        <div class="background-repeat-repeat-x" >
            <input oninput="clearTimeout(front_tmt);front_tmt = setTimeout(function(){tpt_pg_generate_prevew_short('im_tpt_pg_front');}, 500);" onfocus="removeClass(this.parentNode.parentNode.parentNode.parentNode, 'invalid_field');activate_text_field(this);tpt_pg_generate_prevew_short('im_tpt_pg_front');" oncontextmenu="return false" autocomplete="off" readonly="readonly" id="im_tpt_pg_front_message" class="plain-input-field height-26 line-height-26 padding-left-2 padding-right-2 padding-top-2 padding-bottom-2 font-size-14" type="text" name="im_tpt_pg_FrontMessage" value="$im_UDpgFrontMessage" title="Inside Front Message" style="width: 100%; border: 1px solid #CCCCCC; border-radius: 8px; background-color: #FFF;" />
        </div>
    </div>
</div>
EOT;

//if ($pgType != 1) //2-�� ��� �� ������ �� �� ������ ���� ��� �� � Custom 1/4" Thin Wristband Builder
//{

$section_message .= <<< EOT
<div class="font-size-14 clearFix $im_lndisplay $im_fmDisplayClass">
    <div class="float-left" id="im_fl2add">
        <a href="#" onclick="add_text_line_im($im_lntogglefuncparams); return false;" class="plain-link">[Add 2nd line of text]</a>
    </div>
    <div class="float-right display-none" id="im_fl2remove">
        <a href="#" onclick="remove_text_line_im($im_lntogglefuncparams); return false;" class="amz_red plain-link">[X]</a>
    </div>
</div>
EOT;

//}

/*
$section_message .= <<< EOT
<div id="im_fl2container" class="display-none">
<div class="padding-left-12 background-position-LC background-repeat-no-repeat" style="">
    <div class="padding-right-60 background-position-RC background-repeat-no-repeat" style=" cursor: pointer;" onclick="tpt_pg_generate_prevew_short('im_tpt_pg_front2');" title="Update Inside Front Message Line 2 Preview">
        <div class="background-repeat-repeat-x" style="">
            <input disabled="disabled" oninput="clearTimeout(im_front2_tmt);im_front2_tmt = setTimeout(function(){tpt_pg_generate_prevew_short('im_tpt_pg_front2');}, 500);" onfocus="removeClass(this.parentNode.parentNode.parentNode.parentNode, 'invalid_field');activate_text_field(this);tpt_pg_generate_prevew_short('im_tpt_pg_front2');" oncontextmenu="return false" autocomplete="off" readonly="readonly" id="im_tpt_pg_front2_message" class="plain-input-field height-26 line-height-26 padding-left-2 padding-right-2 padding-top-2 padding-bottom-2 font-size-14" type="text" name="im_tpt_pg_FrontMessage2" value="$im_UDpgFrontMessage2" title="Inside Front Message Line 2" style="width: 100%; border: 1px solid #CCCCCC; border-radius: 8px; background-color: #FFF;" />
        </div>
    </div>
</div>
</div>
$im_fl2ext
EOT;
*/

	$section_message .= <<< EOT
<div id="im_fl2container" class="display-none">
<div class="padding-left-12 background-position-LC background-repeat-no-repeat" >
    <div class="padding-right-60 background-position-RC background-repeat-no-repeat" style="cursor: pointer;" onclick="tpt_pg_generate_prevew_short('im_tpt_pg_front2');" title="Update Inside Front Message Line 2 Preview">
        <div class="background-repeat-repeat-x" >
            <input disabled="disabled" oninput="clearTimeout(im_front2_tmt);im_front2_tmt = setTimeout(function(){tpt_pg_generate_prevew_short('im_tpt_pg_front2');}, 500);" onfocus="removeClass(this.parentNode.parentNode.parentNode.parentNode, 'invalid_field');activate_text_field(this);tpt_pg_generate_prevew_short('im_tpt_pg_front2');" oncontextmenu="return false" autocomplete="off" readonly="readonly" id="im_tpt_pg_front2_message" class="plain-input-field height-26 line-height-26 padding-left-2 padding-right-2 padding-top-2 padding-bottom-2 font-size-14" type="text" name="im_tpt_pg_FrontMessage2" value="$im_UDpgFrontMessage2" title="Inside Front Message Line 2" style="width: 100%; border: 1px solid #CCCCCC; border-radius: 8px; background-color: #FFF;" />
        </div>
    </div>
</div>
</div>
$im_fl2ext
EOT;

$section_message .= <<< EOT
</div>
EOT;



/*
$section_message .= <<< EOT
<div id="im_tpt_pg_back_input_container" class="$im_bdisplay $im_bmdisplaycls">
<div class="height-10"></div>

<div class="padding-left-12 background-position-LC background-repeat-no-repeat" style="">
    <div class="silver-line-separator clear-both"></div>
    <div style="font-family: Arial;height: 20px;padding-top: 10px;" class="amz_brown font-size-14 font-weight-bold">Back Message: </div>
    $im_bmWritableLabel
    <div class="$im_bmDisplayClass padding-right-60 background-position-RC background-repeat-no-repeat" style=" cursor: pointer;" onclick="tpt_pg_generate_prevew_short('im_tpt_pg_back');" title="Update Inside Back Message Preview">
        <div class="background-repeat-repeat-x" style="">
            <input $im_bdisabled oninput="clearTimeout(im_back_tmt);im_back_tmt = setTimeout(function(){tpt_pg_generate_prevew_short('im_tpt_pg_back');}, 500);" onfocus="removeClass(this.parentNode.parentNode.parentNode.parentNode, 'invalid_field');activate_text_field(this);tpt_pg_generate_prevew_short('im_tpt_pg_back');" oncontextmenu="return false" autocomplete="off" readonly="readonly" id="im_tpt_pg_back_message" class="plain-input-field height-26 line-height-26 padding-left-2 padding-right-2 padding-top-2 padding-bottom-2 font-size-14" type="text" name="im_tpt_pg_BackMessage" value="$im_UDpgBackMessage" title="Inside Back Message" style="width: 100%; border: 1px solid #CCCCCC; border-radius: 8px; background-color: #FFF;" />
        </div>
    </div>
</div>
EOT;
*/

	$section_message .= <<< EOT
<div id="im_tpt_pg_back_input_container" class="$im_bdisplay $im_bmdisplaycls">
<div class="height-10"></div>

<div class="padding-left-12 background-position-LC background-repeat-no-repeat" >
    <div class="silver-line-separator clear-both"></div>
    <div style="font-family: Arial;height: 20px;padding-top: 10px;" class="amz_brown font-size-14 font-weight-bold">Back Message: </div>
    $im_bmWritableLabel
    <div class="$im_bmDisplayClass padding-right-60 background-position-RC background-repeat-no-repeat" style="cursor: pointer;" onclick="tpt_pg_generate_prevew_short('im_tpt_pg_back');" title="Update Inside Back Message Preview">
        <div class="background-repeat-repeat-x" >
            <input $im_bdisabled oninput="clearTimeout(im_back_tmt);im_back_tmt = setTimeout(function(){tpt_pg_generate_prevew_short('im_tpt_pg_back');}, 500);" onfocus="removeClass(this.parentNode.parentNode.parentNode.parentNode, 'invalid_field');activate_text_field(this);tpt_pg_generate_prevew_short('im_tpt_pg_back');" oncontextmenu="return false" autocomplete="off" readonly="readonly" id="im_tpt_pg_back_message" class="plain-input-field height-26 line-height-26 padding-left-2 padding-right-2 padding-top-2 padding-bottom-2 font-size-14" type="text" name="im_tpt_pg_BackMessage" value="$im_UDpgBackMessage" title="Inside Back Message" style="width: 100%; border: 1px solid #CCCCCC; border-radius: 8px; background-color: #FFF;" />
        </div>
    </div>
</div>
EOT;

//if ($pgType != 1) //2-�� ��� �� ������ �� �� ������ ���� ��� �� � Custom 1/4" Thin Wristband Builder
//{

$section_message .= <<< EOT
<div class="font-size-14 clearFix $im_lndisplay $im_bmDisplayClass">
    <div class="float-left" id="im_bl2add">
        <a href="#" onclick="add_text_line($lntogglefuncparams); return false;" class="plain-link">[Add 2nd line of text]</a>
    </div>
    <div class="float-right display-none" id="im_bl2remove">
        <a href="#" onclick="remove_text_line($lntogglefuncparams); return false;" class="amz_red plain-link">[X]</a>
    </div>
</div>
EOT;

//}

/*
$section_message .= <<< EOT
<div id="im_bl2container" class="display-none">
<div class="padding-left-12 background-position-LC background-repeat-no-repeat" style="">
    <div class="padding-right-60 background-position-RC background-repeat-no-repeat" style=" cursor: pointer;" onclick="tpt_pg_generate_prevew_short('im_tpt_pg_back2');" title="Update Inside Back Message Line 2 Preview">
        <div class="background-repeat-repeat-x" style="">
            <input disabled="disabled" oninput="clearTimeout(im_back2_tmt);im_back2_tmt = setTimeout(function(){tpt_pg_generate_prevew_short('im_tpt_pg_back2');}, 500);" onfocus="removeClass(this.parentNode.parentNode.parentNode.parentNode, 'invalid_field');activate_text_field(this);tpt_pg_generate_prevew_short('im_tpt_pg_back2');" oncontextmenu="return false" autocomplete="off" readonly="readonly" id="im_tpt_pg_back2_message" class="plain-input-field height-26 line-height-26 padding-left-2 padding-right-2 padding-top-2 padding-bottom-2 font-size-14" type="text" name="im_tpt_pg_BackMessage2" value="$im_UDpgBackMessage2" title="Back Message Line 2" style="width: 100%; border: 1px solid #CCCCCC; border-radius: 8px; background-color: #FFF;" />
        </div>
    </div>
</div>
</div>
EOT;
*/

	$section_message .= <<< EOT
<div id="im_bl2container" class="display-none">
<div class="padding-left-12 background-position-LC background-repeat-no-repeat" >
    <div class="padding-right-60 background-position-RC background-repeat-no-repeat" style="cursor: pointer;" onclick="tpt_pg_generate_prevew_short('im_tpt_pg_back2');" title="Update Inside Back Message Line 2 Preview">
        <div class="background-repeat-repeat-x" >
            <input disabled="disabled" oninput="clearTimeout(im_back2_tmt);im_back2_tmt = setTimeout(function(){tpt_pg_generate_prevew_short('im_tpt_pg_back2');}, 500);" onfocus="removeClass(this.parentNode.parentNode.parentNode.parentNode, 'invalid_field');activate_text_field(this);tpt_pg_generate_prevew_short('im_tpt_pg_back2');" oncontextmenu="return false" autocomplete="off" readonly="readonly" id="im_tpt_pg_back2_message" class="plain-input-field height-26 line-height-26 padding-left-2 padding-right-2 padding-top-2 padding-bottom-2 font-size-14" type="text" name="im_tpt_pg_BackMessage2" value="$im_UDpgBackMessage2" title="Back Message Line 2" style="width: 100%; border: 1px solid #CCCCCC; border-radius: 8px; background-color: #FFF;" />
        </div>
    </div>
</div>
</div>
EOT;

$section_message .= <<< EOT
</div>
EOT;


$section_message .= <<< EOT
</div>
EOT;
}




