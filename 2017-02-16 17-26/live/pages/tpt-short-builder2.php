<?php

defined('TPT_INIT') or die('access denied');

$cpf_module = getModule($tpt_vars, 'CustomProductField');
$addons_module = getModule($tpt_vars, 'BandAddon');
$class_module = getModule($tpt_vars, 'BandClass');
$wclass_module = getModule($tpt_vars, 'WritableClass');
$colors_module = getModule($tpt_vars, 'BandColor');
$orders_module = getModule($tpt_vars, 'Orders');
$status_module = getModule($tpt_vars, 'OrderStatus');
$tracking_module = getModule($tpt_vars, 'TrackingInfo');
$types_module = getModule($tpt_vars, 'BandType');
$font_module = getModule($tpt_vars, 'BandFont');
$messages_module = getModule($tpt_vars, 'BandMessage');
$messages = $messages_module->moduleData['pname'];
$clipart_module = getModule($tpt_vars, 'BandClipart');
$ccat_module = getModule($tpt_vars, 'BandClipartCategory');
$ttable = $types_module->moduleTable;
$styles_module = getModule($tpt_vars, 'BandStyle');
$stable = $styles_module->moduleTable;
$sizes_module = getModule($tpt_vars, 'BandSize');
$sztable = $sizes_module->moduleTable;
$data_module = getModule($tpt_vars, 'BandData');
//$layers_module = getModule($tpt_vars, 'BandPreviewLayer');
$builders_module = getModule($tpt_vars, 'Builder');
$bsection_module = getModule($tpt_vars, 'BuilderSection');
//$otbl = ORDERS_TABLE;
//$ptbl = ORDERS_PRODUCTS_TABLE;


$tpt_jsurl = TPT_JS_URL;
$tpt_cssurl = TPT_CSS_URL;
$tpt_imagesurl = TPT_IMAGES_URL;
$action_url = $vars['url']['handler']->wrap($vars, '/cart_addproduct');

$db = $tpt_vars['db']['handler'];



$sections = array();



if(!isset($options)) {
	$options = $builder = $builders_module->getBuilder($tpt_vars);
}
$builder_id = $builder['id'];
tpt_dump($builder_id, false, 'V');




//tpt_dump($builder, false, 'R');




// init pgconf
/*
$input = array(
	'type'=>0,
	'style'=>0,
	'font'=>DEFAULT_FONT_ID,
	'msg1'=>'Front Message',
	'msg3'=>'',
	'msg2'=>'',
	'msg4'=>'',
);
*/



if(!isset($input)) {
	$input = array_replace($_GET, $_POST);
}

////////////////////// get builder band data and sections
$type = $types_module->getActiveItem($tpt_vars, $input, $options);
$style = $styles_module->getActiveItem($tpt_vars, $input, $options);

$bdata = $data_module->typeStyle[$type][$style];

$scs = explode(',', $bdata['builder_sections']);
$scs = array_combine($scs, $scs);
$scs = array_intersect_key($bsection_module->moduleData['id'], $scs);
$section_pnames = array();
foreach($scs as $sid=>$sc) {
	if(isset($messages[$sc['pname']]) && empty($messages[$sc['pname']]['line2'])) {
		$section_pnames[] = $sc['pname'];
	}
}
////////////////


$defaults = $cpf_module->getDefaults($tpt_vars, $input, $options);
//tpt_dump($defaults, true, 'R');

$actives = $_input = $defaults;
foreach($messages as $msg=>$msgdata) {
	if(!in_array($msg, $section_pnames)) {
		unset($actives[$msg]);
	}
	unset($_input[$msg]);
}
$actives = array_replace($actives, $input);

$_input = array_fill_keys(array_keys($_input), null);
$input = array_replace($_input, $input);
/*
//unset($actives['msg1']);
unset($actives['msg2']);
unset($actives['msg3']);
unset($actives['msg4']);
*/

//tpt_dump($defaults);

//unset($_input['msg1']);
//unset($_input['msg2']);
//unset($_input['msg3']);
//unset($_input['msg4']);

