<?php
// Currently this file is not being used. I can't get ErrorDocument 414 to point to a script
// only redirect to URL and display text do. When redirect to URL is used the original long request URL cannot be obtained.
define('TPT_INIT', 1);

$status = 414;
include(dirname(__FILE__).DIRECTORY_SEPARATOR.'error_document.php');