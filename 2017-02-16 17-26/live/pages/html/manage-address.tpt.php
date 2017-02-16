<?php

defined('TPT_INIT') or die('access denied');

$tpt_vars['template']['title'] = 'Change Billing Address';
$urlparams = array();
//$cclhref = $tpt_vars['url']['handler']->wrap($tpt_vars, '/address-shipping');
$cclhref = 'history.go(-1);';
if(!empty($_GET['fromshipping'])) {
    $urlparams[] = 'fromshipping=1';
} else if(!empty($_GET['frompayment'])) {
    $urlparams[] = 'frompayment=1';
}
$action_url = $tpt_vars['url']['handler']->wrap($tpt_vars, '/manage-address');

$fields_data = array();
$task_value = 'user.edit_payment_address';
if(!empty($_GET['shipping'])) {
    $urlparams[] = 'shipping=1';
    $fields_data = $tpt_vars['db']['handler']->getData($tpt_vars, 'tpt_form_edit_shipping_address_form_fields', '*', 'enabled=1', 'id', false);
    $tpt_vars['template']['title'] = 'Change Shipping Address';
    $task_value = 'user.edit_shipping_address';
} else {
    $fields_data = $tpt_vars['db']['handler']->getData($tpt_vars, 'tpt_form_edit_billing_address_form_fields', '*', 'enabled=1', 'id', false);
}

$urlparam = (!empty($urlparams)?'?'.implode('&amp;', $urlparams):'');

//var_dump($address_entr);die();
    
$tpt_vars['template_data']['form_values'] = $tpt_vars['user']['data'];

$tpt_vars['template_data']['head'][] = <<< EOT
EOT;

$an = '';

$frl = array(
             '<div class="urlabel urontrol height-37 line-height-37 padding-top-4 padding-bottom-4 padding-top-4 padding-bottom-4"></div>',
             '<div class="urlabel urontrol height-37 line-height-37 padding-top-4 padding-bottom-4 padding-top-4 padding-bottom-4"></div>'
             );
$frc = array(
             '<div id="submit_tptformcontrol'.'" class="urontrol height-37 line-height-37 padding-top-4 padding-bottom-4">',
             '<input type="hidden" name="task" value="'.$task_value.'" />',
             $an,
             '<input type="submit" title="Save Address" value="Save Address" class="ma_btn hoverCB background-repeat-no-repeat background-position-CT"  />',
             '<a title="Cancel Changes" class="amz_red text-decoration-underline plain-input-field display-inline-block hoverCB width-82 height-37 background-repeat-no-repeat background-position-CT" onclick="'.$cclhref.'" href="javascript:void(0);" >Cancel</a>',
             '</div>'
             );
$fra = array(
             '<div class="urontrol height-37 line-height-37 padding-top-4 padding-bottom-4 position-relative"></div>'
             );
$rlabels_before = '';
$rcontrols_before = '';
$rafter_before = '';
//include(TPT_TEMPLATES_DIR.DIRECTORY_SEPARATOR.'form-fields.tpt.php');
$address_fields = tpt_html::render_form_fields($tpt_vars, $fields_data, array(), array(), array(), $frl, $frc, $fra);


$tpt_vars['template']['content'] .= <<< EOT
    <div class="overflow-hidden clearBoth">
        <!--<span class="display-block height-46 background-position-CC background-repeat-no-repeat" style="background-image: url($tpt_imagesurl/new-customer-label.png);"><a class="display-block outline-none height-46" href="javascript:void(0);" onclick="if(this.parentNode.parentNode.className.match(unfoldedClassRegExp)){toggle_product_section(this, 1);toggle_product_section(document.getElementById('toggle_login', 2));}else{toggle_product_section(this, 2);toggle_product_section(document.getElementById('toggle_login', 1));}" id="toggle_reg"></a></span>-->
        <div class="my-account">Manage Address</div>
        <div class="top-line-sep"></div>
        <form method="POST" action="$action_url$urlparam" accept-charset="utf-8">
            <div class="white-box padding-10">           
            <div class="clearFix">
                $address_fields
            </div>
            </div>
        </form>
    </div>
EOT;

?>