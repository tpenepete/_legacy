<?php
defined('TPT_INIT') or die('access denied');

$tpt_vars['config']['clipart'] = array();
$tpt_vars['config']['clipart']['cliparts_path'] = $clipartsPath = TPT_RESOURCE_DIR.DIRECTORY_SEPARATOR.'clipart'; // legacy
$tpt_vars['config']['clipart']['clipart_path'] = $clipartPath = TPT_RESOURCE_DIR.DIRECTORY_SEPARATOR.'clipart';
$tpt_vars['config']['clipart']['cliparts_url'] = $clipartsUrl = RESOURCE_URL.'/clipart'; // legacy
$tpt_vars['config']['clipart']['clipart_url'] = $clipartUrl = RESOURCE_URL.'/clipart';
define( 'CLIPARTS_PATH', $clipartPath ); // legacy
define( 'CLIPART_PATH', $clipartPath );
define( 'CLIPARTS_URL', $clipartUrl ); // legacy
define( 'CLIPART_URL', $clipartUrl );

$tpt_vars['config']['clipart']['custom_clipart_path'] = $custom_clipartPath = TPT_RESOURCE_DIR.DIRECTORY_SEPARATOR.'custom-clipart';
$tpt_vars['config']['clipart']['custom_clipart_url'] = $custom_clipartUrl = RESOURCE_URL.'/custom-clipart';
define( 'CUSTOM_CLIPART_PATH', $custom_clipartPath );
define( 'CUSTOM_CLIPART_URL', $custom_clipartUrl );