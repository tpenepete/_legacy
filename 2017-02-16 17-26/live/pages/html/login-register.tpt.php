<?php

defined('TPT_INIT') or die('access denied');
    $action_url = $tpt_vars['url']['handler']->wrap($tpt_vars, '/login-register');
    $rpasslink = $tpt_vars['url']['handler']->wrap($tpt_vars, '/reset-password');
    //$ppolicylink = $tpt_vars['url']['handler']->wrap($tpt_vars, '/password-policy');
    //http://www.amazingwristbands.com/policies#password
    $ppolicylink = $tpt_vars['url']['handler']->wrap($tpt_vars, '/policies#password');
    
    $login_class = 'sectionUnFolded';
    $login_opc = '100';
    $reg_class = 'sectionFolded';
    $reg_opc = '0';
    if(!empty($_GET['register'])) {
        $reg_class = 'sectionUnFolded';
        $reg_opc = '100';
        $login_class = 'sectionFolded';
        $login_opc = '0';
    }
    
$tpt_vars['template']['title'] = 'Login or Create an Account';

$tpt_vars['template']['quote_link'] = '';
/*$tpt_vars['template_data']['head'][] = <<< EOT
<script type="text/javascript" src="$tpt_jsurl/registration.js"></script>
EOT;*/
    
$username = (!empty($_POST['username'])?$_POST['username']:'');

$fields_data = $tpt_vars['db']['handler']->getData($tpt_vars, 'tpt_form_registration_form_fields', '*', 'enabled=1', 'id', false);
$frl = array(
            '<div class="urlabel urontrol height-37 line-height-37 padding-top-4 padding-bottom-4 padding-top-4 padding-bottom-4"></div>'
            );
$frc = array(
            '<div id="submit_tptformcontrol" class="urontrol height-37 line-height-37 padding-top-4 padding-bottom-4">',
            '<input type="hidden" name="task" value="user.register" />',
            '<input type="submit" title="Create a new Account" value="" class="plain-input-field display-inline-block hoverCB width-82 height-37 background-repeat-no-repeat background-position-CT" style="background-image: url('.$tpt_imagesurl.'/btn_register.png);" />',
            '</div>'
            );
$fra = array(
            '<div class="urontrol height-37 line-height-37 padding-top-4 padding-bottom-4 position-relative"></div>'
            );
$rlabels_before = '';
$rcontrols_before = '';
$rafter_before = '';
//include(TPT_TEMPLATES_DIR.DIRECTORY_SEPARATOR.'form-fields.tpt.php');
//$registration_fields = $form_fields;
$registration_fields = tpt_html::render_form_fields($tpt_vars, $fields_data, array(), array(), array(), $frl, $frc, $fra);


