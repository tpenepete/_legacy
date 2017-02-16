<?php

defined('TPT_INIT') or die('access denied');

//$cclhref = $tpt_vars['url']['handler']->wrap($tpt_vars, '/my-account-info');
$cclhref = 'history.go(-1);';
$cpasshref = $tpt_vars['url']['handler']->wrap($tpt_vars, '/my-account-info');
$action_url = $tpt_vars['url']['handler']->wrap($tpt_vars, '/change-password');


$fields_data = $tpt_vars['db']['handler']->getData($tpt_vars, 'tpt_form_edit_password_form_fields', '*', 'enabled=1', 'id', false);
$task_value = 'user.edit_password';
$tpt_vars['template']['title'] = 'Change Your Password';



//var_dump($address_entr);die();
    
$tpt_vars['template_data']['form_values'] = array();
    

$tpt_vars['template_data']['head'][] = <<< EOT
EOT;
    

$frl = array(
             '<div style="color: #909090;" class="urontrol height-37 line-height-37 padding-top-4 padding-bottom-4 padding-top-4 padding-bottom-4"></div>',
             '<div style="color: #909090;" class="urontrol height-22 line-height-22 padding-top-4 padding-bottom-4 padding-top-4 padding-bottom-4"></div>'
             );
$frc = array(
             '<div id="submit_tptformcontrol'.'" class="urontrol height-37 line-height-37 padding-top-4 padding-bottom-4">',
             '<input type="hidden" name="task" value="'.$task_value.'" />',
             '<input type="submit" title="Change Password" value="Change Password" class="ma_btn display-inline-block hoverCB background-repeat-no-repeat background-position-CT" style="width: auto;" />&nbsp;',
             '<a title="Cancel Changes" class="amz_red text-decoration-underline display-inline-block hoverCB background-repeat-no-repeat background-position-CT" onclick="'.$cclhref.'" href="javascript:void(0);" style="">Cancel</a>',
             '</div>',
             '<div style="color: #909090;" class="urontrol height-22 line-height-22 padding-top-4 padding-bottom-4 padding-top-4 padding-bottom-4"></div>'
             );
$fra = array(
             '<div class="urontrol height-37 line-height-37 padding-top-4 padding-bottom-4 position-relative"></div>',
             '<div style="color: #909090;" class="urontrol height-22 line-height-22 padding-top-4 padding-bottom-4 padding-top-4 padding-bottom-4"></div>'
             );
//include(TPT_TEMPLATES_DIR.DIRECTORY_SEPARATOR.'form-fields.tpt.php');
$f_fields = tpt_html::render_form_fields($tpt_vars, $fields_data, array(), array(), array(), $frl, $frc, $fra);


$tpt_vars['template']['content'] .= <<< EOT
    <div class="clearBoth position-relative">
        <div class="position-absolute top-0 left-420 right-0" style="min-height: 300px; background: transparent url($tpt_imagesurl/banner-change-password.png) no-repeat scroll center 30px;"><div class="position-absolute top-70 bottom-90" style="border-left: 1px solid #cdcdcd;"></div></div>
        <div class="my-account">Change Password</div>
        <div class="top-line-sep"></div>
        <form method="POST" action="$action_url" accept-charset="utf-8" class="">
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
                $f_fields
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

?>