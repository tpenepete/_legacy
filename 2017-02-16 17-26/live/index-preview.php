<?php
//var_dump($_GET);die();
//var_dump($_GET['l'][0]['command']);die();
define('TPT_INIT', 1);

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


include_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'log_handlers' . DIRECTORY_SEPARATOR . 'log-php-errors.php');
include_once(dirname(__FILE__) . DIRECTORY_SEPARATOR . 'tpt_init-minimal.php');




// END config

include(TPT_INCLUDES_DIR.DIRECTORY_SEPARATOR.'tpt_previewgenerator.php');

//$generator = new tpt_PreviewGenerator($tpt_vars);
//var_dump($generator);die();
$steps = array();
$steps['commands'] = array();
$steps['errors'] = array();

//$_GET['text'] = isset($_GET['text'])?$_GET['text']:'';

//if (isset($_GET['color']) && strpos($_GET['color'],'%')!==false) $_GET['color'] = urldecode($_GET['color']);

$input = $_GET;

/*
if(isset($input['l'][0]['id'])) {
	$layerid = intval($input['l'][0]['id'], 10);
	$db = $tpt_vars['db']['handler'];
	$db->prepare('SELECT * FROM `tpt_module_previewlayer` WHERE `id`=' . $layerid);
	$db->execute();
	$layer = $db->fetch();
	tpt_dump(http_build_query(array('l'=>array($layer))));
}
tpt_dump(http_build_query($input), true);
*/


$out = tpt_PreviewGenerator::createImage($tpt_vars, $input);
if(isset($input['cache']) && (!isset($tpt_vars['config']['pGenerator']['cache']['disable']['storage']['general']) || empty($tpt_vars['config']['pGenerator']['disable_cache']['storage']['general']))) {
	//unset($input['cache']);
	if(isset($input['l']) && !empty($input['l'])) {
		$dir = tpt_PreviewGenerator::getCachedImageDir($tpt_vars, $input);
		$filename = tpt_PreviewGenerator::getCachedImageName($tpt_vars, $input);

		//tpt_dump($ldiff, true);
		//tpt_dump($input, true);
		//$filename = sha1(http_build_query($input)) . '.png';
		if (!file_exists($dir)) {
			mkdir($dir);
		}

		if(strlen($filename)<256) {
			file_put_contents($dir . DIRECTORY_SEPARATOR . $filename, $out);
		}
	}

}
//var_dump($out);die();
//tpt_dump('', true);

//$image = imagecreatetruecolor ( 500 , 100 );
//imagettftext ( $image , $pointsize=40 , $angle=0 ,$x=10 , $y=80 , $color=150 , $fontfile='/home/amazingw/public_html/live_resources/live/fonts/Walley-World.ttf' , $text='Front Message' );
if(empty($_GET['debug_php']) && !(isDump() && !empty($tpt_vars['config']['dev']['debugpreviews_php']))) {
	header('Pragma: public');
	header('Cache-Control: max-age=86400');
	header('Expires: '. gmdate('D, d M Y H:i:s \G\M\T', time() + 86400));
	header('Content-Type: image/png');
}
echo $out;
//imagepng($image);


