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
$type_module = getModule($tpt_vars, 'BandType');
$fonts_module = getModule($tpt_vars, 'BandFont');

//$section_band_font = '';
//if(empty($type_module->moduleData['id'][$pgType]['blank'])) {
$section_band_font = getModule($tpt_vars, "BandFont")->BandFont_PlainSelect($tpt_vars, $selectedFont);
/*
$section_band_font = <<< EOT
<div id="fontwrapper">
$section_band_font
</div>
EOT;
*/
//}

/* $section_band_font.='
<div class="ccc_wr">
	<a class="thickbox view-all-fonts" href="#TB_inline?width=900&height=500&inlineId=_">View All Fonts</a>
</div>
';*/

