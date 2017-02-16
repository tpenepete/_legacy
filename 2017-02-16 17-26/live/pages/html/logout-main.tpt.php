<?php

defined('TPT_INIT') or die('access denied');
$action_url = $tpt_vars['url']['handler']->wrap($tpt_vars, '/login-register');

$tpt_vars['template']['title'] = 'Customer Area';
$tpt_vars['template']['content'] .= <<< EOT
    <form method="POST" action="$action_url" accept-charset="utf-8">
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
        
            <input type="hidden" name="task" value="user.logout" />
            <input type="submit" title="Logout of Your Account" value="Logout" class="ma_btn display-inline-block hoverCB background-repeat-no-repeat background-position-CT width-82 height-37" style="width: auto;" />
            <!--<input type="submit" value="Logout" />-->
            
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