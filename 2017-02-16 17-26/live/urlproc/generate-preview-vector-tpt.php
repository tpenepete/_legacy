<?php
defined('TPT_INIT') or die('access denied');

//var_dump($_GET);die();

//$generator = new tpt_PreviewGenerator($tpt_vars);
//var_dump($generator);die();
$steps = array();
$steps['commands'] = array();
$steps['errors'] = array();

$_GET['text'] = !empty($_GET['text'])?stripslashes($_GET['text']):'';
$out = tpt_PreviewGenerator::generatePreviewVector($tpt_vars, $_GET);
//die();
header('Content-type: image/svg+xml');
echo $out;


