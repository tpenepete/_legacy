<?php

defined('TPT_INIT') or die('access denied');

/*
if ($_SERVER['REMOTE_ADDR'] == '85.130.71.163')
{
    echo $pgStyle.':'.$builder['style'].':'.$builder['type'].':'.$builder_id;
}

$keychain_builder_enabled_arr = array('1', '2', '9', '10', '11', '12', '13', '14');

$keychain_option = '';

if (in_array($builder_id, $keychain_builder_enabled_arr)) {
    $keychain_class = '';
    
    if ($builder_id > 2 && $builder['type'] != '1' && $builder['type'] != '2') // if not 1/4 or 1/2 builder hide the option until such product type is selected
        $keychain_class = 'display-none';
    
    $keychain_option = '<div class="'.$keychain_class.'" id="keychain_addon"><input value="5" type="checkbox" id="create_keychain" name="create_keychain" />&nbsp;&nbsp;<label for="create_keychain">Make into a keychain.</label></div>';
}
*/

$indvlcheck = !empty($pgAddons['indvl_packaging'])?' checked="checked"':'';

$keychain_class = '';
if ($builder['inhouse']) {
    $keychain_class = ' display-none ';
}

$keychain_option = '';
if(($pgType == 1) || ($pgType == 2)) {
$keychain_option = '<div class="'.$keychain_class.'" id="keychain_addon"><input value="1" type="checkbox" id="create_keychain" name="key_chain" />&nbsp;&nbsp;<label for="create_keychain">Make into a keychain.</label></div>';
}
//disable keychain addons (TEMPORARILY)
$keychain_option = '';

$section_add_ons = <<< EOT
<div id="addons_container">
</div>

<div class="band-extras">				
	<div><input $indvlcheck class="" value="1" type="checkbox" id="create_packing" name="indvl_packaging" />&nbsp;&nbsp;<label for="create_packing">Add individual packaging to your band.</label></div>
	$keychain_option
</div>

<!--div class="ccc_wr">
<a class="thickbox view-all-artwork" href="#TB_inline?width=900&amp;height=500&amp;inlineId=_">All Artwork</a>
</div-->


EOT;


?>
