<?php
defined('TPT_INIT') or die('access denied');

global $tpt_vars;

$fields_data2 = $tpt_vars['db']['handler']->getData($tpt_vars, 'tpt_form_add_billing_address_form_fields', '*', 'enabled=1', 'id', false);
//tpt_dump($fields_data2, true);
/*$frl = array(
            '<div style="color: #909090;" class="urontrol height-37 line-height-37 padding-top-4 padding-bottom-4 padding-top-4 padding-bottom-4"></div>'
            );
$frc = array(
            '<div id="submit_tptformcontrol'.'" class="urontrol height-37 line-height-37 padding-top-4 padding-bottom-4">',
            '<input type="hidden" name="task" value="user.register" />',
            '<input type="submit" title="Create a new Account" value="" class="plain-input-field display-inline-block hoverCB width-82 height-37 background-repeat-no-repeat background-position-CT" style="background-image: url('.$tpt_imagesurl.'/btn_register.png);" />',
            '</div>'
            );
$fra = array(
            '<div class="urontrol height-37 line-height-37 padding-top-4 padding-bottom-4 position-relative"></div>'
            );
*/
//include(TPT_TEMPLATES_DIR.DIRECTORY_SEPARATOR.'form-fields2.tpt.php');
//if(isDump()) {
	//die('asd');
	$section_html = tpt_html::render_form_fields2($tpt_vars, $fields_data2, ' width-119');
//}
