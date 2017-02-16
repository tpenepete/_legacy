<?php
defined('TPT_INIT') or die('access denied');

$smodule = getModule($tpt_vars, 'BuilderSection');
$sections = $smodule->moduleData['id'];
$cpfmodule = getModule($tpt_vars, 'CustomProductField');
$cpfs = $cpfmodule->moduleData['id'];

/*
$db = $tpt_vars['db']['handler'];
$r=71;
for($i=23;$i<86;$i+=2) {
	$query = <<< EOT
INSERT INTO `tpt_module_buildersection_source_copy`
(
`name`,
`pname`,
`target_field`,
`label`,
`preview_name`,
`builder_name`,
`toggle_section`,
`toggle_sections_ids`,
`toggle_control_wrappers_ids`,
`disable_section_parameter`,
`check_zeroindexselected`,
`check_emptystring`,
`check_zerovalue`,
`check_invalid_message`,
`control_type`,
`subsection`,
`section_label`,
`self_function`,
`module_function`,
`module`,
`function`,
`group`,
`order`,
`update_layers`,
`html_classes`,
`isset_html_classes`,
`isset_html_classes_related_section_pname`,
`onfocus`,
`onblur`,
`onclick`,
`onchange`,
`oninput`,
`onpropertychange`,
`onkeypress`,
`onkeyup`,
`onkeydown`,
`enabled`
)
SELECT
`name`,
`pname`,
`target_field`,
`label`,
`preview_name`,
`builder_name`,
`toggle_section`,
`toggle_sections_ids`,
`toggle_control_wrappers_ids`,
`disable_section_parameter`,
`check_zeroindexselected`,
`check_emptystring`,
`check_zerovalue`,
`check_invalid_message`,
`control_type`,
`subsection`,
`section_label`,
`self_function`,
`module_function`,
`module`,
`function`,
`group`,
`order`,
`update_layers`,
`html_classes`,
`isset_html_classes`,
`isset_html_classes_related_section_pname`,
`onfocus`,
`onblur`,
`onclick`,
`onchange`,
`oninput`,
`onpropertychange`,
`onkeypress`,
`onkeyup`,
`onkeydown`,
`enabled`
FROM
`tpt_module_buildersection_source`
WHERE `id`=$i
EOT;
	$db->prepare($query);
	$db->execute();
	$query = 'INSERT INTO `tpt_module_buildersection_source_copy` (`name`) VALUES("'.$sections[$i]['name'].'_pos_x")';
	$db->prepare($query);
	$db->execute();
	$query = 'INSERT INTO `tpt_module_buildersection_source_copy` (`name`) VALUES("'.$sections[$i]['name'].'_pos_y")';
	$db->prepare($query);
	$db->execute();
	$query = 'INSERT INTO `tpt_module_buildersection_source_copy` (`name`) VALUES("'.$sections[$i]['name'].'_scale_x")';
	$db->prepare($query);
	$db->execute();
	$query = 'INSERT INTO `tpt_module_buildersection_source_copy` (`name`) VALUES("'.$sections[$i]['name'].'_scale_y")';
	$db->prepare($query);
	$db->execute();
	$query = 'INSERT INTO `tpt_module_buildersection_source_copy` (`name`) VALUES("'.$sections[$i]['name'].'_color")';
	$db->prepare($query);
	$db->execute();
	$query = 'INSERT INTO `tpt_module_buildersection_source_copy` (`name`) VALUES("'.$sections[$i]['name'].'_texture")';
	$db->prepare($query);
	$db->execute();
	$query = 'INSERT INTO `tpt_module_buildersection_source_copy` (`name`) VALUES("'.$sections[$i]['name'].'_texture_pos_x")';
	$db->prepare($query);
	$db->execute();
	$query = 'INSERT INTO `tpt_module_buildersection_source_copy` (`name`) VALUES("'.$sections[$i]['name'].'_texture_pos_y")';
	$db->prepare($query);
	$db->execute();
	$query = 'INSERT INTO `tpt_module_buildersection_source_copy` (`name`) VALUES("'.$sections[$i]['name'].'_texture_scale_x")';
	$db->prepare($query);
	$db->execute();
	$query = 'INSERT INTO `tpt_module_buildersection_source_copy` (`name`) VALUES("'.$sections[$i]['name'].'_texture_scale_y")';
	$db->prepare($query);
	$db->execute();
	for($j=0;$j<10;$j++) {
		$query = 'INSERT INTO `tpt_module_buildersection_source_copy` (`name`) VALUES("reserved'.($r+$j).'")';
		$db->prepare($query);
		$db->execute();
	}
	$r+=10;
}
die();
*/

