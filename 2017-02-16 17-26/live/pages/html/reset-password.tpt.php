<?php

defined('TPT_INIT') or die('access denied');

if(!isset($_GET['token']) && ($tpt_vars['environment']['request_method'] != 'post')) {
    include(TPT_TEMPLATES_DIR.DIRECTORY_SEPARATOR.'reset-password-emailform.tpt.php');
} else if($tpt_vars['environment']['request_method'] == 'post'){
    include(TPT_TEMPLATES_DIR.DIRECTORY_SEPARATOR.'reset-password-checkemail.tpt.php');
} else {
    include(TPT_TEMPLATES_DIR.DIRECTORY_SEPARATOR.'change-password2.tpt.php');
}

