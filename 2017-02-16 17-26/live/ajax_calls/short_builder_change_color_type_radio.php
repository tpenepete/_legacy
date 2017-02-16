<?php

defined('TPT_INIT') or die('access denied');


$builders_module = getModule($tpt_vars, 'Builder');
$colors_module = getModule($tpt_vars, 'BandColor');
$sections_module = getModule($tpt_vars, 'BuilderSection');
$sections = $sections_module->moduleData['id'];
$data_module = getModule($tpt_vars, 'BandData');
$data = $data_module->typeStyle;
$cpf_module = getModule($tpt_vars, 'CustomProductField');
$cpfs = $cpf_module->moduleData['id'];

//$tpt_vars['environment']['ajax_result']['update_elements'][$elm_id]
if(!isset($input)) {
	$input = array_replace($_GET, $_POST);
}



//tpt_dump($input, true);

/*
foreach($input['fields'] as $key=>$value) {
	if(!empty($cpfs[$key])) {
		$data[$cpfs[$key]['pname']] = $value;
	}
}
*/
//tpt_dump($product, true);

$sColorType = !empty($input['color_type'])?reset($input['color_type']):null;
$sid = key($input['color_type']);
$section = $sections[$sid];
//$subtotal = reset($input['subtotal']);
//$shipping = reset($input['shipping']);
//$discount = reset($input['discount']);
//$tax = reset($input['tax']);


//$product['price_subtotal'] = $subtotal;
//$product['price_shipping'] = $shipping;
//$product['price_discount'] = $discount;
//$product['price_tax'] = $tax;

$options = $builders_module->getBuilder($tpt_vars);


////////////////////// get builder band data and sections
//$type = $types_module->getActiveItem($vars, $input, $options);
//$style = $cStyle = $styles_module->getActiveItem($vars, $input, $options);

//$bdata = $data_module->typeStyle[$type][$style];

//$cType = (!empty($data[$type][$style]['base_type'])?$data[$type][$style]['base_type']:(!empty($type)?$style:0));
//$cData = (!empty($data[$cType][$cStyle])?$data[$cType][$cStyle]:array());

//$pgBandColor = $colors_module->getSelectedItem($tpt_vars, $input, $options);

$bcsel = $colors_module->SB_Section($vars, $section, $input, $options);
$tpt_vars['environment']['ajax_result']['update_elements']['control_wrapper_'.$sid] = $bcsel['content'];