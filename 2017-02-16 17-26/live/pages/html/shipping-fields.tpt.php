<?php
defined('TPT_INIT') or die('access denied');

global $tpt_vars;

$fields_data2 = $tpt_vars['db']['handler']->getData($tpt_vars, 'tpt_form_add_shipping_address_form_fields', '*', 'enabled=1', 'id', false);
$fbl = array(
            '<div style="color: #909090;" class="urontrol height-22 width-119 line-height-22 padding-top-4 padding-bottom-4 padding-top-4 padding-bottom-4">Same As Billing</div>'
            );
$fbc = array(
            '<div id="sameaddress_tptformcontrol'.'" class="urontrol height-22 line-height-22 padding-top-4 padding-bottom-4">',
            tpt_html::createCheckbox($tpt_vars, 'same_address', 1, intval(isset($_POST['same_address'])?$_POST['same_address']:0, 10), ' onclick="if(this.checked){toggle_product_section(document.getElementById(\'shipping_section_form_section_toggle\'), 1);}else{toggle_product_section(document.getElementById(\'shipping_section_form_section_toggle\'), 2);}" onkeyup="if(this.checked){toggle_product_section(document.getElementById(\'shipping_section_form_section_toggle\'), 1);}else{toggle_product_section(document.getElementById(\'shipping_section_form_section_toggle\'), 2);}"', 'removeClass(document.getElementById("shipping_section_form_section"), unfoldedClassRegExp);removeClass(document.getElementById("shipping_section_form_section_body"), new RegExp(/opacity-[0-9]*/));addClass(document.getElementById("shipping_section_form_section"), "sectionFolded");addClass(document.getElementById("shipping_section_form_section_body"), "opacity-0");', 'removeClass(document.getElementById("shipping_section_form_section"), "sectionFolded");removeClass(document.getElementById("shipping_section_form_section_body"), new RegExp(/opacity-[0-9]*/));addClass(document.getElementById("shipping_section_form_section_body"), "sectionUnFolded");addClass(document.getElementById("shipping_section_form_section_body"), "opacity-100");'),
            '</div>'
            );
$fba = array(
            '<div class="urontrol height-22 line-height-22 padding-top-4 padding-bottom-4 position-relative"></div>'
            );

$section_class = ' sectionUnFolded';
//include(TPT_TEMPLATES_DIR.DIRECTORY_SEPARATOR.'form-fields2.tpt.php');
//if(isDump()) {
	$section_html = tpt_html::render_form_fields2($tpt_vars, $fields_data2, ' width-119');
//}
