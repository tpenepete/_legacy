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

//$message_color = getModule($tpt_vars, "BandColor")->Message_Color_Select($tpt_vars, $selectedColor);
//$message_color_dual_layer = getModule($tpt_vars, "BandColor")->Dual_Color_Select($tpt_vars, "");
$message_color = getModule($tpt_vars, 'BandColor')->Message_Color_Select($tpt_vars, $selectedColor);
$message_color_dual_layer = getModule($tpt_vars, 'BandColor')->Dual_Color_Select($tpt_vars, "");

$section_message_color = <<< EOT
<div class="standard_msg_color_wr">
$message_color
</div>

<div class="dual_layer_msg_color_wr display-none">
<p>( Band Color / Message Color )</p>
$message_color_dual_layer
</div>

<div class="ccc_wr">
	<a class="thickbox create_custom_color_message" href="#TB_inline?width=900&height=500&inlineId=_">Choose Custom <b>Message Color</b></a>
</div>
EOT;

?>
