<?php
defined('TPT_INIT') or die('access denied');

if(!isset($_GET['image']) && !isset($_GET['type'])) {
	exit;
} else {
    if(!isset($_GET['timestamp']) || empty($_GET['timestamp'])) { exit; }
}
//var_dump($_GET);die();

//$generator = new tpt_PreviewGenerator($tpt_vars);
//var_dump($generator);die();
$steps = array();
$steps['commands'] = array();
$steps['errors'] = array();


$_GET['text'] = isset($_GET['text'])?$_GET['text']:'';

if (isset($_GET['color']) && strpos($_GET['color'],'%')!==false) $_GET['color'] = urldecode($_GET['color']);

$out = tpt_PreviewGenerator::generatePreview($tpt_vars, $_GET);
//var_dump($out);die();
//tpt_dump('', true);

if(empty($_GET['debug_php']) && !(isDump() && !empty($tpt_vars['config']['dev']['debugpreviews_php']))) {
	header('Pragma: public');
	header('Cache-Control: max-age=2592000');
	header('Expires: ' . gmdate('D, d M Y H:i:s \G\M\T', time() + 2592000));
	header('Content-Type: image/png');
}

echo $out;