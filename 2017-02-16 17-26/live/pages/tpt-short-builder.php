<?php

defined('TPT_INIT') or die('access denied');


if(isDev('newpreview')) {
	if(isDev('newpreview_G')) {
		//goran's version
		include(dirname(__FILE__).DIRECTORY_SEPARATOR.'tpt-short-builder_VER3G.php');
	} else {
		include(dirname(__FILE__).DIRECTORY_SEPARATOR.'tpt-short-builder_VER2.php');
	}
	
//} elseif($_SERVER['REMOTE_ADDR']=='83.222.171.3') {
}/* elseif(@$_SESSION['ADMIN_TESTER']) {
	//goran's version
	include(dirname(__FILE__).DIRECTORY_SEPARATOR.'tpt-short-builder_VER3G.php');

}*/ else {



/*
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
*/



/*
$time = time();

define('SWIRL_COLORS', 6);
define('SEGMENT_COLORS', 6);

// process product parameters
$type = intval((empty($_POST['type'])?0:$_POST['type']), 10);
$style = intval((empty($_POST['style'])?0:$_POST['style']), 10);
$qty_xs = intval((empty($_POST['qty_xs'])?0:$_POST['qty_xs']), 10);
$qty_xs_im = intval((empty($_POST['qty_xs_im'])?0:$_POST['qty_xs_im']), 10); // inside mold
$qty_sm = intval((empty($_POST['qty_sm'])?0:$_POST['qty_sm']), 10);
$qty_sm_im = intval((empty($_POST['qty_sm_im'])?0:$_POST['qty_sm_im']), 10);
$qty_m = intval((empty($_POST['qty_m'])?0:$_POST['qty_m']), 10);
$qty_m_im = intval((empty($_POST['qty_m_im'])?0:$_POST['qty_m_im']), 10);
$qty_lg = intval((empty($_POST['qty_lg'])?0:$_POST['qty_lg']), 10);
$qty_lg_im = intval((empty($_POST['qty_lg_im'])?0:$_POST['qty_lg_im']), 10);
$qty_xl = intval((empty($_POST['qty_xl'])?0:$_POST['qty_xl']), 10);
$qty_xl_im = intval((empty($_POST['qty_xl_im'])?0:$_POST['qty_xl_im']), 10);


$qty_Input = '<input autocomplete="off" class="display-inline" id="qty_input" oninput="document.getElementById(\'qty_lg_input\').value = this.value;" onpropertychange="document.getElementById(\'qty_lg_input\').value = this.value;" type="text" size="6" value="'.$qty_lg.'" />';
$qty_Input_im = tpt_html::createCheckbox($tpt_vars, '', 1, $qty_lg_im, ' class="imcontrol" id="qty_lg_im1" autocomplete="off" onclick="if(this.checked)document.getElementById(\'qty_lg_im2\').checked=true; else document.getElementById(\'qty_lg_im2\').checked=false;"', '', '');
$qty_lgInput = '<input autocomplete="off" class="display-inline" id="qty_lg_input" oninput="document.getElementById(\'qty_input\').value = this.value;" onpropertychange="document.getElementById(\'qty_input\').value = this.value;" type="text" size="6" name="qty_lg" value="'.$qty_lg.'" />';
$qty_lgInput_im = tpt_html::createCheckbox($tpt_vars, 'qty_lg_im', 1, $qty_lg_im, ' class="imcontrol" id="qty_lg_im2" autocomplete="off" onclick="if(this.checked)document.getElementById(\'qty_lg_im1\').checked=true; else document.getElementById(\'qty_lg_im1\').checked=false;"', 'document.getElementById(\'qty_lg_im1\').checked=true;', 'document.getElementById(\'qty_lg_im1\').checked=false;');
$qty_xsInput = '<input autocomplete="off" class="display-inline" type="text" size="6" name="qty_xs" value="'.$qty_xs.'" />';
$qty_xsInput_im = tpt_html::createCheckbox($tpt_vars, 'qty_xs_im', 1, $qty_xs_im, ' class="imcontrol" id="qty_xs_im" autocomplete="off"', '', '');
$qty_smInput = '<input autocomplete="off" class="display-inline" type="text" size="6" name="qty_sm" value="'.$qty_sm.'" />';
$qty_smInput_im = tpt_html::createCheckbox($tpt_vars, 'qty_sm_im', 1, $qty_sm_im, ' class="imcontrol" id="qty_xs_im" autocomplete="off"', '', '');
$qty_mInput = '<input autocomplete="off" class="display-inline" type="text" size="6" name="qty_m" value="'.$qty_m.'" />';
$qty_mInput_im = tpt_html::createCheckbox($tpt_vars, 'qty_m_im', 1, $qty_m_im, ' class="imcontrol" id="qty_m_im" autocomplete="off"', '', '');
$qty_xlInput = '<input autocomplete="off" class="display-inline" type="text" size="6" name="qty_xl" value="'.$qty_xl.'" />';
$qty_xlInput_im = tpt_html::createCheckbox($tpt_vars, 'qty_xl_im', 1, $qty_xl_im, ' class="imcontrol" id="qty_xl_im" autocomplete="off"', '', '');
//$qty_lgInput = '<input type="text" size="6" name="qty_lg" value="'.$qty_lg.'" />';

ob_start();
include(dirname(__FILE__).DIRECTORY_SEPARATOR.'html'.DIRECTORY_SEPARATOR.'builder-qty-box.tpt.php');
$qty_panel = ob_get_contents();
ob_end_clean();
$qty_panel = '';




$typeSelectS = getModule($tpt_vars, "BandType")->BandType_Select($tpt_vars);
$typeSelectS = '';
$styleSelectS = getModule($tpt_vars, "BandStyle")->BandStyle_Select($tpt_vars);
$styleSelectS = '';


$colorSelect = getModule($tpt_vars, "BandColor")->BandColor_Select($tpt_vars);

$fontSelect = getModule($tpt_vars, "BandFont")->BandFont_Select($tpt_vars);

$textcolorSelect = getModule($tpt_vars, "BandColor")->TextColor_Select($tpt_vars);


$swirlsHtml = '';
for($i=0; $i<SWIRL_COLORS; $i++) {
$swirlCheckbox = tpt_html::createCheckbox($tpt_vars, 'enable_swirl[]', 1, 0, ' autocomplete="off" id="tpt_pg_swirl_enable'.$i.'" onclick="if(this.checked)document.getElementById(\'swirl_select'.$i.'\').style.visibility=\'visible\'; else document.getElementById(\'swirl_select'.$i.'\').style.visibility=\'hidden\';_debossed_tpt_pg_generate_prevew_all()"');
$swirlSelect = getModule($tpt_vars, "BandColor")->SwirlColor_Select($tpt_vars, $i);
$swirlsHtml .= 'Enable swirl #'.($i+1).'?&nbsp;'.$swirlCheckbox.$swirlSelect.'<br />'."\n";
}


$glitterCheckbox = tpt_html::createCheckbox($tpt_vars, '', 1, 0, ' autocomplete="off" id="tpt_pg_glitter" onclick="_debossed_tpt_pg_generate_prevew_all()"');


$bandClipartPanel = getModule($tpt_vars, "BandClipartCategory")->BandClipartCategory_Panel($tpt_vars);
//var_dump($colorSelect);die();

$addElementButton = '<a class="display-block width-130 height-38 background-repeat-no-repeat hoverCB" style="background-image: url('.TPT_IMAGES_URL.'/add-element-button.png)" href="javascript:void(0);" title="Add Message Elements (multiple fonts text, clipart...)"></a>';
$allFontsButton = '<a class="display-block width-130 height-38 background-repeat-no-repeat hoverCB" style="background-image: url('.TPT_IMAGES_URL.'/all-fonts-button.png)" href="javascript:void(0);" title="Check out our other fonts"></a>';
$adjustElementsButton = '<a class="display-inline-block width-130 height-32 background-repeat-no-repeat hoverCB" style="background-image: url('.TPT_IMAGES_URL.'/adjust-elements-button.png)" href="javascript:void(0);" title="Click Here to Arrange Your Message"></a>';



$tpt_res_url = RESOURCE_URL;



<script type="text/javascript">
$product_types_js
</script>


// good old templay - stuff the $tpt_vars array
$tpt_vars['template_data']['head'][] = <<< EOT
<script type="text/javascript" src="$tpt_baseurl/js/builder.js"></script>

EOT;


$pricing = <<< EOT
        <div class="amz_red font-size-28 height-22 line-height-22 padding-top-15 padding-bottom-5" style="font-family:TODAYSHOP-LIGHT,arial;">Our Price:</div>
        <div class="font-size-28 height-22 line-height-22" style="">
            <span class="amz_green" style="font-family:TODAYSHOP-LIGHT,arial;">From&nbsp;</span>
            <span class="amz_red" style="font-family:TODAYSHOP-BOLD,arial;">$0.00&nbsp;</span>
            <span class="amz_green" style="font-family:TODAYSHOP-LIGHT,arial;">to&nbsp;</span>
            <span class="amz_red" style="font-family:TODAYSHOP-BOLD,arial;">$0.00</span>
        </div>
EOT;
*/


/*
$fields_row = array(
    'id'=>'1',
    'label'=>'',
    'name'=>'',
    'control'=>'s',
    'classes'=>'',
    'order'=>'2',
    'value'=>'{color_type_control:short-builder-color-type.tpt.php}',
    'html_attribs'=>'',
    'oncheck'=>'',
    'onuncheck'=>'',
    'row_height'=>'28',
    'label_line_height'=>'28',
    'control_line_height'=>'28',
    'after_line_height'=>'28',
    'after_content'=>'',
    'required'=>'',
    'validation_regex'=>'',
    'store_field'=>'',
    'enabled'=>'1',
);
$fields_data[] = $fields_row;

$fields_row = array(
    'id'=>'1',
    'label'=>'Solid',
    'name'=>'color_type',
    'control'=>'r',
    'classes'=>'',
    'order'=>'2',
    'value'=>'1',
    'html_attribs'=>' onclick="update_color_select(this.value);"',
    'oncheck'=>'update_color_select(this.value);',
    'onuncheck'=>'',
    'row_height'=>'28',
    'label_line_height'=>'28',
    'control_line_height'=>'28',
    'after_line_height'=>'28',
    'after_content'=>'',
    'required'=>'',
    'validation_regex'=>'',
    'store_field'=>'',
    'enabled'=>'1',
);
$fields_data[] = $fields_row;
$fields_row = array(
    'id'=>'1',
    'label'=>'Swirl',
    'name'=>'color_type',
    'control'=>'r',
    'classes'=>'',
    'order'=>'3',
    'value'=>'2',
    'html_attribs'=>' onclick="update_color_select(this.value);"',
    'oncheck'=>'update_color_select(this.value);',
    'onuncheck'=>'',
    'row_height'=>'28',
    'label_line_height'=>'28',
    'control_line_height'=>'28',
    'after_line_height'=>'28',
    'after_content'=>'',
    'required'=>'',
    'validation_regex'=>'',
    'store_field'=>'',
    'enabled'=>'1',
);
$fields_data[] = $fields_row;
$fields_row = array(
    'id'=>'1',
    'label'=>'Segmented',
    'name'=>'color_type',
    'control'=>'r',
    'classes'=>'',
    'order'=>'4',
    'value'=>'3',
    'html_attribs'=>' onclick="update_color_select(this.value);"',
    'oncheck'=>'update_color_select(this.value);',
    'onuncheck'=>'',
    'row_height'=>'28',
    'label_line_height'=>'28',
    'control_line_height'=>'28',
    'after_line_height'=>'28',
    'after_content'=>'',
    'required'=>'',
    'validation_regex'=>'',
    'store_field'=>'',
    'enabled'=>'1',
);
$fields_data[] = $fields_row;

*/
$input = $_GET;
if($tpt_vars['environment']['request_method'] == 'post') {
	$input = $_POST;
}

$tpt_vars['template']['social_bar'] = '';

$tpt_jsurl = TPT_JS_URL;
$tpt_cssurl = TPT_CSS_URL;

//tpt_dump('asdasasdasd', true);
$data_module = getModule($tpt_vars, 'BandData');
$types_module = getModule($tpt_vars, 'BandType');
$wclass_module = getModule($tpt_vars, 'WritableClass');
$styles_module = getModule($tpt_vars, 'BandStyle');
$colors_module = getModule($tpt_vars, 'BandColor');
$sizes_module = getModule($tpt_vars, 'BandSize');
$pfields_module = getModule($tpt_vars, 'CustomProductField');
$layers_module = getModule($tpt_vars, 'BandPreviewLayer');
$builders_module = getModule($tpt_vars, 'Builder');
$url_builders = $builders_module->moduleData['url_id'];
    //tpt_dump($url_builders);
$id_builders = $builders_module->moduleData['id'];



$builder = array();
$builder_id = 0;
$url_id = 0;
$builder_title_new = '';
if(empty($_POST['short_builder'])) {
    $url_id = $tpt_vars['environment']['page_rule']['id'];
    $builder = $url_builders[$url_id];
    //tpt_dump($builder);
} else {
    $builder_id = intval($_POST['short_builder'], 10);
    $builder = $id_builders[$builder_id];
    $url_id = $builder['url_id'];
}

//var_dump($url_id);die();

if(!empty($builder)) {
    $builder_id = $builder['id'];

    //var_dump()
    $bta = $tpt_vars['db']['handler']->getData($tpt_vars, 'tpt_module_urlrules', 'html_title', '`id`='.$url_id);
    $builder_title_arr = reset($bta);
    $builder_title_new = $builder_title_arr['html_title'];


} else {
    $builder['type'] = 0;
    $builder['style'] = 0;
    $builder_title_new = $builder['label'] = 'Easy Silicone Wristband Builder';
    $builder['inhouse'] = 0;
    $builder['writable'] = 0;

    //$builder_id = 0;
    //$builder_title_new = '';
}

$inhouse = $builder['inhouse'];

$builder_title = $builder['label'];
$builder_breadcrumb = $builder['breadcrumb'];

if(empty($tpt_vars['template']['title']))
$tpt_vars['template']['title'] = $builder_title_new;




$cproduct = 0;
if(!empty($_GET['product'])) {
    if(!empty(amz_cart::$products[$_GET['product']])) {
        $cproduct = intval($_GET['product'], 10);
    }
}




//////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////





if(!empty($_GET['product_id'])) {
    $orders_module = getModule($tpt_vars, "Orders");
    $pin = $orders_module->createProductObject($tpt_vars, intval($_GET['product_id'], 10));
    //var_dump($pin);die();
    if(!empty($pin)) {
        $pin = reset($pin);
        $_GET = $pin->getDesignParams3($tpt_vars);
    }
}





$pgFullPreview = 1;
$pgEnableJavascript = 1;
$pgAjaxJavascript = 0;
$selected_type = '';
$initType = $builders_module->initBuilderType($tpt_vars, $builder);
extract($initType, EXTR_OVERWRITE);
/*
if(!empty($_POST['band_type'])) {
    $selected_type = intval($_POST['band_type'], 10);
    $pgAjaxJavascript = 1;
} else if(!empty($_GET['band_type'])) {
	$selected_type = intval($_GET['band_type'], 10);
}


$default_type = !empty($builder['writable'])?DEFAULT_WRITABLE_TYPE:DEFAULT_TYPE;
$default_type = !empty($builder['cl'])?DEFAULT_CL_TYPE:$default_type;
if(!empty($builder['type']) && (($builder_types = explode(',', $builder['type'])) > 1)) {
    //tpt_dump('asd', true);
    $default_type = reset($builder_types);
}



$pgType = (!empty($_GET['band_type'])?intval($_GET['band_type'], 10):$default_type);
if(!empty($selected_type)) {
    $pgType = $selected_type;
}

if(!empty($builder['type']) && (($builder_types = explode(',', $builder['type'])) > 1)) {
} else {
    $pgType = !empty($builder['type'])?$builder['type']:$pgType;
}

//tpt_dump($pgType, true);
*/

//$default_writable_style = reset($wclass_module->wrt[$pgType]);
//$default_writable_style = $default_writable_style['style'];
//$default_style = !empty($builder['writable'])?$default_writable_style:DEFAULT_STYLE;
//$default_style = !empty($builder['cl'])?DEFAULT_CL_TYPE:$default_style;
//$pgStyle = (!empty($_GET['band_style'])?intval($_GET['band_style'], 10):$default_style);


$pgWritableClass = 0;
//tpt_dump($pgType);
//tpt_dump($pgStyle);
//tpt_dump($_POST['writable_class']);
//tpt_dump($data_module->typeStyle[$pgType][$pgStyle],true);

if(!empty($builder['writable']) && empty($builder['inhouse'])) {
	if($_SERVER["REMOTE_ADDR"] == '120.63.19.200'){
		echo '1';
	}
        //$pgStyle = $wclass_module->wrt[$pgType]['style'];
        //tpt_dump($wclass_module->wrt[$pgType], true);
        //tpt_dump($pgStyle, true);
        //$pgStyle = $pgStyle['style'];
        //tpt_dump($pgStyle, true);
	if(empty($_POST['writable_class'])) {
		if($_SERVER["REMOTE_ADDR"] == '120.63.19.200'){
			echo '2-'.$pgType;
		}
		$selected_type = '';
		//tpt_dump($pgType, true);
		//$pgType = (!empty($_GET['band_type'])?intval($_GET['band_type'], 10):$default_type);
		$wtypes = $wclass_module->getWritableTypesFromType($tpt_vars, $pgType);
		if($_SERVER["REMOTE_ADDR"] == '120.63.19.200'){
			var_dump($wtypes);die();
		}

		$pgType__ = $pgType;
                //tpt_dump($wtypes[1]['id'], true);
		$selected_type = $pgType = $wtypes[1]['type'];
                $pgWritableClass = $wtypes[1]['writable_class'];
                
                
		if(!empty($_GET['band_style'])) {
			$pgWritableClass = $data_module->typeStyle[$pgType__][$_GET['band_style']]['writable_class'];
			$pgType = $pgType__;
		}

		//tpt_dump($selected_type, true);
	} else {
		if($_SERVER["REMOTE_ADDR"] == '120.63.19.200'){
			echo '3';
		}
		//var_dump($_POST['writable_class']);die();
		$pgWritableClass = intval($_POST['writable_class'], 10);
		$wtypes = $wclass_module->getWritableTypesFromType($tpt_vars, $pgType);

                //tpt_dump($pgWritableClass);
                //tpt_dump($wtypes[$pgWritableClass]['id']);
                //tpt_dump($wtypes[$pgWritableClass]['type']);
                //tpt_dump($wtypes[$pgWritableClass]['style'], true);
                if(!empty($wtypes[$pgWritableClass])) {
                //tpt_dump($pgWritableClass);
                //tpt_dump($wtypes, true);

                    $selected_type = $pgType = $wtypes[$pgWritableClass]['type'];
                    $pgStyle = $wtypes[$pgWritableClass]['style'];
                } else {
                    $ftype = reset($wtypes);
                    $selected_type = $pgType = $ftype['type'];
                    $pgStyle = $ftype['style'];
                }

		//var_dump($pgType);
		//var_dump($pgWritableClass);
		//die();
	}
	if($_SERVER["REMOTE_ADDR"] == '120.63.19.200'){
		echo '::' . $pgWritableClass;
	}
}



//tpt_dump($pgType);
//tpt_dump($selected_type);
//tpt_dump($selected_type);
//tpt_dump($pgStyle);
//tpt_dump($data_module->typeStyle[$pgType][$pgStyle],true);
//tpt_dump($pgWritableClass,true);

//var_dump($pgType);

//var_dump($pgWritableClass);
//die();

$pgStyle = (!empty($_GET['band_style'])?intval($_GET['band_style'], 10):(!empty($builder['inhouse'])?DEFAULT_INHOUSE_STYLE:DEFAULT_STYLE));
//var_dump($pgStyle);die();

$pgFont = (!empty($_GET['band_font'])?$_GET['band_font']:DEFAULT_FONT_ID);
$pgFrontRows = 0;
$pgBackRows = 0;
$pgTextCont = 1;
$pgBandColor = '-1:'.DEFAULT_BAND_COLOR;

$fm = false;
$bm = false;
$fm2 = false;
$bm2 = false;



//var_dump(array_filter(array($pgFrontMessage, $pgFrontMessage2)));
//var_dump(count(array_filter(array($pgBackMessage, $pgBackMessage2))));
//die();
if(!empty($_GET['band_color'])) {
    $pgBandColor = $_GET['band_color'];
    $tpt_vars['template_data']['footer_scripts']['content'][] = <<< EOT
    <script type="text/javascript">
        _short_tpt_pg_change_band_fill();
    </script>
EOT;
}
$pgMessageColor = '-1:'.DEFAULT_MESSAGE_COLOR;
//$pgMessageColor = '0:534';
if(!empty($_GET['message_color'])) {
    $pgMessageColor = $_GET['message_color'];
}



//var_dump($builder);die();
//var_dump($pgStyle);
$builder_styles = explode(',', $builder['style']);
if(!empty($builder['style'])) {
    if(count($builder_styles) > 1) {
        $pgStyle = reset($builder_styles);
    } else {
        $pgStyle = $builder['style'];
    }
}

//tpt_dump($pgType);
//tpt_dump($pgStyle,true);
if(empty($pgStyle))
	$pgStyle = DEFAULT_STYLE;


//var_dump($pgStyle);die();
$pgBandColorType = 0;
//var_dump($pgStyle);die();
/*if(($pgStyle == 7)) {
    $pgBandColorType = 4;
} else*/ if(!empty($_POST['color_type'])) {
    $pgBandColorType = intval($_POST['color_type'], 10);
}
if(!empty($_GET['featured'])) {
	$csearch = mysql_real_escape_string(strtolower(str_replace('-', '/', $_GET['band_color'])));
	$pgBandColor = $colors_module->getColorFromString($tpt_vars, $csearch, $pgType, $pgStyle, $builder['inhouse']);
	//var_dump($pgBandColor);die();
	//var_dump($pgStyle);die();
	if(($pgStyle == 7) || ($builder['style'] == 7)) {
		$pgMessageColor = $pgBandColor;
	}
	//$pgBandColor = $tpt_vars['db']['handler']->getData($tpt_vars, 'tpt_short_builders', '*', '`url_id`='.$url_id);

    $pgBandColorType = $colors_module->getColorSelectType($tpt_vars, $pgBandColor, $pgType, $pgStyle, $builder);
}


$selected_style = 0;
if(!empty($_POST['band_style'])) {
    $pgStyle = $selected_style = intval($_POST['band_style'], 10);
} else if(!empty($_GET['band_style'])) {
	$pgStyle = $selected_style = intval($_GET['band_style'], 10);
}
//tpt_dump($_POST['band_style']);
//tpt_dump($pgType, true);
//tpt_dump($pgStyle);
//tpt_dump($selected_style, true);
$pgCutAway = 0;
if(!empty($_POST['cut_away'])) {
    $pgStyle = 8;
    $pgCutAway = 1;
}
if(!empty($_GET['cut_away'])) {
    $pgStyle = 8;
    $pgCutAway = 1;
}
if(!empty($types_module->moduleData['id'][$pgType])) {

    $avstyles = explode(',', $types_module->moduleData['id'][$pgType]['available_styles_id']);
	//tpt_dump($builder['inhouse']);
	$avstyles = array_combine($avstyles, $avstyles);

    if(!empty($builder['inhouse'])) {
        //var_dump($avstyles);die();
        $avstyles = array_intersect_key($avstyles, array(6=>0, 7=>1, 8=>2, 11=>3, 12=>4, 17=>5));
        //var_dump($avstyles);die();
    }
	//tpt_dump($avstyles);
    //var_dump($avstyles);die();

	$tdata = $data_module->typeStyle[$pgType];
	$trow = $types_module->moduleData['id'][$pgType];
	//$avstyles = explode(',', $trow['available_styles_id']);
	//$avstyles = array_combine($avstyles, $avstyles);
	//tpt_dump($avstyles);
	//tpt_dump($tdata);
	$avstyles = array_intersect_key($avstyles, $tdata);
	//tpt_dump($avstyles);

    if(!in_array($pgStyle, $avstyles)) {
        $pgStyle = reset($avstyles);
		//tpt_dump($pgStyle);

        //$pgStyle = $selected_style;
        //var_dump($pgStyle);die();

        if(($pgStyle == 7)) {
            $pgBandColorType = 4;
        } else {
            $pgBandColorType = 1;
        }
    }

}
//tpt_dump($pgType);
//tpt_dump($pgStyle);


$lnum = (isset($data_module->typeStyle[$pgType][$pgStyle]['text_lines_num'])?$data_module->typeStyle[$pgType][$pgStyle]['text_lines_num']:1);
if(empty($pgFrontRows)) {
    $pgFrontRows = 1;
}
if(empty($pgBackRows)) {
    $pgBackRows = 1;
}
$tback = (isset($data_module->typeStyle[$pgType][$pgStyle]['text_back_msg'])?$data_module->typeStyle[$pgType][$pgStyle]['text_back_msg']:1);
$tcont = (isset($data_module->typeStyle[$pgType][$pgStyle]['text_continuous_msg'])?$data_module->typeStyle[$pgType][$pgStyle]['text_continuous_msg']:1);

if(($pgStyle == 7) && ($pgType != 5)) {
    $tcont = 0;
    $tback = 1;
}


// recent fIXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
//if(!empty($inhouse)) {
//	$tcont = 0;
//	$tback = 1;
//}

if(!empty($_GET['band_color']))
$pgBandColor = $_GET['band_color'];
if(!empty($_POST['band_color']))
$pgBandColor = $_POST['band_color'];

if(!empty($_POST['message_color']))
$pgMessageColor = $_POST['message_color'];
if(!empty($_GET['message_color']))
$pgMessageColor = $_GET['message_color'];

$pgconf = compact(
		'pgType',
		'pgStyle',
		'pgBandColor',
		'pgMessageColor'
		);
$pgBandColor = $colors_module->BandColor_Section_SB($tpt_vars, $pgconf, $builder, $pgBandColorType);
$pgBandColor = $pgBandColor['pgBandColor'];
//var_dump($pgMessageColor);die();
$pgMessageColor = $colors_module->MessageColor_Section_SB($tpt_vars, $pgMessageColor, $pgType, $pgStyle, $builder);
$pgMessageColor = $pgMessageColor['pgMessageColor'];
//var_dump($pgMessageColor);die();

if(($pgStyle == 8) || ($pgCutAway)) {
    $pgMessageColor = $pgBandColor;
}

$selectedFont = '';
if(!empty($_POST['BandFont'])) {
    $selectedFont = $pgFont = $_POST['band_font'];
} else if(!empty($_GET['band_font'])) {
	$selectedFont = $pgFont = $_GET['band_font'];
}


$pgFrontMessage = DEFAULT_MESSAGE_FRONT;
$pgBackMessage = '';
$pgFrontMessage2 = '';
$pgBackMessage2 = '';
//var_dump($pgStyle);die();

//$pgFrontMessage2 = DEFAULT_MESSAGE_FRONT2;
if($tback) {
    $pgBackMessage = DEFAULT_MESSAGE_BACK;
    //$pgBackMessage2 = DEFAULT_MESSAGE_BACK2;
}


//$pgFrontMessage = '';
if(isset($_GET['message_front'])) {
	$pgFrontMessage = $_GET['message_front'];
	if(!empty($_GET['message_front'])) {
		$fm = true;
	}
}

$pgClipartFrontLeft = (!empty($input['clipart_front_left'])?$input['clipart_front_left']:'');
@$pgClipartFrontLeft_c = $front_clipart_left_c = array_filter(array($input['flclipart_c'], $input['clipart_front_left_c']));
$pgClipartFrontLeft_c = $front_clipart_left_c = !empty($front_clipart_left_c)?reset($front_clipart_left_c):'';
$pgClipartFrontRight = (!empty($input['clipart_front_right'])?$input['clipart_front_right']:'');
@$pgClipartFrontRight_c = $front_clipart_right_c = array_filter(array($input['frclipart_c'], $input['clipart_front_right_c']));
$pgClipartFrontRight_c = $front_clipart_right_c = !empty($front_clipart_right_c)?reset($front_clipart_right_c):'';

//$pgFrontMessage2 = '';
if(($lnum > 1) && !empty($input['message_front2'])) {
	$pgFrontMessage2 = $input['message_front2'];
	$fm2 = true;
}
$pgClipartFrontLeft2 = (!empty($input['clipart_front_left2'])?$input['clipart_front_left2']:'');
@$pgClipartFrontLeft2_c = $front_clipart_left2_c = array_filter(array($input['flclipart2_c'], $input['fl2clipart_c'], $input['clipart_front_left2_c']));
$pgClipartFrontLeft2_c = $front_clipart_left2_c = !empty($front_clipart_left2_c)?reset($front_clipart_left2_c):'';
$pgClipartFrontRight2 = (!empty($input['clipart_front_right2'])?$input['clipart_front_right2']:'');
@$pgClipartFrontRight2_c = $front_clipart_right2_c = array_filter(array($input['frclipart2_c'], $input['fr2clipart_c'], $input['clipart_front_right2_c']));
//tpt_dump($front_clipart_right2_c);
$pgClipartFrontRight2_c = $front_clipart_right2_c = !empty($front_clipart_right2_c)?reset($front_clipart_right2_c):'';

//$pgBackMessage = '';
if(!empty($input['message_back'])) {
	$pgBackMessage = $input['message_back'];
	$bm = true;
}
$pgClipartBackLeft = (!empty($input['clipart_back_left'])?$input['clipart_back_left']:'');
@$pgClipartBackLeft_c = $back_clipart_left_c = array_filter(array($input['blclipart_c'], $input['clipart_back_left_c']));
$pgClipartBackLeft_c = $back_clipart_left_c = !empty($back_clipart_left_c)?reset($back_clipart_left_c):'';
$pgClipartBackRight = (!empty($input['clipart_back_right'])?$input['clipart_back_right']:'');
@$pgClipartBackRight_c = $back_clipart_right_c = array_filter(array($input['brclipart_c'], $input['clipart_back_right_c']));
$pgClipartBackRight_c = $back_clipart_right_c = !empty($back_clipart_right_c)?reset($back_clipart_right_c):'';

//$pgBackMessage2 = '';
if(($lnum > 1) && !empty($input['message_back2'])) {
	$pgBackMessage2 = $input['message_back2'];
	$bm2 = true;
}
$pgClipartBackLeft2 = (!empty($input['clipart_back_left2'])?$input['clipart_back_left2']:'');
@$pgClipartBackLeft2_c = $back_clipart_left2_c = array_filter(array($input['blclipart2_c'], $input['bl2clipart_c'], $input['clipart_back_left2_c']));
$pgClipartBackLeft2_c = $back_clipart_left2_c = !empty($back_clipart_left2_c)?reset($back_clipart_left2_c):'';
$pgClipartBackRight2 = (!empty($input['clipart_back_right2'])?$input['clipart_back_right2']:'');
@$pgClipartBackRight2_c = $back_clipart_right2_c = array_filter(array($input['brclipart2_c'], $input['br2clipart_c'], $input['clipart_back_right2_c']));
$pgClipartBackRight2_c = $back_clipart_right2_c = !empty($back_clipart_right2_c)?reset($back_clipart_right2_c):'';


//tpt_dump($pgClipartFrontRight_c);
//tpt_dump($pgClipartFrontRight2_c);
//tpt_dump($pgClipartBackRight2_c);

if(isset($_POST['tpt_pg_FrontMessage'])) {
    $pgFrontMessage = $_POST['tpt_pg_FrontMessage'];
    $fm = true;
}


if(isset($_POST['tpt_pg_BackMessage'])) {
    $pgBackMessage = $_POST['tpt_pg_BackMessage'];
    $bm = true;
}


if(($lnum > 1) && isset($_POST['tpt_pg_FrontMessage2'])) {
    $pgFrontMessage2 = $_POST['tpt_pg_FrontMessage2'];
    $fm2 = true;
}


if(($lnum > 1) && isset($_POST['tpt_pg_BackMessage2'])) {
    $pgBackMessage2 = $_POST['tpt_pg_BackMessage2'];
    $bm2 = true;
}


/*
if(empty($types_module->moduleData['id'][$pgType]['blank'])) {

}
*/

$rFrontMessage = $pgFrontMessage;
$rBackMessage = $pgBackMessage;

//var_dump($types_module->moduleData['id'][$pgType]['writable']);die();
if(!empty($data_module->typeStyle[$pgType][$pgStyle]['writable'])/* && ($pgFrontMessage == DEFAULT_MESSAGE_FRONT)*/) {
	if(($data_module->typeStyle[$pgType][$pgStyle]['writable'] == 1)/* && ($pgFrontMessage == DEFAULT_MESSAGE_FRONT)*/) {
	    $pgFrontMessage = '';
	} else if(($data_module->typeStyle[$pgType][$pgStyle]['writable'] == 2)) {
		if(($data_module->typeStyle[$pgType][$pgStyle]['writable_strip_position'] == 0)) {
			$pgFrontMessage = '';
		} else if(($data_module->typeStyle[$pgType][$pgStyle]['writable_strip_position'] == 3)) {
			$pgFrontMessage = '';
		} else if(($data_module->typeStyle[$pgType][$pgStyle]['writable_strip_position'] == 2)) {
			$pgFrontMessage = '';
			if(empty($pgBackMessage)) {
				$pgBackMessage = DEFAULT_MESSAGE_BACK;
			}
		} else {
			$pgBackMessage = '';
			if(empty($pgFrontMessage)) {
				$pgFrontMessage = DEFAULT_MESSAGE_FRONT;
			}
		}
	}
}

$dwb = false;
if(($pgType == 12) && (($pgBackMessage == DEFAULT_MESSAGE_BACK) || ($pgBackMessage == ''))) {
    $pgBackMessage = '';
    $dwb = true;
}


$dwbchck = '';
$bmdisplaycls = '';
if(!$dwb) {
    $dwbchck = 'checked="checked"';
} else {
    $bmdisplaycls = 'visibility-hidden';
}

/*
if(empty($data_module->typeStyle[$pgType][$pgStyle]['writable']) && !empty($_POST['r_front_message'])) {
    $pgFrontMessage = $_POST['r_front_message'];
}
if(empty($data_module->typeStyle[$pgType][$pgStyle]['writable']) && !empty($_POST['r_back_message'])) {
    $pgBackMessage = $_POST['r_back_message'];
}
*/
if(isset($_GET['message_span']))
$p_msg_span = $_GET['message_span'];
if(empty($_GET['message_span'])) {
    //tpt_dump($pgType, true);
    /*
    if(($pgType != 5) && empty($data_module->typeStyle[$pgType][$pgStyle]['writable']) && ($data_module->typeStyle[$pgType][$pgStyle]['base_type'] != 5)) {
        $filter = array_filter(array($bm, $bm2));
        if(!empty($filter)) {
            $pgTextCont = 0;
        } else {
            $pgTextCont = getModule($tpt_vars, "BandType")->moduleData['id'][$pgType]['text_continuous_msg'];
        }
    } else if(($pgType == 5)) {

        if ((empty($bm) || ($bm == DEFAULT_MESSAGE_BACK)) && (empty($bm2) || ($bm2 == DEFAULT_MESSAGE_BACK2))) {
            $pgTextCont = 1;
        }
    }
    */
} else {
    $tcont = intval($_GET['message_span'], 10);
}

//tpt_dump($tcont, true);
//tpt_dump($tback, true);
$pgTextCont = $tcont;
$pgTextBackMsg = $tback;
//var_dump($pgTextCont);die();


if(empty($_GET['front_rows'])) {
    $pgFrontRows = count(array_filter(array(true, $fm2)));
    //var_dump($pgFrontRows);die();
} else {
    $pgFrontRows = intval($_GET['front_rows'], 10);
}
if(empty($_GET['back_rows'])) {
    $pgBackRows = count(array_filter(array(true, $bm2)));
} else {
    $pgBackRows = intval($_GET['back_rows'], 10);
}


$pgSizesQty = array();
if(!empty($_GET['sizes']) && is_array($_GET['sizes'])) {
    $pgSizesQty = $_GET['sizes'];
}
//var_dump($pgSizesQty);die();

$pgAddons = array();
if(!empty($_GET['addons']) && is_array($_GET['addons'])) {
    $pgAddons = $_GET['addons'];
}





$pgconf = compact(
		'pgType',
		'pgStyle',
		'pgFont',
		'pgFrontRows',
		'pgBackRows',
		'pgTextCont',
		'pgTextBackMsg',
		'pgBandColor',
		'pgMessageColor',
                'pgFrontMessage',
                'pgClipartFrontLeft',
                'pgClipartFrontLeft_c',
                'pgClipartFrontRight',
                'pgClipartFrontRight_c',
                'pgFrontMessage2',
                'pgClipartFrontLeft2',
                'pgClipartFrontLeft2_c',
                'pgClipartFrontRight2',
                'pgClipartFrontRight2_c',
                'pgBackMessage',
                'pgClipartBackLeft',
                'pgClipartBackLeft_c',
                'pgClipartBackRight',
                'pgClipartBackRight_c',
                'pgBackMessage2',
                'pgClipartBackLeft2',
                'pgClipartBackLeft2_c',
                'pgClipartBackRight2',
                'pgClipartBackRight2_c',
                'pgCutAway',
                'pgFullPreview',
                'pgEnableJavascript',
                'pgAjaxJavascript',
		'fm',
		'fm2',
		'bm',
		'bm2'
		);


//tpt_dump($pgconf,true);

/*
 * Date: 31 July 2016
 * Modified to accept + in the message added by user
 * removed urldecode();
 * earlier: htmlentities(urldecode($pgFrontMessage))
 * */
$UDpgFrontMessage = htmlentities($pgFrontMessage);
$UDpgFrontMessage2 = htmlentities($pgFrontMessage2);
$UDpgBackMessage = htmlentities($pgBackMessage);
$UDpgBackMessage2 = htmlentities($pgBackMessage2);




























$fields_data = array();


//Message Style
/*
if(empty($builder['type'])) {
$fields_row = array(
    'id'=>'1',
    'label'=>'<div style="color: #669669;" class="todayshop-bold font-size-18 padding-top-5 padding-bottom-10">Message Style</div><div class="amz_brown font-size-14 font-weight-bold">'.$message_style.'</div>',
    'name'=>'',
    'control'=>'sec',
    'classes'=>' display-block float-right',
    'order'=>'',
    'value'=>'',
    'html_attribs'=>'',
    'oncheck'=>'',
    'onuncheck'=>'',
    'row_height'=>'',
    'label_line_height'=>'',
    'control_line_height'=>'',
    'after_line_height'=>'',
    'after_content'=>'',
    'required'=>'',
    'validation_regex'=>'',
    'store_field'=>'',
    'enabled'=>'',
);
} else {
*/
$fields_row = array(
    'id'=>'1',
    'label'=>'<div style="color: #669669;" class="todayshop-bold font-size-18 padding-top-5 padding-bottom-10">Product Type</div>',
    'name'=>'',
    'control'=>'sec',
    'classes'=>' display-block float-left',
    'order'=>'',
    'value'=>'{section_message_style:short-builder-section-product-type-message-style.tpt.php}',
    'html_attribs'=>'',
    'oncheck'=>'',
    'onuncheck'=>'',
    'row_height'=>'',
    'label_line_height'=>'',
    'control_line_height'=>'',
    'after_line_height'=>'',
    'after_content'=>'',
    'required'=>'',
    'validation_regex'=>'',
    'store_field'=>'',
    'enabled'=>'',
);
/*
}
*/
$fields_data[] = $fields_row;



// Band Color
$fields_row = array(
    'id'=>'1',
    'label'=>'<div style="color: #669669;text-align: left !important;" class="todayshop-bold font-size-18 padding-top-5 padding-bottom-10">Band Color</div>',
    'name'=>'',
    'control'=>'sec',
    'classes'=>' display-block float-right text-align-left',
    'order'=>'',
    'value'=>'{section_band_color:short-builder-section-band-color.tpt.php}',
    'html_attribs'=>'',
    'oncheck'=>'',
    'onuncheck'=>'',
    'row_height'=>'',
    'label_line_height'=>'',
    'control_line_height'=>'',
    'after_line_height'=>'',
    'after_content'=>'',
    'required'=>'',
    'validation_regex'=>'',
    'store_field'=>'',
    'enabled'=>'',
);
$fields_data[] = $fields_row;


// Message
$fields_row = array(
    'id'=>'1',
    'label'=>'<div style="color: #669669;" class="todayshop-bold font-size-18 padding-top-5 padding-bottom-10">Message</div>',
    'name'=>'',
    'control'=>'sec',
    'classes'=>' display-block float-left',
    'order'=>'',
    'value'=>'{section_message:short-builder-section-message.tpt.php}',
    'html_attribs'=>'',
    'oncheck'=>'',
    'onuncheck'=>'',
    'row_height'=>'',
    'label_line_height'=>'',
    'control_line_height'=>'',
    'after_line_height'=>'',
    'after_content'=>'',
    'required'=>'',
    'validation_regex'=>'',
    'store_field'=>'',
    'enabled'=>'',
);
$fields_data[] = $fields_row;

if(empty($data_module->typeStyle[$pgType][$pgStyle]['blank'])) {
//<a class="thickbox view-all-fonts float-right plain-link TBinline_900_500" href="javascript:/*TB_inline?width=900&amp;height=500*/">View All Fonts</a>
$add_font_title = <<< EOT
<div class="ccc_wr">
<a class="thickbox view-all-fonts float-right plain-link TBinline_900_500" href="javascript:;">View All Fonts</a>
</div>
EOT;
// Choose Font
$fields_row = array(
    'id'=>'1',
    'label'=>$add_font_title.'<div style="color: #669669;" class="todayshop-bold font-size-18 padding-top-5 padding-bottom-10">Choose Font</div>',
    'name'=>'',
    'control'=>'sec',
    'classes'=>' display-block float-right '.$bmdisplaycls,
    'order'=>'',
    'value'=>'{section_band_font:short-builder-section-band-font.tpt.php}',
    'html_attribs'=>' id="fontwrapper"',
    'oncheck'=>'',
    'onuncheck'=>'',
    'row_height'=>'',
    'label_line_height'=>'',
    'control_line_height'=>'',
    'after_line_height'=>'',
    'after_content'=>'',
    'required'=>'',
    'validation_regex'=>'',
    'store_field'=>'',
    'enabled'=>'',
);
$fields_data[] = $fields_row;

$ajax_call = tpt_ajax::getCall('clipart.get_clipart_selects');
$ccchk = (
	!empty($pgClipartFrontLeft) ||
	!empty($pgClipartFrontLeft_c) ||
	!empty($pgClipartFrontRight) ||
	!empty($pgClipartFrontRight_c) ||
	!empty($pgClipartBackLeft) ||
	!empty($pgClipartBackLeft_c) ||
	!empty($pgClipartBackRight) ||
	!empty($pgClipartBackRight_c) ||
	!empty($pgClipartFrontLeft2) ||
	!empty($pgClipartFrontLeft2_c) ||
	!empty($pgClipartFrontRight2) ||
	!empty($pgClipartFrontRight2_c) ||
	!empty($pgClipartBackLeft2) ||
	!empty($pgClipartBackLeft2_c) ||
	!empty($pgClipartBackRight2) ||
	!empty($pgClipartBackRight2_c)
);
$enable_clipart = tpt_html::createCheckbox($tpt_vars, 'enable_clipart'/*name*/, '1'/*control value*/, $ccchk, ' onclick="try{enable_clipart_cb_init_f()}catch(e){};toggle_artwork(this);" id="enable_clipart_cb"'/*html attribs*/, ''/*oncheck*/);
// Add Artwork
/*
$fields_row = array(
    'id'=>'1',
    'label'=>'<div class="clearFix"><div style="color: #669669;" class="float-left todayshop-bold font-size-18 padding-top-5 padding-bottom-10">Add Artwork?</div><div class="padding-left-40 float-left amz_brown font-size-14 font-weight-bold padding-top-5 padding-bottom-10 arial-black">Yes&nbsp;'.$enable_clipart.'</div></div>',
    'name'=>'',
    'control'=>'sec',
    'classes'=>' display-block float-left '.$bmdisplaycls,
    'order'=>'',
    'value'=>'{section_add_artwork:short-builder-section-add-artwork.tpt.php}',
    'html_attribs'=>' id="awwrapper"',
    'oncheck'=>'',
    'onuncheck'=>'',
    'row_height'=>'',
    'label_line_height'=>'',
    'control_line_height'=>'',
    'after_line_height'=>'',
    'after_content'=>'',
    'required'=>'',
    'validation_regex'=>'',
    'store_field'=>'',
    'enabled'=>'',
);
*/
######################################################
######################################################
######################################################
//if ($_SERVER['REMOTE_ADDR']=='85.130.3.155' || preg_match('#Debossed\-Wristbands\-test#',$_SERVER['REQUEST_URI']) ) {
$fields_row = array(
    'id'=>'1',
    'label'=>'<div class="clearFix"><div style="color: #669669;" class="float-left todayshop-bold font-size-18 padding-top-5 padding-bottom-10">Add Artwork?</div><div class="padding-left-40 float-left amz_brown font-size-14 font-weight-bold padding-top-5 padding-bottom-10 arial-black">Yes&nbsp;'.$enable_clipart.'</div></div>',
    'name'=>'',
    'control'=>'sec',
    'classes'=>' display-block float-left '.$bmdisplaycls,
    'order'=>'',
    'value'=>'{section_add_artwork:short-builder-section-add-artwork-MU.tpt.php}',
    'html_attribs'=>' id="awwrapper"',
    'oncheck'=>'',
    'onuncheck'=>'',
    'row_height'=>'',
    'label_line_height'=>'',
    'control_line_height'=>'',
    'after_line_height'=>'',
    'after_content'=>'',
    'required'=>'',
    'validation_regex'=>'',
    'store_field'=>'',
    'enabled'=>'',
);
######################################################
######################################################
######################################################



$fields_data[] = $fields_row;
}

////// NEWEST SECTION ADDONS
//SEPARATOR
/*
// Message Color
if ($url_id != '180' && $url_id != '178' && $url_id != '183') //A�� ������� � embossed, debossed ��� dual layer �� ��������� message color ������� � � ������� ������ ������� �� �� �������� layout-a
{
    $fields_row = array(
        'id'=>'1',
        'label'=>'<div id="msg_col_title" style="color: #669669;" class="todayshop-bold font-size-18 padding-top-5 padding-bottom-10">Message Color</div>',
        'name'=>'',
        'control'=>'sec',
        'classes'=>' display-block float-right',
        'order'=>'',
        'value'=>'{section_message_color:short-builder-section-message-color.tpt.php}',
        'html_attribs'=>'',
        'oncheck'=>'',
        'onuncheck'=>'',
        'row_height'=>'',
        'label_line_height'=>'',
        'control_line_height'=>'',
        'after_line_height'=>'',
        'after_content'=>'',
        'required'=>'',
        'validation_regex'=>'',
        'store_field'=>'',
        'enabled'=>'',
    );
    $fields_data[] = $fields_row;
}
else
{
*/
    $fields_row = array(
        'id'=>'1',
        'label'=>'<div style="color: #669669;" class="todayshop-bold font-size-18 padding-top-5 padding-bottom-10">Add Ons</div>',
        'name'=>'',
        'control'=>'sec',
        'classes'=>' display-block float-right',
        'order'=>'',
        'value'=>'{section_add_ons:short-builder-section-add-ons.tpt.php}',
        'html_attribs'=>'',
        'oncheck'=>'',
        'onuncheck'=>'',
        'row_height'=>'',
        'label_line_height'=>'',
        'control_line_height'=>'',
        'after_line_height'=>'',
        'after_content'=>'',
        'required'=>'',
        'validation_regex'=>'',
        'store_field'=>'',
        'enabled'=>'',
    );
    $fields_data[] = $fields_row;
/*
}
*/

// Comments/Design Idea
$fields_row = array(
    'id'=>'1',
    'label'=>'<div style="color: #669669;" class="todayshop-bold font-size-18 padding-top-5 padding-bottom-10">Comments/Design Idea</div>',
    'name'=>'',
    'control'=>'sec',
    'classes'=>' display-block float-left',
    'order'=>'',
    'value'=>'{section_comments_design_idea:short-builder-section-comments-design-idea.tpt.php}',
    'html_attribs'=>'',
    'oncheck'=>'',
    'onuncheck'=>'',
    'row_height'=>'',
    'label_line_height'=>'',
    'control_line_height'=>'',
    'after_line_height'=>'',
    'after_content'=>'',
    'required'=>'',
    'validation_regex'=>'',
    'store_field'=>'',
    'enabled'=>'',
);
$fields_data[] = $fields_row;

// Size and Quantity
$rush_order_html = '';
if(!empty($builder['id']) && !empty($builder['rush_order'])) {
    $rush_order_select = getModule($tpt_vars, 'RushOrder')->RushOrder_Select($tpt_vars);
    $rush_order_html = <<< EOT
    <div style="color: #669669;" class="todayshop-bold font-size-18 padding-top-5 padding-bottom-10">Rush Order</div>
    <div style="" class="padding-top-5 padding-bottom-10">
    $rush_order_select
    </div>
EOT;

}

$size_guide_link = '
<a rel="nofollow" href="amz-sizeguide-popup.php?KeepThis=true&amp;TB_iframe=true&amp;height=600&amp;width=778" title="Amazing Wristbands Sizing Guide" class="thickbox plain-link" style="float:right;font-size:12px;font-weight:normal !important;font-family:Arial,Helvetica,sans-serif;position:absolute;right:0;margin-top:-3px;">Size guide</a>
';
$size_guide_link2 = '
<a rel="nofollow" href="amz-sizeguide-popup.php?KeepThis=true&amp;TB_iframe=true&amp;height=600&amp;width=778" title="Amazing Wristbands Sizing Guide" class="thickbox plain-link" style="float:right;font-size:12px;font-weight:normal !important;font-family:Arial,Helvetica,sans-serif;position:absolute;right:0;top:-10px;">Size guide</a>
';

$fields_row = array(
    'id'=>'1',
    'label'=>$rush_order_html.'<div id="bulkpricingmessage" style="background-color:#F1EDE9;position:relative;z-index:2;" class="visibility-hidden overflow-visible color-red font-weight-bold font-size-14 font-style-italic float-right padding-top-5 padding-bottom-10">(You are getting bulk pricing!)'.$size_guide_link2.'</div><div style="color: #669669;" class="position-relative todayshop-bold font-size-18 padding-top-5 padding-bottom-10">Size and Quantity'.$size_guide_link.'</div>',
    'name'=>'',
    'control'=>'sec',
    'classes'=>' display-block float-right',
    'order'=>'',
    'value'=>'{short-builder-qty-fields.tpt.php}',
    'html_attribs'=>'',
    'oncheck'=>'',
    'onuncheck'=>'',
    'row_height'=>'',
    'label_line_height'=>'',
    'control_line_height'=>'',
    'after_line_height'=>'',
    'after_content'=>'',
    'required'=>'',
    'validation_regex'=>'',
    'store_field'=>'',
    'enabled'=>'',
);
$fields_data[] = $fields_row;


include(TPT_TEMPLATES_DIR.DIRECTORY_SEPARATOR.'form-fields1.tpt.php');
//var_dump($form_fields);die();
























//var_dump($form_fields);
//$builder_html = $form_fields;
$builder_html = preg_replace('#(<div class="tpt_form_section[^"]*float-left[^"]*"[\s]*(id="[^"]*")?[\s]*>.*?<div class="tpt_form_section[^"]*float-right[^"]*"[\s]*(id="[^"]*")?[\s]*>.*?</div>[^<]*)<div class="tpt_form_section"#si', '<div class="tpt_form_sections_row clearFix">$1</div><div class="tpt_form_section"', $form_fields);
//var_dump($builder_html);
//die();

ob_start();
include(TPT_TEMPLATES_DIR.DIRECTORY_SEPARATOR.'short-builder-section-custom-box.tpt.php');
$custombox_html = ob_get_clean();
$tpt_vars['template_data']['footer_scripts']['content'][] = <<< EOT
$custombox_html
EOT;
/*
$dummies = array();
$dummies[] = $fl_select_dummy = getModule($tpt_vars, "BandClipart")->Clipart_Select_Dummy($tpt_vars, 0, 'lclipart');
$dummies[] = $fr_select_dummy = getModule($tpt_vars, "BandClipart")->Clipart_Select_Dummy($tpt_vars, 0, 'rclipart');
$dummies[] = $fl2_select_dummy = getModule($tpt_vars, "BandClipart")->Clipart_Select_Dummy($tpt_vars, 0, 'lclipart2');
$dummies[] = $fr2_select_dummy = getModule($tpt_vars, "BandClipart")->Clipart_Select_Dummy($tpt_vars, 0, 'rclipart2');
$dummies[] = $bl_select_dummy = getModule($tpt_vars, "BandClipart")->Clipart_Select_Dummy($tpt_vars, 0, 'blclipart');
$dummies[] = $br_select_dummy = getModule($tpt_vars, "BandClipart")->Clipart_Select_Dummy($tpt_vars, 0, 'brclipart');
$dummies[] = $bl2_select_dummy = getModule($tpt_vars, "BandClipart")->Clipart_Select_Dummy($tpt_vars, 0, 'blclipart2');
$dummies[] = $br2_select_dummy = getModule($tpt_vars, "BandClipart")->Clipart_Select_Dummy($tpt_vars, 0, 'brclipart2');

$selects = array();
$selects[] = $fl_select = getModule($tpt_vars, "BandClipart")->Clipart_Select($tpt_vars, 0, 'lclipart', 'Select Front Left Clipart');
$selects[] = $fr_select = getModule($tpt_vars, "BandClipart")->Clipart_Select($tpt_vars, 0, 'rclipart', 'Select Front Right Clipart');
$selects[] = $fl2_select = getModule($tpt_vars, "BandClipart")->Clipart_Select($tpt_vars, 0, 'lclipart2', 'Select Front Left Ln2 Clipart');
$selects[] = $fr2_select = getModule($tpt_vars, "BandClipart")->Clipart_Select($tpt_vars, 0, 'rclipart2', 'Select Front Right Ln2 Clipart');
$selects[] = $bl_select = getModule($tpt_vars, "BandClipart")->Clipart_Select($tpt_vars, 0, 'blclipart', 'Select Back Left Clipart');
$selects[] = $br_select = getModule($tpt_vars, "BandClipart")->Clipart_Select($tpt_vars, 0, 'brclipart', 'Select Back Right Clipart');
$selects[] = $bl2_select = getModule($tpt_vars, "BandClipart")->Clipart_Select($tpt_vars, 0, 'blclipart2', 'Select Back Left Ln2 Clipart');
$selects[] = $br2_select = getModule($tpt_vars, "BandClipart")->Clipart_Select($tpt_vars, 0, 'brclipart2', 'Select Back Right Ln2 Clipart');

foreach($dummies as $i=>$dummy) {
$builder_html = str_replace($dummies[$i], $selects[$i], $builder_html);
}
*/
//$builder_html = $form_fields;
//var_dump($form_fields);die();
//var_dump($builder_html);die();


if(((isDev('rebuildcontent') && !empty($_GET['rebuildcontent'])))) {
	$types_json = $types_module->userEndData($tpt_vars);
	$styles_json = $styles_module->userEndData($tpt_vars);
	$sizes_json = $sizes_module->userEndData($tpt_vars);
	$data_json = $data_module->userEndData($tpt_vars);

	$pfields_json = $pfields_module->userEndData($tpt_vars);
	$layers_json = $layers_module->userEndData($tpt_vars);

	$colors_data = $colors_module->userEndData($tpt_vars);
	$colors_json = $colors_data['stock_to_custom'];
	$default_json = $colors_data['default'];
	$dual_layers_json = $colors_data['dual_layers'];
	$solids_hex = $colors_data['solids_hex'];
	$json = 'var stockToCustomColors = [];'."\n";
	$json .= 'stockToCustomColors[3] = JSON.parse("'.addslashes(json_encode($colors_json[3])).'");'."\n";
	$json .= 'stockToCustomColors[4] = JSON.parse("'.addslashes(json_encode($colors_json[4])).'");'."\n";
	$json .= 'stockToCustomColors[5] = JSON.parse("'.addslashes(json_encode($colors_json[5])).'");'."\n";
	$json .= 'stockToCustomColors[6] = JSON.parse("'.addslashes(json_encode($colors_json[6])).'");'."\n";
	$json .= 'dualLayerData = JSON.parse("'.addslashes(json_encode($dual_layers_json)).'");'."\n";
	$json .= 'defaultData = JSON.parse("'.addslashes(json_encode($default_json)).'");'."\n";

	$json .= 'var solidColorsHEX = JSON.parse("'.addslashes(json_encode($solids_hex)).'");'."\n";

	$json .= 'var typesData = JSON.parse("'.addslashes(json_encode($types_json)).'");'."\n";
	$json .= 'var stylesData = JSON.parse("'.addslashes(json_encode($styles_json)).'");'."\n";
	$json .= 'var sizesData = JSON.parse("'.addslashes(json_encode($sizes_json)).'");'."\n";

	$json .= 'var bandData = JSON.parse("'.addslashes(json_encode($data_json)).'");'."\n";

	$json .= 'var fieldsData = JSON.parse("'.addslashes(json_encode($pfields_json)).'");'."\n";
	$json .= 'var layersData = JSON.parse("'.addslashes(json_encode($layers_json)).'");'."\n";

	$builder_json = <<< EOT
$json
var floatingPGPreview = false;
EOT;

	file_put_contents(TPT_JS_DIR.DIRECTORY_SEPARATOR.'builder_json.js', $builder_json);
}

/*
<!--[if lt IE 7]>
 <script src="http://ie7-js.googlecode.com/svn/version/2.0(beta3)/IE7.js"
 type="text/javascript">
 </script>
 <script src="http://ie7-js.googlecode.com/svn/version/2.0(beta3)/IE7-squish.js"
 type="text/javascript">
 </script>
<![endif]-->
 */
//<link type="text/css" rel="stylesheet" href="$tpt_cssurl/wide_layout.css" />
//<link type="text/css" rel="stylesheet" href="$tpt_cssurl/short_builder.css" />

$section_builder_style = <<< EOT

<style type="text/css">
body .outer-wrapper {
    padding-right: 0px !important;
    width: 1000px !important;
}
.main-content .content {
    width: 788px !important;
}
.short_builder_wrapper>div>div>.tpt_form_section {
    display: none;
}
.tpt_form_section {
    float: left;
}
.tpt_form_section.float-left>div, .tpt_form_section.float-right>div {
    padding: 20px;
}
.tpt_form_section.float-left, .tpt_form_section.float-right {
    width: 50%;
    text-align: left;
}
.tpt_form_sections_row {
    border-bottom: 1px solid #E2DEDA;    
}
.tpt_form_section.float-left>div {
    border-right: 1px solid #E2DEDA;
}
.tpt_form_section.float-right>div {
    border-left: 1px solid #E2DEDA;
    margin-left: -1px;
}
.tpt_form_section.float-left {
}
.tpt_form_section.float-right {
}
</style>
EOT;

$tpt_vars['template_data']['head'][] = $section_builder_style;

$tpt_vars['template_data']['head'][] = <<< EOT
<link type="text/css" rel="stylesheet" href="$tpt_cssurl/short_builder2.css" />
<script type="text/javascript" src="$tpt_jsurl/short-builder-jsval.js" defer></script>
<script type="text/javascript" src="$tpt_jsurl/builder_json.js"></script>

<script type="text/javascript">
//<![CDATA[
$(function(){
  var images_name = "";
  var btnUpload=$('#upload');
  var status=$('#status');

  new AjaxUpload(btnUpload, {
   action: base_url+'/upload-file.php',
   name: 'uploaded',
   onSubmit: function(file, ext){

	//   console.log(file);

     if (! (ext && /^(png|jpe?g|pdf|bmp|gif|eps|svg|tiff?|tga|ico|psd|ai)$/.test(ext))){
                    // extension is not allowed
     status.text('Only image files are allowed.');
     return false;
    }
    $('#files').html('');
    status.text('Uploading...');
   },
   onComplete: function(file, response){
    //On completion clear the status
    status.text('');
    //Add uploaded file to list

    if(response==="success" || 'success&lt;div id="LCS_336D0C35_8A85_403a_B9D2_65C292C39087_communicationDiv"&gt;&lt;/div&gt;')
	{
		var splitstr = response.split('|');

		file = splitstr[1];
      images_name += file +',';

     try{
      document.getElementById('custom_clipart').value=file;
     }catch(e){}

     //alert(images_name);
	 con = document.getElementById('files');

     con.innerHTML = file;
     if (document.getElementById('convert_clipart_check_div'))
     {
        document.getElementById('convert_clipart_check_div').className = 'display-block';
     }

	 //document.getElementById('custom_art').style.display='none';

    } else if(response==="toobig") {
     $('<li></li>').appendTo('#files').text('Please use an image that is less than 5 Megabytes in size.').addClass('error');

    } else{
     $('<li></li>').appendTo('#files').text(response).addClass('error');
    }
   }
  });
 });
//]]>
</script>


<script defer="defer" type="text/javascript" src="$tpt_jsurl/short_builder.js"></script>
EOT;


/*
######################################################
######################################################
######################################################
if ($_SERVER['REMOTE_ADDR']=='85.130.3.155') {
$tpt_vars['template_data']['head'][] = <<< EOT
<script type="text/javascript" src="$tpt_baseurl/js/multiupload/jquery.knob.js"></script>
<script type="text/javascript" src="$tpt_baseurl/js/multiupload/jquery.ui.widget.js"></script>
<script type="text/javascript" src="$tpt_baseurl/js/multiupload/jquery.iframe-transport.js"></script>
<script type="text/javascript" src="$tpt_baseurl/js/multiupload/jquery.fileupload.js"></script>
<script type="text/javascript" src="$tpt_baseurl/js/multiupload/script.js"></script>

EOT;
}
######################################################
######################################################
######################################################
*/




$tpt_vars['template_data']['head'][1] = '<script defer="defer" type="text/javascript" src="'.$tpt_jsurl.'/json2.js"></script>'.$tpt_vars['template_data']['head'][1];
$tpt_vars['template_data']['head'][] = <<< EOT
<script type="text/javascript">
    document.addEventListener("DOMContentLoaded", function(event) {
        $('#main_content').on('click','a.read-more',function(e){
            $(this).siblings('.more-descr').slideToggle(1000);
            if ($(this).text().match(/more/)) {
                $(this).html('Hide...');
            } else {
                $(this).html('Read more...');
            }
        });
    });
</script>
EOT;

$builder_addtocart_button = amz_cart::addToCartFormCustom($tpt_vars, 'add_to_cart', false, 'validate_short_builder');
// addind validation to the add to cart
//$builder_addtocart_button = preg_replace('#onclick="#','onclick="if(!validate_designset())return;',$builder_addtocart_button);

$base_url = BASE_URL;

$tpt_vars['template_data']['footer_scripts']['content'][] = <<< EOT
<script src="$tpt_jsurl/upload.js" type="text/javascript"></script>
EOT;

$tpt_vars['template']['quote_link'] = '';
	//tpt_dump($pgClipartFrontLeft_c);
include(dirname(__FILE__).DIRECTORY_SEPARATOR.'html'.DIRECTORY_SEPARATOR.'short-builder-main.tpt.php');




}