/*
$db = $tpt_vars['db']['handler'];

//$smodule = getModule($tpt_vars, 'BuilderSection');
//$sectionsdata = $smodule->moduleData['id'];
$query = 'SELECT * FROM `tpt_module_buildersection_source_2017-02-07` WHERE `id`<112';
$db->prepare($query);
$db->execute();
$sectionsdata = $db->fetchAllIndexed('id', false);
$dmodule = getModule($tpt_vars, 'BandData');
$data = $dmodule->moduleData['id'];
$query = 'SELECT * FROM `tpt_module_buildersection_source` WHERE `id`<966';
$db->prepare($query);
$db->execute();
$bs = $db->fetchAllIndexed('pname', false);
//tpt_dump($bs, true);

foreach($data as $id=>$d) {
	$sections = $d['builder_sections'];
	$sections = explode(',', $sections);
	$sections = array_combine($sections, $sections);
	$sections = array_intersect_key($sectionsdata, $sections);
	$sections = array_column($sections, 'pname', 'pname');
	$sections = array_intersect_key($bs, $sections);
	$sections = array_column($sections, 'id');
	sort($sections);
	$sections = implode(',', $sections);
	//tpt_dump($sections);
	$query = 'UPDATE `tpt_module_banddata` SET `builder_sections`=:builder_sections WHERE `id`=:id';
	$db->prepare($query);
	$db->bindParam('id', $id);
	$db->bindParam('builder_sections', $sections);
	$db->execute();
	$query = 'UPDATE `tpt_module_banddata_source` SET `builder_sections`=:builder_sections WHERE `id`=:id';
	$db->prepare($query);
	$db->bindParam('id', $id);
	$db->bindParam('builder_sections', $sections);
	$db->execute();
}
*/


/*
$db = $tpt_vars['db']['handler'];

$lmodule = getModule($tpt_vars, 'PreviewLayer');
$layersdata = $lmodule->moduleData['id'];
$fmodule = getModule($tpt_vars, 'CustomProductField');
$cpfs = $fmodule->moduleData['pname'];
$query = 'SELECT * FROM `tpt_module_customproductfield_source_2017-02-07`';
$db->prepare($query);
$db->execute();
$of = $db->fetchAllIndexed('id', false);
//tpt_dump($bs, true);

foreach($layersdata as $id=>$l) {
	$params = $l['preview_params_ids'];
	$params = explode(',', $params);
	$params = array_combine($params, $params);
	$params = array_intersect_key($of, $params);
	$params = array_column($params, 'pname', 'pname');
	$params = array_intersect_key($cpfs, $params);
	$params = array_column($params, 'id');
	sort($params);
	$params = implode(',', $params);
	//tpt_dump($sections);
	$query = 'UPDATE `tpt_module_previewlayer` SET `preview_params_ids`=:preview_params_ids WHERE `id`=:id';
	$db->prepare($query);
	$db->bindParam('id', $id);
	$db->bindParam('preview_params_ids', $params);
	$db->execute();
	$query = 'UPDATE `tpt_module_previewlayer_source` SET `preview_params_ids`=:preview_params_ids WHERE `id`=:id';
	$db->prepare($query);
	$db->bindParam('id', $id);
	$db->bindParam('preview_params_ids', $params);
	$db->execute();
}
*/

/*
$db = $tpt_vars['db']['handler'];

$lmodule = getModule($tpt_vars, 'PreviewLayer');
$layersdata = $lmodule->moduleData['id'];
$fmodule = getModule($tpt_vars, 'CustomProductField');
$cpfs = $fmodule->moduleData['pname'];
$query = 'SELECT * FROM `tpt_module_customproductfield_source_2017-02-07`';
$db->prepare($query);
$db->execute();
$of = $db->fetchAllIndexed('id', false);
//tpt_dump($bs, true);

foreach($layersdata as $id=>$l) {
	$params = $l['target'];
	$params = explode(',', $params);
	$params = array_combine($params, $params);
	$params = array_intersect_key($of, $params);
	$params = array_column($params, 'pname', 'pname');
	$params = array_intersect_key($cpfs, $params);
	$params = array_column($params, 'id');
	sort($params);
	$params = implode(',', $params);
	//tpt_dump($sections);
	$query = 'UPDATE `tpt_module_previewlayer` SET `target`=:target WHERE `id`=:id';
	$db->prepare($query);
	$db->bindParam('id', $id);
	$db->bindParam('target', $params);
	$db->execute();
	$query = 'UPDATE `tpt_module_previewlayer_source` SET `target`=:target WHERE `id`=:id';
	$db->prepare($query);
	$db->bindParam('id', $id);
	$db->bindParam('target', $params);
	$db->execute();
}
*/