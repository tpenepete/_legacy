<?php
http_response_code (404);
header('HTTP/1.1 404 Not Found');
defined('TPT_INIT') or die('access denied');

$evars = tpt_functions::f_get_defined_vars($vars, get_defined_vars());
if((empty($vars['environment']['client']) && !defined('TPT_ADMIN')) || ($vars['environment']['client'] == 1)) {
	$fpath = TPT_SYSTEM_TEMPLATES_DIR.DIRECTORY_SEPARATOR.'404.tpt.php';
	$fvars = self::f_include_once($vars, $fpath, $evars);
} else {
	$fpath = TPT_SYSTEM_TEMPLATES_DIR.DIRECTORY_SEPARATOR.'admin-404.tpt.php';
	$fvars = self::f_include_once($vars, $fpath, $evars);
}

extract($fvars);