tpt_dump($input, false, 'R');

if($builder_id == '25'){
	if($input['style'] == NULL) {
		$input['style'] = 19;
	}

	/*if($input['type'] == NULL) {
		$input['type'] = 37;
	}*/
}



//tpt_dump($input, true);

$vinput = $input;


$content = '';


$html = '';
$fpath = TPT_TEMPLATES_DIR . DIRECTORY_SEPARATOR . 'short-builder-head.tpt.php';
$evars = tpt_functions::f_get_defined_vars($tpt_vars, get_defined_vars());
$fvars = tpt_functions::f_include_once($tpt_vars, $fpath, $evars);
extract($fvars, EXTR_OVERWRITE);
$content .= $html;

$html = '';
$fpath = TPT_TEMPLATES_DIR . DIRECTORY_SEPARATOR . 'short-builder-breadcrumbs.tpt.php';
$evars = tpt_functions::f_get_defined_vars($tpt_vars, get_defined_vars());
$fvars = tpt_functions::f_include_once($tpt_vars, $fpath, $evars);
extract($fvars, EXTR_OVERWRITE);
$breadcrumbs = $html;

$html = '';
$fpath = TPT_TEMPLATES_DIR . DIRECTORY_SEPARATOR . 'short-builder-description.tpt.php';
$evars = tpt_functions::f_get_defined_vars($tpt_vars, get_defined_vars());
$fvars = tpt_functions::f_include_once($tpt_vars, $fpath, $evars);
extract($fvars, EXTR_OVERWRITE);
$description = $html;

$html = '';
$fpath = TPT_TEMPLATES_DIR . DIRECTORY_SEPARATOR . 'short-builder-controls.tpt.php';
$evars = tpt_functions::f_get_defined_vars($tpt_vars, get_defined_vars());
$fvars = tpt_functions::f_include_once($tpt_vars, $fpath, $evars);
//tpt_dump($fvars, true);
extract($fvars, EXTR_OVERWRITE);
$content .= $html;

$html = '';
$fpath = TPT_TEMPLATES_DIR . DIRECTORY_SEPARATOR . 'short-builder-preview.tpt.php';
$evars = tpt_functions::f_get_defined_vars($tpt_vars, get_defined_vars());
$fvars = tpt_functions::f_include_once($tpt_vars, $fpath, $evars);
extract($fvars, EXTR_OVERWRITE);
$preview = $html;
//tpt_dump($preview);
//tpt_dump($html);

//tpt_dump($preview);

$content .= implode("\n", $sections);

$builder_addtocart_button = $builders_module->add_products_button($tpt_vars, 'add_to_cart', 'validate_short_builder2');

//tpt_dump($preview);
$tpt_vars['template']['quote_link'] = '';
$tpt_vars['template']['quote_link'] = <<< EOT
<div class="text-align-center font-size-55 amz_red badaboombb">START YOUR DESIGN!</div>
EOT;
$tpt_vars['template']['content'] = <<< EOT
<div class="padding-top-0 padding-right-25">
$breadcrumbs
</div>
<div class="padding-top-0 padding-left-25 padding-right-25 padding-bottom-25">
$description
</div>
<div class="display-inline-block">
$preview
</div>
<div id="loading_preview" class="font-size-15 todayshop-bolditalic visibility-hidden" style="text-shadow: 2px 0 4px #f1ede9, -2px 0 4px #f1ede9, 4px 0 4px #f1ede9, -4px 0 4px #f1ede9, 0 0 4px #f1ede9;letter-spacing: 2px;color: #5c3925;">
	Generating Preview... <img height="15" width="15" src="$tpt_imagesurl/GP.gif">
</div>
<div class="short_builder_wrapper padding-left-20 padding-right-20">
	<form id="short_builder_form" autocomplete="off" method="post" action="$action_url" accept-charset="utf-8" class="">
		<div class="clearFix">
			$content
		</div>
		<div class="text-align-right">
			$builder_addtocart_button
		</div>
		<input type="hidden" name="short_builder" value="$builder_id" />
	</form>
</div>
EOT;

$content = '';
