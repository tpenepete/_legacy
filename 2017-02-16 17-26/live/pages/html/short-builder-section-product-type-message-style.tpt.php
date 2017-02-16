<?php

defined('TPT_INIT') or die('access denied');

//$solid_radio = tpt_html::createRadiobutton($tpt_vars, 'color_type'/*name*/, '1'/*control value*/, '1'/*checked value*/, ' id="solid_colors"'/*html attribs*/, ''/*oncheck*/);
//$swirl_radio = tpt_html::createRadiobutton($tpt_vars, 'color_type'/*name*/, '2'/*control value*/, '1'/*checked value*/, ' id="swirl_colors"'/*html attribs*/, ''/*oncheck*/);
//$segmented_radio = tpt_html::createRadiobutton($tpt_vars, 'color_type'/*name*/, '3'/*control value*/, '1'/*checked value*/, ' id="segmented_colors"'/*html attribs*/, ''/*oncheck*/);

/*
// master template
$section_band_color = <<< EOT
<label class="color-black" for="solid_colors">Solid</label>$solid_radio
<label class="color-black" for="swirl_colors">Swirl</label>$swirl_radio
<label class="color-black" for="segmented_colors">Segmented</label>$segmented_radio
EOT;
*/



$type_module = getModule($tpt_vars, "BandType");
$product_type = '';
$product_type_select = '';
$builder_types = array();
$mtype = '';
if(!empty($builder['type'])) {
    $builder_types = explode(',', $builder['type']);
    //tpt_dump($builder_types, true);
    if(count($builder_types) == 1) {
        $product_type = $type_module->moduleData['id'][reset($builder_types)]['name'];
        $mtype = <<< EOT
<div class="amz_brown font-size-plus2 font-weight-bold" style="font-family: Arial, Helvetica, sans-serif;">$product_type</div>
EOT;
    } else {
        
        $product_type_select = getModule($tpt_vars, "BandType")->BandType_Select_SB($tpt_vars, $pgStyle, $selected_type, $builder);
        $mtype = <<< EOT
<div class="amz_brown font-size-plus2 font-weight-bold" style="font-family: Arial, Helvetica, sans-serif;">$product_type_select</div>
EOT;
    }
} else {
    $product_type_select = getModule($tpt_vars, "BandType")->BandType_Select_SB($tpt_vars, $pgStyle, $selected_type, $builder);
    $mtype = <<< EOT
<div class="amz_brown font-size-plus2 font-weight-bold" style="font-family: Arial, Helvetica, sans-serif;">$product_type_select</div>
EOT;
}
//if(!empty($_POST['short_builder']))
//var_dump($pgStyle);die();
//var_dump($builder);die();




//var_dump($builder['style']);die();

/*
// �������� message style ��� ��� � ������ ��� ������, ����� ���� ����� �� ���� �����
$message_style = '';
switch ($url_id)
{
    case '183':
        $message_style = 'Dual Layer';
        break;
    case '182':
        $message_style = 'ScreenPrinted';
        break;
    case '181':
        $message_style = 'Colorized Embossed';
        break;
    case '180':
        $message_style = 'Embossed';
        break;
    case '179':
        $message_style = 'Ink Filled Debossed';
        break;
    case '178':
        $message_style = 'Debossed';
        break;
}
*/

$type_module = getModule($tpt_vars, "BandType");
$style_module = getModule($tpt_vars, "BandStyle");

$message_style = '';
if(!empty($style_module->moduleData['id'][$builder['style']]))
$message_style = $style_module->moduleData['id'][$builder['style']]['name'];

//var_dump($selected_style);die();

