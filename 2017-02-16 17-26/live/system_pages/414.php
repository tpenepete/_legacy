<?php

defined('TPT_INIT') or die('access denied');
http_response_code (414);
header('HTTP/1.1 414 Request-URI Too Long');

$status = '414';
$evars = tpt_functions::f_get_defined_vars($vars, get_defined_vars());
$fpath = TPT_SYSTEM_PAGES_DIR.DIRECTORY_SEPARATOR.'error_document.php';
$fvars = self::f_include_once($vars, $fpath, $evars);
extract($fvars);