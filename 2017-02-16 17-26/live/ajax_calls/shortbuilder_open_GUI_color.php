<?php

defined('TPT_INIT') or die('access denied');


$color_module = getModule($tpt_vars, 'BandColor');
//$orders_module = getModule($tpt_vars, 'Orders');
$cpf_module = getModule($tpt_vars, 'CustomProductField');
$cpfs = $cpf_module->moduleData['pname'];

//$fonts = $fonts_module->moduleData['id'];
//$fonts = $vars['db']['handler']->getData($vars, $fonts_module->moduleTable, '`id`,`name`,`alt_name`,`file`', ' (1=1) ORDER BY `name` ASC');
$html = '';

$rows = array();
//$cells = array();

/*
$i=0;
$row = '';
$fcount = count($fonts);
foreach($fonts as $font) {
	if(($i==0) || ($i%4==0)) {
		//$row = '<div>';
		$cells = array();
	}
	$id = $font['id'];
	$name = $font['name'];
	$altname = str_replace('* ', '', $font['alt_name']);
	$UEname = urlencode($name);
	$time = time();
	//$src = BASE_URL.'/generate-preview?type=simple&amp;pg_x=70&amp;timestamp='.$time.'&amp;pg_y=30&amp;font='.$id.'&amp;text='.$UEname;
	$src = BASE_URL.'/fonts/images/'.$altname.'.png';
	$cell = <<< EOT
<div class="float-left width-25prc">
	<div class="padding-5 text-align-center">
		<img src="$src" />
	</div>
</div>
EOT;
	$cells[] = $cell;
	if(($i%4==3) || ($i==$fcount-1)) {
		//$row .= '</div>';
		$rows[] = '<div class="clearFix">'.implode('', $cells).'</div>';
	}
	$i++;
}

$rows = implode('', $rows);
*/

$input = $_POST;

$id = (isset($input['id']) && is_array($input['id'])) ? reset($input['id']) : null;
$pname = isset($input['pname']) ? $input['pname'] : null;

$sItem = (isset($input['fields'][$pname]) && !empty($input['fields'][$pname])) ? $input['fields'][$pname] : 0;

$rows = $color_module->CustomColor_Panel($tpt_vars, $sItem, $id);

$rows = <<< EOT
<div id="cus-col-con" class="overflow-scroll height-100prc" style="max-height: 100%;">
$rows
</div>
EOT;


$tpt_vars['environment']['ajax_result']['update_elements']['tpt_lightbox_content'] = $rows;