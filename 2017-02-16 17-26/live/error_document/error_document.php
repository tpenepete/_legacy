<?php
defined('TPT_INIT') or die('access denied');

define('BDIR', DIRECTORY_SEPARATOR.'home'.DIRECTORY_SEPARATOR.'templay'.DIRECTORY_SEPARATOR.'public_html');
define('SDIR', BDIR.DIRECTORY_SEPARATOR.'system_pages');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once(BDIR . DIRECTORY_SEPARATOR . 'log_handlers' . DIRECTORY_SEPARATOR . 'log-php-errors.php');
include_once(BDIR . DIRECTORY_SEPARATOR . 'tpt_init-minimal.php');

include_once(SDIR . DIRECTORY_SEPARATOR . 'error_document.php');