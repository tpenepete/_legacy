<?php
//die();
defined('TPT_INIT') or die('access denied');

$hashedtoken = '';
$action_url = $tpt_vars['url']['handler']->wrap($tpt_vars, '/reset-password');

$tpt_vars['template']['content'] .= <<< EOT
    <form method="POST" action="$action_url" accept-charset="utf-8">
	<h1 class="product-title">Forgot your password?</h1>
        <div class="amz_login clearFix">
        <div class="padding-left-15 height-15 background-repeat-no-repeat" style="background-image: url($tpt_imagesurl/whbox/whbox-tl-15-15.png);">
        <div class="padding-right-15 height-15 background-repeat-no-repeat background-position-RT" style="background-image: url($tpt_imagesurl/whbox/whbox-tr-15-15.png);">
        <div class="height-35 background-repeat-repeat-x" style="background-image: url($tpt_imagesurl/whbox/whbox-t-15-15.png);">
        </div>
        </div>
        </div>
        
        <div class="padding-left-15 background-repeat-repeat-y" style="background-image: url($tpt_imagesurl/whbox/whbox-l-15-15.png);">
        <div class="padding-right-15 background-repeat-repeat-y background-position-RC" style="background-image: url($tpt_imagesurl/whbox/whbox-r-15-15.png);">
        <div class="" style="background-color: #FFF;">
        
            
            <div>
				<div style="" class="">Use the form below to submit your account's email address. We will send you an email with instructions on resetting your password.</div>
                <div>
                    <div class="display-inline-block text-align-right padding-left-22">
                        <div class="height-22 line-height-22 padding-top-4 padding-bottom-4">
                            <div style="color: #909090;" class="">Enter Your Email:</div>
                        </div>
                        <div class="height-22 line-height-22 padding-top-4 padding-bottom-4">
                        </div>
                    </div>
                    <div class="display-inline-block">
                        <div class="height-22 line-height-22 padding-top-4 padding-bottom-4">
                            <div class="padding-left-8 background-position-LC background-repeat-no-repeat" style="background-image: url($tpt_imagesurl/user-form-field-left.png);">
                                <div class="padding-right-8 background-position-RC background-repeat-no-repeat" style="background-image: url($tpt_imagesurl/user-form-field-right.png);">
                                    <div class="background-repeat-repeat-x" style="background-image: url($tpt_imagesurl/user-form-field-mid.png);">
                                        <input type="text" class="plain-input-field padding-top-3 padding-bottom-3 font-size-14" name="username" value="" style="width: 100%;">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="height-22 line-height-22 padding-top-4 padding-bottom-4">
                            <input type="hidden" name="task" value="user.reset_password" />
                            <input type="submit" value="Submit" class="ma_btn display-inline-block hoverCB background-repeat-no-repeat background-position-CT width-82 height-37" style="width: auto;" />
                        </div>
                    </div>
                </div>
            </div>
            
            
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
EOT;

?>