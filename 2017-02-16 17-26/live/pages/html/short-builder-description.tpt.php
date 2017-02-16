<?php

defined('TPT_INIT') or die('access denied');

$BID = $options['id'];

ob_start();
// include custom file
$cfdir = dirname(__FILE__).DS.'short-builder-custom-files';
define('CUSTOM_FILES_DIR',$cfdir);
$builder_descr = '';
if (is_file($cfile=CUSTOM_FILES_DIR.DS.$BID.'.php')){ include $cfile;}

if($builder_descr == '') {
	$builder_descr = ob_get_clean();
}

$c = <<< EOT
$builder_descr
EOT;
//$c = htmlspecialchars($c);

$html = <<< EOT
<div style="border: 1px solid #ccc; border-radius: 5px;">
	<div class="padding-top-10 padding-bottom-10">
	$c
	</div>
</div>
EOT;

