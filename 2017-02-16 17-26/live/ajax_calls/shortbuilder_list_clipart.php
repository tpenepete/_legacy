<?php

defined('TPT_INIT') or die('access denied');


//$fonts_module = getModule($tpt_vars, 'BandFont');
//$ccat_module = getModule($tpt_vars, 'BandClipartCategory');
$clipart_module = getModule($tpt_vars, 'BandClipart');
//$orders_module = getModule($tpt_vars, 'Orders');
$cpf_module = getModule($tpt_vars, 'CustomProductField');
$cpfs = $cpf_module->moduleData['pname'];

//$fonts = $fonts_module->moduleData['id'];
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

//$id = 0;
$ccat = !empty($input['cid'])?$input['cid']:0;
$sid = !empty($input['sid'])?$input['sid']:0;
//$ccat = 19;
//if(!empty($input['ccat'])) {
//	$ccat = reset($input['ccat']);
//}


//$rows = $fonts_module->BandFont_Panel2($tpt_vars, $sFont, $id);
$rows = $clipart_module->BandClipart_Panel4($tpt_vars, $sid, $ccat);

/*
$rows = <<< EOT
<div class="overflow-scroll height-100prc" style="max-height: 100%;">
$rows
</div>
EOT;
*/

$tpt_vars['environment']['ajax_result']['update_elements']['clipartsitems'] = $rows;