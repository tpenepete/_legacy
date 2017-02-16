<?php
defined('TPT_INIT') or die('access denied');

//var_dump($_GET);die();

//$generator = new tpt_PreviewGenerator($tpt_vars);
//var_dump($generator);die();
$steps = array();
$steps['commands'] = array();
$steps['errors'] = array();

//$_GET['text'] = isset($_GET['text'])?$_GET['text']:'';

//if (isset($_GET['color']) && strpos($_GET['color'],'%')!==false) $_GET['color'] = urldecode($_GET['color']);

$input = $_GET;

tpt_dump($input, false, 'R');
tpt_dump($tpt_vars, false, 'R');

$out = tpt_PreviewGenerator::createImage($tpt_vars, $input);
//var_dump($out);die();


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


