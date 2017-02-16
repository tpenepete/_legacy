<?php

defined('TPT_INIT') or die('access denied');

//var_dump($_POST);die();


$input = array_replace($_GET, $_POST);

include(TPT_PAGES_DIR.DIRECTORY_SEPARATOR.'tpt-short-builder2.php');
//var_dump($tpt_vars['template']['content']);die();

if(!isset($options)) {
	$options = $builder = $builders_module->getBuilder($tpt_vars);
}
$type = $types_module->getActiveItem($tpt_vars, $input, $options);
$style = $styles_module->getActiveItem($tpt_vars, $input, $options);

$tpt_vars['environment']['ajax_result']['update_elements'] = array('main_content'=>$tpt_vars['template']['content']);
$tpt_vars['environment']['ajax_result']['exec_script'][] = <<< EOT
tb_init('a.thickbox, area.thickbox, input.thickbox');

var pType = $type;
var pStyle = $style;
EOT;

//$tpt_vars['environment']['isAjax'] = true;
