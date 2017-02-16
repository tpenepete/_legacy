<?php
defined('TPT_INIT') or die('access denied');

$tpt_vars['config']['fonts'] = array();
$tpt_vars['config']['fonts']['fonts_path'] = $fontsPath = TPT_RESOURCE_DIR.DIRECTORY_SEPARATOR.'fonts';
$tpt_vars['config']['fonts']['svg_fonts_path'] = $svgFontsPath = $fontsPath.DIRECTORY_SEPARATOR.'SVG';

define( 'FONTS_PATH', $fontsPath );
define( 'SVG_FONTS_PATH', $svgFontsPath );