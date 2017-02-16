<?php

defined('TPT_INIT') or die('access denied');

$cpasshref = $tpt_vars['url']['handler']->wrap($tpt_vars, '/my-account-info');
$action_url = $tpt_vars['url']['handler']->wrap($tpt_vars, '/reset-password');

if(strtolower($_SERVER['REQUEST_METHOD']) == 'post') {
    $token = $_POST['token'];
} else {
    $token = $_GET['token'];
}
//var_dump($_REQUEST['token']);
$fields_data = $tpt_vars['db']['handler']->getData($tpt_vars, 'tpt_form_edit_password2_form_fields', '*', 'enabled=1', 'id', false);
$task_value = 'user.edit_password2';
$tpt_vars['template']['title'] = 'Change Your Password';



//var_dump($address_entr);die();
    
$tpt_vars['template_data']['form_values'] = array();
    

$tpt_vars['template_data']['head'][] = <<< EOT
EOT;
    

$frl = array(
             '<div style="color: #909090;" class="urontrol height-37 line-height-37 padding-top-4 padding-bottom-4 padding-top-4 padding-bottom-4"></div>'
             );
$frc = array(
             '<div id="submit_tptformcontrol'.'" class="urontrol height-37 line-height-37 padding-top-4 padding-bottom-4">',
             '<input type="hidden" name="token" value="'.$token.'" />',
             '<input type="hidden" name="task" value="'.$task_value.'" />',
             '<input type="submit" title="Change Password" value="Change Password" class="display-inline-block hoverCB background-repeat-no-repeat background-position-CT" style="width: auto;" />&nbsp;',
             '</div>'
             );
$fra = array(
             '<div class="urontrol height-37 line-height-37 padding-top-4 padding-bottom-4 position-relative"></div>'
             );
//include(TPT_TEMPLATES_DIR.DIRECTORY_SEPARATOR.'form-fields.tpt.php');
$f_fields = tpt_html::render_form_fields($tpt_vars, $fields_data, array(), array(), array(), $frl, $frc, $fra);


$tpt_vars['template']['content'] .= <<< EOT
    <div class="overflow-hidden clearBoth">
        <div class="padding-top-0 text-align-center">
            <div class="height-35 padding-top-5 padding-bottom-5 background-position-CC background-repeat-no-repeat font-size-32" style="font-family: BADABOOMBB,Arial !important; text-shadow: 0.1em 0.1em 0.07em #5b3824; color: #ffa32d;">ENTER YOUR NEW PASSWORD</div>
        </div>
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