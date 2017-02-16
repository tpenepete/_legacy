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

//$section_message_style = getModule($tpt_vars, "BandStyle")->BandStyle_PlainSelect($tpt_vars);
$section_comments_design_idea = tpt_html::createTextarea($tpt_vars, 'comments', '', ' class="plain-input-field height-170" style=" background-color: #FFF; width: 100%;"');

?>
