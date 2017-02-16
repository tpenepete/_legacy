<?php

defined('TPT_INIT') or die('access denied');

//tpt_dump($pgconf, true);

$type_module = getModule($tpt_vars, "BandType");
$colors_module = getModule($tpt_vars, "BandColor");
$cpf_module = getModule($tpt_vars, "CustomProductField");
$colorField = $cpf_module->moduleData['name']['color'];
//$section_band_color = $cpf_module->SB_Control($tpt_vars, $colorField, $pgconf, $builder, array($pgBandColorType));
$section_band_color = $colors_module->BandColor_Section_SB($tpt_vars, $pgconf, $builder, $pgBandColorType);
$section_band_color = $section_band_color['content'];


$section_message_color = '';
//tpt_dump($pgType);
//tpt_dump(empty($type_module->moduleData['id'][$pgType]['blank']));
//tpt_dump($type_module->moduleData['id'][$pgType], true);
if(empty($type_module->moduleData['id'][$pgType]['blank'])) {
    //tpt_dump('asdasdasdasd', true);
$section_message_color = $colors_module->MessageColor_Section_SB($tpt_vars, $pgMessageColor, $pgType, $pgStyle, $builder);
$section_message_color = $section_message_color['content'];
}

$section_band_color = <<< EOT
<div id="bandcolor_section">
$section_band_color
$section_message_color
</div>
EOT;