/*
$tpt_vars['template']['content'] .= <<< EOT
    <div id="tpt_login_form" class="$login_class overflow-hidden clearBoth">
    	<h1 class="product-title">Login or Create an Account</h1>
        <span class="display-block height-46 background-position-CC background-repeat-no-repeat" style="background-image: url($tpt_imagesurl/existing-customer-label.png);"><a class="display-block outline-none height-46" href="#" onclick="if(this.parentNode.parentNode.className.match(unfoldedClassRegExp)){toggle_product_section(this, 1);toggle_product_section(document.getElementById('toggle_reg'), 2);}else{toggle_product_section(this, 2);toggle_product_section(document.getElementById('toggle_reg', 1));}" id="toggle_login"></a></span>
        <form method="POST" action="$action_url" accept-charset="utf-8" class="opacity-$login_opc">
            <div class="amz_login clearFix">
            <div class="white-box">
            <div class="clearFix">
                <div class="float-left padding-right-55" style="border-right: 1px solid #CCC;">
                    <div>
                        <div class="display-inline-block text-align-right padding-left-22">
                            <div class="urlabel height-22 line-height-22 padding-top-4 padding-bottom-4">
                                <div>Email:</div>
                            </div>
                            <div class="urlabel height-22 line-height-22 padding-top-4 padding-bottom-4">
                                <div>Password:</div>
                            </div>
                        </div>
                        <div class="display-inline-block width-160">
                            <div class="height-22 line-height-22 padding-top-4 padding-bottom-4">
                                <div style="background-image: url($tpt_imagesurl/user-form-field-left.png);" class="padding-left-8 background-position-LC background-repeat-no-repeat">
                                    <div style="background-image: url($tpt_imagesurl/user-form-field-right.png);" class="padding-right-8 background-position-RC background-repeat-no-repeat">
                                        <div style="background-image: url($tpt_imagesurl/user-form-field-mid.png);" class="background-repeat-repeat-x background-position-CC">
                                            <input type="text" style="width: 100%;" value="$username" name="username" class="plain-input-field padding-top-4 padding-bottom-4 font-size-14">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="padding-top-4 padding-bottom-4">
                                <div style="background-image: url($tpt_imagesurl/user-form-field-left.png);" class="padding-left-8 background-position-LC background-repeat-no-repeat">
                                    <div style="background-image: url($tpt_imagesurl/user-form-field-right.png);" class="padding-right-8 background-position-RC background-repeat-no-repeat">
                                        <div style="background-image: url($tpt_imagesurl/user-form-field-mid.png);" class="background-repeat-repeat-x background-position-CC">
                                            <input type="password" style="width: 100%;" value="" name="password" class="plain-input-field padding-top-3 padding-bottom-3 font-size-14">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <input type="hidden" name="task" value="user.login" />
                    
                    <div class="text-align-right">
                        <input type="submit" title="Login to Your Account" value="" class="plain-input-field display-inline-block hoverCB width-82 height-37 background-repeat-no-repeat background-position-CT" style="background-image: url($tpt_imagesurl/login-btn2.png);" />
                    </div>
                </div>
                <div class="">
                    <div class="overflow-hidden padding-left-10">
                        <div class="height-22 line-height-22 padding-top-4 padding-bottom-4">
                            <div class="amz_red"><a href="$rpasslink" title="Password Recovery">Forgotten Your Password?</a></div>
                        </div>
                        <div class="height-22 line-height-22 padding-top-4 padding-bottom-4">
                            <div class="amz_red"><a href="$ppolicylink" title="Password Policy">Password Policy</a></div>
                        </div>
                    </div>
                </div>
            
            </div>
            </div>
          </div>
        </form>
    </div>
    
    <div id="tpt_reg_form" class="$reg_class overflow-hidden clearBoth">
        <span class="display-block height-46 background-position-CC background-repeat-no-repeat" style="background-image: url($tpt_imagesurl/new-customer-label.png);"><a class="display-block outline-none height-46" href="#" onclick="if(this.parentNode.parentNode.className.match(unfoldedClassRegExp)){toggle_product_section(this, 1);toggle_product_section(document.getElementById('toggle_login', 2));}else{toggle_product_section(this, 2);toggle_product_section(document.getElementById('toggle_login', 1));}" id="toggle_reg"></a></span>
        <form autocomplete="off" method="POST" action="$action_url?register=1" accept-charset="utf-8" class="opacity-$reg_opc">
            <div class="amz_login clearFix">
            <div class="white-box">
            <div class="clearFix">
                $registration_fields
            </div>
            </div>
            </div>
            
        </form>
    </div>
EOT;
*/
$tpt_vars['template']['content'] .= <<< EOT
    <div id="tpt_login_form" class="$login_class overflow-hidden clearBoth">
    	<h1 class="product-title">Login or Create an Account</h1>
        <span class="display-block height-46 background-position-CC background-repeat-no-repeat" style="background-image: url($tpt_imagesurl/existing-customer-label.png);"><a class="display-block outline-none height-46" href="#" onclick="if(this.parentNode.parentNode.className.match(unfoldedClassRegExp)){toggle_product_section(this, 1);toggle_product_section(document.getElementById('toggle_reg'), 2);}else{toggle_product_section(this, 2);toggle_product_section(document.getElementById('toggle_reg', 1));}" id="toggle_login"></a></span>
        <form method="POST" action="$action_url" accept-charset="utf-8" class="opacity-$login_opc">
            <div class="amz_login clearFix">
            <div class="white-box padding-10">
            <div class="clearFix">
                <div class="float-left padding-right-20">
                    <div>
                        <div class="padding-left-10">
                            <div class="padding-top-4 padding-bottom-4 width-70 height-22 line-height-22 urlabel float-left">
                                <div class="text-align-right padding-right-4">Email:</div>
                            </div>
                            <div class="padding-top-4 padding-bottom-4 height-22 line-height-22 float-left">
								<input type="text" style="border: 1px solid #CCCCCC; background: #E5E5E5 none;" value="$username" name="username" class="plain-input-field padding-left-2 padding-right-2 padding-top-2 padding-bottom-2 font-size-14 width-100prc border-radius-10">
                            </div>
                        </div>
                        <div class="padding-left-10">
                            <div class="padding-top-4 padding-bottom-4 width-70 height-22 line-height-22 urlabel float-left">
                                <div class="text-align-right padding-right-4">Password:</div>
                            </div>
                            <div class="padding-top-4 padding-bottom-4 float-left">
								<input type="password" style="border: 1px solid #CCCCCC;  background: #E5E5E5 none;" value="" name="password" class="plain-input-field padding-left-2 padding-right-2 padding-top-2 padding-bottom-2 font-size-14 width-100prc border-radius-10">
                            </div>
                        </div>
                    </div>
    
                    <input type="hidden" name="task" value="user.login" />
                    <div class="text-align-right width-95prc">
                        <input type="submit" title="Login to Your Account" value="" class="plain-input-field display-inline-block hoverCB width-82 height-37 background-repeat-no-repeat background-position-CT" style="background-image: url($tpt_imagesurl/login-btn2.png);" />
                    </div>
                </div>
                <div class="float-left">
                    <div class="div_border_left">
                        <div class="line-height-22 padding-top-4 padding-bottom-4">
                            <div class="amz_red"><a href="$rpasslink" title="Password Recovery">Forgotten Your Password?</a></div>
                        </div>
                        <div class="height-22 line-height-22 padding-top-4 padding-bottom-4">
                            <div class="amz_red"><a href="$ppolicylink" title="Password Policy">Password Policy</a></div>
                        </div>
                    </div>
                </div>
    
            </div>
            </div>
            </div>
    
        </form>
    </div>
    
    
    <div id="tpt_reg_form" class="$reg_class overflow-hidden clearBoth">
        <span class="display-block height-46 background-position-CC background-repeat-no-repeat" style="background-image: url($tpt_imagesurl/new-customer-label.png);"><a class="display-block outline-none height-46" href="#" onclick="if(this.parentNode.parentNode.className.match(unfoldedClassRegExp)){toggle_product_section(this, 1);toggle_product_section(document.getElementById('toggle_login', 2));}else{toggle_product_section(this, 2);toggle_product_section(document.getElementById('toggle_login', 1));}" id="toggle_reg"></a></span>
        <form autocomplete="off" method="POST" action="$action_url?register=1" accept-charset="utf-8" class="opacity-$reg_opc">
            <div class="amz_login clearFix">
            <div class="white-box">
            <div class="clearFix">
                $registration_fields
            </div>
            </div>
            </div>
    
        </form>
    </div>
EOT;
