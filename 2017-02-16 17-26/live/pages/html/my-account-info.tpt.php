<?php

defined('TPT_INIT') or die('access denied');

//$cclhref = $vars['environment']['login_return_url'];
$cclhref = 'history.go(-1);';
$cpasshref = $tpt_vars['url']['handler']->wrap($tpt_vars, '/change-password');
$action_url = $tpt_vars['url']['handler']->wrap($tpt_vars, '/my-account-info');
$urlparam = '';


$fields_data = $tpt_vars['db']['handler']->getData($tpt_vars, 'tpt_form_edit_account_form_fields', '*', 'enabled=1', 'id', false);
$task_value = 'user.edit_account_info';
$tpt_vars['template']['title'] = 'Change Your Account Info';



//var_dump($address_entr);die();

$tpt_vars['template_data']['form_values'] = $tpt_vars['user']['data'];


$tpt_vars['template_data']['head'][] = <<< EOT
EOT;

$an = '';

$frl = array(
             '<div style="color: #909090;" class="urontrol height-37 line-height-37 padding-top-4 padding-bottom-4 padding-top-4 padding-bottom-4"></div>',
             '<div style="color: #909090;" class="urontrol height-22 line-height-22 padding-top-4 padding-bottom-4 padding-top-4 padding-bottom-4"></div>'
             );
$frc = array(
             '<div id="submit_tptformcontrol'.'" class="urontrol height-37 line-height-37 padding-top-4 padding-bottom-4">',
             '<input type="hidden" name="task" value="'.$task_value.'" />',
             $an,
             '<input type="submit" title="Save Account Info" value="Save Account Info" class="ma_btn display-inline-block hoverCB background-repeat-no-repeat background-position-CT" style="width: auto;" />&nbsp;',
             '<a title="Cancel Changes" class="amz_red text-decoration-underline display-inline-block hoverCB background-repeat-no-repeat background-position-CT" onclick="'.$cclhref.'" href="javascript:void(0);" style="">Cancel</a>',
             '</div>',
             '<div style="color: #909090;" class="urontrol height-22 line-height-22 padding-top-4 padding-bottom-4 padding-top-4 padding-bottom-4"></div>'
             );
$fra = array(
             '<div class="urontrol height-37 line-height-37 padding-top-4 padding-bottom-4 position-relative"></div>',
             '<div style="color: #909090;" class="urontrol height-22 line-height-22 padding-top-4 padding-bottom-4 padding-top-4 padding-bottom-4"></div>'
             );
include(TPT_TEMPLATES_DIR.DIRECTORY_SEPARATOR.'form-fields.tpt.php');
$account_fields = $form_fields;


$tpt_vars['template']['content'] .= <<< EOT
    <div class="overflow-hidden clearBoth">
        <div class="my-account">My Account Info</div>
        <div class="top-line-sep"></div>
        <form method="POST" action="$action_url$urlparam" accept-charset="utf-8" class="position-relative">
            <div class="position-absolute top-20 left-420 width-1 bottom-50" style="background-color: #cdcdcd;"></div>
        
            <div class="clearFix">
            <div class="padding-left-15 height-15 background-repeat-no-repeat" style="background-image: url($tpt_imagesurl/whbox/whbox-tl-15-15.png);">
            <div class="padding-right-15 height-15 background-repeat-no-repeat background-position-RT" style="background-image: url($tpt_imagesurl/whbox/whbox-tr-15-15.png);">
            <div class="height-35 background-repeat-repeat-x" style="background-image: url($tpt_imagesurl/whbox/whbox-t-15-15.png);">
            </div>
            </div>
            </div>
            
            <div class="padding-left-15 background-repeat-repeat-y" style="background-image: url($tpt_imagesurl/whbox/whbox-l-15-15.png);">
            <div class="padding-right-15 background-repeat-repeat-y background-position-RC" style="background-image: url($tpt_imagesurl/whbox/whbox-r-15-15.png);">
            <div class="clearFix" style="background-color: #FFF;">
                $account_fields
                <br />
                <br />
                <div class="text-align-center"><a title="Change Your Password" class="amz_red text-decoration-underline display-inline-block hoverCB background-repeat-no-repeat background-position-CT" href="$cpasshref" style="">Change Your Password</a></div>
            </div>
            </div>
            </div>
            
            <div class="padding-left-15 height-15 background-repeat-no-repeat" style="background-image: url($tpt_imagesurl/whbox/whbox-bl-15-15.png);">
            <div class="padding-right-15 height-15 background-repeat-no-repeat background-position-RB" style="background-image: url($tpt_imagesurl/whbox/whbox-br-15-15.png);">
            <div class="height-15 background-repeat-repeat-x" style="background-image: url($tpt_imagesurl/whbox/whbox-b-15-15.png);">
            </div>
            </div>
            </div>
            
            </div>
            
        </form>
    </div>
EOT;