$stbox = '';
if( ( /*($pgType == 9) || ($pgType == 10) || ($pgType == 11) || */($pgType == 12))) {
    /*
    $onclick = '';
    $onclick .= 'if(this.checked) {';
    $onclick .= 'removeClass(document.getElementById(\'stylewrapper\'), \'visibility-hidden\');';
    $onclick .= 'removeClass(document.getElementById(\'tpt_pg_back_input_container\'), \'visibility-hidden\');';
    $onclick .= 'removeClass(document.getElementById(\'fontwrapper\'), \'visibility-hidden\');';
    $onclick .= 'removeClass(document.getElementById(\'awwrapper\'), \'visibility-hidden\');';
    $onclick .= 'document.getElementById(\'tpt_pg_back_message\').value = \'Back Message\';';
    $onclick .= '} else {';
    $onclick .= 'addClass(document.getElementById(\'stylewrapper\'), \'visibility-hidden\');';
    $onclick .= 'addClass(document.getElementById(\'tpt_pg_back_input_container\'), \'visibility-hidden\');';
    $onclick .= 'addClass(document.getElementById(\'fontwrapper\'), \'visibility-hidden\');';
    $onclick .= 'addClass(document.getElementById(\'awwrapper\'), \'visibility-hidden\');';
    $onclick .= 'document.getElementById(\'tpt_pg_back_message\').value = \'\';';
    $onclick .= 'document.getElementById(\'tpt_pg_back2_message\').value = \'\';';
    $onclick .= 'document.getElementById(\'tpt_pg_back_lclipart\').value = \'\';';
    $onclick .= 'document.getElementById(\'tpt_pg_back_rclipart\').value = \'\';';
    $onclick .= 'document.getElementById(\'tpt_pg_back2_lclipart\').value = \'\';';
    $onclick .= 'document.getElementById(\'tpt_pg_back2_rclipart\').value = \'\';';
    $onclick .= 'document.getElementById(\'tpt_pg_back_lclipart_ctr\').value = \'\';';
    $onclick .= 'document.getElementById(\'tpt_pg_back_rclipart_ctr\').value = \'\';';
    $onclick .= 'document.getElementById(\'tpt_pg_back2_lclipart_ctr\').value = \'\';';
    $onclick .= 'document.getElementById(\'tpt_pg_back2_rclipart_ctr\').value = \'\';';
    $onclick .= 'document.getElementById(\'back_left_trigger\').innerHTML = document.getElementById(\'back_left_trigger\').title;';
    $onclick .= 'document.getElementById(\'back_right_trigger\').innerHTML = document.getElementById(\'back_right_trigger\').title;';
    $onclick .= 'document.getElementById(\'back_left_trigger2\').innerHTML = document.getElementById(\'back_left_trigger2\').title;';
    $onclick .= 'document.getElementById(\'back_right_trigger2\').innerHTML = document.getElementById(\'back_right_trigger2\').title;';
    $onclick .= '}';
    $onclick .= 'tpt_pg_generate_prevew_short(\'tpt_pg_back\');';
    */
    $stbox = '&nbsp;&nbsp;&nbsp;&nbsp;<input id="ebmcb" '.$dwbchck.' onclick="disable_back_message(this);" type="checkbox" name="" value="" />&nbsp;<span class="font-size-12 amz_brown">Add Back Message!</span>';


}

$mclass = '';
if(!empty($data_module->typeStyle[$pgType][$pgStyle]['writable']) && ($data_module->typeStyle[$pgType][$pgStyle]['writable_class'] != 5)) {
$mclass = getModule($tpt_vars, "WritableClass")->WritableClass_Select_SB($tpt_vars, $pgType, $pgStyle, $pgWritableClass, $builder['inhouse']);
//tpt_dump($mclass, true);
$mclass = <<< EOT
<div class="todayshop-bold font-size-plus6 padding-top-15 padding-bottom-10" style="color: #669669;">Writable Option</div>
$mclass
EOT;
}
//die();

$mstyle = '';
if(empty($data_module->typeStyle[$pgType][$pgStyle]['blank']) || ($data_module->typeStyle[$pgType][$pgStyle]['writable_class'] == 5)) {
if(!empty($builder['style'])) {
    $builder_styles = explode(',', $builder['style']);
    //tpt_dump($builder_types, true);
    if(count($builder_styles) == 1) {
		//tpt_dump($builder_styles);
    $mstyle = <<< EOT
<div class="todayshop-bold font-size-plus6 padding-top-15 padding-bottom-10" style="color: #669669;">Message Style$stbox</div>
<div id="stylewrapper" class="amz_brown font-size-plus2 font-weight-bold $bmdisplaycls" style="font-family: Arial, Helvetica, sans-serif;">$message_style</div>
EOT;
    } else {
		//tpt_dump($builder_styles);
    $message_style_select = getModule($tpt_vars, "BandStyle")->BandStyle_Select_SB($tpt_vars, $pgType, $selected_style, $builder);
    $mstyle = <<< EOT
<div class="todayshop-bold font-size-plus6 padding-top-15 padding-bottom-10" style="color: #669669;">Message Style$stbox</div>
<div id="stylewrapper" class="amz_brown font-size-plus2 font-weight-bold $bmdisplaycls" style="font-family: Arial, Helvetica, sans-serif;">$message_style_select</div>
EOT;
    }
} else {
	//tpt_dump($builder_styles);
    $message_style_select = getModule($tpt_vars, "BandStyle")->BandStyle_Select_SB($tpt_vars, $pgType, $selected_style, $builder);
    $mstyle = <<< EOT
<div class="todayshop-bold font-size-plus6 padding-top-15 padding-bottom-10" style="color: #669669;">Message Style$stbox</div>
<div id="stylewrapper" class="amz_brown font-size-plus2 font-weight-bold $bmdisplaycls" style="font-family: Arial, Helvetica, sans-serif;">$message_style_select</div>
EOT;
}
} else {
    $mstyle = '';
}


$section_message_style = <<< EOT
$mtype
$mclass
$mstyle
EOT;

