<?php

defined('TPT_INIT') or die('access denied');

$iPod = (stripos($tpt_vars['environment']['http_user_agent'],"iPod") !== false);
$iPhone = (stripos($tpt_vars['environment']['http_user_agent'],"iPhone") !== false);
$iPad = (stripos($tpt_vars['environment']['http_user_agent'],"iPad") !== false);
$Android= (stripos($tpt_vars['environment']['http_user_agent'],"Android") !== false);
$webOS= (stripos($tpt_vars['environment']['http_user_agent'],"webOS") !== false);

$tpt_vars['environment']['isMobileDevice']['ipod'] = $iPod;
$tpt_vars['environment']['isMobileDevice']['iphone'] = $iPhone;
$tpt_vars['environment']['isMobileDevice']['ipad'] = $iPad;
$tpt_vars['environment']['isMobileDevice']['android'] = $Android;
$tpt_vars['environment']['isMobileDevice']['webos'] = $webOS;



if($iPod || $iPhone || $iPad || $Android) {
	$tpt_vars['config']['paypal']['api_nvp_param'] = '_express-checkout-mobile';
}