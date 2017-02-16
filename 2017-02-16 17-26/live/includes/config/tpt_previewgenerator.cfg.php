<?php
defined('TPT_INIT') or die('access denied');

/*
if(!isset($tpt_vars)) {
	$tpt_vars = array();
}
if(!isset($tpt_vars['config'])) {
	$tpt_vars['config'] = array();
}
*/

$tpt_vars['config']['pGenerator'] = array();
$tpt_vars['config']['pGenerator']['bin_path'] = $binPath = '/usr/local/ImageMagick-6.9.2-10/bin/';
$tpt_vars['config']['pGenerator']['imagemagick_bin'] = $imagemagickBin = 'convert';
define( 'BIN_PATH', $binPath );
define( 'IMAGEMAGICK_BIN', $imagemagickBin );


$tpt_vars['config']['pGenerator']['space_character_replace'] = 'O';

$tpt_vars['config']['pGenerator']['defaults'] = array();
$tpt_vars['config']['pGenerator']['defaults']['a_get_best_fit_metrics'] = array();
$tpt_vars['config']['pGenerator']['defaults']['a_get_best_fit_metrics']['loop_low'] = -9;
$tpt_vars['config']['pGenerator']['defaults']['a_get_best_fit_metrics']['loop_high'] = 18;



$tpt_vars['config']['pGenerator']['cache'] = array();
$tpt_vars['config']['pGenerator']['cache']['disable'] = array();

$tpt_vars['config']['pGenerator']['cache']['disable']['use'] = array();
$tpt_vars['config']['pGenerator']['cache']['disable']['use']['general'] = 1;
$tpt_vars['config']['pGenerator']['cache']['disable']['use']['layertype'] = array();
$tpt_vars['config']['pGenerator']['cache']['disable']['use']['layertype']['flat'] = 0;
$tpt_vars['config']['pGenerator']['cache']['disable']['use']['layertype']['led_message'] = 0;

$tpt_vars['config']['pGenerator']['cache']['disable']['storage'] = array();
$tpt_vars['config']['pGenerator']['cache']['disable']['storage']['general'] = 0;
$tpt_vars['config']['pGenerator']['cache']['disable']['storage']['layertype'] = array();
$tpt_vars['config']['pGenerator']['cache']['disable']['storage']['layertype']['flat'] = 0;
$tpt_vars['config']['pGenerator']['cache']['disable']['storage']['layertype']['led_message'] = 0;