<?php

defined('TPT_INIT') or die('access denied');


//<input onkeyup="if(document.body.attachEvent)pass_input(estimate_password_strength(this.value));compare_passwords(document.getElementById(\'password_field\').value, document.getElementById(\'password2_field\').value);" onpaste="pass_input(estimate_password_strength(this.value));compare_passwords(document.getElementById(\'password_field\').value, document.getElementById(\'password2_field\').value);" oncut="pass_input(estimate_password_strength(this.value));compare_passwords(document.getElementById(\'password_field\').value, document.getElementById(\'password2_field\').value);" oninput="pass_input(estimate_password_strength(this.value));compare_passwords(document.getElementById(\'password_field\').value, document.getElementById(\'password2_field\').value);" onchange="pass_input(estimate_password_strength(this.value));compare_passwords(document.getElementById(\'password_field\').value, document.getElementById(\'password2_field\').value);" type="password" id="'.$field->name.'_field" name="'.$field->name.'" size="30" class="inputbox" /> At least 6 chars
$tpt_vars['template_data']['head'][] = <<< EOT
<script type="text/javascript" src="$tpt_baseurl/js/xregexp/xregexp-min.js"></script>
<script type="text/javascript" src="$tpt_baseurl/js/xregexp/unicode-base.js"></script>
<script type="text/javascript" src="$tpt_baseurl/js/xregexp/unicode-categories.js"></script>
<script type="text/javascript">
        var regexp_validator_ex = XRegExp("^((\\p{L}|\\p{N}|_))+$");
        var regexp_lc3_ex = XRegExp("\\p{Ll}{3,}");
        var regexp_lc2_ex = XRegExp("\\p{Ll}{2}");
        var regexp_lc1_ex = XRegExp("\\p{Ll}{1}");
        var regexp_uc3_ex = XRegExp("\\p{Lu}{3,}");
        var regexp_uc2_ex = XRegExp("\\p{Lu}{2}");
        var regexp_uc1_ex = XRegExp("\\p{Lu}{1}");
        var regexp_n3_ex = XRegExp("\\p{N}{3,}");
        var regexp_n2_ex = XRegExp("\\p{N}{2}");
        var regexp_n1_ex = XRegExp("\\p{N}{1}");
</script>
<script type="text/javascript" src="$tpt_baseurl/js/password_strength_meter.js"></script>
<link rel="stylesheet" type="text/css" href="$tpt_baseurl/css/password_strength_meter.css" />
EOT;
$tpt_vars['template_data']['footer_scripts']['scripts'][] = <<< EOT
var pinput1 = document.getElementById('password_field');
var pinput2 = document.getElementById('password2_field');
var the_meter = document.getElementById('pm_indicator');
var the_label = document.getElementById('pm_label');
EOT;

// master template
$password_meter = <<< EOT
<div class="p_meter display-inline-block" id="pm">
    <div class="pm_title font-size-12">Password Strength</div>
    <div class="pm_border">
            <div id="pm_indicator"></div>
    </div>
</div>
EOT;


?>
